@extends('dashboard.layout.master')
@section('content')
    <div class="p-4 md:p-6 lg:p-8 relative">

        <div id="toast-container" class="fixed top-4 left-4 right-4 md:left-auto md:right-6 md:top-6 z-[9999] flex flex-col gap-3 pointer-events-none">
            @if(session('success'))
                <div class="animate-toast pointer-events-auto bg-white border-r-4 border-emerald-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto text-right mb-3">
                    <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600 shrink-0">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">تمت العملية</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="animate-toast pointer-events-auto bg-white border-r-4 border-rose-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto text-right mb-3">
                    <div class="bg-rose-100 p-2 rounded-xl text-rose-600 shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">خطأ</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">{{ $errors->first() ?? 'يرجى مراجعة البيانات المدخلة.' }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
        </div>

        <div id="ajax-toast-container" class="fixed top-4 left-4 right-4 md:left-auto md:right-6 md:top-6 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 md:mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">إدارة شركات التوصيل</h2>
                <p class="text-xs md:text-sm text-gray-500 font-medium mt-1">عرض وإدارة بيانات مناديب وشركات الشحن المعتمدة</p>
            </div>
            <button onclick="toggleModal('createCompanyModal')" class="bg-primary hover:bg-primaryDark text-white px-5 md:px-6 py-3 md:py-3.5 rounded-xl md:rounded-2xl font-bold transition-all shadow-sm shadow-primary/20 flex items-center gap-2 shrink-0 w-full sm:w-auto justify-center text-sm md:text-base">
                <i class="fa-solid fa-plus"></i>
                إضافة شركة جديدة
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-8">
            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-primary/20 group">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-50 text-blue-600 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-sm text-gray-500 font-bold truncate mb-1">إجمالي الشركات</p>
                    <h3 class="text-xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['total'] }}</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-emerald-200 group">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-emerald-50 text-emerald-500 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-sm text-gray-500 font-bold truncate mb-1">شركات نشطة</p>
                    <h3 id="stat-active" class="text-xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['active'] }}</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-rose-200 group">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-rose-50 text-rose-500 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-sm text-gray-500 font-bold truncate mb-1">شركات موقوفة</p>
                    <h3 id="stat-inactive" class="text-xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['inactive'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden transition-all mb-6">
            <div class="p-5 md:p-6 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 bg-slate-50/30">
                <div class="flex items-center gap-3 shrink-0">
                    <h3 class="font-black text-slate-800 text-base md:text-lg tracking-tight">سجل الشركات</h3>
                    <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-3 py-1.5 rounded-full transition-all uppercase tracking-wider">
                        {{ $companies->total() }} نتيجة
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto flex-wrap">
                    <div class="relative w-full sm:w-64">
                        <input type="text" id="serverSearchInput" value="{{ request('search') }}" placeholder="ابحث بالاسم، البريد أو الهاتف..." class="w-full bg-white border border-gray-200 rounded-xl md:rounded-2xl px-4 py-2.5 md:px-5 md:py-3 pr-10 md:pr-11 text-xs md:text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none text-slate-700 shadow-sm">
                        <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        @if(request('search'))
                            <button type="button" onclick="setServerFilter('search', '')" class="absolute left-3 top-1/2 -translate-y-1/2 text-rose-400 hover:text-rose-600 text-[10px] font-bold">إلغاء</button>
                        @endif
                    </div>

                    <div class="relative w-full sm:w-auto">
                        @php $currentStatus = request('status', 'all'); @endphp
                        <select onchange="setServerFilter('status', this.value)" class="w-full sm:w-auto bg-white border border-gray-200 rounded-xl md:rounded-2xl py-2.5 md:py-3 pr-9 md:pr-10 pl-9 md:pl-10 text-xs md:text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none appearance-none text-slate-600 shadow-sm font-medium cursor-pointer">
                            <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>جميع الحالات</option>
                            <option value="1" {{ $currentStatus === '1' ? 'selected' : '' }}>الشركات النشطة فقط</option>
                            <option value="0" {{ $currentStatus === '0' ? 'selected' : '' }}>المتوقفة فقط</option>
                        </select>
                        <i class="fa-solid fa-filter absolute right-3 md:right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-sm"></i>
                        <i class="fa-solid fa-chevron-down absolute left-3 md:left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($companies as $company)
                <div class="company-card bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col relative group overflow-hidden {{ $company->status == 0 ? 'opacity-75 grayscale-[30%]' : '' }}" data-status="{{ $company->status }}">

                    <div class="flex justify-between items-start p-5">
                        <div class="flex items-center gap-1.5">
                            <button onclick="openEditModal({{ json_encode($company) }}, '{{ $company->image_url }}')" class="w-8 h-8 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 flex items-center justify-center transition-all shadow-sm tooltip" title="تعديل">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <button onclick="openDeleteModal({{ $company->id }})" class="w-8 h-8 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 flex items-center justify-center transition-all shadow-sm tooltip" title="حذف">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>

                        <span id="badge-{{ $company->id }}" class="px-3 py-1 rounded-full text-[10px] font-black border shadow-sm {{ $company->status ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-rose-50 border-rose-100 text-rose-600' }}">
                            {{ $company->status ? 'نشط' : 'موقوف' }}
                        </span>
                    </div>

                    <div class="mt-2 mb-4 flex justify-center relative">
                        <div class="w-24 h-24 rounded-full p-1 border-2 border-slate-100 shadow-md bg-white relative z-0">
                            @if ($company->image)
                                <img src="{{ $company->image_url }}" alt="{{ $company->name }}" class="w-full h-full object-cover rounded-full">
                            @else
                                <div class="w-full h-full bg-slate-50 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-truck-fast text-slate-300 text-3xl"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="px-6 pb-6 text-center flex-1 flex flex-col">
                        <h3 class="font-black text-slate-800 text-lg mb-1 company-name">{{ $company->name }}</h3>
                        <p class="text-[10px] text-gray-400 font-bold mb-5"><i class="fa-regular fa-calendar ml-1"></i> انضمت في {{ $company->created_at->format('Y/m/d') }}</p>

                        <div class="bg-slate-50 rounded-2xl p-4 space-y-3 mt-auto border border-gray-100 text-right w-full">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-white shadow-sm border border-gray-100 flex items-center justify-center text-primary shrink-0"><i class="fa-solid fa-phone text-[10px]"></i></div>
                                <span class="text-xs font-bold text-slate-700 font-mono" dir="ltr">{{ $company->phone }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-white shadow-sm border border-gray-100 flex items-center justify-center text-primary shrink-0"><i class="fa-solid fa-envelope text-[10px]"></i></div>
                                @if($company->email)
                                    <span class="text-[11px] font-bold text-slate-600 font-mono truncate block w-full" dir="ltr" title="{{ $company->email }}">{{ $company->email }}</span>
                                @else
                                    <span class="text-[10px] font-bold text-gray-400">غير متوفر</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-50 bg-slate-50/80 p-4 flex items-center justify-between mt-auto rounded-b-[2rem]">
                        <span class="text-xs font-black text-slate-500">حالة التفعيل بالشحن</span>
                        <button type="button"
                                id="toggle-btn-{{ $company->id }}"
                                onclick="openToggleConfirmModal({{ $company->id }}, '{{ addslashes($company->name) }}', {{ $company->status ? 'true' : 'false' }})"
                                class="relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none {{ $company->status ? 'bg-emerald-500' : 'bg-gray-300' }}">
                            <span id="toggle-knob-{{ $company->id }}"
                                  class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 ease-in-out {{ $company->status ? '-translate-x-7' : '-translate-x-1' }} shadow-sm">
                            </span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center flex flex-col items-center justify-center bg-white rounded-[2rem] border border-gray-100 shadow-sm">
                    @if(request('search') || request('status'))
                        <i class="fa-solid fa-magnifying-glass text-4xl mb-4 text-gray-200"></i>
                        <p class="font-bold text-lg text-slate-600">لا توجد نتائج مطابقة لبحثك</p>
                        <a href="{{ route('delivery-companies.index') }}" class="mt-4 text-sm font-bold text-primary hover:underline">إلغاء جميع الفلاتر</a>
                    @else
                        <i class="fa-solid fa-motorcycle text-4xl mb-4 text-gray-300"></i>
                        <p class="font-bold text-lg text-slate-600">لا توجد شركات توصيل مضافة بعد</p>
                        <button onclick="toggleModal('createCompanyModal')" class="mt-4 text-sm font-bold text-primary hover:underline">إضافة شركة جديدة الآن</button>
                    @endif
                </div>
            @endforelse
        </div>

        @if($companies->hasPages())
            <div class="mt-6 px-6 py-4 bg-white rounded-[2rem] border border-gray-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs font-bold text-slate-500 text-center sm:text-right">
                    عرض <span class="text-slate-800">{{ $companies->firstItem() ?? 0 }}</span> إلى <span class="text-slate-800">{{ $companies->lastItem() ?? 0 }}</span> من إجمالي <span class="text-slate-800">{{ $companies->total() }}</span> شركة
                </p>
                <div class="custom-pagination">
                    {{ $companies->links() }}
                </div>
            </div>
        @endif

    </div>

    <div id="toggleConfirmModal" class="fixed inset-0 z-[110] hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('toggleConfirmModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-scale-up text-center p-6 md:p-10">
            <div id="tc-icon-bg" class="w-20 h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center mx-auto mb-5 md:mb-6 text-3xl md:text-4xl shadow-inner border-[6px]">
                <i id="tc-icon" class="fa-solid animate-bounce-short"></i>
            </div>
            <h3 class="text-xl md:text-2xl font-black text-slate-800 mb-2">تأكيد تغيير الحالة</h3>
            <p id="tc-message" class="text-xs md:text-sm text-gray-500 font-medium mb-6 md:mb-8 leading-relaxed"></p>
            <div class="flex items-center justify-center gap-3">
                <button type="button" onclick="toggleModal('toggleConfirmModal')" class="flex-1 px-4 md:px-6 py-3.5 md:py-4 rounded-xl md:rounded-2xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-xs md:text-sm">إلغاء الأمر</button>
                <button type="button" id="tc-confirm-btn" onclick="executeToggle()" class="flex-1 px-4 md:px-6 py-3.5 md:py-4 rounded-xl md:rounded-2xl font-bold text-white shadow-lg transition-all text-xs md:text-sm">تأكيد</button>
            </div>
        </div>
    </div>

    <div id="createCompanyModal" class="fixed inset-0 z-50 hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('createCompanyModal')"></div>
        <div class="relative bg-white rounded-[2rem] md:rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden animate-scale-up flex flex-col max-h-[90vh]">
            <div class="p-5 md:p-8 border-b border-gray-100 flex justify-between items-center bg-slate-50/50 shrink-0 text-right">
                <h3 class="text-lg md:text-xl font-black text-slate-800">إضافة شركة توصيل</h3>
                <button type="button" onclick="toggleModal('createCompanyModal')" class="w-8 h-8 md:w-10 md:h-10 rounded-xl md:rounded-2xl bg-white border border-gray-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-xmark text-base md:text-lg"></i>
                </button>
            </div>
            <form action="{{ route('delivery-companies.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden" onsubmit="disableSubmitButton(this)">
                @csrf
                <input type="hidden" name="form_type" value="create">
                <div class="p-5 md:p-8 space-y-5 md:space-y-6 overflow-y-auto scrollbar-thin text-right">

                    <div class="flex flex-col items-center justify-center">
                        <label class="text-xs md:text-sm font-bold text-slate-700 mb-3 text-center">شعار الشركة (اختياري)</label>
                        <div class="relative w-28 h-28 md:w-32 md:h-32 rounded-full border-4 border-dashed {{ $errors->has('image') && old('form_type') == 'create' ? 'border-rose-400 bg-rose-50' : 'border-gray-200 bg-slate-50' }} flex flex-col items-center justify-center text-center hover:border-primary/50 transition-colors group shadow-inner overflow-hidden cursor-pointer">
                            <input type="file" name="image" id="createImageInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewImage(this, 'createImagePreview', 'createPreviewContainer', 'createDefaultState')">
                            <div id="createDefaultState" class="absolute inset-0 flex flex-col items-center justify-center transition-opacity duration-300 z-10 pointer-events-none">
                                <i class="fa-solid fa-camera text-2xl md:text-3xl {{ $errors->has('image') && old('form_type') == 'create' ? 'text-rose-400' : 'text-gray-400' }} group-hover:text-primary transition-colors mb-1"></i>
                                <span class="text-[9px] md:text-[10px] font-bold text-gray-500">اختر صورة</span>
                            </div>
                            <div id="createPreviewContainer" class="absolute inset-0 hidden w-full h-full bg-white rounded-full z-10 pointer-events-none">
                                <img id="createImagePreview" class="w-full h-full object-cover rounded-full" alt="Preview">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">اسم الشركة <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('form_type') == 'create' ? old('name') : '' }}" required class="w-full bg-slate-50 border {{ $errors->has('name') && old('form_type') == 'create' ? 'border-rose-400 focus:ring-rose-500/20' : 'border-gray-100 focus:ring-primary/20' }} rounded-xl md:rounded-2xl px-4 py-3 md:px-5 md:py-3.5 text-xs md:text-sm focus:bg-white focus:ring-4 transition-all outline-none text-right shadow-sm" placeholder="مثال: شركة أرامكس للتوصيل">
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">رقم الهاتف <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('form_type') == 'create' ? old('phone') : '' }}" required class="w-full bg-slate-50 border {{ $errors->has('phone') && old('form_type') == 'create' ? 'border-rose-400 focus:ring-rose-500/20' : 'border-gray-100 focus:ring-primary/20' }} rounded-xl md:rounded-2xl px-4 py-3 md:px-5 md:py-3.5 text-xs md:text-sm focus:bg-white focus:ring-4 transition-all outline-none text-left font-mono shadow-sm" placeholder="0123456789" dir="ltr">
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني (اختياري)</label>
                        <input type="email" name="email" value="{{ old('form_type') == 'create' ? old('email') : '' }}" class="w-full bg-slate-50 border {{ $errors->has('email') && old('form_type') == 'create' ? 'border-rose-400 focus:ring-rose-500/20' : 'border-gray-100 focus:ring-primary/20' }} rounded-xl md:rounded-2xl px-4 py-3 md:px-5 md:py-3.5 text-xs md:text-sm focus:bg-white focus:ring-4 transition-all outline-none text-left font-mono shadow-sm" placeholder="contact@company.com" dir="ltr">
                    </div>

                    <div class="flex items-center justify-between bg-emerald-50/50 p-4 md:p-5 rounded-xl md:rounded-2xl border border-emerald-100">
                        <label class="text-xs md:text-sm font-black text-emerald-800">تفعيل الشركة فوراً</label>
                        <label class="relative inline-flex items-center cursor-pointer group/switch">
                            <input type="checkbox" name="status" value="1" class="sr-only peer" checked>
                            <div class="w-10 h-5 md:w-12 md:h-6 bg-gray-300 rounded-full peer peer-checked:bg-emerald-500 transition-colors shadow-inner group-hover/switch:bg-gray-400"></div>
                            <div class="absolute right-1 top-1 bg-white w-3 h-3 md:w-4 md:h-4 rounded-full transition-transform peer-checked:-translate-x-5 md:peer-checked:-translate-x-6 shadow-sm"></div>
                        </label>
                    </div>
                </div>
                <div class="p-5 md:p-8 bg-slate-50/80 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" onclick="toggleModal('createCompanyModal')" class="px-5 md:px-6 py-3 md:py-3.5 rounded-xl md:rounded-2xl font-bold text-slate-500 bg-white border border-gray-200 hover:bg-slate-50 transition-colors text-xs md:text-sm">إلغاء</button>
                    <button type="submit" class="px-6 md:px-8 py-3 md:py-3.5 rounded-xl md:rounded-2xl font-bold text-white bg-primary hover:bg-primaryDark shadow-lg shadow-primary/30 transition-all text-xs md:text-sm">حفظ الشركة</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editCompanyModal" class="fixed inset-0 z-50 hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('editCompanyModal')"></div>
        <div class="relative bg-white rounded-[2rem] md:rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden animate-scale-up flex flex-col max-h-[90vh]">
            <div class="p-5 md:p-8 border-b border-gray-100 flex justify-between items-center bg-slate-50/50 shrink-0 text-right">
                <h3 class="text-lg md:text-xl font-black text-slate-800">تعديل بيانات الشركة</h3>
                <button type="button" onclick="toggleModal('editCompanyModal')" class="w-8 h-8 md:w-10 md:h-10 rounded-xl md:rounded-2xl bg-white border border-gray-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-xmark text-base md:text-lg"></i>
                </button>
            </div>
            <form id="editCompanyForm" action="{{ old('form_type') == 'edit' ? old('update_url') : '' }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden" onsubmit="disableSubmitButton(this)">
                @csrf @method('PUT')
                <input type="hidden" name="form_type" value="edit">
                <input type="hidden" id="editUpdateUrl" name="update_url" value="{{ old('update_url') }}">

                <div class="p-5 md:p-8 space-y-5 md:space-y-6 overflow-y-auto scrollbar-thin text-right">

                    <div class="flex flex-col items-center justify-center">
                        <div class="relative w-28 h-28 md:w-32 md:h-32 rounded-full border-4 border-dashed border-gray-200 bg-slate-50 flex flex-col items-center justify-center text-center hover:border-primary/50 cursor-pointer overflow-hidden group shadow-inner">
                            <input type="file" name="image" id="editImageInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewImage(this, 'editImagePreview', 'editPreviewContainer', 'editDefaultState')">
                            <div id="editDefaultState" class="absolute inset-0 flex flex-col items-center justify-center transition-opacity z-10 pointer-events-none {{ old('image_preview_url') && old('form_type') == 'edit' ? 'hidden' : '' }}">
                                <i class="fa-solid fa-camera text-2xl md:text-3xl text-gray-400"></i>
                            </div>
                            <div id="editPreviewContainer" class="absolute inset-0 {{ old('image_preview_url') && old('form_type') == 'edit' ? '' : 'hidden' }} w-full h-full bg-white rounded-full z-10 pointer-events-none">
                                <input type="hidden" id="editImagePreviewUrl" name="image_preview_url" value="{{ old('image_preview_url') }}">
                                <img id="editImagePreview" src="{{ old('form_type') == 'edit' ? old('image_preview_url') : '' }}" class="w-full h-full object-cover rounded-full">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">اسم الشركة <span class="text-rose-500">*</span></label>
                        <input type="text" id="editNameInput" name="name" value="{{ old('form_type') == 'edit' ? old('name') : '' }}" required class="w-full bg-slate-50 border border-gray-100 rounded-xl md:rounded-2xl px-4 py-3 md:px-5 md:py-3.5 text-xs md:text-sm focus:bg-white focus:ring-4 transition-all outline-none text-right shadow-sm">
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">رقم الهاتف <span class="text-rose-500">*</span></label>
                        <input type="text" id="editPhoneInput" name="phone" value="{{ old('form_type') == 'edit' ? old('phone') : '' }}" required class="w-full bg-slate-50 border border-gray-100 rounded-xl md:rounded-2xl px-4 py-3 md:px-5 md:py-3.5 text-xs md:text-sm focus:bg-white focus:ring-4 transition-all outline-none text-left font-mono shadow-sm" dir="ltr">
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني (اختياري)</label>
                        <input type="email" id="editEmailInput" name="email" value="{{ old('form_type') == 'edit' ? old('email') : '' }}" class="w-full bg-slate-50 border border-gray-100 rounded-xl md:rounded-2xl px-4 py-3 md:px-5 md:py-3.5 text-xs md:text-sm focus:bg-white focus:ring-4 transition-all outline-none text-left font-mono shadow-sm" dir="ltr">
                    </div>

                    <div class="flex items-center justify-between bg-blue-50/50 p-4 md:p-5 rounded-xl md:rounded-2xl border border-blue-100">
                        <label class="text-xs md:text-sm font-black text-blue-800">حالة الشركة</label>
                        <label class="relative inline-flex items-center cursor-pointer group/switch">
                            <input type="checkbox" id="editStatusInput" name="status" value="1" class="sr-only peer" {{ old('form_type') == 'edit' && !old('status') ? '' : 'checked' }}>
                            <div class="w-10 h-5 md:w-12 md:h-6 bg-slate-300 rounded-full peer peer-checked:bg-blue-500 transition-colors shadow-inner group-hover/switch:bg-slate-400"></div>
                            <div class="absolute right-1 top-1 bg-white w-3 h-3 md:w-4 md:h-4 rounded-full transition-transform peer-checked:-translate-x-5 md:peer-checked:-translate-x-6 shadow-sm"></div>
                        </label>
                    </div>
                </div>

                <div class="p-5 md:p-8 bg-slate-50/80 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" onclick="toggleModal('editCompanyModal')" class="px-5 md:px-6 py-3 md:py-3.5 rounded-xl md:rounded-2xl font-bold text-slate-500 bg-white border border-gray-200 hover:bg-slate-50 transition-colors text-xs md:text-sm">إلغاء</button>
                    <button type="submit" class="px-6 md:px-8 py-3 md:py-3.5 rounded-xl md:rounded-2xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all text-xs md:text-sm">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteCompanyModal" class="fixed inset-0 z-50 hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('deleteCompanyModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-scale-up text-center p-6 md:p-10">
            <div class="w-20 h-20 md:w-24 md:h-24 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-5 md:mb-6 text-rose-500 text-3xl md:text-4xl shadow-inner border-[6px] border-rose-100">
                <i class="fa-solid fa-trash-can animate-bounce-short text-rose-500"></i>
            </div>
            <h3 class="text-xl md:text-2xl font-black text-slate-800 mb-2">حذف الشركة؟</h3>
            <p class="text-xs md:text-sm text-gray-500 font-medium mb-6 md:mb-8 leading-relaxed">سيتم حذف بيانات الشركة نهائياً من النظام. هل أنت متأكد؟</p>

            <form id="deleteCompanyForm" action="" method="POST" onsubmit="disableSubmitButton(this)">
                @csrf @method('DELETE')
                <div class="flex items-center justify-center gap-3">
                    <button type="button" onclick="toggleModal('deleteCompanyModal')" class="flex-1 px-4 md:px-6 py-3.5 md:py-4 rounded-xl md:rounded-2xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-xs md:text-sm">إلغاء الأمر</button>
                    <button type="submit" class="flex-1 px-4 md:px-6 py-3.5 md:py-4 rounded-xl md:rounded-2xl font-bold text-white bg-red-500 hover:bg-red-600 shadow-lg shadow-red-500/30 transition-all text-xs md:text-sm">نعم، احذف</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentToggleId = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        // --- Server-side Filtering ---
        function setServerFilter(param, value) {
            const url = new URL(window.location.href);
            if (value === 'all' || value === '') {
                url.searchParams.delete(param);
            } else {
                url.searchParams.set(param, value);
            }
            url.searchParams.delete('page');
            window.location.href = url.href;
        }

        document.getElementById('serverSearchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                setServerFilter('search', this.value);
            }
        });

        // --- Modal Functions ---
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function disableSubmitButton(form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if(submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin ml-2"></i> جاري المعالجة...';
            }
            return true;
        }

        // --- Image Preview ---
        function previewImage(inputElement, imageId, containerId, defaultStateId) {
            const previewImage = document.getElementById(imageId);
            const previewContainer = document.getElementById(containerId);
            const defaultState = document.getElementById(defaultStateId);
            if (inputElement.files && inputElement.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    defaultState.classList.add('hidden');
                }
                reader.readAsDataURL(inputElement.files[0]);
            }
        }

        // --- Open Edit/Delete Modals ---
        function openEditModal(company, imageUrl) {
            let form = document.getElementById('editCompanyForm');
            form.action = `/admin/delivery-companies/${company.id}`;
            document.getElementById('editUpdateUrl').value = form.action;

            document.getElementById('editNameInput').value = company.name;
            document.getElementById('editPhoneInput').value = company.phone || '';
            document.getElementById('editEmailInput').value = company.email || '';
            document.getElementById('editStatusInput').checked = (company.status == 1);

            let previewImage = document.getElementById('editImagePreview');
            let previewContainer = document.getElementById('editPreviewContainer');
            let defaultState = document.getElementById('editDefaultState');

            if(company.image) {
                previewImage.src = imageUrl;
                previewContainer.classList.remove('hidden');
                defaultState.classList.add('hidden');
            } else {
                previewContainer.classList.add('hidden');
                defaultState.classList.remove('hidden');
            }

            toggleModal('editCompanyModal');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteCompanyForm').action = `/admin/delivery-companies/${id}`;
            toggleModal('deleteCompanyModal');
        }

        // --- AJAX Status Toggle ---
        function openToggleConfirmModal(id, name, isCurrentlyActive) {
            currentToggleId = id;

            const iconBg = document.getElementById('tc-icon-bg');
            const icon = document.getElementById('tc-icon');
            const message = document.getElementById('tc-message');
            const confirmBtn = document.getElementById('tc-confirm-btn');

            if (isCurrentlyActive) {
                iconBg.className = 'w-20 h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center mx-auto mb-5 md:mb-6 text-3xl md:text-4xl shadow-inner border-[6px] bg-rose-50 text-rose-500 border-rose-100';
                icon.className = 'fa-solid fa-power-off animate-bounce-short';
                message.innerHTML = `هل أنت متأكد أنك تريد <strong>إيقاف</strong> التعامل مع شركة "${name}"؟<br>لن تظهر الشركة كخيار متاح للشحن.`;
                confirmBtn.className = 'flex-1 px-4 md:px-6 py-3.5 md:py-4 rounded-xl md:rounded-2xl font-bold text-white shadow-lg transition-all text-xs md:text-sm bg-rose-500 hover:bg-rose-600 shadow-rose-500/30';
                confirmBtn.innerText = 'نعم، قم بالإيقاف';
            } else {
                iconBg.className = 'w-20 h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center mx-auto mb-5 md:mb-6 text-3xl md:text-4xl shadow-inner border-[6px] bg-emerald-50 text-emerald-500 border-emerald-100';
                icon.className = 'fa-solid fa-check animate-bounce-short';
                message.innerHTML = `هل أنت متأكد أنك تريد <strong>تفعيل</strong> التعامل مع شركة "${name}"؟<br>ستصبح متاحة لتوصيل الطلبات.`;
                confirmBtn.className = 'flex-1 px-4 md:px-6 py-3.5 md:py-4 rounded-xl md:rounded-2xl font-bold text-white shadow-lg transition-all text-xs md:text-sm bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/30';
                confirmBtn.innerText = 'نعم، قم بالتفعيل';
            }

            toggleModal('toggleConfirmModal');
        }

        function executeToggle() {
            const btn = document.getElementById('tc-confirm-btn');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري المعالجة...';
            btn.disabled = true;

            fetch(`/admin/delivery-companies/${currentToggleId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                toggleModal('toggleConfirmModal');
                if(data.success) {
                    showDynamicToast('success', data.message);
                    updateSwitchUI(currentToggleId, data.status == 1);
                    updateStatsCounters(data.status == 1);
                } else {
                    showDynamicToast('error', data.message || 'حدث خطأ غير متوقع');
                }
            })
            .catch(error => {
                toggleModal('toggleConfirmModal');
                showDynamicToast('error', 'خطأ في الاتصال بالخادم');
            })
            .finally(() => {
                btn.disabled = false;
            });
        }

        function updateSwitchUI(id, isNowActive) {
            const btn = document.getElementById(`toggle-btn-${id}`);
            const knob = document.getElementById(`toggle-knob-${id}`);
            const badge = document.getElementById(`badge-${id}`);
            const card = btn.closest('.company-card');

            card.setAttribute('data-status', isNowActive ? '1' : '0');
            card.classList.toggle('opacity-75', !isNowActive);
            card.classList.toggle('grayscale-[30%]', !isNowActive);

            btn.className = `relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none ${isNowActive ? 'bg-emerald-500' : 'bg-gray-300'}`;
            knob.className = `inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 ease-in-out ${isNowActive ? '-translate-x-7' : '-translate-x-1'} shadow-sm`;

            if(badge) {
                if(isNowActive) {
                    badge.className = 'px-3 py-1 rounded-full text-[10px] font-black border shadow-sm bg-emerald-50 border-emerald-100 text-emerald-600';
                    badge.innerText = 'نشط';
                } else {
                    badge.className = 'px-3 py-1 rounded-full text-[10px] font-black border shadow-sm bg-rose-50 border-rose-100 text-rose-600';
                    badge.innerText = 'موقوف';
                }
            }

            const nameElement = card.querySelector('.company-name');
            const companyName = nameElement ? nameElement.innerText : '';
            btn.setAttribute('onclick', `openToggleConfirmModal(${id}, '${companyName}', ${isNowActive ? 'true' : 'false'})`);
        }

        function updateStatsCounters(isNowActive) {
            let activeEl = document.getElementById('stat-active');
            let inactiveEl = document.getElementById('stat-inactive');

            if (activeEl && inactiveEl) {
                let currentActive = parseInt(activeEl.innerText);
                let currentInactive = parseInt(inactiveEl.innerText);
                if (isNowActive) {
                    activeEl.innerText = currentActive + 1;
                    inactiveEl.innerText = currentInactive - 1;
                } else {
                    activeEl.innerText = currentActive - 1;
                    inactiveEl.innerText = currentInactive + 1;
                }
            }
        }

        function showDynamicToast(type, message) {
            let container = document.getElementById('ajax-toast-container');
            const toast = document.createElement('div');
            toast.className = `animate-toast pointer-events-auto bg-white border-r-4 ${type === 'success' ? 'border-emerald-500' : 'border-rose-500'} shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto text-right mb-3`;
            toast.innerHTML = `
                <div class="${type === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'} p-2 rounded-xl shrink-0"><i class="fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'} text-xl"></i></div>
                <div class="flex-1"><p class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">${type === 'success' ? 'تمت العملية' : 'خطأ في النظام'}</p><p class="text-sm font-black text-slate-800 leading-tight">${message}</p></div>
                <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-500"><i class="fa-solid fa-xmark"></i></button>
            `;
            container.appendChild(toast);
            setTimeout(() => { if(toast.parentElement) toast.remove(); }, 3000);
        }

        // Handle validation error reopening
        @if($errors->any())
            @if(old('form_type') == 'create') toggleModal('createCompanyModal');
            @elseif(old('form_type') == 'edit') toggleModal('editCompanyModal');
            @endif
        @endif

        // Auto hide session toasts
        setTimeout(() => {
            document.querySelectorAll('#toast-container .animate-toast').forEach(t => t.remove());
        }, 5000);
    </script>

    <style>
        .animate-scale-up { animation: scaleUp 0.3s forwards; }
        @keyframes scaleUp { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1); } }
        .animate-toast { animation: toastSlideIn 0.4s forwards; }
        @keyframes toastSlideIn { from { opacity: 0; transform: translateX(100%); } to { opacity: 1; transform: translateX(0); } }
        .animate-bounce-short { animation: bounceShort 1s infinite; }
        @keyframes bounceShort { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10%); } }
        .custom-pagination nav svg { width: 1.25rem; height: 1.25rem; }
        .custom-pagination nav > div:first-child { display: none; }
        @media (min-width: 768px) { .custom-pagination nav > div:first-child { display: flex; } }
    </style>
@endsection
