<?php

declare(strict_types=1);

namespace Theolangstraat\Dailyverses\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Core\Environment;

class DailyVersesController extends ActionController
{
    protected array $languageOptions = [];

    public function __construct()
    {
        $configPath = __DIR__ . '/../../Configuration/LanguageOptions.php';
        $this->languageOptions = require $configPath;

    }

    public function Generate() {

        $languageCode = $this->request->getAttribute('language')?->getLocale()?->getLanguageCode() ?? 'en';

        // Mapping from language ID to Bible version and URL
        $dailyUrl = 'https://dailyverses.net/get/verse.js?language=';
        $randomUrl = 'https://dailyverses.net/get/random.js?language=';

        if ($this->settings['modus'] === 'daily') {
            $basesUrl = $dailyUrl;
        }
        if ($this->settings['modus'] === 'random') {
            $basesUrl = $randomUrl;
        }

        $version = $this->settings[$languageCode];
        $versions = $this->languageOptions[$languageCode];

        $foundVersion = null;
        foreach ($versions as $item) {
            if ($item['url'] === $version) {
                $foundVersion = $item['version'];
                break;
            }
        }

        $url = $basesUrl . $version;
        $bibleVersion = $foundVersion;

        // Step 1: Retrieve the content from the URL
        $html = @file_get_contents($url);
        if ($html === false) {
            echo 'Could not retrieve the Bible verse.';
            return;
        }

        /* Format returned content of dailyverses.net (javascript)
           document.getElementById("dailyVersesWrapper").innerHTML = 
           '\u003cdiv class=\"dailyVerses bibleText\"\u003eGod zei: ‘Laat er licht zijn,’ en er was licht.\u003c/div\u003e\u003cdiv class=\"dailyVerses bibleVerse\"\u003e\u003ca href=\"https://dailyverses.net/nl/2025/9/29\" rel=\"noopener\" target=\"_blank\"\u003eGenesis 1:3\u003c/a\u003e\u003c/div\u003e';
        */

        // substract content of innerhtml
        $pos = strpos($html, "'"); // start of content
        $html = substr($html, $pos, null); // length: null = to the end of the line in php8.0

        // Step 2: Decode Unicode-escaped HTML to readable HTML
        $html = '"' . $html . '"'; // create json string
        $html = json_decode($html);
        $html = substr($html, 1, strlen($html)-3); // remove first "'" and last "';"

        // Add meta tag for UTF-8
        $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $html;
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        // Step 3: Load the HTML into a DOMDocument
        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true); // suppress warnings
        $dom->loadHTML($html);
        libxml_clear_errors();

        // Step 4: Find the elements
        $bibleTextElement = '';
        $bibleVerseElement = '';

        foreach ($dom->getElementsByTagName('div') as $div) {
            if ($div->getAttribute('class') === 'dailyVerses bibleText') {
                $bibleTextElement = $div->nodeValue;
            }
            if ($div->getAttribute('class') === 'dailyVerses bibleVerse') {
                $bibleVerseElement = $div->nodeValue;
            }
        }

        // Retrieve the link to the dailyverses.net site
        $links = $dom->getElementsByTagName('a');

        $link = $links[0]->getAttribute('href');
        $linkText = $links[0]->nodeValue;

        // Fetch data from the external site
        // Return the result as an associative array
        $result = [
            'text' => $bibleTextElement,
            'verse' => $bibleVerseElement,
            'version' => $bibleVersion,
            'link' => $link,
            'linkText' => $linkText,
        ];

        return $result;
    }

    public function showverseAction(): ResponseInterface
    {
        // The user may define a custom theme using a CSS file located outside the extension.
        // If the bootstrap_package extension is installed, Bootstrap theme variables from bootstrap.css will be applied.
        // Otherwise, the default styling from dailyverses.css will be used.

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);

        $customCss = $this->settings['customcss'] ?? '';
        $customCssPath = Environment::getPublicPath() . '/' . ltrim($customCss, '/');

        if ($customCss && file_exists($customCssPath)) {
            $pageRenderer->addCssFile($customCss);
        } else {
            if (ExtensionManagementUtility::isLoaded('bootstrap_package')) {
                $pageRenderer->addCssFile('EXT:dailyverses/Resources/Public/Css/bootstrap.css');
            } else {
                $pageRenderer->addCssFile('EXT:dailyverses/Resources/Public/Css/dailyverses.css');
            }
        }

        if ($this->settings['modus'] === 'daily') {
            $context = GeneralUtility::makeInstance(Context::class);
            $languageUid = $context->getPropertyFromAspect('language', 'id');

            // Retrieve the siteIdentifier
            $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
            $pageId = (int)$this->request->getAttribute('routing')->getPageId();
            $site = $siteFinder->getSiteByPageId($pageId);
            $siteIdentifier = $site->getIdentifier();

            // Add siteIdentifier to the cache key
            $cacheKey = 'daily_verse_' . $siteIdentifier . '_' . $languageUid;

            $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
            $cache = $cacheManager->getCache('daily_verse_cache');

            $today = (new \DateTime())->format('Y-m-d');
            $cachedData = $cache->get($cacheKey);

            if (
                is_array($cachedData) &&
                isset($cachedData['date'], $cachedData['verse']) &&
                $cachedData['date'] === $today
            ) {
                $content = $cachedData['verse'];
            } else {
                $content = $this->Generate();
                $cache->set($cacheKey, [
                    'date' => $today,
                    'verse' => $content,
                ]);
            }
        } else {
            $content = $this->Generate();
        }

        $this->view->assign('bible', $content);
        return $this->htmlResponse();
    }
}
