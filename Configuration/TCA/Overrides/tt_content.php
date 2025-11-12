<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

$ctypeKey = ExtensionUtility::registerPlugin(
    'Dailyverses', // denotes the extension key in UpperCamelCase (ExtensionKey)
    'DailyVerses', // denotes the plugin name in UpperCamelCase (PluginName). This is later used to clearly identify the plugin
    'Daily Verses', // descriptive title
    'ext-dailyverses-plugin', // icon identifier
    'plugins', // plugin group
    'Show a daily Bible verse', // descriptive title
);

ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

$GLOBALS['TCA']['tt_content']['types']['dailyverses_ct'] = [
    'showitem' => '
        --div--;General,
            header, bodytext,
        --div--;Settings,
            pi_flexform,
    ',
];

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:dailyverses/Configuration/FlexForms/FlexForm.xml',
    $ctypeKey,
);
