.. include:: /Includes.rst.txt

Controller
==========

This controller is part of the ``Theolangstraat\Dailyverses`` TYPO3 extension. It fetches and displays daily or random Bible verses from `dailyverses.net`, with caching per site and language.

Overview DailyVersesController
------------------------------

**Namespace:** ``Theolangstraat\Dailyverses\Controller``  
**Class:** ``DailyVersesController``  
**Extends:** ``TYPO3\CMS\Extbase\Mvc\Controller\ActionController``

This controller provides two main functionalities:

- Fetching Bible verses from an external source.
- Caching the result per site and language context.

Properties
----------

**languageOptions**  
Type: ``array``  
Loaded from ``Configuration/LanguageOptions.php``. Maps language codes to available Bible versions and URLs.

Constructor
-----------

**__construct()**  
Loads the language options configuration file into the ``$languageOptions`` property.

Methods
-------

**Generate()**  
Fetches a Bible verse from `dailyverses.net` based on the current language and mode.

Returns: associative array with keys:

- ``text``: The Bible verse text.
- ``verse``: The reference (e.g. John 3:16).
- ``version``: The Bible version used.
- ``link``: Link to the full verse on dailyverses.net.
- ``linkText``: Text of the link.

Behavior:

- Determines the language code from the request context.
- Selects the correct URL based on the mode (`daily` or `random`).
- Matches the configured version to the available options.
- Retrieves and parses the HTML response.
- Extracts the verse text, reference, version, and link.

Error Handling:

- If the external request fails, it returns early with a message.

---

**showverseAction()**  
Main action method that renders the Bible verse in the view.

Returns: ``ResponseInterface``

Behavior:

- Checks if the mode is set to `daily`.
- Retrieves the current language UID and site identifier.
- Constructs a cache key using both values.
- Checks if a verse is already cached for today.
- If not cached, calls `Generate()` and stores the result.
- Assigns the verse data to the view.

Caching:

- Uses TYPO3's CacheManager to store daily verses.
- Cache key format: ``daily_verse_<siteIdentifier>_<languageUid>``

Usage Notes
-----------

- Ensure the cache ``daily_verse_cache`` is configured in ``LocalConfiguration.php`` or ``ext_localconf.php``.
- The external service `dailyverses.net` must be reachable for verse retrieval.
- Language mappings must be defined in ``Configuration/LanguageOptions.php``.

----

.. raw:: html

   <div style="height: 1em;"></div>
