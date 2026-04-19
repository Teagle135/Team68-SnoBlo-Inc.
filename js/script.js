// script.js — lightweight client-side validation for auth forms

/**
 * Validate the Sign-Up form before it is submitted.
 * Returns false to block submission if any check fails.
 */
function validateSignup() {
    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirm  = document.getElementById('confirm_password').value;
    const phone    = document.getElementById('phone').value.trim();
    const msgBox   = document.getElementById('client-msg');

    function fail(msg) {
        msgBox.textContent = msg;
        msgBox.className   = 'form-msg error';
        return false;
    }

    if (!email || !password || !confirm || !phone) {
        return fail('Please fill in all fields.');
    }

    // Basic email format check
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        return fail('Please enter a valid email address.');
    }

    if (password.length < 6) {
        return fail('Password must be at least 6 characters.');
    }

    if (password !== confirm) {
        return fail('Passwords do not match.');
    }

    const phonePattern = /^[\d\s\-().+]{7,20}$/;
    if (!phonePattern.test(phone)) {
        return fail('Please enter a valid phone number.');
    }

    // All good — clear any previous message and allow submit
    msgBox.textContent = '';
    msgBox.className   = '';
    return true;
}

/**
 * Validate the Login form before it is submitted.
 */
function validateLogin() {
    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const msgBox   = document.getElementById('client-msg');

    function fail(msg) {
        msgBox.textContent = msg;
        msgBox.className   = 'form-msg error';
        return false;
    }

    if (!email || !password) {
        return fail('Please enter your email and password.');
    }

    msgBox.textContent = '';
    msgBox.className   = '';
    return true;
}

/**
 * Toggle a password field between visible and hidden.
 * @param {string} fieldId - the id of the <input type="password">
 * @param {HTMLElement} btn - the toggle button element
 */
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
        btn.textContent = 'Hide';
    } else {
        field.type = 'password';
        btn.textContent = 'Show';
    }
}
