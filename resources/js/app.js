import "./bootstrap";

const AUTH_STORAGE_KEY = "markup.auth.user";

// Dummy admin allowlist — email yang dianggap admin saat login mode demo.
const ADMIN_EMAILS = ["admin@markup.id", "admin@admin.com"];

function isAdminEmail(email) {
    return ADMIN_EMAILS.includes(String(email).trim().toLowerCase());
}

document.addEventListener("DOMContentLoaded", () => {
    initMobileMenu();
    initProductFilters();
    initAuthForms();
    initGoogleSignIn();
    initNavbarAuthState();
    initNavbarCart();
    initFlashToast();
    initScrollReveal();
    initCountUp();
});

/* ========== Scroll reveal (fade/slide-up saat masuk viewport) ========== */
function initScrollReveal() {
    const els = document.querySelectorAll("[data-reveal]");
    if (!els.length) return;

    // Hormati preferensi reduce-motion: langsung tampilkan tanpa animasi.
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        els.forEach((el) => el.classList.add("is-revealed"));
        return;
    }

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const delay = el.getAttribute("data-reveal-delay") || 0;
                el.style.transitionDelay = `${delay}ms`;
                el.classList.add("is-revealed");
                obs.unobserve(el);
            });
        },
        { threshold: 0.15, rootMargin: "0px 0px -8% 0px" }
    );

    els.forEach((el) => observer.observe(el));
}

/* ========== Count-up angka statistik ========== */
function initCountUp() {
    const els = document.querySelectorAll("[data-countup]");
    if (!els.length) return;

    const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    const run = (el) => {
        const target = parseFloat(el.getAttribute("data-countup"));
        const decimals = parseInt(el.getAttribute("data-decimals") || "0", 10);
        const prefix = el.getAttribute("data-prefix") || "";
        const suffix = el.getAttribute("data-suffix") || "";
        const format = (v) =>
            prefix + v.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ".") + suffix;

        if (reduce) {
            el.textContent = format(target);
            return;
        }

        const duration = 1400;
        const start = performance.now();
        const tick = (now) => {
            const p = Math.min((now - start) / duration, 1);
            // easeOutCubic
            const eased = 1 - Math.pow(1 - p, 3);
            el.textContent = format(target * eased);
            if (p < 1) requestAnimationFrame(tick);
            else el.textContent = format(target);
        };
        requestAnimationFrame(tick);
    };

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                run(entry.target);
                obs.unobserve(entry.target);
            });
        },
        { threshold: 0.5 }
    );

    els.forEach((el) => observer.observe(el));
}

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
            // FA i2svg may have replaced <i> with <svg>; query both
            const icon = btn.querySelector("i, svg");
            if (icon) {
                icon.classList.toggle("fa-eye", !isPassword);
                icon.classList.toggle("fa-eye-slash", isPassword);
            }
        });
    });

    document.querySelectorAll("[data-auth-form]").forEach((form) => {
        const mode = form.dataset.authForm;

        // Reset state awal
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
    const type = input.dataset.validate;

    // Field password tidak di-trim agar spasi diakui
    const value = (type === "password" || type === "password_confirmation")
        ? input.value
        : input.value.trim();

    let error = "";

    if (!value) {
        // Error kosong hanya muncul saat blur (pindah field) atau submit
        if (eventType === "blur" || !eventType) {
            error = "Field ini wajib diisi";
        }
    } else if (type === "email") {
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) error = "Format email tidak valid";
    } else if (type === "password") {
        if (value.length < 8) error = "Password minimal 8 karakter";
    } else if (type === "username") {
        if (value.length < 3) error = "Username minimal 3 karakter";
        if (!/^[a-zA-Z0-9_]+$/.test(value)) error = "Username hanya boleh huruf, angka, dan underscore";
    } else if (type === "first_name") {
        if (value.length < 2) error = "Nama depan minimal 2 karakter";
    } else if (type === "last_name") {
        if (value.length < 2) error = "Nama belakang minimal 2 karakter";
    } else if (type === "name") {
        if (value.length < 3) error = "Nama minimal 3 karakter";
    } else if (type === "role") {
        if (!value) error = "Pilih role terlebih dahulu";
    } else if (type === "password_confirmation") {
        const pw = form.querySelector('[data-validate="password"]')?.value || "";
        if (value !== pw) error = "Password tidak cocok";
    }

    setFieldState(wrapper, errorEl, error);

    return !error && value.length > 0;
}

function setFieldState(wrapper, errorEl, error) {
    if (!wrapper) return;

    wrapper.classList.toggle("border-red-400", !!error);
    wrapper.classList.toggle("border-gray-200", !error);

    if (errorEl) {
        errorEl.textContent = error;
        errorEl.classList.toggle("hidden", !error);
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

    const data = new FormData(form);
    const url = mode === "login" ? "/login" : "/register";

    fetch(url, {
        method: "POST",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json"
        },
        body: data
    })
    .then(async response => {
        const result = await response.json();
        if (!response.ok) {
            throw new Error(result.message || "Terjadi kesalahan.");
        }
        return result;
    })
    .then(onAuthSuccess)
    .catch(err => {
        console.error("[auth] failed", err);
        setSubmitting(form, false);
        showAlert(form, "error", err.message);
    });
}

// Ambil ?redirect= dari URL saat ini bila berupa path relatif same-origin yang
// aman (diawali "/" tapi bukan "//"). Mengembalikan null jika tidak ada/berbahaya.
function safeRedirectParam() {
    const raw = new URLSearchParams(window.location.search).get("redirect");
    if (!raw) return null;
    if (!raw.startsWith("/") || raw.startsWith("//")) return null;
    return raw;
}

// Simpan sesi (localStorage untuk state navbar), lalu redirect sesuai role.
// Dipakai bersama oleh login/register password dan Google Sign-In.
function onAuthSuccess(user) {
    localStorage.setItem(AUTH_STORAGE_KEY, JSON.stringify(user));

    let flash;
    let redirect;
    if (user.role === "admin") {
        flash = `Selamat datang, ${user.name}! Anda masuk sebagai Admin.`;
        redirect = "/admin/products";
    } else if (user.role === "mentor") {
        flash = `Selamat datang, ${user.name}! Anda masuk sebagai Mentor.`;
        redirect = "/mentor/products";
    } else {
        flash = `Selamat datang kembali, ${user.name}!`;
        // Hormati ?redirect= (mis. dari tombol "tambah ke keranjang" di /produk).
        // Hanya path relatif same-origin yang diizinkan — cegah open redirect.
        redirect = safeRedirectParam() || "/";
    }
    sessionStorage.setItem("markup.flash", flash);
    window.location.href = redirect;
}

/* ========== Google Sign-In (GIS) ========== */
// Google Identity Services me-render tombol resminya sendiri ke dalam
// [data-google-btn]. Saat pengguna klik, Google mengembalikan ID token →
// dikirim ke /auth/google untuk login berbasis session.
//
// Catatan: pola lama (render tombol tersembunyi lalu klik-programatik dari
// tombol custom) tidak lagi bekerja — GIS kini merender tombolnya di dalam
// iframe lintas-domain yang tidak bisa di-klik dari parent. Karena itu kita
// pakai tombol native yang dirender langsung (cara yang didukung resmi).
function initGoogleSignIn() {
    const holder = document.querySelector("[data-google-btn]");
    if (!holder) return;

    const form = document.querySelector("[data-auth-form]");
    const clientId = document
        .querySelector('meta[name="google-client-id"]')
        ?.getAttribute("content");

    // Google Sign-In belum dikonfigurasi di server → sembunyikan seluruh area.
    if (!clientId) {
        const wrapper = holder.closest("[data-google-area]") || holder;
        wrapper.style.display = "none";
        return;
    }

    let ready = false;
    function ensureSetup() {
        if (ready) return true;
        if (!window.google?.accounts?.id) return false;

        window.google.accounts.id.initialize({
            client_id: clientId,
            ux_mode: "popup",
            callback: (res) => {
                if (res?.credential) submitGoogleToken(form, res.credential);
            },
        });
        // Render tombol resmi Google langsung ke dalam container (terlihat).
        window.google.accounts.id.renderButton(holder, {
            type: "standard",
            theme: "outline",
            size: "large",
            text: "continue_with",
            shape: "pill",
            logo_alignment: "center",
        });
        ready = true;
        return true;
    }

    // GIS dimuat async, jadi poll singkat sampai objek tersedia lalu setup.
    (function waitForGis(attempt = 0) {
        if (ensureSetup() || attempt > 40) return;
        setTimeout(() => waitForGis(attempt + 1), 150);
    })();
}

function submitGoogleToken(form, idToken) {
    if (form) {
        setSubmitting(form, true);
        showAlert(form, null);
    }

    const csrf = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    fetch("/auth/google", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": csrf,
            "Accept": "application/json",
        },
        body: JSON.stringify({ id_token: idToken }),
    })
        .then(async (response) => {
            const result = await response.json();
            if (!response.ok) {
                throw new Error(result.message || "Login Google gagal.");
            }
            return result;
        })
        .then(onAuthSuccess)
        .catch((err) => {
            console.error("[auth] google failed", err);
            if (form) {
                setSubmitting(form, false);
                showAlert(form, "error", err.message);
            }
        });
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
        const isAdmin = user.role === "admin";

        const dashboardLink = isAdmin
            ? `<a href="/admin/products" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-navy-600">
                    <i class="fas fa-shield-halved mr-2 text-orange-500"></i>Admin Panel
                </a>`
            : `<a href="/dashboard" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-navy-600">
                    <i class="fas fa-th-large mr-2 text-slate-400"></i>Dashboard
                </a>`;

        const roleBadge = isAdmin
            ? `<span class="ml-2 rounded-full bg-orange-100 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wide text-orange-700">Admin</span>`
            : "";

        authSlot.innerHTML = `
            <div class="group relative">
                <button type="button" class="flex items-center gap-2 rounded-full border border-slate-200 px-1.5 py-1.5 pr-3 transition hover:bg-slate-50">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full ${isAdmin ? 'bg-orange-500' : 'bg-navy-600'} text-xs font-bold text-white">${escapeHtml(initials)}</span>
                    <span class="hidden text-sm font-semibold text-navy-600 sm:inline">${escapeHtml(user.name)}</span>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                </button>
                <div class="invisible absolute right-0 top-full z-50 mt-1 w-56 translate-y-2 rounded-xl border border-slate-100 bg-white py-2 opacity-0 shadow-lg transition-all duration-200 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                    <div class="border-b border-slate-100 px-4 pb-2 pt-1">
                        <p class="truncate text-sm font-bold text-navy-600">${escapeHtml(user.name)}${roleBadge}</p>
                        <p class="truncate text-xs text-slate-500">${escapeHtml(user.email)}</p>
                    </div>
                    ${dashboardLink}
                    <div class="my-1 border-t border-slate-100"></div>
                    <button type="button" data-logout class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                    </button>
                </div>
            </div>
        `;

        authSlot.querySelector("[data-logout]").addEventListener("click", () => {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            fetch('/logout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).finally(() => {
                localStorage.removeItem(AUTH_STORAGE_KEY);
                sessionStorage.setItem("markup.flash", "Berhasil keluar.");
                window.location.href = "/";
            });
        });
    } catch (e) {
        localStorage.removeItem(AUTH_STORAGE_KEY);
    }
}

/* ========== Keranjang di navbar ========== */
// Ikon keranjang hanya relevan untuk user yang sudah login. Badge menampilkan
// jumlah item dari localStorage "markup_cart" (sumber yang sama dg halaman
// produk & keranjang). Diekspos global agar bisa dipanggil setelah add-to-cart
// tanpa reload halaman.
function initNavbarCart() {
    const cartEl = document.querySelector("[data-nav-cart]");
    if (!cartEl) return;

    window.markupUpdateCartBadge = updateCartBadge;

    const isLoggedIn = !!localStorage.getItem(AUTH_STORAGE_KEY);
    if (!isLoggedIn) return; // biarkan tersembunyi untuk guest

    cartEl.classList.remove("hidden");
    cartEl.classList.add("inline-flex");
    updateCartBadge();

    // Sinkron bila keranjang berubah di tab lain.
    window.addEventListener("storage", (e) => {
        if (e.key === "markup_cart") updateCartBadge();
    });
}

function updateCartBadge() {
    const badge = document.querySelector("[data-cart-count]");
    if (!badge) return;

    let count = 0;
    try {
        const cart = JSON.parse(localStorage.getItem("markup_cart"));
        count = Array.isArray(cart) ? cart.length : 0;
    } catch {
        count = 0;
    }

    badge.textContent = count > 99 ? "99+" : String(count);
    badge.classList.toggle("hidden", count === 0);
    badge.classList.toggle("flex", count > 0);
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