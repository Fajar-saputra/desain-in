function openAuth() {
    const modal = document.getElementById("authModal");
    if (!modal) return;
    modal.style.display = "flex";
}

function closeAuth() {
    const modal = document.getElementById("authModal");
    if (!modal) return;
    modal.style.display = "none";
}

function showAuthTab(tab) {
    const loginTab = document.getElementById("loginTab");
    const registerTab = document.getElementById("registerTab");
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");

    if (tab === "register") {
        if (loginTab) loginTab.classList.remove("active");
        if (registerTab) registerTab.classList.add("active");
        loginForm.classList.remove("active");
        registerForm.classList.add("active");
    } else {
        if (registerTab) registerTab.classList.remove("active");
        if (loginTab) loginTab.classList.add("active");
        registerForm.classList.remove("active");
        loginForm.classList.add("active");
    }
}

window.onclick = function (e) {
    const modal = document.getElementById("authModal");
    if (modal && e.target === modal) {
        modal.style.display = "none";
    }
};

function openLogin() {
    openAuth();
}

function closeLogin() {
    closeAuth();
}

window.addEventListener("DOMContentLoaded", function () {
    const openTarget = document.body.dataset.authOpen;
    const authTab = document.body.dataset.authTab;
    if (openTarget === "true") {
        if (authTab === "register") {
            showAuthTab("register");
        } else {
            showAuthTab("login");
        }
        openAuth();
    }
});
