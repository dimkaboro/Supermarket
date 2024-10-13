// Получаем элементы формы и ссылку "Забыл пароль"
const loginForm = document.getElementById('loginForm');
const forgotPasswordForm = document.getElementById('forgotPasswordForm');
const forgotPasswordLink = document.getElementById('forgotPasswordLink');

// Обработчик нажатия на "Забыл пароль"
forgotPasswordLink.addEventListener('click', function(event) {
  event.preventDefault(); // Останавливаем стандартное поведение ссылки
  loginForm.style.display = 'none'; // Скрываем форму авторизации
  forgotPasswordForm.style.display = 'block'; // Показываем форму восстановления пароля
});

// Валидация и отправка формы восстановления пароля
forgotPasswordForm.addEventListener('submit', function(event) {
  event.preventDefault();
  
  const recoveryIdentifier = document.getElementById('recoveryIdentifier').value;

  if (recoveryIdentifier.trim() === "") {
    alert("Zadejte email nebo telefonní číslo.");
  } else {
    alert("Pokyny pro obnovení hesla byly odeslány.");
    // Скрываем форму восстановления и возвращаем форму авторизации
    forgotPasswordForm.style.display = 'none';
    loginForm.style.display = 'block';
  }
});
