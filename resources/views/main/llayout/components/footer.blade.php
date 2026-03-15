<footer
    class="bg-footerBg text-gray-300 pt-16 md:pt-20 pb-6 md:pb-8 mt-auto border-t-[6px] md:border-t-[8px] border-primary">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-8 md:gap-10 mb-12 md:mb-16">
            <div class="flex items-center gap-3 md:gap-4 cursor-default">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="w-10 h-10 md:w-12 md:h-12">
                    <defs>
                        <linearGradient id="logoGradFooter" x1="80%" y1="0%" x2="20%" y2="100%">
                            <stop offset="0%" stop-color="#84cc16" />
                            <stop offset="100%" stop-color="#14b8a6" />
                        </linearGradient>
                    </defs>
                    <circle cx="50" cy="50" r="46" stroke="url(#logoGradFooter)" stroke-width="4" />
                    <path
                        d="M50 15C34.536 15 22 27.536 22 43C22 62.5 50 85 50 85C50 85 78 62.5 78 43C78 27.536 65.464 15 50 15Z"
                        fill="url(#logoGradFooter)" />
                    <path d="M45 28H55V37H64V47H55V56H45V47H36V37H45V28Z" fill="white" />
                    <g transform="translate(38, 65) rotate(-50)">
                        <rect x="-14" y="-26" width="28" height="52" rx="14" fill="white" />
                        <rect x="-11" y="-23" width="22" height="46" rx="11" fill="white"
                            stroke="url(#logoGradFooter)" stroke-width="2" />
                        <path d="M-10 0 H10 V12 A10 10 0 0 1 -10 12 Z" fill="url(#logoGradFooter)" />
                    </g>
                </svg>
                <span class="font-black text-2xl md:text-3xl text-white tracking-tight">MediNear</span>
            </div>

            <div class="flex flex-wrap justify-center gap-6 md:gap-8 text-sm md:text-base font-medium">
                <span class="text-primary font-black hidden lg:inline" data-i18n="footImportantLinks">روابط
                    هامة:</span>
                <button
                    onclick="navigateTo('home'); setTimeout(()=>document.getElementById('features').scrollIntoView({behavior:'smooth'}), 100);"
                    class="hover:text-white transition-colors" data-i18n="navFeatures">المميزات</button>
                <button
                    onclick="navigateTo('home'); setTimeout(()=>document.getElementById('how-it-works').scrollIntoView({behavior:'smooth'}), 100);"
                    class="hover:text-white transition-colors" data-i18n="navHow">كيف يعمل</button>
                <button
                    onclick="navigateTo('home'); setTimeout(()=>document.getElementById('contact').scrollIntoView({behavior:'smooth'}), 100);"
                    class="hover:text-white transition-colors" data-i18n="navContact">اتصل بنا</button>
                <button onclick="window.location.href='pharmacies.html'" class="hover:text-white transition-colors"
                    data-i18n="navPharmacies">للصيدليات</button>
            </div>

            <div class="flex flex-wrap justify-center gap-4 md:gap-6 text-xs md:text-sm text-gray-500 font-medium">
                <a href="#" class="hover:text-white transition-colors" data-i18n="footPrivacy">سياسة الخصوصية</a>
                <a href="#" class="hover:text-white transition-colors" data-i18n="footTerms">الشروط والأحكام</a>
                <a href="#" class="hover:text-white transition-colors" data-i18n="footData">سياسة حماية
                    البيانات</a>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-6 md:pt-8 text-center text-xs md:text-sm text-gray-500 font-medium">
            <p data-i18n="footCopy">2026 MediNear جميع الحقوق محفوظة.</p>
        </div>
    </div>
</footer>
