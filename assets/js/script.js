// assets/js/script.js

document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#inputPassword');

    // Cek jika elemen ada di halaman aktif (mencegah error di halaman lain)
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
            // Tukar tipe input antara password dan text
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Tukar icon bootstrap clasess
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    }
});