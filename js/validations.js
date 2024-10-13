// Функция для проверки формата телефона
function validatePhone(phone) {
  const phonePattern = /^\+?(\d{3})? ?\d{9}$/;
  return phonePattern.test(phone);
}

// Функция для проверки формата email
function validateEmail(email) {
  const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  return emailPattern.test(email);
}

// Функция для проверки длины пароля и его силы
function validateStrongPassword(password) {
  const passwordPattern = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
  return passwordPattern.test(password);
}

// Очистка всех сообщений об ошибках
function clearErrors() {
  document.getElementById('identifierError').textContent = '';
  document.getElementById('passwordError').textContent = '';
}

// Валидация формы авторизации
document.getElementById("loginForm").addEventListener("submit", function(event) {
  clearErrors(); // Очистить старые ошибки
  let isValid = true;

  const identifier = document.getElementById("identifier").value;
  const password = document.getElementById("password").value;

  // Проверка на наличие введенного email или телефона
  if (identifier.trim() === "") {
    document.getElementById('identifierError').textContent = "Zadejte email nebo telefonní číslo.";
    isValid = false;
  } else if (!validateEmail(identifier) && !validatePhone(identifier)) {
    document.getElementById('identifierError').textContent = "Zadejte platný email nebo telefonní číslo.";
    isValid = false;
  }

  // Проверка силы пароля
  if (!validateStrongPassword(password)) {
    document.getElementById('passwordError').textContent = "Heslo musí obsahovat alespoň 8 znaků, jednu číslici a jedno velké písmeno.";
    isValid = false;
  }

  // Если есть ошибки, предотвратить отправку формы
  if (!isValid) {
    event.preventDefault();
  }
});

// Валидация формы регистрации (если она на другой странице)
document.getElementById("registrationForm").addEventListener("submit", function(event) {
  let isValid = true;
  clearErrors();

  const firstName = document.getElementById("firstName").value;
  const lastName = document.getElementById("lastName").value;
  const email = document.getElementById("email").value;
  const phone = document.getElementById("phone").value;
  const password = document.getElementById("password").value;
  const profilePicture = document.getElementById("profilePicture").files[0];

  // Проверка имени и фамилии
  if (firstName.trim() === "" || lastName.trim() === "") {
    alert("Jméno a příjmení jsou povinné.");
    isValid = false;
  }

  // Проверка email
  if (!validateEmail(email)) {
    alert("Zadejte platnou emailovou adresu.");
    isValid = false;
  }

  // Проверка телефона
  if (!validatePhone(phone)) {
    alert("Zadejte platné telefonní číslo.");
    isValid = false;
  }

  // Проверка пароля
  if (!validateStrongPassword(password)) {
    alert("Heslo musí obsahovat alespoň 8 znaků, jednu číslici a jedno velké písmeno.");
    isValid = false;
  }

  // Проверка фотографии профиля
  if (profilePicture && !/\.(jpe?g)$/i.test(profilePicture.name)) {
    alert("Nahrát fotografii ve formátu JPEG.");
    isValid = false;
  }

  if (!isValid) {
    event.preventDefault();
  }
});
