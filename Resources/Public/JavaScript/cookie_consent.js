'use strict';

(function () {
  // Polyfill for IE missing custom javascript events ---------------------------------------------
  // (see: https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent/CustomEvent#Polyfill) -----
  if (typeof window.CustomEvent === 'function') return false;

  function CustomEvent(event, params) {
    let customEvent = document.createEvent('CustomEvent');

    params = params || { bubbles: false, cancelable: false, detail: null };
    customEvent.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);

    return customEvent;
  }

  window.CustomEvent = CustomEvent;
  //-----------------------------------------------------------------------------------------------

  // Polyfill for IE not supporting forEach on nodelists
  if (typeof NodeList.prototype.forEach === 'undefined') {
    NodeList.prototype.forEach = Array.prototype.forEach;
  }
  //-----------------------------------------------------------------------------------------------

  // Polyfill for closest selector ----------------------------------------------------------------
  // (see: https://developer.mozilla.org/en-US/docs/Web/API/Element/closest#Polyfill) -------------
  if (!Element.prototype.matches) {
    Element.prototype.matches =
      Element.prototype.msMatchesSelector ||
      Element.prototype.webkitMatchesSelector;
  }

  if (!Element.prototype.closest) {
    Element.prototype.closest = function (selector) {
      let element = this;

      do {
        if (Element.prototype.matches.call(element, selector)) {
          return element;
        }

        element = element.parentElement || element.parentNode;
      } while (element !== null && element.nodeType === 1);

      return null;
    };
  }
  //-----------------------------------------------------------------------------------------------
})();

(function (cookieConsentConfiguration) {
  const _cookieConsent = {

    cookieName: 'cookie_consent',
    settingsClass: '',
    openButtonClass: 'cookie-consent-open',
    detailsOpenContainerSelector: '.detail, .show-details, .consent-modal',
    consentVariableName: 'cookieConsent',
    containerDisplayStyle: 'block',
    currentLanguageCode: null,
    expiryDays: 365,
    modalContainer: null,
    modalForm: null,
    saveButton: null,
    denyButton: null,
    selectAllButton: null,
    isSelectAll: false,
    isDeny: false,
    hideOnInit: false,
    pushConsentToTagManager: false,
    lazyloading: false,
    lazyloadingTimeout: 120000,
    lazyloadingEvents: ['mousedown', 'mousemove', 'keydown', 'scroll', 'touchstart'],
    consentButtons: [],
    consentScripts: [],

    /**
     * @param {object} configuration
     */
    init: function (configuration) {
      const that = this;
      this.cookieName = 'cookieName' in configuration ? configuration.cookieName : this.cookieName;
      this.openButtonClass = 'openButtonClass' in configuration ? configuration.openButtonClass : this.openButtonClass;
      this.currentLanguageCode = 'currentLanguageCode' in configuration ? configuration.currentLanguageCode : this.currentLanguageCode;
      this.expiryDays = 'expiryDays' in configuration ? parseInt(configuration.expiryDays) : this.expiryDays;
      this.hideOnInit = 'hideOnInit' in configuration ? Boolean(configuration.hideOnInit) : this.hideOnInit;
      this.pushConsentToTagManager = 'pushConsentToTagManager' in configuration ? Boolean(configuration.pushConsentToTagManager) : false;
      this.lazyloading = 'lazyloading' in configuration ? Boolean(configuration.lazyloading) : this.lazyloading;
      this.lazyloadingTimeout = 'lazyloadingTimeout' in configuration ? parseInt(configuration.lazyloadingTimeout) * 1000 : this.lazyloadingTimeout;

      this.updateConsentButtons();

      window[this.consentVariableName] = { consent: false, options: [] };
      window.cookieConsentModalToggle = function () {
        that.modalContainer.style.display = 'none' === that.modalContainer.style.display
          ? that.containerDisplayStyle
          : 'none';
      };

      if ('containerId' in configuration) {
        try {
          this.modalContainer = document.querySelector('#' + configuration.containerId);
        } catch (exception) {
          throw new Error('invalid container selector');
        }
      }

      if (null !== this.modalContainer) {
        this.saveButton = this.modalContainer.querySelector('button.save, input.save');
        this.denyButton = this.modalContainer.querySelector('button.deny, input.deny');
        this.selectAllButton = this.modalContainer.querySelector('button.select-all, input.select-all');

        this.registerButtonEvents(this.modalContainer);
        this.modalForm = this.modalContainer.querySelector('form');
      }

      if (true === this.hasConsent()) {
        this.consentEventDispatch();
      } else if (false === this.hideOnInit && false === this.lazyloading) {
        this.openModal(this.modalContainer);
      } else if (false === this.hideOnInit && true === this.lazyloading) {
        this.lazyOpenModal(this.modalContainer);
      }

      document.querySelectorAll('.' + this.openButtonClass).forEach(function (openButton) {
        openButton.addEventListener('click', function (event) {
          event.preventDefault();
          that.modalContainer.style.display = that.containerDisplayStyle;
        });
      });

      this.consentButtons.forEach(function (acceptButton) {
        acceptButton.addEventListener('click', function () {
          let cookie = that.getCookie();
          let cookieOpions = null !== cookie ? cookie.getOptions() : [];

          cookieOpions.push(this.getAttribute('data-identifier'));

          that.setConsentCookie(cookieOpions);
          that.replaceConsentButtons(this.getAttribute('data-identifier'));
        });
      });

      this.modalForm.querySelectorAll('.option').forEach(function (optionCheckbox) {
        optionCheckbox.addEventListener('change', function () {
          const parentOptionCheckbox = this;
          const cookieOptionsList = that.modalForm.querySelector('.cookieoptions[data-parent="#' + this.id + '"]');

          cookieOptionsList.querySelectorAll('input[type="checkbox"]').forEach(function (cookieOptionCheckbox) {
            cookieOptionCheckbox.checked = parentOptionCheckbox.checked;
          });

          that.updateParentOptionState(cookieOptionsList);
        });
      });

      this.modalForm.querySelectorAll('.cookieoptions input[type="checkbox"]').forEach(function (cookieOptionCheckbox) {
        cookieOptionCheckbox.addEventListener('change', function () {
          const cookieOptionsList = this.closest('.cookieoptions');

          if (cookieOptionsList instanceof Element) {
            that.updateParentOptionState(cookieOptionsList);
          }
        });
      });
    },

    /**
     * @param {HTMLElement} container
     */
    lazyOpenModal: function (container) {
      const that = this;
      let lazyloadingTimeout = null;

      if (0 < this.lazyloadingTimeout) {
        lazyloadingTimeout = setTimeout(function () {that.openModal(container);}, this.lazyloadingTimeout);
      }

      const interactionEventListener = function () {
        that.openModal(container);
        clearTimeout(lazyloadingTimeout);
        that.lazyloadingEvents.forEach(function (eventName) {
          document.removeEventListener(eventName, interactionEventListener);
        });
      };

      this.lazyloadingEvents.forEach(function (eventName) {
        document.addEventListener(eventName, interactionEventListener);
      });
    },

    updateConsentButtons: function () {
      this.consentButtons = document.querySelectorAll('.cookie-consent-replacement .accept');
    },

    /**
     * @param {string} cookieOption
     */
    replaceConsentButtons: function (cookieOption) {
      const that = this;

      this.consentButtons.forEach(function (acceptButton) {
        const consentReplacement = acceptButton.closest('.cookie-consent-replacement');
        const textArea = document.createElement('textarea');
        const replacement = document.createElement('div');

        if (cookieOption === acceptButton.getAttribute('data-identifier')) {
          textArea.innerHTML = consentReplacement.getAttribute('data-replacement');
          replacement.innerHTML = textArea.innerText;

          Array.prototype.slice.call(replacement.children).forEach(function (replaceElement) {
            consentReplacement.parentNode.appendChild(replaceElement);
            consentReplacement.parentNode.insertBefore(consentReplacement, replaceElement);
          });

          consentReplacement.parentNode.removeChild(consentReplacement);

          that.updateConsentButtons();
        }

        if (true === consentReplacement.hasAttribute('data-scripts')) {
          const scripts = JSON.parse(consentReplacement.getAttribute('data-scripts'));

          for (let key in scripts) {
            let async = false;
            let defer = false;
            let src = undefined;
            let eventName = undefined;

            if (typeof key === 'string') {
              eventName = key;
            }

            if (typeof scripts[key] === 'string') {
              src = scripts[key];
            } else {
              src = scripts[key]['src'];
              async = scripts[key]['async'];
              defer = scripts[key]['defer'];
            }

            if (-1 === that.consentScripts.indexOf(src)) {
              that.consentScripts.push(src);
              that.addScript(src, async, defer, eventName);
            }
          }
        }
      });
    },

    /**
     * @param {string} src
     * @param {boolean} async
     * @param {boolean} defer
     * @param {string} eventName
     */
    addScript: function (src, async, defer, eventName) {
      const script = document.createElement('script');

      script.async = async;
      script.defer = defer;

      if (typeof eventName === 'string') {
        script.onload = script.onreadystatechange = function (_, isAbort) {
          if (isAbort || !this.readyState || /loaded|complete/.test(this.readyState)) {
            this.onload = null;
            this.onreadystatechange = null;

            if (!isAbort) {
              window.dispatchEvent(new CustomEvent(eventName));
            }
          }
        };
      }

      script.src = src;
      document.body.appendChild(script);
    },

    /**
     * @param {HTMLElement} container
     */
    registerButtonEvents: function (container) {
      const that = this;
      const showDetailsButton = container.querySelector('.show-details');

      if (null !== this.selectAllButton) {
        this.selectAllButton.addEventListener('click', function (event) {
          that.isSelectAll = true;
          that.isDeny = false;
          that.toggleFormDisabledState(true);

          that.modalForm.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
            checkbox.checked = true;
          });

          // Workaround for older edge versions not supporting URLSearchParams
          if (typeof URLSearchParams === 'undefined') {
            that.fallbackSubmitForm();
            return;
          } else {
            event.preventDefault();
          }

          that.submitForm();
        });
      }

      if (null !== this.saveButton) {
        this.saveButton.addEventListener('click', function (event) {
          that.isSelectAll = false;
          that.isDeny = false;
          // Workaround for older edge versions not supporting URLSearchParams
          if (typeof URLSearchParams === 'undefined') {
            that.fallbackSubmitForm();
            return;
          } else {
            event.preventDefault();
          }

          that.toggleFormDisabledState(true);
          that.submitForm();
        });
      }

      if (null !== this.denyButton) {
        this.denyButton.addEventListener('click', function (event) {
          that.isSelectAll = false;
          that.isDeny = true;
          that.toggleFormDisabledState(true);

          that.modalForm.querySelectorAll('input[type="checkbox"]:not(.option-necessary)').forEach(function (checkbox) {
            checkbox.checked = false;
          });

          // Workaround for older edge versions not supporting URLSearchParams
          if (typeof URLSearchParams === 'undefined') {
            that.fallbackSubmitForm();
            return;
          } else {
            event.preventDefault();
          }

          that.toggleFormDisabledState(true);
          that.submitForm();
        });
      }

      if (null !== showDetailsButton) {
        showDetailsButton.addEventListener('click', function (event) {
          event.preventDefault();
          that.toggleModalDetails(container);
        });
      }
    },

    /**
     * @param {HTMLElement} container
     */
    toggleModalDetails: function (container) {
      container.querySelectorAll(this.detailsOpenContainerSelector).forEach(function (element) {
        element.classList.toggle('open');
      });
    },

    /**
     * @param {HTMLElement} container
     */
    openModalDetails: function (container) {
      container.querySelectorAll(this.detailsOpenContainerSelector).forEach(function (element) {
        element.classList.add('open');
      });
    },

    /**
     * @param {HTMLElement} container
     */
    closeModalDetails: function (container) {
      container.querySelectorAll(this.detailsOpenContainerSelector).forEach(function (element) {
        element.classList.remove('open');
      });
    },

    /**
     * @returns {boolean}
     */
    hasCookie: function () {
      return null !== this.getCookie() && this.getCookie() instanceof Object;
    },

    /**
     * @returns {boolean}
     */
    hasConsent: function() {
      return true === this.hasCookie() && true === this.getCookie().getConsent();
    },

    /**
     * @returns {object|null}
     */
    getCookie: function () {
      const that = this;
      const cookie = document.cookie.match('(^|[^;]+)\\s*' + this.cookieName + '\\s*=\\s*([^;]+)');
      const consent = null !== cookie ? JSON.parse(decodeURIComponent(cookie.pop())) : null;

      if (null !== consent) {
        consent['hasOption'] = function (identifier) {
          if (null === that.currentLanguageCode) {
            return 0 <= this.options.indexOf(identifier);
          } else {
            return that.currentLanguageCode in this.languageOptions &&
              0 <= this.languageOptions[that.currentLanguageCode].indexOf(identifier);
          }
        };

        consent['getOptions'] = function () {
          if (null === that.currentLanguageCode) {
            return this.options;
          } else if (that.currentLanguageCode in this.languageOptions) {
            return this.languageOptions[that.currentLanguageCode];
          }

          return [];
        };

        consent['getConsent'] = function () {
          if (null === that.currentLanguageCode) {
            return this.consent;
          } else if (that.currentLanguageCode in this.languageConsent) {
            return this.languageConsent[that.currentLanguageCode];
          }

          return false;
        };
      }

      return consent;
    },

    submitForm: function () {
      const that = this;

      this.setXhrSubmit(this.modalForm, true);

      setTimeout(function () {
        that.closeModal(that.modalContainer);
      }, 200);

      try {
        const formData = new FormData();

        this.modalForm.querySelectorAll('input').forEach(function (input) {
          if (
            false === input.disabled &&
            (
              'checkbox' !== input.type ||
              true === input.checked
            )
          ) {
            formData.append(input.name, input.value);
          }
        });

        const parameters = new URLSearchParams();
        const formDataEntries = formData.entries();
        let formDataEntry = formDataEntries.next();

        while (false === formDataEntry.done) {
          parameters.append(formDataEntry.value[0], formDataEntry.value[1]);
          formDataEntry = formDataEntries.next();
        }

        if (true === this.isDeny) {
          parameters.append(this.modalForm.querySelector('.deny').getAttribute('name'), '1');
        } else if (true === this.isSelectAll) {
          parameters.append(this.modalForm.querySelector('.select-all').getAttribute('name'), '1');
        }

        fetch(
          this.modalForm.getAttribute('action'),
          {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: parameters
          }
        ).then(function (response) {
          if (200 !== response.status) {
            throw new Error('xhr request failed: ' + response.status + ' - reason: "' + response.statusText + '"');
          }

          that.isSelectAll = false;
          that.toggleFormDisabledState(false);
        }).catch(function (error) {
          that.toggleFormDisabledState(false);
          console.error(error);
        });
      } catch (error) {
        that.toggleFormDisabledState(false);
        console.error(error);
      }

      this.setConsentCookie();
    },

    fallbackSubmitForm: function () {
      this.setXhrSubmit(this.modalForm, false);

      if (true === this.isSelectAll) {
        const input = document.createElement('input');

        input.type = 'hidden';
        input.name = this.modalForm.querySelector('.select-all').getAttribute('name');
        input.value = '1';

        this.modalForm.appendChild(input);
      }

      this.setConsentCookie();
    },

    /**
     * @param {array} [cookieOptions]
     */
    setConsentCookie: function (cookieOptions) {
      const that = this;
      const expiryDate = new Date();

      expiryDate.setDate(expiryDate.getDate() + this.expiryDays);

      if (false === Array.isArray(cookieOptions)) {
        cookieOptions = [];

        this.modalForm.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
          if (true === checkbox.checked && null !== checkbox.getAttribute('data-identifier')) {
            cookieOptions.push(checkbox.getAttribute('data-identifier'));
          }
        });
      }

      if (
        true === this.pushConsentToTagManager &&
        window.dataLayer instanceof Object &&
        window.dataLayer.push instanceof Function
      ) {
        window.dataLayer.push({
          'event': 'cookieConsent',
          'options': cookieOptions
        });
      }

      let cookie = {};

      if (null === this.currentLanguageCode) {
        cookie.consent = true;
        cookie.options = cookieOptions;
      } else {
        cookie.languageConsent = {};
        cookie.languageOptions = {};

        if (true === this.hasCookie()) {
          if ('languageOptions' in this.getCookie()) {
            cookie.languageOptions = this.getCookie()['languageOptions'];
          }

          if ('languageConsent' in this.getCookie()) {
            cookie.languageConsent = this.getCookie()['languageConsent'];
          }
        }

        cookie.languageConsent[this.currentLanguageCode] = true;
        cookie.languageOptions[this.currentLanguageCode] = cookieOptions;
      }

      document.cookie = that.cookieName
        + '='
        + encodeURI(JSON.stringify(cookie))
        + ';expires='
        + expiryDate.toUTCString()
        + ';samesite=strict'
        + ';path=/';

      this.consentEventDispatch();
    },

    /**
     * @param {HTMLElement} form
     * @param {boolean} enable
     */
    setXhrSubmit: function (form, enable) {
      if (null !== form) {
        form.querySelector('.is-ajax').value = true === enable ? 1 : 0;
      }
    },

    /**
     * @param {boolean} state
     */
    toggleFormDisabledState: function (state) {
      if (null !== this.selectAllButton) {
        this.selectAllButton.disabled = state;
      }

      this.saveButton.disabled = state;

      this.modalForm.querySelectorAll('input[type="checkbox"]:not(.option-necessary)').forEach(function (checkbox) {
        checkbox.disabled = state;
      });
    },

    /**
     * @param {HTMLElement} container
     * @return {boolean}
     */
    isModalOpen: function (container) {
      return container.style.display === this.containerDisplayStyle;
    },

    /**
     * @param {HTMLElement} container
     */
    openModal: function (container) {
      container.style.display = this.containerDisplayStyle;
      this.closeModalDetails(container);
    },

    /**
     * @param {HTMLElement} container
     */
    closeModal: function (container) {
      container.style.display = 'none';
      this.closeModalDetails(container);
    },

    consentEventDispatch: function () {
      const that = this;

      if (false === this.hasCookie()) {
        throw new Error('Can\'t do event dispatch if the necessary cookie hasn\'t been set');
      }

      window[this.consentVariableName] = this.getCookie();

      window.dispatchEvent(
        new CustomEvent('cookieConsent', { detail: this.getCookie() })
      );

      this.modalForm.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
        if (true === that.getCookie().hasOption(checkbox.getAttribute('data-identifier'))) {
          checkbox.checked = true;
        }
      });

      this.modalForm.querySelectorAll('.cookieoptions').forEach(function (cookieOptionsList) {
        that.updateParentOptionState(cookieOptionsList);
      });

      this.getCookie().getOptions().forEach(function (cookieOption) {
        that.replaceConsentButtons(cookieOption);
      });

      window.dispatchEvent(
        new CustomEvent('cookieConsentButtonsReplaced', { detail: this.getCookie() })
      );
    },

    /**
     * @param {object} cookieOptionsList
     */
    updateParentOptionState: function (cookieOptionsList) {
      const parentCheckbox = this.modalForm.querySelector(cookieOptionsList.getAttribute('data-parent'));
      const parentCheckboxLabel = parentCheckbox.closest('.label');
      const checkboxes = cookieOptionsList.querySelectorAll('input[type="checkbox"]');
      const checkedCheckboxes = cookieOptionsList.querySelectorAll('input[type="checkbox"]:checked');

      if (0 === checkedCheckboxes.length) {
        parentCheckboxLabel.classList.remove('partially-checked');
        parentCheckbox.checked = false;
      } else if (checkboxes.length === checkedCheckboxes.length) {
        parentCheckboxLabel.classList.remove('partially-checked');
        parentCheckbox.checked = true;
      } else {
        parentCheckboxLabel.classList.add('partially-checked');
        parentCheckbox.checked = false;
      }
    }
  };

  document.addEventListener('DOMContentLoaded', function () {
    try {
      _cookieConsent.init(cookieConsentConfiguration);
    } catch (exception) {
      console.error('Cookie Consent: ' + exception);
    }
  });

})(typeof cookieConsentConfiguration === 'object' ? cookieConsentConfiguration : {});

// Example
// window.addEventListener('cookieConsent', function (event) {
//   console.debug('Cookie Consent:')
//   console.debug(event.detail.getOptions())
// });
