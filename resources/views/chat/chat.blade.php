<style>
    /* --- المتغيرات والتنسيقات الأساسية --- */
    :root {
        --chat-primary: #00b894;
        --chat-secondary: #00cec9;
        --chat-main-gradient: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
        --chat-text-dark: #2d3436;
        --chat-text-muted: #636e72;
        --chat-bg-light: #f8f9fa;
        --chat-shadow-lg: 0 15px 35px rgba(0,0,0,0.1);
        --chat-shadow-hover: 0 20px 40px rgba(0, 184, 148, 0.25);
        --chat-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* --- Floating Action Button (FAB) --- */
    .medinear-fab-container {
        position: fixed; bottom: 30px; z-index: 9999;
        display: flex; flex-direction: column; align-items: start;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    html[dir="rtl"] .medinear-fab-container { right: 30px; left: auto; }
    html[dir="ltr"] .medinear-fab-container { left: 30px; right: auto; }
    .medinear-fab-main {
        width: 65px; height: 65px; border-radius: 50%; background: var(--chat-main-gradient);
        color: white; border: none; font-size: 26px; cursor: pointer;
        box-shadow: 0 8px 25px rgba(0, 184, 148, 0.3);
        transition: var(--chat-transition); display: flex; align-items: center; justify-content: center;
        position: relative;
    }
    /* Notification Badge */
    .medinear-fab-main::after {
        content: '1'; position: absolute; top: -2px; right: -2px; background: #ff7675; color: white;
        font-size: 12px; font-weight: bold; width: 22px; height: 22px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; border: 2px solid white;
        animation: bounce 2s infinite;
    }
    .medinear-fab-container.active .medinear-fab-main::after { display: none; }

    .medinear-fab-main:hover { transform: scale(1.1) rotate(5deg); box-shadow: var(--chat-shadow-hover); }
    .medinear-fab-container.active .medinear-fab-main { transform: rotate(45deg); background: #ff7675; box-shadow: 0 8px 25px rgba(255, 118, 117, 0.3); }

    .medinear-fab-menu { display: none; flex-direction: column; gap: 12px; margin-bottom: 20px; }
    .medinear-fab-container.active .medinear-fab-menu { display: flex; animation: fadeInUp 0.4s ease forwards; }

    .medinear-menu-item {
        background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.5); padding: 14px 22px; border-radius: 18px;
        cursor: pointer; box-shadow: var(--chat-shadow-lg); font-weight: 700; font-size: 15px;
        display: flex; align-items: center; gap: 12px; color: var(--chat-text-dark); transition: var(--chat-transition);
        white-space: nowrap;
    }
    .medinear-menu-item:hover { transform: translateX(-8px); color: var(--chat-primary); border-color: var(--chat-primary); box-shadow: var(--chat-shadow-hover); }
    .medinear-menu-item i { background: var(--chat-bg-light); width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: var(--chat-primary); }

    /* --- Chat Window --- */
    .medinear-chat-window {
        position: fixed; bottom: 110px; width: 400px; height: 75vh; max-height: 700px;
        background: white; border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        display: none; flex-direction: column; overflow: hidden; z-index: 9998;
        border: 1px solid rgba(0,0,0,0.05);
        animation: windowReveal 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    html[dir="rtl"] .medinear-chat-window { right: 30px; left: auto; }
    html[dir="ltr"] .medinear-chat-window { left: 30px; right: auto; }

    .medinear-window-header {
        padding: 20px; background: var(--chat-main-gradient); color: white;
        display: flex; justify-content: space-between; align-items: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); flex-shrink: 0; position: relative;
    }

    .medinear-header-info { display: flex; align-items: center; gap: 15px; }
    .ai-avatar-header { width: 45px; height: 45px; border-radius: 50%; background: white; border: 2px solid rgba(255,255,255,0.5); display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

    .medinear-header-text h3 { margin: 0; font-size: 16px; font-weight: 800; display: flex; align-items: center; gap: 5px;}
    .medinear-header-text p { margin: 2px 0 0 0; font-size: 12px; opacity: 0.9; display: flex; align-items: center; gap: 4px; }
    .online-dot { width: 8px; height: 8px; background: #55efc4; border-radius: 50%; display: inline-block; box-shadow: 0 0 0 2px rgba(85, 239, 196, 0.3); }

    /* --- Messages Area --- */
    .medinear-messages-container {
        flex: 1; padding: 25px 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px;
        background: #f8fcfb; background-image: radial-gradient(#00b894 0.5px, transparent 0.5px); background-size: 20px 20px; background-position: 0 0, 10px 10px; background-color: #fafafa;
    }
    .medinear-messages-container::-webkit-scrollbar { width: 6px; }
    .medinear-messages-container::-webkit-scrollbar-thumb { background: #dfe6e9; border-radius: 10px; }

    .medinear-msg-wrapper { display: flex; gap: 10px; animation: msgIn 0.3s ease forwards; max-width: 92%; }
    .medinear-msg-wrapper.user { align-self: flex-start; flex-direction: row-reverse; }
    .medinear-msg-wrapper.bot { align-self: flex-end; }

    .msg-avatar { width: 35px; height: 35px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 16px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .bot .msg-avatar { background: var(--chat-main-gradient); color: white; }
    .user .msg-avatar { background: #dfe6e9; color: var(--chat-text-muted); }

    .medinear-msg-content { display: flex; flex-direction: column; gap: 4px; }
    .user .medinear-msg-content { align-items: flex-end; }

    .medinear-bubble {
        padding: 14px 18px; border-radius: 20px; font-size: 14px; line-height: 1.6;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .medinear-msg-wrapper.user .medinear-bubble { background: white; color: var(--chat-text-dark); border: 1px solid #f0f0f0; border-bottom-right-radius: 4px; }
    .medinear-msg-wrapper.bot .medinear-bubble { background: var(--chat-main-gradient); color: white; border-bottom-left-radius: 4px; }

    .medinear-bubble strong { font-weight: 800; color: inherit; }
    .user .medinear-bubble strong { color: var(--chat-primary); }

    .msg-time { font-size: 11px; color: #a4b0be; font-weight: 600; padding: 0 5px; }

    /* --- Ai Addons (Tabs & Chips) --- */
    .medinear-addons-wrapper { background: white; border-top: 1px solid #f0f0f0; padding-top: 5px; box-shadow: 0 -5px 10px rgba(0,0,0,0.02); z-index: 10; }
    .medinear-tabs { padding: 10px 15px; display: flex; gap: 8px; overflow-x: auto; scrollbar-width: none; }
    .medinear-tabs::-webkit-scrollbar { display: none; }
    .medinear-tab-btn {
        padding: 8px 16px; border-radius: 12px; border: none; background: #f1f3f4;
        font-size: 13px; font-weight: 700; color: var(--chat-text-muted); cursor: pointer; transition: var(--chat-transition); white-space: nowrap;
    }
    .medinear-tab-btn.active { background: var(--chat-primary); color: white; box-shadow: 0 4px 10px rgba(0, 184, 148, 0.2); }

    .medinear-chips-container { padding: 5px 15px 15px 15px; display: flex; flex-wrap: wrap; gap: 8px; max-height: 110px; overflow-y: auto;}
    .medinear-chip {
        background: white; border: 1.5px solid #eee; padding: 8px 14px; border-radius: 20px;
        font-size: 12.5px; color: var(--chat-text-dark); cursor: pointer; transition: all 0.2s ease; font-weight: 600;
    }
    .medinear-chip:hover { border-color: var(--chat-primary); color: var(--chat-primary); transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .medinear-chip:active { transform: scale(0.95); }

    /* --- Input Area --- */
    .medinear-input-area { padding: 15px; background: white; display: flex; gap: 10px; align-items: center; border-top: 1px solid #f0f0f0; }
    .medinear-input-area input {
        flex: 1; padding: 14px 20px; border-radius: 25px; border: 1.5px solid #eee;
        outline: none; background: #f8f9fa; transition: var(--chat-transition); font-weight: 500; font-size: 14px;
    }
    .medinear-input-area input:focus { border-color: var(--chat-primary); background: #fff; box-shadow: 0 0 0 4px rgba(0, 184, 148, 0.1); }

    .medinear-send-btn {
        background: var(--chat-main-gradient); color: white; border: none; width: 46px; height: 46px; border-radius: 50%;
        cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--chat-transition); box-shadow: 0 4px 12px rgba(0, 184, 148, 0.2); font-size: 16px; flex-shrink:0;
    }
    .medinear-send-btn:hover { transform: scale(1.08) rotate(-10deg); box-shadow: 0 6px 15px rgba(0, 184, 148, 0.3); }
    .medinear-send-btn:active { transform: scale(0.95); }

    /* --- Responsive --- */
    @media (max-width: 500px) {
        .medinear-chat-window { width: 100%; height: 100%; max-height: 100vh; bottom: 0; left: 0 !important; right: 0 !important; border-radius: 0; }
        .medinear-fab-container { bottom: 20px; }
        html[dir="rtl"] .medinear-fab-container { right: 20px; left: auto; }
        html[dir="ltr"] .medinear-fab-container { left: 20px; right: auto; }
        .medinear-msg-wrapper { max-width: 95%; }
        .medinear-window-header { border-radius: 0; padding-top: 25px; }
    }

    /* --- Animations --- */
    @keyframes windowReveal { 0% { opacity: 0; transform: translateY(50px) scale(0.95); } 100% { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes msgIn { from { opacity: 0; transform: translateY(15px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-3px); } }

    /* Typing Indicator */
    .chat-typing-indicator { display: none; padding: 15px 20px; align-items: center; gap: 15px; animation: msgIn 0.3s ease; }
    .medinear-dot { width: 6px; height: 6px; background: var(--chat-primary); border-radius: 50%; animation: blink 1.4s infinite; }
    .medinear-dot:nth-child(2) { animation-delay: 0.2s; }
    .medinear-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes blink { 0%, 100% { opacity: 0.2; transform: translateY(0); } 50% { opacity: 1; transform: translateY(-4px); } }
</style>

<div class="medinear-fab-container" id="fabContainer" dir="rtl">
    <div class="medinear-fab-menu">
        <button class="medinear-menu-item" onclick="openChatSection('list')"><i class="fas fa-user-md"></i> <span id="chatFabDoctors">أطباء وصيدليات</span></button>
        <button class="medinear-menu-item" onclick="openChatSection('ai')"><i class="fas fa-robot"></i> <span id="chatFabAi">مساعد برشامة 🤖</span></button>
    </div>
    <button class="medinear-fab-main" id="fabToggle"><i class="fas fa-comment-medical"></i></button>
</div>

<div class="medinear-chat-window" id="chatWindow" dir="rtl">
    <div class="medinear-window-header">
        <div class="medinear-header-info">
            <i class="fas fa-chevron-right" id="chatBackBtn" onclick="openChatSection('list')" style="display:none; cursor:pointer; font-size: 18px; padding-left: 10px;"></i>

            <div class="ai-avatar-header" id="headerAvatar">🤖</div>
            <div class="medinear-header-text">
                <h3 id="chatWindowTitle">MediNear</h3>
                <p id="chatWindowSubtitle"><span class="online-dot"></span> متصل الآن، جاهز للمساعدة</p>
            </div>
        </div>

        <div style="display: flex; gap: 15px; align-items: center;">
            <button id="clearChatBtn" onclick="clearChatHistory()" style="background:none; border:none; color:white; opacity: 0.8; font-size:16px; cursor:pointer;" title="مسح المحادثة"><i class="fas fa-trash-can"></i></button>
            <button onclick="closeChatWindow()" style="background:rgba(255,255,255,0.2); border:none; color:white; width: 32px; height:32px; border-radius:50%; font-size:20px; cursor:pointer; transition: 0.3s; display:flex; align-items:center; justify-content:center;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">&times;</button>
        </div>
    </div>

    <div id="chatListSection" class="section active" style="overflow-y:auto; flex:1; padding: 20px;">
        <h4 id="chatListTitleStr" style="font-size: 14px; color: var(--chat-text-muted); margin-top:0; margin-bottom: 15px; font-weight: 700;">المراسلات النشطة</h4>
        <div id="contactsGrid"></div>
    </div>

    <div id="chatConversationSection" class="section" style="display:none; flex-direction:column; flex:1; overflow:hidden;">
        <div class="medinear-messages-container" id="chatMsgBody"></div>

        <div class="chat-typing-indicator" id="chatTypingArea">
            <div class="msg-avatar" style="background: var(--chat-main-gradient); color: white;">🤖</div>
            <div style="background: white; padding: 12px 18px; border-radius: 20px; display: flex; gap: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-bottom-left-radius: 4px;">
                <div class="medinear-dot"></div><div class="medinear-dot"></div><div class="medinear-dot"></div>
            </div>
        </div>

        <div class="medinear-addons-wrapper" id="aiAddons" style="display:none;">
            <div class="medinear-tabs" id="categoryTabs"></div>
            <div class="medinear-chips-container" id="chipsGrid"></div>
        </div>

        <div class="medinear-input-area">
            <input type="text" id="chatInput" placeholder="اكتب سؤالك هنا... (مثال: بديل بنادول؟)" onkeypress="handleChatEnter(event)" autocomplete="off">
            <button class="medinear-send-btn" onclick="processChatMessage()"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<script>
// --- المنطق البرمجي والذكاء الاصطناعي ---





// ⚠️ ملحوظة: في بيئة الإنتاج، يجب وضع الـ API_KEY في الـ Backend.
const GROQ_API_KEY = "{{ env('GROQ_API_KEY') }}";
const STORAGE_KEY = "medinear_chat_history";

let chatState = {
    currentMode: 'ai',
    storage: { ai: [], "dr": [], "pharmacy": [] }
};

const savedStorage = localStorage.getItem(STORAGE_KEY);
if(savedStorage) {
    chatState.storage = JSON.parse(savedStorage);
    if (!chatState.storage.dr) {
        chatState.storage = { ai: [], "dr": [], "pharmacy": [] };
        localStorage.removeItem(STORAGE_KEY);
    }
}

const chatI18n = {
    ar: {
        fabDoctors: "أطباء وصيدليات",
        fabAi: "مساعد برشامة 🤖",
        chatListTitle: "المراسلات النشطة",
        chatWinTitleAi: "المساعد الذكي 'برشامة'",
        chatWinSubAi: "متصل الآن، مستعد للإجابة",
        chatTitleList: "مراسلاتي",
        chatSubList: "اختر جهة للتواصل",
        chatPlaceholder: "اكتب سؤالك هنا... (مثال: بديل بنادول؟)",
        clearChatConfirm: "هل أنت متأكد من مسح سجل المحادثة؟",
        clearChatTooltip: "مسح المحادثة",
        aiWelcome: "أهلاً بك في **MediNear**! أنا المساعد الذكي 'برشامة' 🤖\nأقدر أساعدك إزاي النهاردة؟ بتبحث عن دواء كـ **مريض** ولا بتستفسر عن حاجة كـ **صيدلي**؟",
        aiOffline: "شكراً لتواصلك. هذه نسخة تجريبية، سيتم الرد عليك قريباً. ✅",
        aiError: "عذراً، واجهت مشكلة في شبكة الاتصال دلوقتي. ممكن تجرب تبعت رسالتك تاني؟ ⚠️",

        tabMeds: "💊 أدوية",
        tabPatient: "🧑‍🤝‍🧑 للمرضى",
        tabPharmacy: "🏪 للصيدليات",
        tabHealth: "💡 نصائح طبية",
        tabAbout: "ℹ️ عن المنصة",

        clickToStart: "اضغط لبدء المحادثة",
        chatAiName: "المساعد الذكي برشامة",
        chatDrName: "د. أحمد سالم",
        chatPharmName: "صيدلية ميدي نير",

        aiData_meds_0: "بديل كونجستال؟ 💊",
        aiData_meds_1: "سعر بنادول ادفانس؟ 💰",
        aiData_meds_2: "دواء ناقص ألاقيه فين؟",
        aiData_meds_3: "إيه أفضل مسكن للصداع؟",
        aiData_meds_4: "ألاقي حقن كليكسان فين؟",

        aiData_patient_0: "إزاي أرفع روشتة؟ 📸",
        aiData_patient_1: "أطلب أدوية إزاي؟",
        aiData_patient_2: "مدة التوصيل؟ 🛵",
        aiData_patient_3: "إزاي أتبع طلبي؟",
        aiData_patient_4: "طرق الدفع المتاحة؟ 💳",

        aiData_pharmacy_0: "إزاي أضيف دواء للمخزون؟ 📦",
        aiData_pharmacy_1: "شروط الانضمام للمنصة؟ 🏪",
        aiData_pharmacy_2: "تعديل ساعات العمل ⏰",
        aiData_pharmacy_3: "تسوية الحسابات والأرباح 💰",

        aiData_health_0: "نصائح لمرضى السكر 🩸",
        aiData_health_1: "فيتامينات للمناعة 🛡️",
        aiData_health_2: "أعراض البرد والإنفلونزا 🤧",
        aiData_health_3: "الإسعافات الأولية للحروق",

        aiData_about_0: "ما هو MediNear؟",
        aiData_about_1: "التواصل مع الدعم الفني 📞",

        canned_about_0: "نحن **MediNear**، منصة ذكية مصرية بتربطك بأقرب صيدلية متوفر فيها دوائك، سواء كنت مريض بتبحث عن دواء أو صيدلي حابب يزود مبيعاته. هدفنا نسهل عليك المنظومة الطبية!",
        canned_about_1: "تقدر تتواصل مع فريق دعم MediNear على الرقم: **01110562097** أو من خلال الإيميل: support@medinear.com. إحنا موجودين لمساعدتك 24/7.",

        canned_patient_0: "عشان ترفع روشتة:\n1. افتح صفحة 'الروشتات'.\n2. اضغط على **'تصوير روشتة'** وصورها أو ارفعها من الاستوديو.\n3. الصيدلية الأقرب هتشوفها وتجهزلك الطلب وتكلمك للتأكيد.",
        canned_patient_1: "اكتب اسم الدواء في شريط البحث الرئيسي، وهنعرضلك أقرب الصيدليات اللي متوفر فيها. اختار الصيدلية واضغط **'أضف للعربة'** وكمل الطلب بسهولة.",
        canned_patient_2: "منصة MediNear مصممة عشان تحل المشكلة دي! ابحث عن الدواء في الموقع، وهيظهرلك حتى لو في صيدلية بعيدة عنك شوية، وتقدر تطلبه دليفري.",
        canned_patient_3: "حالياً الدفع متاح **كاش عند الاستلام** (الدفع نقداً للمندوب)، وقريباً هنوفر الدفع بالفيزا والمحافظ الإلكترونية.",

        canned_pharmacy_0: "كصيدلي، تقدر تضيف أدوية لمخزونك كالتالي:\n1. افتح لوحة التحكم (Dashboard).\n2. اختار **'مخزوني'**.\n3. اضغط **'إضافة للمخزون'**، ابحث عن الدواء وحدد الكمية والسعر، واضغط حفظ.",
        canned_pharmacy_1: "عشان تنضم لينا كصيدلية محتاجين:\n- رخصة صيدلية سارية.\n- بيانات الصيدلي المسؤول.\n- مقر فعلي للصيدلية.\nسجل بياناتك في صفحة (انضمام الصيدليات) وهيتم تفعيل حسابك فور المراجعة.",
        canned_pharmacy_2: "من لوحة تحكم الصيدلية، ادخل على **'الملف الشخصي'** (Profile). هتلاقي حقل 'ساعات العمل'، عدله للمواعيد المناسبة واضغط حفظ التعديلات."
    },
    en: {
        fabDoctors: "Doctors & Pharmacies",
        fabAi: "Barshama AI Assistant 🤖",
        chatListTitle: "Active Chats",
        chatWinTitleAi: "AI Assistant 'Barshama'",
        chatWinSubAi: "Online, ready to help",
        chatTitleList: "My Chats",
        chatSubList: "Select a contact",
        chatPlaceholder: "Type your question here... (e.g. Panadol alternative?)",
        clearChatConfirm: "Are you sure you want to clear chat history?",
        clearChatTooltip: "Clear Chat",
        aiWelcome: "Welcome to **MediNear**! I'm your AI Assistant 'Barshama' 🤖\nHow can I help you today? Are you searching for medicine as a **Patient**, or inquiring as a **Pharmacist**?",
        aiOffline: "Thank you for reaching out. This is a beta version, we will reply soon. ✅",
        aiError: "Sorry, I'm having trouble connecting right now. Can you try sending your message again? ⚠️",

        tabMeds: "💊 Medicines",
        tabPatient: "🧑‍🤝‍🧑 Patients",
        tabPharmacy: "🏪 Pharmacies",
        tabHealth: "💡 Health Tips",
        tabAbout: "ℹ️ About Platform",

        clickToStart: "Click to start chatting",
        chatAiName: "Barshama AI Assistant",
        chatDrName: "Dr. Ahmed Salem",
        chatPharmName: "MediNear Pharmacy",

        aiData_meds_0: "Congestal alternative? 💊",
        aiData_meds_1: "Panadol Advance price? 💰",
        aiData_meds_2: "Where to find a shortage medicine?",
        aiData_meds_3: "What is the best painkiller for headache?",
        aiData_meds_4: "Where to find Clexane injections?",

        aiData_patient_0: "How to upload a prescription? 📸",
        aiData_patient_1: "How to order medicines?",
        aiData_patient_2: "Delivery time? 🛵",
        aiData_patient_3: "How to track my order?",
        aiData_patient_4: "Available payment methods? 💳",

        aiData_pharmacy_0: "How to add medicine to stock? 📦",
        aiData_pharmacy_1: "Conditions for joining the platform? 🏪",
        aiData_pharmacy_2: "Updating working hours ⏰",
        aiData_pharmacy_3: "Settling accounts and profits 💰",

        aiData_health_0: "Tips for diabetic patients 🩸",
        aiData_health_1: "Vitamins for immunity 🛡️",
        aiData_health_2: "Cold and flu symptoms 🤧",
        aiData_health_3: "First aid for burns",

        aiData_about_0: "What is MediNear?",
        aiData_about_1: "Contact Technical Support 📞",

        canned_about_0: "We are **MediNear**, a smart platform connecting you to the nearest pharmacy that has your medicine, whether you are a patient looking for medicine or a pharmacist wanting to increase sales. Our goal is to make the medical system easier for you!",
        canned_about_1: "You can contact the MediNear support team at: **01110562097** or via email: support@medinear.com. We are here to help you 24/7.",

        canned_patient_0: "To upload a prescription:\n1. Open the 'Prescriptions' page.\n2. Click on **'Take a picture of the prescription'** and capture it or upload it from the gallery.\n3. The nearest pharmacy will see it, prepare your order, and contact you for confirmation.",
        canned_patient_1: "Type the name of the medicine in the main search bar, and we'll show you the nearest pharmacies where it's available. Choose the pharmacy, click **'Add to Cart'**, and easily complete your order.",
        canned_patient_2: "The MediNear platform is designed to solve this problem! Search for the medicine on the site, and it will appear even if it's in a pharmacy a bit far from you, and you can order it with delivery.",
        canned_patient_3: "Currently, payment is available as **Cash on Delivery** (paying cash to the representative), and soon we will provide payment by Visa and Electronic wallets.",

        canned_pharmacy_0: "As a pharmacist, you can add medicines to your stock as follows:\n1. Open the Dashboard.\n2. Choose **'My Stock'**.\n3. Click **'Add to Stock'**, search for the medicine, specify the quantity and price, and click Save.",
        canned_pharmacy_1: "To join us as a pharmacy, we need:\n- A valid pharmacy license.\n- The responsible pharmacist's details.\n- A physical location for the pharmacy.\nRegister your details on the (Join Pharmacies) page, and your account will be activated upon review.",
        canned_pharmacy_2: "From the pharmacy dashboard, go to the **'Profile'**. You'll find a 'Working Hours' field, update it to the appropriate times, and click Save Changes."
    }
};

function getAiAssistantData(lang) {
    return {
        meds: [chatI18n[lang].aiData_meds_0, chatI18n[lang].aiData_meds_1, chatI18n[lang].aiData_meds_2, chatI18n[lang].aiData_meds_3, chatI18n[lang].aiData_meds_4],
        patient: [chatI18n[lang].aiData_patient_0, chatI18n[lang].aiData_patient_1, chatI18n[lang].aiData_patient_2, chatI18n[lang].aiData_patient_3, chatI18n[lang].aiData_patient_4],
        pharmacy: [chatI18n[lang].aiData_pharmacy_0, chatI18n[lang].aiData_pharmacy_1, chatI18n[lang].aiData_pharmacy_2, chatI18n[lang].aiData_pharmacy_3],
        health: [chatI18n[lang].aiData_health_0, chatI18n[lang].aiData_health_1, chatI18n[lang].aiData_health_2, chatI18n[lang].aiData_health_3],
        about: [chatI18n[lang].aiData_about_0, chatI18n[lang].aiData_about_1]
    };
}

function getCannedChatResponses(lang) {
    return {
        [chatI18n[lang].aiData_about_0]: chatI18n[lang].canned_about_0,
        [chatI18n[lang].aiData_about_1]: chatI18n[lang].canned_about_1,

        [chatI18n[lang].aiData_patient_0]: chatI18n[lang].canned_patient_0,
        [chatI18n[lang].aiData_patient_1]: chatI18n[lang].canned_patient_1,
        [chatI18n[lang].aiData_meds_2]: chatI18n[lang].canned_patient_2,
        [chatI18n[lang].aiData_patient_4]: chatI18n[lang].canned_patient_3,

        [chatI18n[lang].aiData_pharmacy_0]: chatI18n[lang].canned_pharmacy_0,
        [chatI18n[lang].aiData_pharmacy_1]: chatI18n[lang].canned_pharmacy_1,
        [chatI18n[lang].aiData_pharmacy_2]: chatI18n[lang].canned_pharmacy_2
    };
}

let activeChatLang = 'ar';

window.updateChatLanguage = function(lang) {
    if(!lang) lang = activeChatLang;
    activeChatLang = lang;

    // Update FAB and Titles
    const elDoctors = document.getElementById('chatFabDoctors');
    const elAi = document.getElementById('chatFabAi');
    const elListTitle = document.getElementById('chatListTitleStr');
    const elInput = document.getElementById('chatInput');
    const elClearBtn = document.getElementById('clearChatBtn');
    const elBackBtn = document.getElementById('chatBackBtn');

    if (elDoctors) elDoctors.innerText = chatI18n[lang].fabDoctors;
    if (elAi) elAi.innerText = chatI18n[lang].fabAi;
    if (elListTitle) elListTitle.innerText = chatI18n[lang].chatListTitle;
    if (elInput) elInput.placeholder = chatI18n[lang].chatPlaceholder;
    if (elClearBtn) elClearBtn.title = chatI18n[lang].clearChatTooltip;
    if (elBackBtn) elBackBtn.className = lang === 'ar' ? 'fas fa-chevron-right' : 'fas fa-chevron-left';

    // Switch directions logic for the chat window container
    const fabContainer = document.getElementById('fabContainer');
    const chatWindow = document.getElementById('chatWindow');
    if (fabContainer) fabContainer.dir = lang === 'ar' ? 'rtl' : 'ltr';
    if (chatWindow) chatWindow.dir = lang === 'ar' ? 'rtl' : 'ltr';

    // Update active chat window texts if open
    if(chatState.currentMode === 'list') {
        document.getElementById('chatWindowTitle').innerText = chatI18n[activeChatLang].chatTitleList;
        document.getElementById('chatWindowSubtitle').innerHTML = chatI18n[activeChatLang].chatSubList;
        renderChatContacts();
    } else if(chatState.currentMode === 'ai') {
        document.getElementById('chatWindowTitle').innerText = chatI18n[activeChatLang].chatWinTitleAi;
        document.getElementById('chatWindowSubtitle').innerHTML = "<span class='online-dot'></span> " + chatI18n[activeChatLang].chatWinSubAi;
        renderChatTabs();

        // Update first message if empty in new language
        if (chatState.storage.ai.length === 0) {
            renderChatMessages();
        }
    } else {
        let title = chatState.currentMode === 'dr' ? chatI18n[activeChatLang].chatDrName : chatI18n[activeChatLang].chatPharmName;
        document.getElementById('chatWindowTitle').innerText = title;
    }
};

function formatMessageText(text) {
    let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    formattedText = formattedText.replace(/\n/g, '<br>');
    return formattedText;
}

function getCurrentTime() {
    const now = new Date();
    let hours = now.getHours();
    let minutes = now.getMinutes();
    const ampm = hours >= 12 ? 'م' : 'ص';
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    return hours + ':' + minutes + ' ' + ampm;
}

document.getElementById('fabToggle').onclick = () => document.getElementById('fabContainer').classList.toggle('active');

function toggleFabVisibility(show) {
    const fab = document.getElementById('fabContainer');
    if (window.innerWidth <= 500) {
        fab.style.opacity = show ? "1" : "0";
        fab.style.pointerEvents = show ? "all" : "none";
        fab.style.transform = show ? "scale(1)" : "scale(0)";
    }
}

function closeChatWindow() {
    document.getElementById('chatWindow').style.display = 'none';
    toggleFabVisibility(true);
}

function clearChatHistory() {
    if(confirm(chatI18n[activeChatLang].clearChatConfirm)) {
        chatState.storage[chatState.currentMode] = [];
        localStorage.setItem(STORAGE_KEY, JSON.stringify(chatState.storage));
        renderChatMessages();
    }
}

function openChatSection(mode) {
    const win = document.getElementById('chatWindow');
    win.style.display = 'flex';
    document.getElementById('fabContainer').classList.remove('active');
    toggleFabVisibility(false);

    document.getElementById('chatListSection').style.display = (mode === 'list') ? 'block' : 'none';
    document.getElementById('chatConversationSection').style.display = (mode === 'list') ? 'none' : 'flex';

    if(mode === 'list') {
        document.getElementById('chatWindowTitle').innerText = chatI18n[activeChatLang].chatTitleList;
        document.getElementById('chatWindowSubtitle').innerHTML = chatI18n[activeChatLang].chatSubList;
        document.getElementById('headerAvatar').innerHTML = "💬";
        document.getElementById('chatBackBtn').style.display = 'none';
        document.getElementById('clearChatBtn').style.display = 'none';
        renderChatContacts();
    } else {
        chatState.currentMode = mode;
        document.getElementById('chatBackBtn').style.display = 'block';
        document.getElementById('clearChatBtn').style.display = 'block';

        if(mode === 'ai') {
            document.getElementById('chatWindowTitle').innerText = chatI18n[activeChatLang].chatWinTitleAi;
            document.getElementById('chatWindowSubtitle').innerHTML = "<span class='online-dot'></span> " + chatI18n[activeChatLang].chatWinSubAi;
            document.getElementById('headerAvatar').innerHTML = "🤖";
            document.getElementById('aiAddons').style.display = 'block';
            renderChatTabs();
        } else {
            let title = mode === 'dr' ? chatI18n[activeChatLang].chatDrName : chatI18n[activeChatLang].chatPharmName;
            let icon = mode === 'dr' ? "👨‍⚕️" : "🏥";
            document.getElementById('chatWindowTitle').innerText = title;
            document.getElementById('chatWindowSubtitle').innerHTML = "<span class='online-dot'></span>";
            document.getElementById('headerAvatar').innerHTML = icon;
            document.getElementById('aiAddons').style.display = 'none';
        }

        renderChatMessages();
        setTimeout(() => document.getElementById('chatInput').focus(), 100);
    }
}

function renderChatContacts() {
    const grid = document.getElementById('contactsGrid');
    const docs = [
        { name: chatI18n[activeChatLang].chatAiName, type: "ai", icon: "🤖", color: "var(--chat-main-gradient)" },
        { name: chatI18n[activeChatLang].chatDrName, type: "dr", icon: "👨‍⚕️", color: "#0984e3" },
        { name: chatI18n[activeChatLang].chatPharmName, type: "pharmacy", icon: "🏥", color: "#e84393" }
    ];

    grid.innerHTML = docs.map(doc => `
        <div class="medinear-contact-card" onclick="openChatSection('${doc.type}')">
            <div style="width:48px; height:48px; border-radius:50%; background:${doc.color}; color:white; display:flex; align-items:center; justify-content:center; font-size:20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">${doc.icon}</div>
            <div style="flex:1">
                <div style="font-weight:800; font-size:15px; color: var(--chat-text-dark);">${doc.name}</div>
                <div style="font-size:12px; color:var(--chat-text-muted); margin-top: 4px;">${chatI18n[activeChatLang].clickToStart}</div>
            </div>
            <i class="fas ${activeChatLang === 'ar' ? 'fa-chevron-left' : 'fa-chevron-right'}" style="color: #dfe6e9; font-size: 16px;"></i>
        </div>
    `).join('');
}

function renderChatMessages() {
    const body = document.getElementById('chatMsgBody');
    body.innerHTML = '';
    const history = chatState.storage[chatState.currentMode];

    if(history.length === 0 && chatState.currentMode === 'ai') {
        pushChatMsg('bot', chatI18n[activeChatLang].aiWelcome);
        return;
    }

    history.forEach(m => {
        const isRtl = /[\\u0600-\\u06FF]/.test(m.text) || activeChatLang === 'ar';
        const avatar = m.role === 'bot' ? (chatState.currentMode === 'ai' ? '🤖' : (chatState.currentMode === 'dr' ? '👨‍⚕️' : '🏥')) : '👤';
        const time = m.time || '';

        body.insertAdjacentHTML('beforeend', `
            <div class="medinear-msg-wrapper ${m.role}" style="direction:${isRtl ? 'rtl':'ltr'};">
                <div class="msg-avatar">${avatar}</div>
                <div class="medinear-msg-content">
                    <div class="medinear-bubble">${formatMessageText(m.text)}</div>
                    <div class="msg-time">${time}</div>
                </div>
            </div>
        `);
    });
    body.scrollTop = body.scrollHeight;
}

function pushChatMsg(role, text) {
    const target = chatState.storage[chatState.currentMode];
    target.push({ role, text, time: getCurrentTime() });
    localStorage.setItem(STORAGE_KEY, JSON.stringify(chatState.storage));
    renderChatMessages();
}

async function processChatMessage() {
    const input = document.getElementById('chatInput');
    const text = input.value.trim();
    if(!text) return;

    const cannedRes = getCannedChatResponses(activeChatLang);

    pushChatMsg('user', text);
    input.value = '';

    if(chatState.currentMode === 'ai') {
        if (cannedRes[text]) {
            showTyping(true);
            setTimeout(() => {
                showTyping(false);
                pushChatMsg('bot', cannedRes[text]);
            }, 800);
        } else {
            await callChatAI(text);
        }
    } else {
        showTyping(true);
        setTimeout(() => {
            showTyping(false);
            pushChatMsg('bot', chatI18n[activeChatLang].aiOffline);
        }, 1500);
    }
}

function showTyping(show) {
    document.getElementById('chatTypingArea').style.display = show ? 'flex' : 'none';
    if(show) {
        const body = document.getElementById('chatMsgBody');
        body.scrollTop = body.scrollHeight;
    }
}

async function callChatAI(prompt) {
    showTyping(true);
    try {
        let recentHistory = chatState.storage.ai.slice(-4).map(m => ({
            role: m.role === 'bot' ? 'assistant' : 'user',
            content: m.text
        }));

        const arabicSystemPrompt = `أنت 'برشامة'، المساعد الذكي الرسمي لمنصة وتطبيق MediNear في مصر.
        دورك: مساعدة المرضى والصيادلة.
        القواعد:
        1. تحدث بلهجة مصرية ودودة واحترافية جداً.
        2. استخدم التنسيق (Markdown) مثل الخط العريض (**) للكلمات المهمة.
        3. للمرضى: ساعدهم في إيجاد الأدوية واستخدام التطبيق.
        4. للصيادلة: ساعدهم في فهم لوحة التحكم وكيفية الانضمام وتعديل المخزون.
        5. تنويه: إذا سأل عن جرعات، أضف تنويه "يرجى استشارة الطبيب أو الصيدلي".`;

        const englishSystemPrompt = `You are 'Barshama', the official smart AI assistant for the MediNear platform in Egypt.
        Your role: Help patients and pharmacists.
        Rules:
        1. Speak in a friendly, professional, and clear tone. Communicate in English.
        2. Use Markdown formatting like bold (**) for important words.
        3. For Patients: Help them find medicines and use the app.
        4. For Pharmacists: Help them understand the dashboard, how to join, and inventory management.
        5. Disclaimer: If asked about dosages, always append "Please consult a doctor or pharmacist."`;

        const systemPrompt = {
            role: "system",
            content: activeChatLang === 'en' ? englishSystemPrompt : arabicSystemPrompt
        };

        const messages = [systemPrompt, ...recentHistory, {role: "user", content: prompt}];

        const res = await fetch("https://api.groq.com/openai/v1/chat/completions", {
            method: "POST",
            headers: { "Content-Type": "application/json", "Authorization": `Bearer ${GROQ_API_KEY}` },
            body: JSON.stringify({
                model: "llama-3.3-70b-versatile",
                messages: messages,
                temperature: 0.7,
                max_tokens: 300
            })
        });

        const data = await res.json();
        pushChatMsg('bot', data.choices[0].message.content);

    } catch (e) {
        pushChatMsg('bot', chatI18n[activeChatLang].aiError);
    } finally {
        showTyping(false);
    }
}

function renderChatTabs() {
    const tabs = document.getElementById('categoryTabs');
    const keys = {
        meds: chatI18n[activeChatLang].tabMeds,
        patient: chatI18n[activeChatLang].tabPatient,
        pharmacy: chatI18n[activeChatLang].tabPharmacy,
        health: chatI18n[activeChatLang].tabHealth,
        about: chatI18n[activeChatLang].tabAbout
    };
    tabs.innerHTML = Object.keys(keys).map((k, i) => `<button class="medinear-tab-btn ${i===0?'active':''}" onclick="filterChatChips('${k}', this)">${keys[k]}</button>`).join('');
    filterChatChips('meds', tabs.children[0]);
}

function filterChatChips(cat, btn) {
    document.querySelectorAll('.medinear-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const grid = document.getElementById('chipsGrid');
    const aiData = getAiAssistantData(activeChatLang);
    grid.innerHTML = aiData[cat].map(text => `<button class="medinear-chip" onclick="quickChatSend('${text}')">${text}</button>`).join('');
}

function quickChatSend(text) {
    document.getElementById('chatInput').value = text;
    processChatMessage();
}

function handleChatEnter(e) {
    if(e.key === 'Enter') processChatMessage();
}

document.addEventListener('DOMContentLoaded', () => {
    updateChatLanguage(activeChatLang);
    renderChatContacts();
});
</script>
