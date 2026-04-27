import "./bootstrap";

const AUTH_STORAGE_KEY = "markup.auth.user";

document.addEventListener("DOMContentLoaded", () => {
    initMobileMenu();
    initProductFilters();
    initAuthForms();
    initNavbarAuthState();
    initFlashToast();
});

/* ========== Mobile menu ========== */
function initMobileMenu() {
    const toggle = document.querySelector("[data-mobile-menu-toggle]");
    const menu = document.querySelector("[data-mobile-menu]");
    if (!toggle || !menu) return;

    toggle.addEventListener("click", () => menu.classList.toggle("hidden"));
    menu.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", () => menu.classList.add("hidden"));
    });
}

/* ========== Product filters ========== */
function initProductFilters() {
    const container = document.querySelector("[data-product-filters]");
    if (!container) return;

    const buttons = container.querySelectorAll("button[data-filter]");
    const activeClasses = ["bg-purple-500", "text-white"];
    const inactiveClasses = ["bg-slate-100", "text-navy-600", "hover:bg-slate-200"];

    buttons.forEach((btn) => {
        btn.addEventListener("click", () => {
            buttons.forEach((b) => {
                b.classList.remove(...activeClasses);
                b.classList.add(...inactiveClasses);
            });
            btn.classList.remove(...inactiveClasses);
            btn.classList.add(...activeClasses);
        });
    });
}

/* ========== Auth forms ========== */
function initAuthForms() {
    // Password visibility toggle
    document.querySelectorAll("[data-toggle-password]").forEach((btn) => {
        btn.addEventListener("click", () => {
            const input = btn.parentElement.querySelector("input");
            if (!input) return;
            const isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";
            btn.querySelector("i").classList.toggle("fa-eye", !isPassword);
            btn.querySelector("i").classList.toggle("fa-eye-slash", isPassword);
        });
    });

    document.querySelectorAll("[data-auth-form]").forEach((form) => {
        const mode = form.dataset.authForm;

        // Reset state awal
        form.querySelectorAll("[data-valid-icon]").forEach((icon) => icon.classList.add("hidden"));
        setSubmitting(form, false);

        // Event listeners untuk validasi
        form.querySelectorAll("[data-validate]").forEach((input) => {
            ["input", "blur", "change"].forEach(event => {
                // PERBAIKAN: Menangkap tipe event agar validasi lebih cerdas
                input.addEventListener(event, (e) => validateField(input, form, e.type));
            });
        });

        // Password strength meter
        if (mode === "register") {
            const meter = form.querySelector("[data-strength-meter]");
            const passwordInput = form.querySelector('[data-validate="password"]');
            if (meter && passwordInput) {
                passwordInput.addEventListener("input", () => updateStrengthMeter(meter, passwordInput.value));
            }
        }

        form.addEventListener("submit", (e) => {
            e.preventDefault(); 
            handleAuthSubmit(form, mode);
        });
    });
}

function validateField(input, form, eventType = null) {
    const wrapper = input.closest("[data-field-wrapper]");
    const errorEl = wrapper?.parentElement.querySelector("[data-field-error]");
    const validIcon = wrapper?.querySelector("[data-valid-icon]");
    const type = input.dataset.validate;

    // PERBAIKAN: Field password tidak boleh di-trim agar spasi diakui
    const value = (type === "password" || type === "password_confirmation") 
        ? input.value 
        : input.value.trim();

    let error = "";

    // Logika penentuan error
    if (!value) {
        // PERBAIKAN: Error kosong hanya muncul saat blur (pindah field) atau submit
        if (eventType === "blur" || !eventType) {
            error = "Field ini wajib diisi";
        }
    } else if (type === "email") {
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) error = "Format email tidak valid";
    } else if (type === "password") {
        if (value.length < 8) error = "Password minimal 8 karakter";
    } else if (type === "name") {
        if (value.length < 3) error = "Nama minimal 3 karakter";
    } else if (type === "role") {
        if (!value) error = "Pilih role terlebih dahulu";
    } else if (type === "password_confirmation") {
        const pw = form.querySelector('[data-validate="password"]')?.value || "";
        if (value !== pw) {
            error = "Password tidak cocok";
        }
    }

    setFieldState(wrapper, errorEl, validIcon, error, value);
    
    // Kembalikan status valid (true) hanya jika tidak ada error DAN field sudah terisi
    return !error && value.length > 0;
}

function setFieldState(wrapper, errorEl, validIcon, error, value = "") {
    if (!wrapper) return;

    // Tambahkan style error hanya jika ada pesan error
    wrapper.classList.toggle("border-red-400", !!error);
    wrapper.classList.toggle("border-gray-200", !error);

    if (errorEl) {
        errorEl.textContent = error;
        errorEl.classList.toggle("hidden", !error);
    }

    if (validIcon) {
        // Ikon hijau hanya tampil jika tidak ada error DAN value tidak kosong
        const shouldShow = !error && value.length > 0;
        validIcon.classList.toggle("hidden", !shouldShow);
    }
}

function updateStrengthMeter(meter, password) {
    const bars = meter.querySelectorAll("span");
    let score = 0;
    if (password.length >= 8) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/\d/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    const colors = ["bg-red-400", "bg-orange-400", "bg-yellow-400", "bg-green-500"];
    bars.forEach((bar, idx) => {
        bar.classList.remove(...colors, "bg-gray-200");
        bar.classList.add(idx < score ? colors[score - 1] : "bg-gray-200");
    });
}

function handleAuthSubmit(form, mode) {
    const inputs = form.querySelectorAll("[data-validate]");
    let valid = true;
    
    inputs.forEach((input) => {
        // Panggil validasi tanpa eventType agar trigger error wajib diisi
        if (!validateField(input, form)) valid = false;
    });

    if (!valid) {
        showAlert(form, "error", "Periksa kembali isian form kamu.");
        return;
    }

    setSubmitting(form, true);
    showAlert(form, null);

    setTimeout(() => {
        try {
            const data = Object.fromEntries(new FormData(form).entries());
            const user = {
                name: data.name || data.email.split("@")[0],
                email: data.email,
                role: data.role || "student",
                token: "dummy-" + Math.random().toString(36).slice(2, 10),
                issuedAt: Date.now(),
            };
            localStorage.setItem(AUTH_STORAGE_KEY, JSON.stringify(user));

            const flash = mode === "login"
                ? `Selamat datang kembali, ${user.name}!`
                : `Akun berhasil dibuat. Selamat datang, ${user.name}!`;
            sessionStorage.setItem("markup.flash", flash);

            window.location.href = "/";
        } catch (err) {
            console.error("[auth] login failed", err);
            setSubmitting(form, false);
            showAlert(form, "error", "Gagal menyimpan sesi.");
        }
    }, 800);
}

function setSubmitting(form, isSubmitting) {
    const btn = form.querySelector("[data-submit-btn]");
    const spinner = btn?.querySelector("[data-btn-spinner]");
    const label = btn?.querySelector("[data-btn-label]");
    if (!btn) return;

    btn.disabled = isSubmitting;
    spinner?.classList.toggle("hidden", !isSubmitting);
    if (label) label.style.opacity = isSubmitting ? "0.7" : "1";
}

function showAlert(form, type, message) {
    const alertEl = form.parentElement.querySelector("[data-form-alert]");
    if (!alertEl) return;
    if (!type) {
        alertEl.classList.add("hidden");
        return;
    }
    alertEl.className = "mb-5 rounded-xl px-4 py-3 text-sm";
    if (type === "error") {
        alertEl.classList.add("border", "border-red-200", "bg-red-50", "text-red-700");
    } else {
        alertEl.classList.add("border", "border-green-200", "bg-green-50", "text-green-700");
    }
    alertEl.textContent = message;
}

function initNavbarAuthState() {
    const authSlot = document.querySelector("[data-nav-auth]");
    if (!authSlot) return;

    const userJson = localStorage.getItem(AUTH_STORAGE_KEY);
    if (!userJson) return;

    try {
        const user = JSON.parse(userJson);
        const initials = user.name.split(" ").map(n => n[0]).join("").slice(0, 2).toUpperCase();

        authSlot.innerHTML = `
            <div class="group relative">
                <button type="button" class="flex items-center gap-2 rounded-full border border-slate-200 px-1.5 py-1.5 pr-3 transition hover:bg-slate-50">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-navy-600 text-xs font-bold text-white">${escapeHtml(initials)}</span>
                    <span class="hidden text-sm font-semibold text-navy-600 sm:inline">${escapeHtml(user.name)}</span>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                </button>
                <div class="invisible absolute right-0 top-full z-50 mt-1 w-52 translate-y-2 rounded-xl border border-slate-100 bg-white py-2 opacity-0 shadow-lg transition-all duration-200 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                    <div class="border-b border-slate-100 px-4 pb-2 pt-1">
                        <p class="truncate text-sm font-bold text-navy-600">${escapeHtml(user.name)}</p>
                        <p class="truncate text-xs text-slate-500">${escapeHtml(user.email)}</p>
                    </div>
                    <a href="#" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-navy-600">Profil Saya</a>
                    <button type="button" data-logout class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                    </button>
                </div>
            </div>
        `;

        authSlot.querySelector("[data-logout]").addEventListener("click", () => {
            localStorage.removeItem(AUTH_STORAGE_KEY);
            sessionStorage.setItem("markup.flash", "Berhasil keluar.");
            window.location.href = "/";
        });
    } catch (e) {
        localStorage.removeItem(AUTH_STORAGE_KEY);
    }
}

function initFlashToast() {
    const message = sessionStorage.getItem("markup.flash");
    if (!message) return;
    sessionStorage.removeItem("markup.flash");

    const toast = document.createElement("div");
    toast.className = "fixed bottom-6 right-6 z-[60] flex items-center gap-3 rounded-xl border border-green-200 bg-white px-5 py-3 text-sm font-semibold text-navy-600 shadow-lg transition-all duration-300";
    toast.style.transform = "translateY(20px)";
    toast.style.opacity = "0";
    toast.innerHTML = `
        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-green-100 text-green-600"><i class="fas fa-check"></i></span>
        <span>${escapeHtml(message)}</span>
    `;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.transform = "translateY(0)";
        toast.style.opacity = "1";
    }, 100);

    setTimeout(() => {
        toast.style.transform = "translateY(20px)";
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, c => ({
        "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;"
    }[c]));
}