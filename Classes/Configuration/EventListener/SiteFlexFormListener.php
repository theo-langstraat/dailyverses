<?php
declare(strict_types=1);

namespace Theolangstraat\Dailyverses\Configuration\EventListener;

use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Configuration\Event\AfterTcaCompilationEvent;
use Theolangstraat\Dailyverses\Configuration\EventListener\GenerateFlexForm;

final class SiteFlexFormListener
{
    private SiteFinder $siteFinder;
    private GenerateFlexForm $generateFlexForm;

    public function __construct(SiteFinder $siteFinder, GenerateFlexForm $generateFlexForm)
    {
        $this->siteFinder = $siteFinder;
        $this->generateFlexForm = $generateFlexForm;
    }

    public function __invoke(AfterTcaCompilationEvent $event): void
    {
        // Hier heb je gegarandeerd een container en services
        foreach ($this->siteFinder->getAllSites() as $site) {
            $this->generateFlexFormForSite($site);
        }
    }

    private function generateFlexFormForSite(Site $site): void
    {
        $configuration = $site->getConfiguration();
        $siteIdentifier = $site->getIdentifier();

        if (isset($configuration['languages'])) {
            $this->generateFlexForm->generate($siteIdentifier, $configuration['languages']);
        }
    }
}
