<?php

declare(strict_types=1);

namespace Theolangstraat\Dailyverses\Configuration\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Configuration\Event\SiteConfigurationBeforeWriteEvent;
use Theolangstraat\Dailyverses\Configuration\EventListener\GenerateFlexForm;
use TYPO3\CMS\Core\Utility\GeneralUtility;

#[AsEventListener(
    identifier: 'dailyverses/site-configuration-before-write',
    method: 'SiteConfigurationBeforeWrite',
)]

final class SiteConfigurationBeforeWrite
{
    public function SiteConfigurationBeforeWrite(SiteConfigurationBeforeWriteEvent $event): void
    //public function __invoke(SiteConfigurationBeforeWriteEvent $event): void
    {
        $siteIdentifier = $event->getSiteIdentifier();
        $configuration = $event->getConfiguration();

        $generateFlexForm = GeneralUtility::makeInstance(GenerateFlexForm::class);
        $generateFlexForm->generate($siteIdentifier, $configuration['languages']);
    }
}

