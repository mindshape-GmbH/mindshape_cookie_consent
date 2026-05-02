.. _typoscript:

===================
TypoScript Settings
===================

This is a list of all TypoScript settings for this extension

+----------------------------------+----------------------------------------------------------------------------------+----------------+
| Constants                        | Description                                                                      | Default        |
+==================================+==================================================================================+================+
| cookieName                       | The name for the cookie                                                          | cookie_consent |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| expiryDays                       | Number of days until the cookie expires                                          | 365            |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| containerId                      | HTML container ID for the consent modal                                          | cookie-consent |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| hideIfJavaScriptDisabled         | Keep the modal hidden if JavaScript is disabled                                  | 1              |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| pushConsentToTagManager          | Push an event to Google Tag Manager when the user submits consent                | 0              |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| lazyloading                      | | Open the consent modal on first user interaction (e.g., move, click, scroll).  | 0              |
|                                  | | This may help improve the page speed score.                                    |                |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| lazyloadingTimeout               | Timeout (in seconds) until the lazy-loaded consent modal opens automatically     | 120            |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| hideCategoriesInOverview         | Hide category titles in the consent modal                                        | 0              |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| addNecessaryCookieCategoryInList | Include necessary cookie category in the list plugin                             | 0              |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| ajaxPageType                     | TYPO3 page type used for XHR requests                                            | 8641           |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| addMarkupToFooter                | Insert the consent modal HTML into the footer                                    | 1              |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| addConfiguration                 | Include configuration settings as JSON in the header                             | 1              |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| addJavaScript                    | Include the default JavaScript                                                   | 1              |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| addStylesheet                    | Include the default stylesheet                                                   | 1              |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| disableConsent                   | Disable the consent modal                                                        | 0              |
+----------------------------------+----------------------------------------------------------------------------------+----------------+
| addLanguageToCookie              | Add the current language to the consent cookie                                   | 0              |
+----------------------------------+----------------------------------------------------------------------------------+----------------+

TypoScript Condition
====================

The extension provides the ``cookieConsent()`` TypoScript condition function. It reads the consent cookie and returns a wrapper with helper methods for TypoScript conditions.

Use ``getConsentOptions()`` to access the accepted cookie option identifiers:

.. code-block:: typoscript

    ['youtube' in cookieConsent().getConsentOptions()]
      lib.youtubeDsgvo = 1
    [end]

Use ``hasOption()`` to check a single cookie option identifier:

.. code-block:: typoscript

    [cookieConsent().hasOption('youtube')]
      lib.youtubeDsgvo = 1
    [end]

Use ``hasConsent()`` to check whether the user has submitted a consent decision:

.. code-block:: typoscript

    [cookieConsent().hasConsent()]
      lib.cookieConsentGiven = 1
    [end]

By default, ``cookieConsent()`` reads the default cookie name ``cookie_consent``. If you configured a custom cookie name, pass it as the first argument:

.. code-block:: typoscript

    [cookieConsent('custom_cookie_name').hasOption('youtube')]
      lib.youtubeDsgvo = 1
    [end]

When ``addLanguageToCookie`` is enabled, the function automatically reads the consent state and option identifiers for the current site language.

Page Cache Identifier
=====================

If you use ``cookieConsent()`` conditions to change cacheable page output, enable the extension setting ``addConsentOptionsToPageCacheIdentifier``.

When enabled, the selected consent option identifiers are added to TYPO3's page cache identifier. This creates separate page cache variants for different consent option combinations and prevents cached output for one consent state from being reused for another consent state.

The setting is disabled by default because it can increase the number of page cache variants. Enable it only when cacheable TypoScript output depends on the accepted consent options.

With ``addConsentOptionsToPageCacheIdentifier`` enabled, pages rendered with the ``youtube`` option accepted use a different page cache identifier than pages rendered without that option.
