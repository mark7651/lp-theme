

document.addEventListener("DOMContentLoaded", function () {
  const formTestimonials = new TESTIMONIALS('form-testimonials');
});

/**
 *--------------------------------------------------------------------------------------------------------------
 * testimonial form
 *--------------------------------------------------------------------------------------------------------------
 */

 const TESTIMONIALS = (function (window) {

  // file attachment
  let inputFile = document.getElementById("fileAttachment");
  let fileName = document.getElementById("fileName");

  if(inputFile){
    inputFile.addEventListener("change", () => {
      let inputFile = document.querySelector("input[type=file]").files[0];
      fileName.innerText = inputFile.name;
    });
  }

  let removeErrorBound = {
    length: 0
  };

  function onFieldFocus(self) {
    self.formFocused = true;
  };

  // Removes error highlighting from target element and cleans submit button
  function removeError(self, targetElement) {
    let els = document.querySelectorAll('[name=' + targetElement.name + ']'),
      i;

    for (i = 0; i < els.length; i++) {
      els[i].classList.remove("error");
      els[i].removeEventListener("focus", removeErrorBound[targetElement.name], false);
    }

    delete removeErrorBound[targetElement.name];
    removeErrorBound.length--;
    if (removeErrorBound.length <= 0) {
      removeErrorBound.length = 0;
      self.setSubmitState("initial");
    }
  };

  // Scrolls window to make visible target element on the screen
  function scrollToShowElement(element) {
   let bounding = element.getBoundingClientRect(),
      fromTop = Math.round(bounding.top) - 5,
      viewportHeight = window.innerHeight;

    if (fromTop <= 0) {
      window.scrollBy(0, fromTop);
      return;
    }

    if (fromTop >= viewportHeight) {
      window.scrollBy(0, fromTop - viewportHeight + 30);
    }
  };

  function TESTIMONIALS(formID) {
    let self = this,
      form = document.getElementById(formID);

    if (!form) {
      console.warn("Couldn't bind to form element");
      return null;
    }

    self.dict = {
      "sendSuccess": 'РЈСЃРїРµС…', //wp_ajax.success_msg, 
      "sendError": "Mail server has experienced an error. Please try again.",
      "timeoutError": "Timeout Error",
      "markedAsSpamError": "SPAM",
    }

    self.responseTimeout = 5000;
    self.url = form.action || location.href;
    self.form = form;
    self.processing = false;

    // Binding submit button
    self.submitButton = form.querySelector("[type=\"submit\"]");
    if (!self.submitButton) {
      console.warn("Couldn't bind to submit button");
      return null;
    }

    // Binding to notification box
    self.notificationBox = form.querySelector(".notification-box");
    if (!self.notificationBox) {
      console.warn("Couldn't bind to submit button");
      return null;
    }

    self.notificationBox.addEventListener("click", function () {
      this.classList.remove("show-error");
      this.classList.remove("show-success");
    }, false);

    // BOT prevent
    self.formFocused = false;
    self.focusBound = null;

    // Init
    self.init();
    return self;
  };

  TESTIMONIALS.prototype.logError = function (msg) {
    this.notify(msg, "error");
  };

  TESTIMONIALS.prototype.notify = function (message, type) {
    let notificationBox = this.notificationBox;

    if (!notificationBox) {
      console.warn("Notification box not found");
      return;
    }
    notificationBox.innerHTML = message;
    notificationBox.classList.add("show-" + (type || "error"));
    scrollToShowElement(notificationBox);

    setTimeout(function () {
      notificationBox.classList.remove("show-error");
      notificationBox.classList.remove("show-success");
    }, 4000);

  };

   // Sets state to submit button
   TESTIMONIALS.prototype.setSubmitState = function (state) {
    let self = this,
    form = self.form,
        submit = self.submitButton,
        className = submit.className.replace(/state-[a-z]+/ig, ""),
        FormClassName = form.className.replace(/state-[a-z]+/ig, "");

    self.processing = state === "processing";
    submit.className = className + " state-" + state;
    form.className = FormClassName + " state-" + state;
  };

  TESTIMONIALS.prototype.validateForm = function () {
    let self = this,
      form = self.form,
      els = form.elements,
      i,
      el,
      error = false,
      formError = false,
      emailPattern = /^([\w\-]+(?:\.[\w\-]+)*)@((?:[\w\-]+\.)*\w[\w\-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

    // Remove errors
    for (i = els.length - 1; i >= 0; --i) {
      el = els[i];
      if (removeErrorBound[el.name]) {
        removeError(self, el);
      }
    }

    // Add new errors
    for (i = els.length - 1; i >= 0; --i) {
      el = els[i];
      error = false;

      if (el.value === "" && el.required) {
        error = true;
      } else {
        if (el.type === "checkbox" && el.required && !el.checked) {
          error = true;
        }
        
        if (el.type === "email" && el.value !== "" && !emailPattern.test(el.value)) {
          error = true;
        }

        if (el.type === "radio" && el.required) {
          if (!document.querySelector('[name=' + el.name + ']:checked')) {
            error = true;
          }
        }

        if (el.tagName.toLowerCase() === "select" && el.selectedIndex == 0 && el.required) {
          error = true;
        }
    
      }

      if (error) {
        el.classList.add("error");
        if (!removeErrorBound[el.name]) {
          removeErrorBound[el.name] = removeError.bind(null, self, el);
          removeErrorBound.length++;
        }
        el.addEventListener("focus", removeErrorBound[el.name], false);
        formError = true;
      } else {
        el.classList.remove("error");
      }

      if (formError) {
        self.setSubmitState("error");
      }

    }

    // Fix for fixed top on iPad if keyboard is hidden after submit.
    setTimeout(function () {
      window.scrollBy(0, -1);
    }, 1);
    return !formError;
  };

  TESTIMONIALS.prototype.successForm = function (msg) {
    let self = this,
      form = self.form;
    form.classList.add("sent");
    self.setSubmitState("success");
    self.notify(msg, "success");
  };

  TESTIMONIALS.prototype.resetForm = function () {
    let self = this,
      formElements = self.form,
      submitButton = self.submitButton,
      tmpElement,
      i;

    formElements.classList.remove("sent");
    formElements.classList.remove("completed");

    for (i = formElements.length - 1; i >= 0; --i) {
      tmpElement = formElements[i];

      if (tmpElement !== submitButton) {
        tmpElement.classList.remove("success");
        tmpElement.value = "";
      }
    }
    self.setSubmitState("initial");
  };

  TESTIMONIALS.prototype.init = function () {
    let self = this,
      form = self.form,
      submit = self.submitButton,
      requiredElements = form.elements,
      tmpElement,
      i;

    // Bind submit event
    form.addEventListener("submit", self.submitForm.bind(self), true);

    self.focusBound = onFieldFocus.bind(null, self);

    // BOT prevent
    self.formFocused = false;
    for (i = requiredElements.length - 1; i >= 0; --i) {
      tmpElement = requiredElements[i];
      if (tmpElement.type !== "submit") {
        tmpElement.addEventListener("focus", self.focusBound, false);
      }
    }

  };

  TESTIMONIALS.prototype.send = function (formData) {
    let self = this,
      dict = self.dict;

    self.setSubmitState("initial");

    formData.append('action', 'send_testimonial');
    formData.append('nonce', wp_ajax.nonce);

    let options = {
      method: "POST",
      mode: "no-cors",
      cache: "no-cache",
      credentials: "same-origin",
      headers: {
        'Content-Type': 'application/json'
      },
      body: formData,
    }

    fetch(wp_ajax.ajax_url, options)
    .then(async (response) => {
      try {
        const data = await response.json();
        console.log(data);
        if (data.code === 200) {
          self.successForm(data.message);
          setTimeout(self.resetForm.bind(self), 3000);
        } else {
          self.logError(data.message);
        }
      } catch (error) {
        console.error('Error:', error);
        self.logError('Error');
      }
    })

  };

  TESTIMONIALS.prototype.submitForm = function (event) {
    let self = this,
      formData = "";

    if (event) {
      event.preventDefault();
      event.stopPropagation();
    }

    // submit if the form is valid
    if (self.validateForm()) {
      self.setSubmitState("processing");
      formData = new FormData(self.form);
      self.send(formData);
    }

  };

  return TESTIMONIALS;

}(window));
