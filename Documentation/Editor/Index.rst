.. _for-editors:

============
Editor Guide
============

Configuration
=============

Open the root page in the List module to find the Cookie Consent Configuration.

.. figure:: ../Images/ConfigurationListModule.png
   :class: with-shadow
   :alt: BE View of Cookie Consent Configuration

   BE View of Cookie Consent Configuration

You can choose the site(s) the cookie consent should cover. In a multi-tree-backend it is possible to have a separate cookie consent configuration for every root.

If you enable statistics, the chosen cookie consent gets collected in the Cookie Statistics backend module.

.. figure:: ../Images/ImprintAndDatapolicyConfig.png
   :class: with-shadow
   :alt: Configuration of imprint and data policy links in consent modal

   Configuration of imprint and data policy links in consent modal

You can edit the header and hint text that appear above the individual cookies.

.. figure:: ../Images/HintBE.png
   :class: with-shadow
   :alt: Configuration of Cookie Hint

   Configuration of Cookie Hint

.. figure:: ../Images/CookieConsentFE.png
   :class: with-shadow
   :alt: Cookie Consent in FE

   Cookie Hint in FE

Creating new cookies
====================

When you create new necessary cookies, they will get listed in the cookie consent modal in the frontend. Users cannot uncheck the boxes for necessary cookies, but you can provide the names, purposes and duration of those cookies.

.. figure:: ../Images/CreateCookies.png
   :class: with-shadow
   :alt: Create new cookies

   Create new cookies

By creating a new cookie category, you can create the checkboxes for the single cookies and collect them in groups (e.g., the cookies of YouTube, Vimeo, and Google Maps get collected in the group "external media"). Creating a new option generates a new group. Inside this option, you create new cookies.

Users can accept the entire option (e.g., all of "external media") or select a single cookie inside this option (e.g., only "YouTube").

.. figure:: ../Images/CookieOptionsFE.png
   :class: with-shadow
   :alt: Cookie options in the FE

   Cookie Options in the FE

**Important:** Note that the cookie options shown in the cookie consent modal have no effect yet. To properly block and allow cookies, a developer needs to work with the event listeners (s. developer section).

Google Consent Mode
===================

| If you want to use Google Consent mode for cookies, you can use the select option in the cookie options.
| For now, only Google Ads & Analytics is supported; the selected options will be pushed to your Tag Manager with "denied" or "granted" depending on the user's choice.
| See `Google's documentation <https://developers.google.com/tag-platform/security/guides/consent?hl=en&consentmode=advanced#implementation_example>`__ about this topic.
|

.. figure:: ../Images/ConsentMode.jpg
   :class: with-shadow
   :alt: Google Consent Mode select for cookies

   Google Consent Mode select for cookies

The Cookie Consent Frontend Plugin
==================================

When you place the frontend plugin on a page, a button will appear in the frontend to open the cookie consent modal. The user can change the cookie consent settings and save them anew.

.. figure:: ../Images/DatapolicyExamplePage.png
   :class: with-shadow
   :alt: Example for Cookie Consent FE Plugin

   Example for Cookie Consent FE Plugin

The Cookie Consent Statistics Backend Module
============================================

To see statistics here, you need to have statistics enabled in the cookie consent configuration.

.. figure:: ../Images/CookieConsentStatistics.png
   :class: with-shadow
   :alt: Cookie Consent Statistics

   Example statistics shown in the statistics backend module.

At the moment, the data collected shows the percentage of users that clicked accept all versus those who only accepted single cookies.
