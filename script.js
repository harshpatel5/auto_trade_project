document.getElementById('login-form').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value.trim(); // Corrected from ariaValueMax to value
    if (password.length < 8) {
        alert('Password must be at least 8 characters long.');
        event.preventDefault(); // Prevent form submission if validation fails
    }
});
