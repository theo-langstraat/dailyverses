<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

//include_once('/var/www/html/sandbox/typo3conf/ext/dailyverses/Configuration/DynamicFlexForm.php');
//DebuggerUtility::var_dump($flexXml, 'flexXml');

$ctypeKey = ExtensionUtility::registerPlugin(
    'Dailyverses', // denotes the extension key in UpperCamelCase (ExtensionKey)
    'DailyVerses', // denotes the plugin name in UpperCamelCase (PluginName). This is later used to clearly identify the plugin
    'Daily Verses', // descriptive title
    'ext-dailyverses-plugin', // icon identifier
    'plugins', // plugin group
    'Show a daily Bible verse', // descriptive title
//    'FILE:EXT:dailyverses/Configuration/FlexForms/FlexForm.xml', // path to the FlexForm definition (XML or PHP file returning XML
    '',
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
