@extends('pharmacy.layout.master')

@section('title', 'الملف الشخصي وإعدادات الصيدلية')

@section('content')

    <div class="p-4 md:p-6 lg:p-8 relative max-w-7xl mx-auto">
        {{--
    @if (session('success'))
        <div class="mb-6 bg-emerald-50 text-emerald-600 p-4 rounded-[1.5rem] border border-emerald-100 flex items-center gap-3 font-bold shadow-sm animate-scale-up z-50">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="fa-solid fa-check text-lg"></i>
            </div>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-rose-50 text-rose-600 p-4 rounded-[1.5rem] border border-rose-100 flex items-center gap-3 font-bold shadow-sm animate-scale-up z-50">
            <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            </div>
            <p>{{ session('error') }}</p>
        </div>
    @endif --}}

        <form action="{{ route('pharmacy.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm mb-8 relative">

                <div class="h-56 md:h-72 w-full bg-slate-100 relative rounded-t-[2rem] overflow-hidden group">
                    @if ($pharmacy->cover)
                        <img id="coverPreview" src="{{ asset('storage/' . $pharmacy->cover) }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div id="coverPlaceholder"
                            class="w-full h-full bg-gradient-to-r from-slate-800 to-slate-600 flex items-center justify-center">
                            <i class="fa-solid fa-store text-6xl text-white/10"></i>
                        </div>
                        <img id="coverPreview" src=""
                            class="w-full h-full object-cover hidden transition-transform duration-700 group-hover:scale-105">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-transparent"></div>

                    <div class="absolute top-4 left-4 z-20">
                        <input type="file" name="cover" id="coverInput" accept="image/*"
                            onchange="previewImage(this, 'coverPreview', 'coverPlaceholder')" class="hidden">
                        <button type="button" onclick="document.getElementById('coverInput').click()"
                            class="bg-white/20 hover:bg-white/40 backdrop-blur-md border border-white/30 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-lg">
                            <i class="fa-solid fa-camera"></i>
                            <span class="hidden md:inline">تغيير الغلاف</span>
                        </button>
                        @error('cover')
                            <span
                                class="block text-[10px] text-rose-300 font-bold mt-1 bg-black/50 px-2 py-1 rounded">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="px-6 md:px-10 pb-8 relative">
                    <div
                        class="flex flex-col md:flex-row items-center md:items-end gap-6 -mt-16 md:-mt-20 relative z-10 text-center md:text-right">

                        <div class="w-32 h-32 md:w-40 md:h-40 rounded-[2rem] bg-white border-4 border-white shadow-xl overflow-hidden shrink-0 relative group/avatar cursor-pointer"
                            onclick="document.getElementById('imageInput').click()">
                            <input type="file" name="image" id="imageInput" accept="image/*"
                                onchange="previewImage(this, 'avatarPreview', 'avatarOverlay')" class="hidden">

                            <img id="avatarPreview"
                                src="{{ $pharmacy->image ? asset('storage/' . $pharmacy->image) : asset('Dashboard/images/logo.png') }}"
                                class="w-full h-full object-cover bg-slate-50 p-2">

                            <div id="avatarOverlay"
                                class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/avatar:opacity-100 transition-opacity backdrop-blur-sm">
                                <div class="text-white text-center">
                                    <i class="fa-solid fa-camera text-2xl mb-1 block"></i>
                                    <span class="text-[10px] font-bold">تغيير الشعار</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 pb-2">
                            <h1 class="text-2xl md:text-3xl font-black text-slate-800">{{ $pharmacy->pharmacy_name }}</h1>
                            <p
                                class="text-sm font-bold text-gray-500 mt-1.5 flex items-center justify-center md:justify-start gap-2">
                                <i class="fa-solid fa-envelope text-primary"></i>
                                <span class="text-slate-700">{{ $pharmacy->email }}</span>
                            </p>
                        </div>

                        <div class="pb-2 flex flex-col items-center md:items-end gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl {{ $pharmacy->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }} border text-sm font-black shadow-sm">
                                <i class="fa-solid {{ $pharmacy->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                                {{ $pharmacy->is_active ? 'حساب نشط' : 'حساب موقوف' }}
                            </span>
                            @error('image')
                                <span class="block text-[10px] text-rose-500 font-bold">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm sticky top-6">
                        <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-500 shadow-inner">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            بيانات السجل الثابتة
                        </h3>

                        <div class="space-y-5">
                            <div class="group">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-wider block mb-1.5 group-hover:text-primary transition-colors">البريد
                                    الإلكتروني للوصول</label>
                                <div
                                    class="font-bold text-sm text-slate-700 bg-slate-50/50 px-4 py-3.5 rounded-xl border border-gray-100 flex items-center justify-between">
                                    <span class="truncate">{{ $pharmacy->email }}</span>
                                    <i class="fa-solid fa-lock text-gray-300 text-xs"></i>
                                </div>
                            </div>

                            <div class="group">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-wider block mb-1.5 group-hover:text-primary transition-colors">رقم
                                    الترخيص</label>
                                <div
                                    class="font-mono font-black text-sm text-slate-700 bg-slate-50/50 px-4 py-3.5 rounded-xl border border-gray-100 flex items-center justify-between">
                                    <span>{{ $pharmacy->license_number ?? 'غير مسجل' }}</span>
                                    <i class="fa-solid fa-lock text-gray-300 text-xs"></i>
                                </div>
                            </div>

                            <div class="group">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-wider block mb-1.5 group-hover:text-primary transition-colors">تصنيف
                                    الصيدلية</label>
                                <div
                                    class="font-bold text-sm text-slate-700 bg-slate-50/50 px-4 py-3.5 rounded-xl border border-gray-100 flex items-center justify-between">
                                    <span>{{ $pharmacy->is_big_pharmacy ? 'صيدلية كبرى (سلاسل)' : 'صيدلية فردية' }}</span>
                                    <i class="fa-solid fa-building-user text-gray-300"></i>
                                </div>
                            </div>

                            <div class="group">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-wider block mb-1.5 group-hover:text-primary transition-colors">تاريخ
                                    الانضمام</label>
                                <div
                                    class="font-bold text-sm text-slate-700 bg-slate-50/50 px-4 py-3.5 rounded-xl border border-gray-100 flex items-center justify-between">
                                    <span>{{ $pharmacy->created_at->translatedFormat('d F Y') }}</span>
                                    <i class="fa-regular fa-calendar-days text-gray-300"></i>
                                </div>
                            </div>

                            <div class="group">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-wider block mb-1.5 group-hover:text-primary transition-colors">الموقع
                                    الجغرافي (GPS)</label>
                                @if ($pharmacy->lat && $pharmacy->lng)
                                    <div
                                        class="font-mono text-sm text-slate-700 bg-slate-50/50 p-3 rounded-xl border border-gray-100 flex flex-col gap-3">
                                        <div class="flex items-center justify-between px-1">
                                            <span dir="ltr"
                                                class="font-bold text-xs text-slate-600">{{ number_format($pharmacy->lat, 5) }},
                                                {{ number_format($pharmacy->lng, 5) }}</span>
                                            <i class="fa-solid fa-location-crosshairs text-gray-300"></i>
                                        </div>
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $pharmacy->lat }},{{ $pharmacy->lng }}"
                                            target="_blank"
                                            class="w-full bg-white hover:bg-blue-50 border border-gray-200 hover:border-blue-200 text-blue-600 rounded-lg py-2 text-center text-xs font-black transition-all flex items-center justify-center gap-2 shadow-sm">
                                            <i class="fa-solid fa-map-location-dot"></i>
                                            <span>عرض على الخريطة</span>
                                        </a>
                                    </div>
                                @else
                                    <div
                                        class="font-bold text-sm text-slate-500 bg-slate-50/50 px-4 py-3.5 rounded-xl border border-gray-100 flex items-center justify-between opacity-70">
                                        <span>لم يتم تحديد الموقع</span>
                                        <i class="fa-solid fa-location-crosshairs text-gray-300"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-8 p-4 rounded-xl bg-blue-50 border border-blue-100 flex items-start gap-3">
                            <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 shrink-0"></i>
                            <p class="text-blue-700 text-xs font-bold leading-relaxed">
                                لتعديل البيانات الأساسية المرتبطة بالترخيص أو الإحداثيات الجغرافية، يرجى تقديم طلب رسمي
                                للإدارة.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div
                        class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden">

                        <h3 class="text-xl font-black text-slate-800 mb-8 flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-[1rem] bg-primary/10 flex items-center justify-center text-primary shadow-inner">
                                <i class="fa-solid fa-pen-nib text-xl"></i>
                            </div>
                            المعلومات الأساسية والتواصل
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

                            <div>
                                <label class="block text-xs font-black text-slate-700 mb-2">اسم الحساب (User Name) <span
                                        class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="name" placeholder="اسمك الشخصي"
                                        value="{{ old('name', $user->name) }}"
                                        class="w-full bg-slate-50 border {{ $errors->has('name') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500/10' : 'border-gray-200 focus:border-primary focus:ring-primary/10' }} rounded-xl py-3.5 pr-11 pl-4 text-sm font-bold text-slate-700 focus:ring-4 transition-all outline-none">
                                    <i
                                        class="fa-regular fa-circle-user absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                                @error('name')
                                    <span class="block text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-700 mb-2">اسم الصيدلي المسؤول (Owner
                                    Name)</label>
                                <div class="relative">
                                    <input type="text" name="owner_name" placeholder="د. محمد، د. أحمد..."
                                        value="{{ old('owner_name', $pharmacy->owner_name) }}"
                                        class="w-full bg-slate-50 border {{ $errors->has('owner_name') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500/10' : 'border-gray-200 focus:border-primary focus:ring-primary/10' }} rounded-xl py-3.5 pr-11 pl-4 text-sm font-bold text-slate-700 focus:ring-4 transition-all outline-none">
                                    <i
                                        class="fa-solid fa-user-doctor absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                                @error('owner_name')
                                    <span class="block text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-700 mb-2">رقم هاتف التواصل <span
                                        class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="phone" dir="ltr" placeholder="+201234567890"
                                        value="{{ old('phone', $pharmacy->phone) }}"
                                        class="w-full bg-slate-50 border {{ $errors->has('phone') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500/10' : 'border-gray-200 focus:border-primary focus:ring-primary/10' }} rounded-xl py-3.5 px-4 text-sm font-bold text-slate-700 focus:ring-4 transition-all outline-none text-left font-mono">
                                    <i
                                        class="fa-solid fa-phone absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                                <span class="block text-[10px] text-gray-400 font-bold mt-1.5">يجب أن يبدأ بكود الدولة
                                    (مثال: +20)</span>
                                @error('phone')
                                    <span class="block text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-700 mb-2">المدينة / المحافظة</label>
                                <div class="relative">
                                    <input type="text" name="city" placeholder="مثال: القاهرة، الإسكندرية..."
                                        value="{{ old('city', $pharmacy->city) }}"
                                        class="w-full bg-slate-50 border {{ $errors->has('city') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500/10' : 'border-gray-200 focus:border-primary focus:ring-primary/10' }} rounded-xl py-3.5 pr-11 pl-4 text-sm font-bold text-slate-700 focus:ring-4 transition-all outline-none">
                                    <i
                                        class="fa-solid fa-location-dot absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                                @error('city')
                                    <span class="block text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-slate-700 mb-2">العنوان التفصيلي</label>
                                <div class="relative">
                                    <input type="text" name="address"
                                        placeholder="اسم الشارع، رقم العمارة، علامة مميزة..."
                                        value="{{ old('address', $pharmacy->address) }}"
                                        class="w-full bg-slate-50 border {{ $errors->has('address') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500/10' : 'border-gray-200 focus:border-primary focus:ring-primary/10' }} rounded-xl py-3.5 pr-11 pl-4 text-sm font-bold text-slate-700 focus:ring-4 transition-all outline-none">
                                    <i
                                        class="fa-solid fa-map-pin absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                                @error('address')
                                    <span class="block text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-slate-700 mb-2">ساعات العمل</label>
                                <div class="relative">
                                    <input type="text" name="working_hours"
                                        placeholder="مثال: 24 ساعة، أو من 9 صباحاً إلى 12 منتصف الليل"
                                        value="{{ old('working_hours', $pharmacy->working_hours) }}"
                                        class="w-full bg-slate-50 border {{ $errors->has('working_hours') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500/10' : 'border-gray-200 focus:border-primary focus:ring-primary/10' }} rounded-xl py-3.5 pr-11 pl-4 text-sm font-bold text-slate-700 focus:ring-4 transition-all outline-none">
                                    <i
                                        class="fa-regular fa-clock absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                                @error('working_hours')
                                    <span class="block text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2 pt-6 border-t border-gray-100">
                                <label class="block text-xs font-black text-slate-700 mb-4">الخدمات المتوفرة في
                                    الصيدلية</label>
                                @php
                                    $services = old(
                                        'services',
                                        is_array($pharmacy->services)
                                            ? $pharmacy->services
                                            : json_decode($pharmacy->services, true) ?? [],
                                    );
                                @endphp
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <label
                                        class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-gray-100 bg-slate-50 hover:bg-slate-100 hover:border-primary/30 transition-all">
                                        <input type="checkbox" name="services[]" value="24_hours"
                                            class="w-5 h-5 text-primary bg-white border-gray-300 rounded focus:ring-primary"
                                            {{ in_array('24_hours', $services) ? 'checked' : '' }}>
                                        <span class="text-xs font-bold text-slate-700 flex items-center gap-2"><i
                                                class="fa-solid fa-clock-rotate-left text-primary/70"></i> تعمل 24
                                            ساعة</span>
                                    </label>

                                    <label
                                        class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-gray-100 bg-slate-50 hover:bg-slate-100 hover:border-primary/30 transition-all">
                                        <input type="checkbox" name="services[]" value="delivery"
                                            class="w-5 h-5 text-primary bg-white border-gray-300 rounded focus:ring-primary"
                                            {{ in_array('delivery', $services) ? 'checked' : '' }}>
                                        <span class="text-xs font-bold text-slate-700 flex items-center gap-2"><i
                                                class="fa-solid fa-motorcycle text-primary/70"></i> خدمة توصيل</span>
                                    </label>

                                    <label
                                        class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-gray-100 bg-slate-50 hover:bg-slate-100 hover:border-primary/30 transition-all">
                                        <input type="checkbox" name="services[]" value="measurements"
                                            class="w-5 h-5 text-primary bg-white border-gray-300 rounded focus:ring-primary"
                                            {{ in_array('measurements', $services) ? 'checked' : '' }}>
                                        <span class="text-xs font-bold text-slate-700 flex items-center gap-2"><i
                                                class="fa-solid fa-stethoscope text-primary/70"></i> قياس ضغط/سكر</span>
                                    </label>
                                </div>
                                @error('services.*')
                                    <span class="block text-[10px] text-rose-500 font-bold mt-2">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2 pt-6 border-t border-gray-100">
                                <label
                                    class="flex items-start md:items-center gap-4 cursor-pointer p-5 rounded-[1.5rem] border {{ old('has_collaboration', $pharmacy->has_collaboration) ? 'bg-primary/5 border-primary/30 shadow-sm shadow-primary/5' : 'bg-slate-50 border-gray-200' }} transition-all group">
                                    <div class="relative flex items-center justify-center shrink-0 mt-1 md:mt-0">
                                        <input type="checkbox" name="has_collaboration" value="1"
                                            class="peer sr-only"
                                            {{ old('has_collaboration', $pharmacy->has_collaboration) ? 'checked' : '' }}>
                                        <div
                                            class="w-10 h-6 bg-gray-200 rounded-full peer peer-checked:bg-primary transition-colors">
                                        </div>
                                        <div
                                            class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-4">
                                        </div>
                                    </div>
                                    <div>
                                        <span
                                            class="text-sm font-black text-slate-800 block group-hover:text-primary transition-colors">مستعد
                                            للتعاون وتبادل النواقص</span>
                                        <span class="text-xs font-bold text-gray-500 block mt-1 leading-relaxed">السماح
                                            للصيدليات المجاورة برؤية بيانات تواصلك وتبادل الأدوية الناقصة لتلبية احتياجات
                                            المرضى.</span>
                                    </div>
                                </label>
                            </div>

                        </div>

                        <div
                            class="mt-10 flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100">
                            <p class="text-xs font-bold text-gray-400">آخر تحديث للبيانات:
                                {{ $pharmacy->updated_at->diffForHumans() }}</p>

                            <button type="submit" id="saveProfileBtn" onclick="handleSave(this)"
                                class="w-full sm:w-auto bg-primary hover:bg-primaryDark text-white rounded-xl px-10 py-3.5 text-sm font-black transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-2 group">
                                <span class="btn-text">حفظ التعديلات</span>
                                <i class="fa-solid fa-floppy-disk btn-icon group-hover:scale-110 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input, previewId, placeholderId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    let previewImage = document.getElementById(previewId);
                    let placeholder = document.getElementById(placeholderId);

                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');

                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function handleSave(btn) {
            const form = document.getElementById('profileForm');
            if (form.checkValidity()) {
                setTimeout(() => {
                    btn.disabled = true;
                    btn.classList.add('opacity-80', 'cursor-not-allowed');
                    btn.querySelector('.btn-text').innerText = 'جاري الحفظ...';

                    let icon = btn.querySelector('.btn-icon');
                    icon.classList.remove('fa-floppy-disk');
                    icon.classList.add('fa-spinner', 'fa-spin');
                }, 50);
            }
        }
    </script>

    <style>
        .animate-scale-up {
            animation: scaleUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes scaleUp {
            0% {
                opacity: 0;
                transform: scale(0.95) translateY(10px);
            }

            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
    </style>
@endsection
