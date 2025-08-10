document.getElementById('inputPassword').addEventListener('input', function () {
  const password = this.value;
  const error = document.getElementById('passwordError');

  const minLength = password.length >= 8;
  const hasLetter = /[A-Za-z]/.test(password);
  const hasNumber = /\d/.test(password);
  const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

  if (!minLength || !hasLetter || !hasNumber || !hasSpecialChar) {
    error.style.display = 'inline';
    error.textContent = 'Password must be at least 8 characters and include letters, numbers, and a special character.';
  } else {
    error.style.display = 'none';
  }
});