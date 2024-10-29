!function(){"use strict";(function(){if("function"==typeof window.CustomEvent)return!1;window.CustomEvent=function(e,t){let n=document.createEvent("CustomEvent");return t=t||{bubbles:!1,cancelable:!1,detail:null},n.initCustomEvent(e,t.bubbles,t.cancelable,t.detail),n},void 0===NodeList.prototype.forEach&&(NodeList.prototype.forEach=Array.prototype.forEach),Element.prototype.matches||(Element.prototype.matches=Element.prototype.msMatchesSelector||Element.prototype.webkitMatchesSelector),Element.prototype.closest||(Element.prototype.closest=function(e){let t=this;do{if(Element.prototype.matches.call(t,e))return t;t=t.parentElement||t.parentNode}while(null!==t&&1===t.nodeType);return null})})(),function(e){function t(){window.dataLayer.push(arguments)}const n={cookieName:"cookie_consent",settingsClass:"",openButtonClass:"cookie-consent-open",detailsOpenContainerSelector:".detail, .show-details, .consent-modal",consentVariableName:"cookieConsent",containerDisplayStyle:"block",currentLanguageCode:null,expiryDays:365,modalContainer:null,modalForm:null,saveButton:null,denyButton:null,selectAllButton:null,isSelectAll:!1,isDeny:!1,isReediting:!1,reloadOnReeditDeny:!1,hideOnInit:!1,pushConsentToTagManager:!1,lazyloading:!1,lazyloadingTimeout:12e4,lazyloadingEvents:["mousedown","mousemove","keydown","scroll","touchstart"],consentScripts:[],consentMode:{},init:function(e){const n=this;if(this.cookieName="cookieName"in e?e.cookieName:this.cookieName,this.openButtonClass="openButtonClass"in e?e.openButtonClass:this.openButtonClass,this.currentLanguageCode="currentLanguageCode"in e?e.currentLanguageCode:this.currentLanguageCode,this.expiryDays="expiryDays"in e?parseInt(e.expiryDays):this.expiryDays,this.hideOnInit="hideOnInit"in e?Boolean(e.hideOnInit):this.hideOnInit,this.reloadOnReeditDeny="reloadOnReeditDeny"in e?Boolean(e.reloadOnReeditDeny):this.reloadOnReeditDeny,this.pushConsentToTagManager="pushConsentToTagManager"in e&&Boolean(e.pushConsentToTagManager),this.lazyloading="lazyloading"in e?Boolean(e.lazyloading):this.lazyloading,this.lazyloadingTimeout="lazyloadingTimeout"in e?1e3*parseInt(e.lazyloadingTimeout):this.lazyloadingTimeout,this.consentMode="consentMode"in e?e.consentMode:this.consentMode,window.dataLayer=window.dataLayer||[],window[this.consentVariableName]={consent:!1,options:[]},window.cookieConsentModalToggle=function(){n.modalContainer.style.display="none"===n.modalContainer.style.display?n.containerDisplayStyle:"none"},window.cookieConsentReplaceConsentButtons=function(){n.replaceConsentButtonsForAcceptedCookies()},Object.keys(this.consentMode).length>0){let e={};Object.values(this.consentMode).forEach((t=>t.forEach((t=>e[t]="denied")))),t("consent","default",e)}if("containerId"in e)try{this.modalContainer=document.querySelector("#"+e.containerId)}catch(e){throw new Error("invalid container selector")}null!==this.modalContainer&&(this.saveButton=this.modalContainer.querySelector("button.save, input.save"),this.denyButton=this.modalContainer.querySelector("button.deny, input.deny"),this.selectAllButton=this.modalContainer.querySelector("button.select-all, input.select-all"),this.registerButtonEvents(this.modalContainer),this.modalForm=this.modalContainer.querySelector("form")),!0===this.hasConsent()?this.consentEventDispatch():!1===this.hideOnInit&&!1===this.lazyloading?this.openModal(this.modalContainer):!1===this.hideOnInit&&!0===this.lazyloading&&this.lazyOpenModal(this.modalContainer),document.querySelectorAll("."+this.openButtonClass).forEach((function(e){e.addEventListener("click",(function(e){e.preventDefault(),n.modalContainer.style.display=n.containerDisplayStyle,n.isReediting=!0}))})),document.addEventListener("click",(e=>{if(e.target.closest(".cookie-consent-replacement")instanceof HTMLElement){const t=e.target.classList.contains(".accept")?e.target:e.target.closest(".accept");if(t instanceof HTMLElement){let o=this.getCookie(),i=null!==o?o.getOptions():[];i.push(t.getAttribute("data-identifier")),n.setConsentCookie(i,e),n.replaceConsentButtons(t.getAttribute("data-identifier"))}}})),this.modalForm.querySelectorAll(".option").forEach((function(e){e.addEventListener("change",(function(){const e=this,t=n.modalForm.querySelector('.cookieoptions[data-parent="#'+this.id+'"]');t.querySelectorAll('input[type="checkbox"]').forEach((function(t){t.checked=e.checked})),n.updateParentOptionState(t)}))})),this.modalForm.querySelectorAll('.cookieoptions input[type="checkbox"]').forEach((function(e){e.addEventListener("change",(function(){const e=this.closest(".cookieoptions");e instanceof Element&&n.updateParentOptionState(e)}))}))},lazyOpenModal:function(e){const t=this;let n=null;0<this.lazyloadingTimeout&&(n=setTimeout((function(){t.openModal(e)}),this.lazyloadingTimeout));const o=function(){t.openModal(e),clearTimeout(n),t.lazyloadingEvents.forEach((function(e){document.removeEventListener(e,o)}))};this.lazyloadingEvents.forEach((function(e){document.addEventListener(e,o)}))},replaceConsentButtonsForAcceptedCookies:function(){this.getCookie().getOptions().forEach((e=>this.replaceConsentButtons(e)))},replaceConsentButtons:function(e){const t=this;document.querySelectorAll(".cookie-consent-replacement .accept").forEach((function(n){const o=n.closest(".cookie-consent-replacement"),i=document.createElement("textarea"),a=document.createElement("div");if(e===n.getAttribute("data-identifier")){if(i.innerHTML=o.getAttribute("data-replacement"),a.innerHTML=i.innerText,Array.prototype.slice.call(a.children).forEach((function(e){o.parentNode.appendChild(e),o.parentNode.insertBefore(e,o)})),!0===o.hasAttribute("data-scripts")){const e=JSON.parse(o.getAttribute("data-scripts"));for(let n in e){let o,i,a=!1,s=!1;"string"==typeof n&&(i=n),"string"==typeof e[n]?o=e[n]:(o=e[n].src,a=e[n].async,s=e[n].defer),-1===t.consentScripts.indexOf(o)&&(t.consentScripts.push(o),t.addScript(o,a,s,i))}}o.parentNode.removeChild(o)}}))},addScript:function(e,t,n,o){const i=document.createElement("script");i.async=t,i.defer=n,"string"==typeof o&&(i.onload=i.onreadystatechange=function(e,t){(t||!this.readyState||/loaded|complete/.test(this.readyState))&&(this.onload=null,this.onreadystatechange=null,t||window.dispatchEvent(new CustomEvent(o)))}),i.src=e,document.body.appendChild(i)},registerButtonEvents:function(e){const t=this,n=e.querySelector(".show-details");null!==this.selectAllButton&&this.selectAllButton.addEventListener("click",(function(e){t.isSelectAll=!0,t.isDeny=!1,t.toggleFormDisabledState(!0),t.modalForm.querySelectorAll('input[type="checkbox"]').forEach((function(e){e.checked=!0})),"undefined"!=typeof URLSearchParams?(e.preventDefault(),t.submitForm()):t.fallbackSubmitForm()})),null!==this.saveButton&&this.saveButton.addEventListener("click",(function(e){t.isSelectAll=!1,t.isDeny=!1,"undefined"!=typeof URLSearchParams?(e.preventDefault(),t.toggleFormDisabledState(!0),t.submitForm()):t.fallbackSubmitForm()})),null!==this.denyButton&&this.denyButton.addEventListener("click",(function(e){t.isSelectAll=!1,t.isDeny=!0,t.toggleFormDisabledState(!0),t.modalForm.querySelectorAll('input[type="checkbox"]:not(.option-necessary)').forEach((function(e){e.checked=!1})),"undefined"!=typeof URLSearchParams?(e.preventDefault(),t.toggleFormDisabledState(!0),t.submitForm(),t.reloadOnReeditDeny&&t.isReediting&&window.location.reload()):t.fallbackSubmitForm()})),null!==n&&n.addEventListener("click",(function(n){n.preventDefault(),t.toggleModalDetails(e)}))},toggleModalDetails:function(e){e.querySelectorAll(this.detailsOpenContainerSelector).forEach((function(e){e.classList.toggle("open")}))},openModalDetails:function(e){e.querySelectorAll(this.detailsOpenContainerSelector).forEach((function(e){e.classList.add("open")}))},closeModalDetails:function(e){e.querySelectorAll(this.detailsOpenContainerSelector).forEach((function(e){e.classList.remove("open")}))},hasCookie:function(){return null!==this.getCookie()&&this.getCookie()instanceof Object},hasConsent:function(){return!0===this.hasCookie()&&!0===this.getCookie().getConsent()},getCookie:function(){const e=this,t=document.cookie.match("(^|[^;]+)\\s*"+this.cookieName+"\\s*=\\s*([^;]+)");let n=null;try{n=null!==t?JSON.parse(decodeURIComponent(t.pop())):null}catch(e){return null}return null!==n&&(n.hasOption=function(t){return null===e.currentLanguageCode?0<=this.options.indexOf(t):e.currentLanguageCode in this.languageOptions&&0<=this.languageOptions[e.currentLanguageCode].indexOf(t)},n.getOptions=function(){return null===e.currentLanguageCode?this.options:e.currentLanguageCode in this.languageOptions?this.languageOptions[e.currentLanguageCode]:[]},n.getConsent=function(){return null===e.currentLanguageCode?this.consent:e.currentLanguageCode in this.languageConsent&&this.languageConsent[e.currentLanguageCode]}),n},submitForm:function(){const e=this;this.setXhrSubmit(this.modalForm,!0),setTimeout((function(){e.closeModal(e.modalContainer)}),200);try{const t=new FormData;this.modalForm.querySelectorAll("input").forEach((function(e){!1!==e.disabled||"checkbox"===e.type&&!0!==e.checked||t.append(e.name,e.value)}));const n=new URLSearchParams,o=t.entries();let i=o.next();for(;!1===i.done;)n.append(i.value[0],i.value[1]),i=o.next();!0===this.isDeny?n.append(this.modalForm.querySelector(".deny").getAttribute("name"),"1"):!0===this.isSelectAll&&n.append(this.modalForm.querySelector(".select-all").getAttribute("name"),"1"),fetch(this.modalForm.getAttribute("action"),{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded"},body:n}).then((function(t){if(200!==t.status)throw new Error("xhr request failed: "+t.status+' - reason: "'+t.statusText+'"');e.isSelectAll=!1,e.toggleFormDisabledState(!1)})).catch((function(t){e.toggleFormDisabledState(!1),console.error(t)}))}catch(t){e.toggleFormDisabledState(!1),console.error(t)}this.setConsentCookie()},fallbackSubmitForm:function(){if(this.setXhrSubmit(this.modalForm,!1),!0===this.isSelectAll){const e=document.createElement("input");e.type="hidden",e.name=this.modalForm.querySelector(".select-all").getAttribute("name"),e.value="1",this.modalForm.appendChild(e)}this.setConsentCookie()},setConsentCookie:function(e,n){const o=new Date;o.setDate(o.getDate()+this.expiryDays),!1===Array.isArray(e)&&(e=[],this.modalForm.querySelectorAll('input[type="checkbox"]').forEach((function(t){null!==t.getAttribute("data-identifier")&&!0===t.checked&&e.push(t.getAttribute("data-identifier"))})));let i={};null===this.currentLanguageCode?(i.consent=!0,i.options=e):(i.languageConsent={},i.languageOptions={},!0===this.hasCookie()&&("languageOptions"in this.getCookie()&&(i.languageOptions=this.getCookie().languageOptions),"languageConsent"in this.getCookie()&&(i.languageConsent=this.getCookie().languageConsent)),i.languageConsent[this.currentLanguageCode]=!0,i.languageOptions[this.currentLanguageCode]=e),document.cookie=this.cookieName+"="+encodeURI(JSON.stringify(i))+";expires="+o.toUTCString()+";samesite=strict;path=/",!0===this.pushConsentToTagManager&&t({event:"cookieConsent",options:e}),this.consentEventDispatch(n)},setXhrSubmit:function(e,t){null!==e&&(e.querySelector(".is-ajax").value=!0===t?1:0)},toggleFormDisabledState:function(e){null!==this.selectAllButton&&(this.selectAllButton.disabled=e),this.saveButton.disabled=e,this.modalForm.querySelectorAll('input[type="checkbox"]:not(.option-necessary)').forEach((function(t){t.disabled=e}))},isModalOpen:function(e){return e.style.display===this.containerDisplayStyle},openModal:function(e){e.style.display=this.containerDisplayStyle,this.closeModalDetails(e)},closeModal:function(e){e.style.display="none",this.closeModalDetails(e)},consentEventDispatch:function(e){const n=this;let o,i={};if(Object.values(this.consentMode).forEach((e=>e.forEach((e=>i[e]="denied")))),void 0!==e&&(o=e.target.closest(".cookie-consent-replacement").parentNode),!1===this.hasCookie())throw new Error("Can't do event dispatch if the necessary cookie hasn't been set");window[this.consentVariableName]=this.getCookie(),window.dispatchEvent(new CustomEvent("cookieConsent",{detail:this.getCookie()})),this.modalForm.querySelectorAll('input[type="checkbox"]').forEach((function(e){!0===n.getCookie().hasOption(e.getAttribute("data-identifier"))&&(e.checked=!0)})),this.modalForm.querySelectorAll(".cookieoptions").forEach((function(e){n.updateParentOptionState(e)})),this.getCookie().getOptions().forEach((function(e){n.replaceConsentButtons(e),e in n.consentMode&&n.consentMode[e].forEach((e=>i[e]="granted"))})),Object.keys(i).length>0&&t("consent","update",i),window.dispatchEvent(new CustomEvent("cookieConsentButtonsReplaced",{detail:Object.assign({originalEvent:e,parentElement:o},this.getCookie())}))},updateParentOptionState:function(e){const t=this.modalForm.querySelector(e.getAttribute("data-parent")),n=t.closest(".label"),o=e.querySelectorAll('input[type="checkbox"]'),i=e.querySelectorAll('input[type="checkbox"]:checked');0===i.length?(n.classList.remove("partially-checked"),t.checked=!1):o.length===i.length?(n.classList.remove("partially-checked"),t.checked=!0):(n.classList.add("partially-checked"),t.checked=!1)}};document.addEventListener("DOMContentLoaded",(function(){try{n.init(e)}catch(e){console.error("Cookie Consent: "+e)}}))}("object"==typeof cookieConsentConfiguration?cookieConsentConfiguration:{})}();