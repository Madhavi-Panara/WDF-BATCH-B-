document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("form").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            const button = form.querySelector('button[type="submit"], button:not([type])');
            if (button && form.checkValidity()) {
                button.disabled = true;
                if (button.textContent.trim() === "Sign in") {
                    button.textContent = "Signing in...";
                }
            }
        });
    });
});
