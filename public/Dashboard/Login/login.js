// Page Load Animations
window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('header-anim').classList.replace('opacity-0', 'opacity-100');
    document.getElementById('header-anim').classList.replace('translate-y-4', 'translate-y-0');
    document.getElementById('card-anim').classList.replace('opacity-0', 'opacity-100');
    document.getElementById('card-anim').classList.replace('scale-95', 'scale-100');
    document.getElementById('footer-anim').classList.replace('opacity-0', 'opacity-100');
});

// Toggle Password
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    } else {
        pwd.type = 'password';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    }
}

// Handle Login
function handleLogin(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const email = document.getElementById('email').value;

    btn.classList.add('btn-loading', 'pointer-events-none');

    // Simulating API
    setTimeout(() => {
        btn.classList.remove('btn-loading');

        if (email === 'admin@medinear.com') {
            btn.style.background = '#10b981';
            btn.innerHTML = '<i class="fa-solid fa-circle-check ml-2"></i> تم بنجاح';
            setTimeout(() => {
                window.location.reload(); // Replace with dashboard URL
            }, 1000);
        } else {
            btn.classList.remove('pointer-events-none');
            btn.classList.add('animate-shake');
            btn.style.background = '#ef4444';
            btn.innerHTML = 'خطأ في البيانات';

            setTimeout(() => {
                btn.classList.remove('animate-shake');
                btn.style.background = '';
                btn.innerHTML = '<span class="btn-text flex items-center gap-2">تسجيل الدخول <i class="fa-solid fa-chevron-left text-xs"></i></span><div class="loading-spinner"></div>';
            }, 2000);
        }
    }, 1500);
}
