function togglePasswordVisibility() {
    var passwordInput = document.getElementById("myInput");
    var eyeIcon = document.querySelector(".eye-icon img");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.src = "images/view.png"; // Replace with the closed eye icon path
    } else {
        passwordInput.type = "password";
        eyeIcon.src = "images/hide.png"; // Replace with the open eye icon path
    }
}

function updateCharCount() {
    const maxLength = 150;
    const textarea = document.getElementById("crecord");
    const charCount = document.getElementById("charCount");

    const remainingChars = maxLength - textarea.value.length;
    charCount.textContent = `Characters left: ${remainingChars}`;
}

function get(input) {
    if (input.files[0]) {
        const reader = new FileReader();
        reader.onloadend = function (e) {
            const imageUrl = e.target.result;
            document.getElementById(
                "preview"
            ).style.backgroundImage = `url(${imageUrl})`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
