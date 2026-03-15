@extends('dashboard.layout.master')
@section('content')
    <div class="p-4 md:p-6 lg:p-8 relative">

        <div id="ajax-toast-container" class="fixed top-24 right-4 md:right-8 z-[100000] flex flex-col gap-3 pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">إدارة الكوبونات</h2>
                <p class="text-sm text-gray-500 font-medium mt-1">إنشاء ومتابعة قسائم الخصم النشطة بسهولة</p>
            </div>
            <button onclick="openCouponModal('add')" class="bg-gradient-custom text-white px-6 py-3 rounded-2xl font-bold shadow-[0_10px_20px_rgba(13,148,136,0.3)] hover:shadow-[0_15px_25px_rgba(13,148,136,0.4)] hover:-translate-y-1 transition-all flex items-center gap-2 group">
                <i class="fa-solid fa-plus transition-transform group-hover:rotate-90"></i> إضافة كوبون جديد
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-8">
            <div class="stat-card bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md group">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform"><i class="fa-solid fa-ticket"></i></div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">إجمالي الكوبونات</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="stat-card bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md group">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform"><i class="fa-solid fa-circle-check"></i></div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">نشطة وصالحة</p>
                    <h3 id="stat-active" class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['active'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="stat-card bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md group">
                <div class="w-14 h-14 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform"><i class="fa-solid fa-ban"></i></div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">موقوفة / منتهية</p>
                    <h3 id="stat-inactive" class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['inactive'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden mb-6 p-5 md:p-6">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-5">
                <div class="flex items-center gap-3 shrink-0">
                    <h3 class="font-black text-slate-800 text-lg">البحث والتصفية</h3>
                    <span id="resultCounter" class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1.5 rounded-full uppercase">{{ $coupons->total() }} كوبون</span>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                    <div class="relative w-full sm:w-64">
                        <input type="text" id="searchInput" placeholder="ابحث بكود أو عنوان الكوبون..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 pr-11 text-sm focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none">
                        <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <select id="statusFilter" class="w-full sm:w-auto bg-slate-50 border border-slate-200 rounded-2xl py-3 px-4 text-sm focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none cursor-pointer">
                        <option value="all">جميع الحالات</option>
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="couponsGrid">
            @forelse($coupons ?? [] as $coupon)
                @php
                    $isExpired = $coupon->expiry_date && \Carbon\Carbon::parse($coupon->expiry_date)->isPast();
                    $isLimitReached = $coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit;
                    $canToggle = !$isExpired && !$isLimitReached;
                    $isActive = $coupon->is_active && $canToggle;
                    $usagePercent = $coupon->usage_limit > 0 ? min(100, ($coupon->used_count / $coupon->usage_limit) * 100) : 0;
                @endphp

                <div class="coupon-card relative bg-white rounded-[2.5rem] border border-gray-200 shadow-sm transition-all duration-300 overflow-hidden flex flex-col {{ !$isActive ? 'opacity-80 grayscale-[30%]' : 'hover:-translate-y-1 hover:shadow-xl hover:border-primary/30' }}"
                     data-search="{{ mb_strtolower($coupon->code . ' ' . $coupon->title) }}" data-active="{{ $isActive ? '1' : '0' }}">

                    @if($isLimitReached)
                        <div class="absolute top-5 left-[-35px] bg-rose-600 text-white text-[10px] font-black py-1 px-10 -rotate-45 shadow-lg z-20 tracking-widest">مكتمل</div>
                    @elseif($isExpired)
                        <div class="absolute top-5 left-[-35px] bg-slate-700 text-white text-[10px] font-black py-1 px-10 -rotate-45 shadow-lg z-20 tracking-widest">منتهي</div>
                    @endif

                    <div class="card-header relative pt-6 pb-10 px-6 text-center transition-colors duration-300 {{ $isActive ? 'bg-gradient-custom text-white' : 'bg-slate-200 text-slate-500' }}">
                        <div class="absolute -bottom-4 -left-4 w-8 h-8 bg-[#f8fafc] rounded-full border-gray-200 border z-10"></div>
                        <div class="absolute -bottom-4 -right-4 w-8 h-8 bg-[#f8fafc] rounded-full border-gray-200 border z-10"></div>

                        <div class="badge-container flex justify-between items-start mb-2 relative z-20">
                            <span class="text-xs font-bold bg-white/20 px-3 py-1 rounded-lg backdrop-blur-sm border border-white/20 truncate max-w-[70%]">
                                {{ $coupon->title ?? 'بدون عنوان' }}
                            </span>
                            @if(!$isActive && $canToggle)
                                <span class="badge-status text-[10px] font-black bg-rose-500 text-white px-2 py-1 rounded-lg shadow-sm">موقوف</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-center gap-3 relative z-20 mt-2">
                            <h3 class="text-3xl font-black tracking-widest font-mono drop-shadow-sm">{{ $coupon->code }}</h3>
                            <button type="button" onclick="copyCode('{{ $coupon->code }}')" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white hover:text-primary transition-all flex items-center justify-center shadow-sm tooltip" title="نسخ الكود">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="relative h-0 border-t-2 border-dashed border-gray-200 w-full"></div>

                    <div class="p-6 flex-grow flex flex-col">
                        <div class="text-center mb-6">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">
                                {{ $coupon->discount_type === 'percentage' ? 'خصم مئوي' : 'خصم مبلغ ثابت' }}
                            </p>
                            <div class="flex items-center justify-center gap-1">
                                <span class="card-value text-4xl font-black transition-colors duration-300 {{ $isActive ? 'text-primary' : 'text-slate-500' }}">{{ (float)$coupon->discount_value }}</span>
                                <span class="card-symbol text-xl font-bold transition-colors duration-300 {{ $isActive ? 'text-teal-500' : 'text-slate-400' }}">{{ $coupon->discount_type === 'percentage' ? '%' : 'ج.م' }}</span>
                            </div>
                        </div>

                        <div class="mt-auto space-y-3">
                            <div class="flex justify-between text-[11px] font-bold">
                                <span class="text-gray-400">الاستخدام الحالي:</span>
                                <span class="{{ $isLimitReached ? 'text-rose-500' : 'text-slate-700' }} font-mono">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?: '∞' }}</span>
                            </div>
                            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                <div class="h-full {{ $isLimitReached ? 'bg-rose-500' : 'bg-gradient-custom' }} transition-all duration-1000" style="width: {{ $coupon->usage_limit ? $usagePercent : 100 }}%"></div>
                            </div>
                            <p class="text-[10px] font-bold text-gray-400 pt-2 flex items-center gap-1">
                                <i class="fa-regular fa-clock"></i> ينتهي في: <span dir="ltr" class="font-mono">{{ $coupon->expiry_date ? $coupon->expiry_date->format('Y-m-d') : 'مفتوح' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="bg-slate-50/80 p-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="tooltip" title="{{ !$canToggle ? 'مغلق تلقائياً' : 'تفعيل / إيقاف' }}">
                            <button type="button"
                                    {{ !$canToggle ? 'disabled' : '' }}
                                    onclick="inlineToggleStatus({{ $coupon->id }}, this)"
                                    class="w-10 h-10 rounded-xl border border-gray-200 transition-all flex items-center justify-center
                                           {{ $coupon->is_active && $canToggle ? 'bg-white text-emerald-500 hover:shadow-md border-emerald-200' : 'bg-white text-slate-400 hover:shadow-md' }}
                                           disabled:opacity-50 disabled:bg-gray-100 disabled:cursor-not-allowed">
                                <i class="fa-solid fa-power-off text-sm"></i>
                            </button>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" onclick='openCouponModal("edit", @json($coupon))' class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-blue-500 hover:bg-blue-50 flex items-center justify-center tooltip" title="تعديل">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </button>
                            <button type="button" onclick="openDeleteModal({{ $coupon->id }})" class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-rose-500 hover:bg-rose-50 flex items-center justify-center tooltip" title="حذف">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-[2rem] border border-dashed border-gray-200">
                    <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-5xl mx-auto mb-4">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <p class="font-bold text-slate-500 text-lg">لا توجد كوبونات مسجلة بعد</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8 custom-pagination">
            {{ method_exists($coupons, 'links') ? $coupons->links() : '' }}
        </div>
    </div>

    <div id="couponModal" class="fixed inset-0 z-[100001] hidden flex-col items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('couponModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-xl shadow-2xl overflow-hidden animate-scale-up flex flex-col max-h-[90vh]">
            <div class="p-6 md:p-8 border-b border-gray-50 flex justify-between items-center bg-slate-50/50 shrink-0">
                <h3 id="modalTitle" class="text-xl md:text-2xl font-black text-slate-800">إضافة كوبون</h3>
                <button type="button" onclick="toggleModal('couponModal')" class="w-10 h-10 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form id="couponForm" action="{{ route('coupons.store') }}" method="POST" class="p-6 md:p-8 overflow-y-auto scrollbar-thin">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="space-y-6 text-right">

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">عنوان الكوبون <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" id="c_title"
                               class="w-full bg-slate-50 border {{ $errors->has('title') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-primary focus:ring-primary/20' }} rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-4 transition-all outline-none text-slate-700"
                               placeholder="مثال: خصومات الجمعة البيضاء" value="{{ old('title') }}">
                        @error('title') <p class="text-rose-500 text-xs font-bold mt-1.5"><i class="fa-solid fa-circle-exclamation ml-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">كود الكوبون <span class="text-rose-500">*</span></label>
                        <div class="flex gap-2">
                            <div class="flex-grow relative">
                                <input type="text" name="code" id="c_code"
                                       class="w-full bg-slate-50 border {{ $errors->has('code') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-primary focus:ring-primary/20' }} rounded-xl px-4 py-3 text-sm font-mono font-black uppercase tracking-widest text-primary focus:bg-white focus:ring-4 transition-all outline-none"
                                       placeholder="مثال: SAVE20" value="{{ old('code') }}">
                                @error('code') <p class="text-rose-500 text-xs font-bold mt-1.5"><i class="fa-solid fa-circle-exclamation ml-1"></i>{{ $message }}</p> @enderror
                            </div>
                            <button type="button" onclick="generateRandomCode()" class="bg-secondary text-primary w-12 h-[46px] rounded-xl border border-teal-100 hover:bg-primary hover:text-white transition-all flex items-center justify-center shrink-0 tooltip" title="توليد عشوائي">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">النوع <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="discount_type" id="c_type" onchange="updateLabel()"
                                        class="w-full bg-slate-50 border {{ $errors->has('discount_type') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-primary focus:ring-primary/20' }} rounded-xl px-4 py-3 pr-10 text-sm focus:bg-white focus:ring-4 transition-all outline-none appearance-none cursor-pointer text-slate-700">
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                            @error('discount_type') <p class="text-rose-500 text-xs font-bold mt-1.5"><i class="fa-solid fa-circle-exclamation ml-1"></i>{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">القيمة <span id="labelSymbol" class="text-primary">(%)</span> <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" min="0" name="discount_value" id="c_value"
                                   class="w-full bg-slate-50 border {{ $errors->has('discount_value') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-primary focus:ring-primary/20' }} rounded-xl px-4 py-3 text-sm font-mono focus:bg-white focus:ring-4 transition-all outline-none text-slate-700"
                                   placeholder="0" value="{{ old('discount_value') }}">
                            @error('discount_value') <p class="text-rose-500 text-xs font-bold mt-1.5"><i class="fa-solid fa-circle-exclamation ml-1"></i>{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">الحد الأقصى (مرات)</label>
                            <input type="number" name="usage_limit" id="c_limit" min="1"
                                   class="w-full bg-slate-50 border {{ $errors->has('usage_limit') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-primary focus:ring-primary/20' }} rounded-xl px-4 py-3 text-sm font-mono focus:bg-white focus:ring-4 transition-all outline-none text-slate-700"
                                   placeholder="فارغ = مفتوح" value="{{ old('usage_limit') }}">
                            @error('usage_limit') <p class="text-rose-500 text-xs font-bold mt-1.5"><i class="fa-solid fa-circle-exclamation ml-1"></i>{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">تاريخ الانتهاء</label>
                            <input type="date" name="expiry_date" id="c_expiry"
                                   class="w-full bg-slate-50 border {{ $errors->has('expiry_date') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-primary focus:ring-primary/20' }} rounded-xl px-4 py-3 text-sm font-mono focus:bg-white focus:ring-4 transition-all outline-none text-slate-600"
                                   value="{{ old('expiry_date') }}">
                            @error('expiry_date') <p class="text-rose-500 text-xs font-bold mt-1.5"><i class="fa-solid fa-circle-exclamation ml-1"></i>{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 mt-10 pt-6 border-t border-gray-100">
                    <button type="button" onclick="toggleModal('couponModal')" class="flex-1 py-4 rounded-2xl font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-all text-sm md:text-base">إلغاء</button>
                    <button type="submit" id="submit-btn" class="flex-[2] py-4 rounded-2xl font-bold text-white bg-gradient-custom shadow-lg hover:shadow-primary/30 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                        <i class="fa-solid fa-save"></i> حفظ البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[100002] hidden flex-col items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('deleteModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl p-8 text-center animate-scale-up">
            <div class="w-24 h-24 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 border-4 shadow-inner bg-rose-50 text-rose-500 border-rose-100">
                <i class="fa-solid fa-trash-can animate-bounce-short"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2">تأكيد الحذف</h3>
            <p class="text-sm text-gray-500 font-medium mb-8">هل أنت متأكد أنك تريد حذف هذا الكوبون نهائياً؟ إجراء لا يمكن التراجع عنه.</p>
            <form id="deleteForm" method="POST" class="flex gap-3">
                @csrf @method('DELETE')
                <button type="button" onclick="toggleModal('deleteModal')" class="flex-1 py-4 rounded-2xl font-bold bg-slate-100 text-slate-500 hover:bg-slate-200 transition-all">تراجع</button>
                <button type="submit" class="flex-1 py-4 rounded-2xl font-bold text-white bg-rose-500 hover:bg-rose-600 shadow-lg shadow-rose-500/30 transition-all">نعم، احذف</button>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = '{{ csrf_token() }}';

        // Auto-open modal if there are validation errors
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                toggleModal('couponModal');
            });
        @endif

        function toggleModal(id) {
            const el = document.getElementById(id);
            if (el.classList.contains('hidden')) {
                el.classList.replace('hidden', 'flex');
            } else {
                el.classList.replace('flex', 'hidden');
                // Clean form visually when closing
                if (id === 'couponModal') {
                    // Remove error borders classes and messages if closed
                    setTimeout(() => {
                        document.getElementById('couponForm').reset();
                        document.getElementById('formMethod').value = 'POST';
                        document.getElementById('couponForm').action = "{{ route('coupons.store') }}";
                        document.getElementById('modalTitle').innerText = 'إضافة كوبون جديد';
                        const errs = document.querySelectorAll('.text-rose-500.text-xs');
                        errs.forEach(e => e.remove());
                        const inputs = document.querySelectorAll('.border-rose-300');
                        inputs.forEach(i => {
                            i.classList.remove('border-rose-300', 'focus:border-rose-500', 'focus:ring-rose-500/20');
                            i.classList.add('border-slate-200', 'focus:border-primary', 'focus:ring-primary/20');
                        });
                    }, 300);
                }
            }
        }

        // Professional JS Toasts (Matched Master Layout Style)
        function showToast(msg, success = true) {
            const container = document.getElementById('ajax-toast-container');
            const toast = document.createElement('div');
            const colorClass = success ? 'green' : 'red';
            const icon = success ? 'fa-check' : 'fa-triangle-exclamation';
            const title = success ? 'نجاح العملية' : 'تنبيه';

            toast.className = `pointer-events-auto bg-white border-r-4 border-${colorClass}-500 shadow-2xl rounded-2xl p-4 flex items-center gap-4 toast-slide-in min-w-[300px] max-w-sm mb-3`;
            toast.innerHTML = `
                <div class="w-10 h-10 bg-${colorClass}-50 text-${colorClass}-500 rounded-full flex items-center justify-center text-xl shrink-0 shadow-inner">
                    <i class="fa-solid ${icon}"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="font-bold text-gray-800 text-sm mb-0.5">${title}</h4>
                    <p class="text-xs text-gray-500 font-medium">${msg}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-${colorClass}-500 transition-colors p-2 shrink-0"><i class="fa-solid fa-xmark"></i></button>
            `;
            container.appendChild(toast);
            setTimeout(() => { if(toast.parentElement) toast.remove(); }, 4000);
        }

        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                showToast('تم نسخ الكود بنجاح: ' + code, true);
            });
        }

        function updateLabel() {
            const type = document.getElementById('c_type').value;
            document.getElementById('labelSymbol').innerText = type === 'percentage' ? '(%)' : '(ج.م)';
        }

        function generateRandomCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let res = '';
            for (let i = 0; i < 8; i++) res += chars.charAt(Math.floor(Math.random() * chars.length));
            document.getElementById('c_code').value = res;

            const icon = event.currentTarget.querySelector('i');
            icon.classList.add('fa-spin');
            setTimeout(() => icon.classList.remove('fa-spin'), 500);
        }

        function openCouponModal(action, data = null) {
            const form = document.getElementById('couponForm');
            const method = document.getElementById('formMethod');

            // Cleanup existing errors from previous validation visually
            const errs = document.querySelectorAll('.text-rose-500.text-xs');
            errs.forEach(e => e.remove());
            const inputs = document.querySelectorAll('.border-rose-300');
            inputs.forEach(i => {
                i.classList.remove('border-rose-300', 'focus:border-rose-500', 'focus:ring-rose-500/20');
                i.classList.add('border-slate-200', 'focus:border-primary', 'focus:ring-primary/20');
            });

            form.reset();

            if (action === 'add') {
                document.getElementById('modalTitle').innerText = 'إضافة كوبون جديد';
                form.action = "{{ route('coupons.store') }}";
                method.value = 'POST';
            } else {
                document.getElementById('modalTitle').innerText = 'تعديل الكوبون';
                form.action = `{{ url('admin/coupons') }}/${data.id}`;
                method.value = 'PUT';

                document.getElementById('c_title').value = data.title;
                document.getElementById('c_code').value = data.code;
                document.getElementById('c_type').value = data.discount_type;
                document.getElementById('c_value').value = data.discount_value;
                document.getElementById('c_limit').value = data.usage_limit || '';
                if(data.expiry_date) {
                    document.getElementById('c_expiry').value = data.expiry_date.split('T')[0];
                }
                updateLabel();
            }
            toggleModal('couponModal');
        }

        function openDeleteModal(id) {
            document.getElementById('confirmForm').action = `{{ url('admin/coupons') }}/${id}`;
            toggleModal('deleteModal');
        }

        // Extremely Safe & Crash-Proof AJAX Toggle
        async function inlineToggleStatus(id, btnEl) {
            const originalHTML = btnEl.innerHTML;
            btnEl.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-sm"></i>';
            btnEl.disabled = true;

            try {
                const response = await fetch(`{{ url('admin/coupons') }}/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    throw new Error("تلقى المتصفح رداً غير مفهوم من الخادم.");
                }

                btnEl.innerHTML = originalHTML;
                btnEl.disabled = false;

                if (data.success) {
                    showToast(data.message, true);
                    updateCardDOM(btnEl, data.is_active);

                    // Update Stats Counters safely
                    const aEl = document.getElementById('stat-active');
                    const iEl = document.getElementById('stat-inactive');
                    if(aEl && iEl) {
                        aEl.innerText = parseInt(aEl.innerText) + (data.is_active ? 1 : -1);
                        iEl.innerText = parseInt(iEl.innerText) + (data.is_active ? -1 : 1);
                    }
                } else {
                    showToast(data.message, false);
                }
            } catch (err) {
                btnEl.innerHTML = originalHTML;
                btnEl.disabled = false;
                showToast(err.message || 'خطأ في الاتصال بالخادم.', false);
            }
        }

        function updateCardDOM(btn, isActive) {
            const card = btn.closest('.coupon-card');
            const header = card.querySelector('.card-header');
            const valSpan = card.querySelector('.card-value');
            const symSpan = card.querySelector('.card-symbol');

            card.setAttribute('data-active', isActive ? '1' : '0');

            if (isActive) {
                card.classList.remove('opacity-80', 'grayscale-[30%]');
                card.classList.add('hover:-translate-y-1', 'hover:shadow-xl', 'hover:border-primary/30');

                header.classList.remove('bg-slate-200', 'text-slate-600');
                header.classList.add('bg-gradient-custom', 'text-white');

                valSpan.classList.remove('text-slate-500');
                valSpan.classList.add('text-primary');

                symSpan.classList.remove('text-slate-400');
                symSpan.classList.add('text-emerald-500');

                btn.className = "w-10 h-10 rounded-xl border border-emerald-200 transition-all flex items-center justify-center bg-white text-emerald-500 hover:shadow-md";

                const badge = header.querySelector('.badge-status');
                if(badge) badge.remove();
            } else {
                card.classList.add('opacity-80', 'grayscale-[30%]');
                card.classList.remove('hover:-translate-y-1', 'hover:shadow-xl', 'hover:border-primary/30');

                header.classList.remove('bg-gradient-custom', 'text-white');
                header.classList.add('bg-slate-200', 'text-slate-600');

                valSpan.classList.remove('text-primary');
                valSpan.classList.add('text-slate-500');

                symSpan.classList.remove('text-emerald-500');
                symSpan.classList.add('text-slate-400');

                btn.className = "w-10 h-10 rounded-xl border border-gray-200 transition-all flex items-center justify-center bg-white text-slate-400 hover:shadow-md";

                const badgeContainer = header.querySelector('.badge-container');
                if (badgeContainer && !badgeContainer.querySelector('.badge-status')) {
                    badgeContainer.insertAdjacentHTML('beforeend', '<span class="badge-status text-[10px] font-black bg-rose-500 text-white px-2 py-1 rounded-lg shadow-sm">موقوف يدوياً</span>');
                }
            }
        }

        // Live Search Filter
        function filterCoupons() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const status = document.getElementById('statusFilter').value;
            const cards = document.querySelectorAll('.coupon-card');
            let count = 0;

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                const active = card.getAttribute('data-active');
                const match = searchData.includes(search) && (status === 'all' || active === status);
                card.style.display = match ? 'flex' : 'none';
                if(match) count++;
            });
            document.getElementById('resultCounter').innerText = count + ' كوبون';
        }

        document.getElementById('searchInput').addEventListener('input', filterCoupons);
        document.getElementById('statusFilter').addEventListener('change', filterCoupons);

        // Form Submit Prevent Double Click
        document.getElementById('couponForm').addEventListener('submit', function() {
            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري الحفظ...';
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        });
    </script>

    <style>
        .animate-scale-up { animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes scaleUp { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .toast-slide-in { animation: slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes slideInRight { 0% { opacity: 0; transform: translateX(100%); } 100% { opacity: 1; transform: translateX(0); } }
        .animate-bounce-short { animation: bounceShort 1.5s infinite; }
        @keyframes bounceShort { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10%); } }
        .bg-gradient-custom { background: linear-gradient(135deg, #0d9488 0%, #84cc16 100%); }
    </style>
@endsection
