================
GenerateFlexForm
================

Overview
========

The `GenerateFlexForm` class is part of the ``Theolangstraat\Dailyverses\Configuration\EventListener`` namespace. It dynamically generates a FlexForm XML configuration file for a TYPO3 site based on available languages and Bible translation options.

This guide explains the structure, purpose, and usage of the class and its `generate()` method.

Location
========

The class is located at:

.. code-block:: console

    typo3conf/ext/dailyverses/Configuration/EventListener/GenerateFlexForm.php

Dependencies
============

- TYPO3 CMS Core Utility (`GeneralUtility`)
- Language options file: `Configuration/LanguageOptions.php`

Purpose
=======

The `generate()` method creates a FlexForm XML file containing:

- A tab for each language with a dropdown of Bible translations
- A default options tab with additional configuration settings

Method Signature
================

.. code-block:: php

    <?php

    public function generate($siteIdentifier, $languages)

Parameters
----------

- `siteIdentifier` (string): Unique identifier for the site, used to name the output XML file.
- `languages` (array): List of language configurations, each containing:
  
  - `locale`: Locale string (e.g., `en_US`)
  - `title`: Human-readable language name

Workflow
========

1. **Load Bible Translation Options**

..  code-block:: php

    <?php

    // This file returns an array of available Bible translations per language code.
    $languageOptions = require __DIR__ . '/../../../Configuration/LanguageOptions.php';


2. **Create Language-Specific Sheets**

   For each language in `$languages`, a sheet is created with:

   - Sheet title set to the language name
   - A dropdown (`selectSingle`) listing Bible translations for that language
   
----

.. raw:: html

   <div style="height: 1em;"></div>
