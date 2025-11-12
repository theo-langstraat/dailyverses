<?php

declare(strict_types=1);

namespace Theolangstraat\Dailyverses\Configuration\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Configuration\Event\BeforeFlexFormDataStructureIdentifierInitializedEvent;
use TYPO3\CMS\Core\Configuration\Event\BeforeFlexFormDataStructureParsedEvent;
use TYPO3\CMS\Core\Package\Event\PackageInitializationEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Core\Environment;
use Doctrine\DBAL\ParameterType;
use Theolangstraat\Dailyverses\Configuration\EventListener\FlexFormContextStorage;
use Theolangstraat\Dailyverses\Configuration\EventListener\GenerateFlexForm;

#[AsEventListener(
    identifier: 'dailyverses/set-data-structure',
    method: 'setDataStructure',
)]
#[AsEventListener(
    identifier: 'dailyverses/set-data-structure-identifier',
    method: 'setDataStructureIdentifier',
)]
#[AsEventListener(
    identifier: 'dailyverses/package-initialization',
    method: 'setFlexForms',
)]

final readonly class FlexFormParsingModifyEventListener
{
    public function setDataStructure(BeforeFlexFormDataStructureParsedEvent $event): void
    {
        $identifier = $event->getIdentifier();
        $pageId = FlexFormContextStorage::get('pageId');

        // The first part is the list_type for which the flexform should be used and the second part the CType. 
        // If one of the parts is empty or a '*' then it matches any value. 
        // In this case we only care about the CType which should be 'dailyverses_dailyverses'.

        // Filter UIDno out '*,dailyverses_dailyverses' to check exact CType
        $haystack = $identifier['dataStructureKey'] ?? '';
        $needle = 'dailyverses_dailyverses';

        if (str_contains($haystack, $needle)) {

            if ($pageId > 0) {
                try {

                    $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
                    $site = $siteFinder->getSiteByPageId($pageId);
                    $siteIdentifier = $site->getIdentifier();

                    $flexFormFile = Environment::getVarPath() . 
                        '/dailyverses/flexforms/' . 
                        $siteIdentifier . '.xml';

                    if (!file_exists($flexFormFile)) {
                        $generateFlexForm = GeneralUtility::makeInstance(GenerateFlexForm::class);
                        $configuration = $site->getConfiguration();
                        $generateFlexForm->generate($siteIdentifier, $configuration['languages']);
                    }
                    $event->setDataStructure('FILE:' . $flexFormFile);

                } catch (\TYPO3\CMS\Core\Exception\SiteNotFoundException $e) {
                }
            }
        }
    }

public function setDataStructureIdentifier(BeforeFlexFormDataStructureIdentifierInitializedEvent $event): void
    {
        $identifier = $event->getIdentifier();
        $row = $event->getRow();
        $uidPid = $row['pid'];

        // $row['pid'] contains a real pid if the value is positive
        // and a negative value if a temporary uid has been created
        // example:
        // "pid": -85, (= uid of the previous content element)
        // "uid": "NEW68d97908397bc949195186",

        if ($uidPid > 0) {
            // It's a real pid
            $pid = $uidPid;
        } else {
            // It's a negative uid, so we need to retrieve the actual pid
            $uid = abs($uidPid);

            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tt_content');

            $pid = $queryBuilder
                ->select('pid')
                ->from('tt_content')
                ->where(
                    // $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($uid, ParameterType::INTEGER)
                    )
                )
                ->executeQuery()
                ->fetchOne();
        }

        FlexFormContextStorage::set('pageId', (int)$pid ?? 0);
        FlexFormContextStorage::set('languageUid', $row['sys_language_uid'] ?? 0); // future use
        FlexFormContextStorage::set('contentType', $row['CType'] ?? ''); // future use
    }

    public function setFlexForms(PackageInitializationEvent $event): void
    {
        if ($event->getExtensionKey() !== 'dailyverses') {
            return;
        }

        $container = $event->getContainer();

        // Bescherm tegen null container
        if ($container === null || !$container->has(SiteFinder::class)) {
            return;
        }

        $generateFlexForm = GeneralUtility::makeInstance(GenerateFlexForm::class);
        $siteFinder = $container->get(SiteFinder::class);

        foreach ($siteFinder->getAllSites() as $site) {
            $configuration = $site->getConfiguration();
            $siteIdentifier = $site->getIdentifier();
            $generateFlexForm->generate($siteIdentifier, $configuration['languages']);
        }
    }
}