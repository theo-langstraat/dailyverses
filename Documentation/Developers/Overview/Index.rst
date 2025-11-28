.. include:: /Includes.rst.txt

========
Overview
========

DailyVerses Extension – Documentation

Purpose
-------

This extension retrieves either a daily or random Bible verse from `dailyverses.net <https://dailyverses.net>`_ and displays it in the TYPO3 frontend. When using the daily mode, caching is applied per language and per date.

File Overview
-------------

- `ext_localconf.php`: Defines the cache configuration `daily_verse_cache`.
- `DailyVersesController.php`: Contains the logic for retrieving, processing, and caching the Bible verse.
- `LanguageOptions.php`: Maps language codes to Bible versions and URLs.

Caching Strategy
----------------

- Cache is stored under the key `daily_verse_<languageUid>`.
- Each cache entry contains:

.. code-block:: php

  <?php
  [
    'date' => 'YYYY-MM-DD',
    'verse' => [ 'text' => ..., 'verse' => ..., 'version' => ..., 'link' => ..., 'linkText' => ... ]
  ]

- Upon retrieval, the date is checked against today's date. If it does not match, a new verse is fetched and stored.

Controller Logic
----------------

- `Generate()`: Fetches HTML from dailyverses.net, decodes it, and filters relevant elements.
- `showverseAction()`:
  - In `daily` mode: uses cache per language and date.
  - In `random` mode: fetches a random verse directly without caching.

External Dependencies
---------------------

- Daily verse: `https://dailyverses.net/get/verse.js?language=<code>`
- Random verse: `https://dailyverses.net/get/random.js?language=<code>`

----

.. raw:: html

   <div style="height: 1em;"></div>
