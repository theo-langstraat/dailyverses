<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'ext-dailyverses-plugin' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:dailyverses/Resources/Public/Icons/Dailyverses.svg',
    ],
];