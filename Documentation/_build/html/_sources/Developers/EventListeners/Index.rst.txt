.. include:: /Includes.rst.txt

FlexFormParsingModifyEventListener
==================================

This class registers event listeners to dynamically configure and modify FlexForm data structures for the TYPO3 extension ``dailyverses``.

Namespace: ``Theolangstraat\Dailyverses\Configuration\EventListener``

Event Listeners
---------------

.. list-table::
   :header-rows: 1
   :widths: 30 70

   * - Event Identifier
     - Method
   * - ``dailyverses/set-data-structure``
     - ``setDataStructure``
   * - ``dailyverses/modify-data-structure``
     - ``modifyDataStructure``
   * - ``dailyverses/set-data-structure-identifier``
     - ``setDataStructureIdentifier``
   * - ``dailyverses/modify-data-structure-identifier``
     - ``modifyDataStructureIdentifier``
   * - ``dailyverses/package-initialization``
     - ``setFlexForms``

Methods
-------

setDataStructure
~~~~~~~~~~~~~~~~

Triggered before the FlexForm data structure is parsed.

- Checks if the `dataStructureKey` contains ``dailyverses_dailyverses``.
- Retrieves the site identifier using `SiteFinder` based on the stored `pageId`.
- Assigns a site-specific XML file as the FlexForm structure:
  ``FILE:EXT:dailyverses/Configuration/FlexForms/{siteIdentifier}.xml``.

modifyDataStructure
~~~~~~~~~~~~~~~~~~~

Triggered after the FlexForm data structure is parsed.

- Currently unused.
- Can be extended to dynamically modify FlexForm sheet titles or fields.

setDataStructureIdentifier
~~~~~~~~~~~~~~~~~~~~~~~~~~

Triggered before the FlexForm identifier is initialized.

- Determines the correct `pid` from the content element row.
- If `pid` is negative (temporary UID), queries the `tt_content` table to resolve the actual `pid`.
- Stores `pageId`, `languageUid`, and `contentType` in `FlexFormContextStorage` for later use.

modifyDataStructureIdentifier
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Triggered after the FlexForm identifier is initialized.

- Currently unused.

setFlexForms
~~~~~~~~~~~~

Triggered during package initialization.

- Checks if the extension key is ``dailyverses``.
- Uses the `GenerateFlexForm` service to create XML FlexForm files for each site.
- Iterates over all sites and generates FlexForms based on their language configuration.

Dependencies
------------

- ``TYPO3\CMS\Core\Site\SiteFinder``
- ``TYPO3\CMS\Core\Database\ConnectionPool``
- ``Theolangstraat\Dailyverses\Configuration\EventListener\FlexFormContextStorage``
- ``Theolangstraat\Dailyverses\Configuration\EventListener\GenerateFlexForm``

Usage
-----

This class enables dynamic assignment of FlexForm configurations for content elements of type ``dailyverses_dailyverses``. It ensures that each site can have its own tailored FlexForm structure.

.. note::
   Make sure the XML files exist in ``EXT:dailyverses/Configuration/FlexForms/``. If not, they will be generated during package initialization.

----

.. raw:: html

   <div style="height: 1em;"></div>
