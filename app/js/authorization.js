// Získání prvků formuláře a odkazu "Zapomněli jste heslo"
const loginForm = document.getElementById('loginForm');
const forgotPasswordForm = document.getElementById('forgotPasswordForm');
const forgotPasswordLink = document.getElementById('forgotPasswordLink');

// Obsluha kliknutí na "Zapomněli jste heslo"
forgotPasswordLink.addEventListener('click', function(event) {
  event.preventDefault(); // Zastavení výchozího chování odkazu
  loginForm.style.display = 'none'; // Skrytí přihlašovacího formuláře
  forgotPasswordForm.style.display = 'block'; // Zobrazení formuláře pro obnovení hesla
});

// Validace a odeslání formuláře pro obnovení hesla
forgotPasswordForm.addEventListener('submit', function(event) {
  event.preventDefault();
  
  const recoveryIdentifier = document.getElementById('recoveryIdentifier').value;

  if (recoveryIdentifier.trim() === "") {
    alert("Zadejte email nebo telefonní číslo."); // Zobrazení upozornění, pokud je pole prázdné
  } else {
    alert("Pokyny pro obnovení hesla byly odeslány."); // Zobrazení potvrzení o odeslání pokynů
    // Skrytí formuláře pro obnovení a návrat k přihlašovacímu formuláři
    forgotPasswordForm.style.display = 'none';
    loginForm.style.display = 'block';
  }
});