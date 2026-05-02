const AUTH_MODAL_ID = "authModal";
const AUTH_ACTIVE_CLASS = "active";

function getAuthModal() {
    return document.getElementById(AUTH_MODAL_ID);
}

function setVisible(element, isVisible) {
    if (!element) {
        return;
    }

    element.style.display = isVisible ? "flex" : "none";
}

function setActive(element, isActive) {
    if (!element) {
        return;
    }

    element.classList.toggle(AUTH_ACTIVE_CLASS, isActive);
}

function openAuth() {
    setVisible(getAuthModal(), true);
}

function closeAuth() {
    setVisible(getAuthModal(), false);
}

function showAuthTab(tab) {
    const isRegister = tab === "register";

    setActive(document.getElementById("loginTab"), !isRegister);
    setActive(document.getElementById("registerTab"), isRegister);
    setActive(document.getElementById("loginForm"), !isRegister);
    setActive(document.getElementById("registerForm"), isRegister);
}

function openLogin() {
    openAuth();
}

function closeLogin() {
    closeAuth();
}

function closeAuthOnBackdropClick(event) {
    const modal = getAuthModal();

    if (modal && event.target === modal) {
        closeAuth();
    }
}

function openRequestedAuthTab() {
    const openTarget = document.body.dataset.authOpen;

    if (openTarget !== "true") {
        return;
    }

    showAuthTab(document.body.dataset.authTab === "register" ? "register" : "login");
    openAuth();
}

function initAdminSidebar() {
    const menuButton = document.querySelector(".admin-menu-button");
    const adminShell = document.querySelector(".admin-shell");
    const mainContent = document.querySelector(".admin-main");

    if (!menuButton || !adminShell) return;

    // Klik tombol untuk buka/tutup
    menuButton.addEventListener("click", function (event) {
        event.stopPropagation();
        adminShell.classList.toggle("sidebar-show");
    });

    // Klik di area konten utama untuk menutup sidebar (opsional/user friendly)
    if (mainContent) {
        mainContent.addEventListener("click", function () {
            if (adminShell.classList.contains("sidebar-show")) {
                adminShell.classList.remove("sidebar-show");
            }
        });
    }
}

document.addEventListener("click", closeAuthOnBackdropClick);

document.addEventListener("DOMContentLoaded", function () {
    openRequestedAuthTab();
    initAdminSidebar();
});


