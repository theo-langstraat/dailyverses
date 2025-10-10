<?php

declare(strict_types=1);

namespace Theolangstraat\Dailyverses\Configuration\EventListener;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class GenerateFlexForm
{
    public function generate($siteIdentifier, $languages)
    {
        $languageOptions = require __DIR__ . '/../../../Configuration/LanguageOptions.php';
        $flexFormArray = [
            'sheets' => []
            ];

        // tabsheet per language with all available bible translations
        foreach ($languages as $language) {
            $langCode = substr($language['locale'], 0, 2);
            $languageTitle = $language['title'];

            $flexFormArray['sheets']['lang_' . $langCode]['ROOT'] = [
                'sheetTitle' => $languageTitle,
                'type' => 'array',
                'el' => 
                ["settings_dot_" . $langCode => 
                    ['label' => 'Select an option:',
                        'config' => 
                        ['type' => 'select',
                            'renderType' => 'selectSingle',
                            'items' => [],
                        ],
                    ],
                ],
            ];

            // Add all bible tranlations for this language
            foreach ($languageOptions[$langCode] as $item) {
                $flexFormArray['sheets']['lang_' . $langCode]['ROOT']['el']["settings_dot_" . $langCode]['config']['items'][] = [
                    $item['version'], $item['url'],
                ];
            }
        }

        $bootstrapLabel = 'LLL:EXT:dailyverses/Resources/Private/Language/locallang_be.xlf:settings.bootstrapLabel.label';
        $dailyversesLabel = 'LLL:EXT:dailyverses/Resources/Private/Language/locallang_be.xlf:settings.dailyversesLabel.label';

        if (ExtensionManagementUtility::isLoaded('bootstrap_package')) {
            $customcssLabel = $bootstrapLabel;
        } else {
            $customcssLabel = $dailyversesLabel;
        }

        // Options tabsheet
        $flexFormArray['sheets']['sDEF']['ROOT'] = [
                'sheetTitle' => 'LLL:EXT:dailyverses/Resources/Private/Language/locallang_be.xlf:sDEF.sheetTitle',
                'type' => 'array',
                'el' => 
                ["settings_dot_dailyversesLink" => 
                    ['label' => 'LLL:EXT:dailyverses/Resources/Private/Language/locallang_be.xlf:settings.dailyversesLink.label',
                        'config' => 
                        ['type' => 'check'],
                    ],
                 "settings_dot_spaceBeneathText" => 
                    ['label' => 'LLL:EXT:dailyverses/Resources/Private/Language/locallang_be.xlf:settings.spaceBeneathText.label',
                        'config' => 
                        ['type' => 'input',
                         'size' => '1',
                         'eval' => 'int',
                         'default' => '',
                        ],
                    ],   
                 "settings_dot_modus" => 
                    ['label' => 'Modus',
                        'config' => 
                        ['type' => 'radio',
                         'items' => [
                            [
                                'label' => 'LLL:EXT:dailyverses/Resources/Private/Language/locallang_be.xlf:settings.modusDaily.label', 
                                'value' => 'daily'
                            ],
                            [
                                'label' => 'LLL:EXT:dailyverses/Resources/Private/Language/locallang_be.xlf:settings.modusRandom.label',
                                'value' => 'random'
                            ],
                         ],
                         'default' => 'daily',
                        ],
                    ],   
                 "settings_dot_customcss" => 
                    ['label' => $customcssLabel,
                        'config' => 
                        ['type' => 'input',
                         'placeholder' => 'fileadmin/UserAssets/dailyverses/custom.css',
                         ],
                         'default' => '',
                    ],
                ],
            ];

        // 4. Convert array to XML
        $flexXmlRaw = GeneralUtility::array2xml($flexFormArray, '', 0, 'T3DataStructure');

        // array2xml removes the dot during conversion. 
        // This dot is needed for <settings.xxxx> to make the variable available in the frontend controller
        $flexXml = str_replace('_dot_', '.', $flexXmlRaw);

        $path = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/ext/dailyverses/Configuration/FlexForms/' . $siteIdentifier . '.xml';
        GeneralUtility::writeFile($path, $flexXml);

        return true;
    }
}
