function showLoginForm() {
    document.getElementById("loginForm").style.display = "block";
    document.getElementById("forgotPasswordForm").style.display = "none";
    document.getElementById("registrationForm").style.display = "none";
}

function showForgotPasswordForm() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("forgotPasswordForm").style.display = "block";
    document.getElementById("registrationForm").style.display = "none";
}

function showRegistrationForm() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("forgotPasswordForm").style.display = "none";
    document.getElementById("registrationForm").style.display = "block";
}