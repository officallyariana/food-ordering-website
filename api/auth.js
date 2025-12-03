/* ==========================================================
   SIGNUP LOGIC
========================================================== */

const signupTrack = document.getElementById("signup-track");
let signupStep = 0;

function goToSignupStep(step) {
    if (!signupTrack) return;
    signupStep = step;
    signupTrack.style.transform = `translateX(${step * -100}%)`;
}

document.querySelectorAll(".btn-next").forEach(btn => {
    btn.addEventListener("click", () => {
        let next = parseInt(btn.dataset.next);
        if (validateSignupStep(signupStep)) {
            goToSignupStep(next);
        }
    });
});

document.querySelectorAll(".btn-back").forEach(btn => {
    btn.addEventListener("click", () => {
        let back = parseInt(btn.dataset.back);
        goToSignupStep(back - 1);
    });
});

function validateSignupStep(step) {
    if (!signupTrack) return true;

    if (step === 0) {
        let email = document.getElementById("su-email");
        let err = document.getElementById("su-email-err");

        if (!email.value.trim()) {
            err.textContent = "Email is required";
            return false;
        }

        if (!email.value.includes("@")) {
            err.textContent = "Enter a valid email";
            return false;
        }

        err.textContent = "";
        return true;
    }

    if (step === 1) {
        let email = document.getElementById("su-email").value.trim();
        let confirm = document.getElementById("su-email-confirm").value.trim();
        let err = document.getElementById("su-email-confirm-err");

        if (!confirm) {
            err.textContent = "Please confirm your email";
            return false;
        }

        if (confirm !== email) {
            err.textContent = "Emails do not match";
            return false;
        }

        err.textContent = "";
        return true;
    }

    if (step === 2) {
        let pass = document.getElementById("su-pass");
        let confirm = document.getElementById("su-pass-confirm");

        let passErr = document.getElementById("su-pass-err");
        let confErr = document.getElementById("su-pass-confirm-err");

        if (pass.value.length < 8) {
            passErr.textContent = "Password must be at least 8 characters";
            return false;
        } else passErr.textContent = "";

        if (pass.value !== confirm.value) {
            confErr.textContent = "Passwords do not match";
            return false;
        } else confErr.textContent = "";

        return true;
    }

    return true;
}

/* ================= SUBMIT SIGNUP ================= */

const suSubmit = document.getElementById("su-submit");

if (suSubmit) {
    suSubmit.addEventListener("click", () => {
        let err = document.getElementById("su-submit-err");
        let email = document.getElementById("su-email").value.trim();
        let pass = document.getElementById("su-pass").value.trim();

        let formData = new FormData();
        formData.append("email", email);
        formData.append("password", pass);

        fetch("signup.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.text())
            .then(data => {
                if (data.includes("success")) {
                    window.location.href = "login.html";
                } else {
                    err.textContent = data;
                }
            })
            .catch(() => {
                err.textContent = "Server error. Check XAMPP.";
            });
    });
}


const loginTrack = document.getElementById("login-track");
let loginStep = 0;

function goToLoginStep(step) {
    if (!loginTrack) return;
    loginStep = step;
    loginTrack.style.transform = `translateX(${step * -100}%)`;
}

document.querySelectorAll(".btn-next").forEach(btn => {
    btn.addEventListener("click", () => {
        if (!loginTrack) return;
        let next = parseInt(btn.dataset.next);
        if (validateLoginStep(loginStep)) {
            goToLoginStep(next);
        }
    });
});

document.querySelectorAll(".btn-back").forEach(btn => {
    btn.addEventListener("click", () => {
        if (!loginTrack) return;
        let back = parseInt(btn.dataset.back);
        goToLoginStep(back - 1);
    });
});

function validateLoginStep(step) {
    if (!loginTrack) return true;

    if (step === 0) {
        let email = document.getElementById("li-email");
        let err = document.getElementById("li-email-err");

        if (!email.value.trim()) {
            err.textContent = "Enter your email";
            return false;
        }

        if (!email.value.includes("@")) {
            err.textContent = "Invalid email";
            return false;
        }

        err.textContent = "";
        return true;
    }

    if (step === 1) {
        let pass = document.getElementById("li-pass");
        let err = document.getElementById("li-pass-err");

        if (!pass.value.trim()) {
            err.textContent = "Password required";
            return false;
        }

        err.textContent = "";
        return true;
    }

    return true;
}

/* ================= SUBMIT LOGIN ================= */

const liSubmit = document.getElementById("li-submit");

if (liSubmit) {
    liSubmit.addEventListener("click", () => {
        let err = document.getElementById("li-pass-err");
        let email = document.getElementById("li-email").value.trim();
        let pass = document.getElementById("li-pass").value.trim();

        let formData = new FormData();
        formData.append("email", email);
        formData.append("password", pass);

        fetch("login.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.text())
            .then(data => {
                if (data.includes("success")) {
                    window.location.href = "dashboard.html";
                } else {
                    err.textContent = data;
                }
            })
            .catch(() => {
                err.textContent = "Server error. Check XAMPP.";
            });
    });
}
