// Funkce pro kontrolu formátu telefonu
function validatePhone(phone) {
  const phonePattern = /^\+?(\d{3})? ?\d{9}$/;
  return phonePattern.test(phone);
}

// Funkce pro kontrolu formátu emailu
function validateEmail(email) {
  const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  return emailPattern.test(email);
}

// Funkce pro kontrolu délky hesla a jeho síly
function validateStrongPassword(password) {
  const passwordPattern = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
  return passwordPattern.test(password);
}

// Vymazání všech chybových zpráv
function clearErrors() {
  const errorElements = document.querySelectorAll('.error-message');
  errorElements.forEach(errorElement => errorElement.textContent = '');
}

// Validace přihlašovacího formuláře
const loginForm = document.getElementById("loginForm");
if (loginForm) {
  loginForm.addEventListener("submit", function(event) {
      clearErrors(); // Vymazat staré chyby
      let isValid = true;

      const identifier = document.getElementById("identifier").value;
      const password = document.getElementById("password").value;

      // Kontrola, zda byl zadán email nebo telefon
      if (identifier.trim() === "") {
          displayError('identifierError', "Zadejte email nebo telefonní číslo.");
          isValid = false;
      } else if (!validateEmail(identifier) && !validatePhone(identifier)) {
          displayError('identifierError', "Zadejte platný email nebo telefonní číslo.");
          isValid = false;
      }

      // Kontrola síly hesla
      if (!validateStrongPassword(password)) {
          displayError('passwordError', "Heslo musí obsahovat alespoň 8 znaků, jednu číslici a jedno velké písmeno.");
          isValid = false;
      }

      // Pokud jsou chyby, zabránit odeslání formuláře
      if (!isValid) {
          event.preventDefault();
      }
  });
}

// Validace registračního formuláře
const registrationForm = document.getElementById("registrationForm");
if (registrationForm) {
  registrationForm.addEventListener("submit", function(event) {
      let isValid = true;
      clearErrors();

      const firstName = document.getElementById("firstName").value;
      const lastName = document.getElementById("lastName").value;
      const email = document.getElementById("email").value;
      const phone = document.getElementById("phone").value;
      const password = document.getElementById("password").value;
      const profilePicture = document.getElementById("profilePicture").files[0];

      // Kontrola jména a příjmení
      if (firstName.trim() === "" || lastName.trim() === "") {
          displayError('nameError', "Jméno a příjmení jsou povinné.");
          isValid = false;
      }

      // Kontrola emailu
      if (!validateEmail(email)) {
          displayError('emailError', "Zadejte platnou emailovou adresu.");
          isValid = false;
      }

      // Kontrola telefonu
      if (!validatePhone(phone)) {
          displayError('phoneError', "Zadejte platné telefonní číslo.");
          isValid = false;
      }

      // Kontrola hesla
      if (!validateStrongPassword(password)) {
          displayError('passwordError', "Heslo musí obsahovat alespoň 8 znaků, jednu číslici a jedno velké písmeno.");
          isValid = false;
      }

      // Kontrola fotografie profilu
      if (profilePicture && !/\.(jpe?g)$/i.test(profilePicture.name)) {
          displayError('profilePictureError', "Nahrát fotografii ve formátu JPEG.");
          isValid = false;
      }

      if (!isValid) {
          event.preventDefault();
      }
  });
}

// Funkce pro zobrazení chyb
function displayError(elementId, message) {
  const errorElement = document.getElementById(elementId);
  if (errorElement) {
      errorElement.textContent = message;
      errorElement.style.color = 'red';
  }
}