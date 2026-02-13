.. _changelog:

=========
Changelog
=========

* 4.1.2

  * Add missing defaults for array keys to prevent warnings on missing TypoScript configuration
  * Remove usage of TypoScriptFrontendController
  * Remove excluded parameters from current url parameter due to cache flooding issues
  * Remove root page id for consent form submit target page

* 4.1.1

  * Add missing PHP 8.4 compatibility
  * Add optimizations for the documentation

* 4.1.0

  * Add missing TCA label/value adjustment for UserFunc items
  * Migrate documentation from sphinx to PHP based rendering
  * Adjust Cookie Consent to a11y standards

* 4.0.0

  * Add compatibility for TYPO3 v13
  * Drop support for TYPO3 v11
  * Statistic record date refactoring

* 3.2.2

  * Clone object storage to prevent mixing of cookie options

* 3.2.1

  * Add optional page reload if consent is denied on re-editing
  * Insert replacement element at the proper position in DOM
  * Properly determine configuration parameter & language in statistic module
  * Add version dependent language label handling for statistic module

* 3.2.0

  * Add Google Consent Mode for cookie options
  * Remove max items validation for categories and cookie options
  * Use event delegation for consent button replacements
  * Add workaround for a `Core bug <https://forge.typo3.org/issues/102847>`__ creating errors in the backend

* 3.1.2

  * Increase maximum allowed PHP version

* 3.1.1

  * Use AssetCollector for consent JavaScript & StyleSheets

* 3.1.0

  * Remove unnecessary parameter due to a breaking change
  * Add option to add necessary cookie category to list plugin

* 3.0.2

  * Use proper assignment for variable to view
  * Allow caching of modal rendering
  * Prevent exception caused by broken consent cookies
  * Update and declare numbered_pagination dependency in emconf

* 3.0.1

  * Add consent replacement parent element due to unavailability after removing the original event target
  * Allow PHP versions for 8.2.x properly in version constraint

* 3.0.0

  * Add compatibility for TYPO3 v12
  * Drop support for TYPO3 v10
  * Add original click event to "cookieConsentButtonsReplaced"-event
  * Add numbered pagination for backend optimization
  * Only load scripts for accepted consent replacements

* 2.2.5

  * Use proper total for deny buttons percentage calculation
  * Add default value to prevent SQL error with "strict trans tables" activated

* 2.2.4

  * Add template arguments option to consent view helper

* 2.2.3

  * Only replace direct children in consent button replacement

* 2.2.2

  * Optimize consent replacement function to prevent pseudo div wrap

* 2.2.1

  * Add custom replacement button label for cookie option

* 2.2.0

  * Fix install event check query for initial default configuration
  * Add disable option for the consent
  * Add option to add the language into the consent cookie
  * Add missing deny label field for configuration

* 2.1.4

  * Don't open modal on imprint & datapolicy page on active lazyloading
  * Add custom event for replaced consent buttons

* 2.1.3

  * Solve translation and compatibility issues

* 2.1.2

  * Add redirect url host check to prevent an "open redirect"

* 2.1.1

  * Use object manager in TYPO3 v10 to instantiate dataMapper

* 2.1.0

  * Add deny button due to ePrivacy changes

* 2.0.1

  * Add missing changes to emconf

* 2.0.0

  * Add TYPO3 v11 compatibility
  * Drop support for TYPO3 v9

* 1.2.2

  * Fixed not replaced multiple replacement buttons

* 1.2.1

  * Use proper replace method for consent replacements

* 1.2.0

  * Add lazyloading option for consent modal
  * Close details when modal is closed
  * Check existing cookie if other consent buttons are clicked
  * Respect current configuration in "all time" statistics
  * Use existing field as tca label to prevent errors in database comparison

* 1.1.1

  * Add cookie option object to iframe replacement media template
  * Add flexform option to change the settings plugin button label

* 1.1.0

  * Add new plugin to list all cookies options & -categories
  * Optimize the hide condition for "select all" button

* 1.0.7

  * Add check if TypoScript is available
  * Hide "select all" button if no optional cookies are available
  * Remove trailing slash from assets path to properly resolve TYPO3 in a subfolder

* 1.0.6

  * Use uid instead of identifier for necessary cookies

* 1.0.5

  * Add option to push consent options to TagManager

* 1.0.4

  * Move global css styles into consent container

* 1.0.3

  * Add total period for statistic date select
  * Add statistic for single cookie selection

* 1.0.2

  * Alternative language label determination for "All sites" configuraiton

* 1.0.1

  * Make cookie name editable over TypoScript
  * Add default necessary cookie option "consent cookie"

* 1.0.0

  * Initial release
