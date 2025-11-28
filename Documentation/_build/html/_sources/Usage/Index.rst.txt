.. include:: /Includes.rst.txt

Usage
=====

Site Configuration
------------------

.. figure:: /Screenshots/Sites.png
   :alt: Sites
   :class: with-shadow
   :width: 600px

   *Edit Site Configuration*

To ensure the plugin functions correctly, it is important to configure the sites properly. The available languages are defined in the site configuration. These are read by the extension when:

* the site configuration is saved
* the extension is installed

Language Fallback Type
----------------------

.. figure:: /Screenshots/SitesFallback.png
   :alt: Sites fallback
   :class: with-shadow
   :width: 600px

   *Language Fallback Type*

If the option **"Show default language if no translation exists"** is selected, it is sufficient to refer to the language in which the plugin was created.  
For greater flexibility, the plugin can optionally be created separately for each language.  
In all cases, the plugin will display the language selected in the frontend.

Create New Content
------------------

.. figure:: /Screenshots/CreateNewContentWizard.png
   :alt: Create New Content Wizard
   :class: with-shadow
   :width: 600px

   *Create New Content*

To use the extension, insert the plugin via the content element wizard.  
Using a headline or any other field is optional.

.. figure:: /Screenshots/CreateNewContentGeneral.png
   :alt: Create New Content General
   :class: with-shadow
   :width: 600px

   *Create New Content – General*

Bible Translations per Language
-------------------------------

.. figure:: /Screenshots/CreateNewContentLanguage.png
   :alt: Create New Content Language
   :class: with-shadow
   :width: 600px

   *Create New Content – Configuration → Languages*

Options
-------

.. figure:: /Screenshots/EditPageContentOptions.png
   :alt: Create New Content Options
   :class: with-shadow
   :width: 600px

   *Create New Content – Configuration → Options*

* **Show Dailyverse link:**  
  Displays a link in the frontend to `dailyverses.net <https://dailyverses.net>`_

* **Minimal height:**  
  You can optionally add a background image. To create more space for the image, define a minimum height in `em` below the text.

* **Mode:**  

  * *Verse of the Day:* Daily Bible verse, cached for that date  
  * *Random Verse:* Random Bible verse, not cached

* **Custom CSS:**
  The user may define a custom theme using a CSS file located outside the extension.
  If the `bootstrap_package` extension is installed, Bootstrap theme variables from bootstrap.css will be applied.
  Otherwise, the default styling from dailyverses.css will be used.

.. warning::

   If the `bootstrap_package` is removed, installed, or activated, it is important to save the site configuration again. This ensures that the FlexForms are updated to reflect the new situation.

Example custom.css:

.. code-block:: css

   .bibletext-outerFrame {
      background: url("achtergrond.jpg");
      background-size: cover;
      background-position: center;
      padding: 2em;
   }                      

   .bibletext-frame {
      padding: 0.5rem;
      border-radius: 0.3rem;
      color: black;
      background-color: white;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
   }

   .bibletext {
      text-align: center;
      line-height: 1.5;
      font-weight: 300;
      font-size: 120%;
      font-style: italic;
   }

   .bibleverse {
      text-align: center;
      font-size: 80%;
      opacity: 40%;
   }

Frontend
--------

.. figure:: /Screenshots/Frontend.png
   :alt: Frontend
   :class: with-shadow
   :width: 100%

Frontend (Background Image)
---------------------------

.. figure:: /Screenshots/FrontendBackground.png
   :alt: Frontend
   :class: with-shadow
   :width: 100%

If you are using `bootstrap_package`, you can add a background using the standard functionality under the **Appearance** tab.  
The **Background Color** field allows you to set a color behind the text content.

If you are not using `bootstrap_package`, you have several alternatives:

* Use custom CSS to apply a background.
* Develop your own templates and add image fields to your FlexForm configuration.

----

.. raw:: html

   <div style="height: 1em;"></div>
