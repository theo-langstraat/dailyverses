<?php

declare(strict_types=1);
defined('TYPO3') or die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Theolangstraat\Dailyverses\Controller\DailyVersesController;

ExtensionUtility::configurePlugin(
    'Dailyverses',
    'DailyVerses',
    [
        DailyVersesController::class => 'showverse',
    ],
    [
        DailyVersesController::class => 'showverse',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['daily_verse_cache'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\FileBackend::class,
        'options' => [
            'defaultLifetime' => 21600,
            'cacheDirectory' => 'typo3temp/dailyverses',
        ],
        'groups' => ['system'],
    ];
});