/* Utilidades de validación para formularios de inicio de sesión y registro */
(function () {
  "use strict";

  const USERNAME_MIN = 6;
  const USERNAME_MAX = 15;
  const PASSWORD_MIN = 8;
  const PASSWORD_MAX = 16; // usamos 16 para coincidir con los placeholders
  const NAME_MIN = 2;
  const NAME_MAX = 50;

  function setFieldState(inputEl, helperEl, valid, message) {
    if (!inputEl) return;
    if (helperEl) {
      if (valid) {
        helperEl.classList.remove("text-danger");
        helperEl.classList.add("text-muted");
        if (helperEl.dataset.default)
          helperEl.textContent = helperEl.dataset.default;
      } else {
        helperEl.classList.remove("text-muted");
        helperEl.classList.add("text-danger");
        if (message) helperEl.textContent = message;
      }
    }

    if (valid) inputEl.classList.remove("border-danger");
    else inputEl.classList.add("border-danger");
  }

  function getOrCreateHelper(id, defaultText) {
    let el = document.getElementById(id);
    if (!el) {
      el = document.createElement("small");
      el.id = id;
      el.className = "form-text text-muted";
      el.textContent = defaultText || "";
    }
    if (!el.dataset.default) el.dataset.default = el.textContent;
    return el;
  }

  // Validadores de login (usa inputs con id 'username' y 'password')
  window.validateUsernameInput = function () {
    const input = document.getElementById("username");
    const help = document.getElementById("helpUsername");
    if (!input || !help) return true;

    const val = input.value.trim();
    const valid = val.length >= USERNAME_MIN && val.length <= USERNAME_MAX;
    setFieldState(
      input,
      help,
      valid,
      `El nombre de usuario debe tener entre ${USERNAME_MIN} y ${USERNAME_MAX} caracteres`
    );
    return valid;
  };

  window.validatePasswordInput = function () {
    const input = document.getElementById("password");
    const help = document.getElementById("helpPassword");
    if (!input || !help) return true;

    const val = input.value;
    const valid = val.length >= PASSWORD_MIN && val.length <= PASSWORD_MAX;
    setFieldState(
      input,
      help,
      valid,
      `La contraseña debe tener entre ${PASSWORD_MIN} y ${PASSWORD_MAX} caracteres`
    );
    return valid;
  };

  window.validateLogin = function (event) {
    if (event && typeof event.preventDefault === "function")
      event.preventDefault();
    const u = validateUsernameInput();
    const p = validatePasswordInput();
    if (u && p) {
      const form = document.getElementById("loginForm");
      if (form) form.submit();
      return true;
    }
    return false;
  };

  // Validadores de registro (ids: reg_username, reg_name, reg_surname, reg_email, reg_password, reg_confirm)
  function validateRegUsername() {
    const input = document.getElementById("reg_username");
    const help = getOrCreateHelper(
      "helpRegUsername",
      `El nombre de usuario debe tener entre ${USERNAME_MIN} y ${USERNAME_MAX} caracteres`
    );
    if (!input) return true;
    if (!help.parentElement) input.parentElement.appendChild(help);

    const val = input.value.trim();
    const valid = val.length >= USERNAME_MIN && val.length <= USERNAME_MAX;
    setFieldState(
      input,
      help,
      valid,
      `El nombre de usuario debe tener entre ${USERNAME_MIN} y ${USERNAME_MAX} caracteres`
    );
    return valid;
  }

  function validateRegName() {
    const input = document.getElementById("reg_name");
    const help = getOrCreateHelper("helpRegName", "Introduce el nombre");
    if (!input) return true;
    if (!help.parentElement) input.parentElement.appendChild(help);

    const val = input.value.trim();
    const valid = val.length >= NAME_MIN && val.length <= NAME_MAX;
    setFieldState(
      input,
      help,
      valid,
      `El nombre debe tener entre ${NAME_MIN} y ${NAME_MAX} caracteres`
    );
    return valid;
  }

  function validateRegSurname() {
    const input = document.getElementById("reg_surname");
    const help = getOrCreateHelper("helpRegSurname", "Introduce el apellido");
    if (!input) return true;
    if (!help.parentElement) input.parentElement.appendChild(help);

    const val = input.value.trim();
    const valid = val.length >= NAME_MIN && val.length <= NAME_MAX;
    setFieldState(
      input,
      help,
      valid,
      `El apellido debe tener entre ${NAME_MIN} y ${NAME_MAX} caracteres`
    );
    return valid;
  }

  function validateRegEmail() {
    const input = document.getElementById("reg_email");
    const help = getOrCreateHelper(
      "helpRegEmail",
      "Introduce un correo electrónico válido"
    );
    if (!input) return true;
    if (!help.parentElement) input.parentElement.appendChild(help);

    const val = input.value.trim();
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const valid = emailRe.test(val);
    setFieldState(
      input,
      help,
      valid,
      "Introduce una dirección de email válida"
    );
    return valid;
  }

  function validateRegPassword() {
    const input = document.getElementById("reg_password");
    const help =
      document.getElementById("helpRegPassword") ||
      getOrCreateHelper(
        "helpRegPassword",
        `La contraseña debe tener entre ${PASSWORD_MIN} y ${PASSWORD_MAX} caracteres`
      );
    if (!input) return true;
    if (!help.parentElement) input.parentElement.appendChild(help);

    const val = input.value;
    const valid = val.length >= PASSWORD_MIN && val.length <= PASSWORD_MAX;
    setFieldState(
      input,
      help,
      valid,
      `La contraseña debe tener entre ${PASSWORD_MIN} y ${PASSWORD_MAX} caracteres`
    );
    return valid;
  }

  function validateRegConfirm() {
    const input = document.getElementById("reg_confirm");
    const pwd = document.getElementById("reg_password");
    const help = getOrCreateHelper(
      "helpRegConfirm",
      "Confirma la contraseña (debe coincidir)"
    );
    if (!input || !pwd) return true;
    if (!help.parentElement) input.parentElement.appendChild(help);

    const valid = input.value === pwd.value && input.value.length > 0;
    setFieldState(input, help, valid, "Las contraseñas no coinciden");
    return valid;
  }

  window.validateRegister = function (event) {
    if (event && typeof event.preventDefault === "function")
      event.preventDefault();
    const checks = [
      validateRegUsername(),
      validateRegName(),
      validateRegSurname(),
      validateRegEmail(),
      validateRegPassword(),
      validateRegConfirm(),
    ];
    const ok = checks.every(Boolean);
    if (ok) {
      const form = document.getElementById("registerForm");
      if (form) form.submit();
      return true;
    }
    return false;
  };

  // Añadir manejadores de eventos
  document.addEventListener("DOMContentLoaded", function () {
    // Formulario de login
    const username = document.getElementById("username");
    const password = document.getElementById("password");
    const loginForm = document.getElementById("loginForm");
    if (username) username.addEventListener("input", validateUsernameInput);
    if (password) password.addEventListener("input", validatePasswordInput);
    if (loginForm) loginForm.addEventListener("submit", validateLogin);

    // Formulario de registro
    const regUsername = document.getElementById("reg_username");
    const regName = document.getElementById("reg_name");
    const regSurname = document.getElementById("reg_surname");
    const regEmail = document.getElementById("reg_email");
    const regPassword = document.getElementById("reg_password");
    const regConfirm = document.getElementById("reg_confirm");
    const regForm = document.getElementById("registerForm");

    if (regUsername) regUsername.addEventListener("input", validateRegUsername);
    if (regName) regName.addEventListener("input", validateRegName);
    if (regSurname) regSurname.addEventListener("input", validateRegSurname);
    if (regEmail) regEmail.addEventListener("input", validateRegEmail);
    if (regPassword) regPassword.addEventListener("input", validateRegPassword);
    if (regConfirm) regConfirm.addEventListener("input", validateRegConfirm);
    if (regForm) regForm.addEventListener("submit", validateRegister);
  });
})();
