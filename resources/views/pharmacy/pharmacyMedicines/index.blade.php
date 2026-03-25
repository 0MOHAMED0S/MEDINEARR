@extends('pharmacy.layout.master')

@section('title', 'أدويتي ومخزوني')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<div class="p-4 md:p-6 lg:p-8 relative">

    @php
        $lowStockCount = $myMedicines->where('pivot.quantity', '<=', 5)->where('pivot.quantity', '>', 0)->count();
        $outOfStockCount = $myMedicines->where('pivot.quantity', 0)->count();
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 mb-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col justify-center transition-all hover:shadow-md">
            <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">إدارة المخزون الذكية</h2>
            <p class="text-sm text-gray-500 font-medium mt-2">تحكم في أسعارك، كمياتك، وتابع النواقص بضغطة زر.</p>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-white p-5 rounded-[2rem] border border-amber-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-xl animate-pulse"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-amber-500 uppercase tracking-wider mb-0.5">أوشكت على النفاد</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $lowStockCount }} <span class="text-sm font-bold text-gray-400">صنف</span></h3>
            </div>
        </div>

        <div class="bg-gradient-to-br from-rose-50 to-white p-5 rounded-[2rem] border border-rose-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-ban text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-rose-500 uppercase tracking-wider mb-0.5">نواقص (رصيد صفر)</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $outOfStockCount }} <span class="text-sm font-bold text-gray-400">صنف</span></h3>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-[2rem] border border-gray-100 shadow-sm mb-6 flex flex-col space-y-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="relative w-full md:w-80 group">
                <input type="text" id="searchInput" oninput="applySmartFilters()" placeholder="ابحث باسم الدواء، المادة الفعالة..."
                    class="w-full bg-slate-50 border border-gray-200 rounded-xl py-3 pr-11 pl-10 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none">
                <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors"></i>
                <button id="clearSearchBtn" onclick="clearSearch()" class="absolute left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-200 hover:bg-rose-500 hover:text-white text-gray-500 rounded-full flex items-center justify-center text-[10px] transition-all hidden">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="flex bg-slate-50 p-1 rounded-xl border border-gray-200 w-full md:w-auto overflow-x-auto hide-scrollbar">
                <button onclick="setStatusFilter('all', this)" class="status-btn active-status flex-1 px-4 py-2 rounded-lg text-xs font-black transition-all text-primary bg-white shadow-sm">الكل</button>
                <button onclick="setStatusFilter('available', this)" class="status-btn flex-1 px-4 py-2 rounded-lg text-xs font-black transition-all text-slate-500 hover:text-slate-800">متوفر</button>
                <button onclick="setStatusFilter('out_of_stock', this)" class="status-btn flex-1 px-4 py-2 rounded-lg text-xs font-black transition-all text-slate-500 hover:text-rose-500">نواقص</button>
            </div>

            <button onclick="openAddModal()" class="w-full md:w-auto shrink-0 bg-primary hover:bg-primaryDark text-white rounded-xl px-5 py-3 text-sm font-black transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>إضافة للمخزون</span>
            </button>
        </div>

        @php
            $uniqueCategories = $myMedicines->pluck('category.name')->filter()->unique();
        @endphp
        @if($uniqueCategories->count() > 0)
        <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar pt-2 border-t border-gray-50">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider shrink-0 ml-2">التصنيفات:</span>
            <button onclick="setCategoryFilter('all', this)" class="cat-btn active-cat px-4 py-1.5 rounded-full text-xs font-bold border border-primary bg-primary text-white transition-all shrink-0">الكل</button>
            @foreach($uniqueCategories as $cat)
                <button onclick="setCategoryFilter('{{ $cat }}', this)" class="cat-btn px-4 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-gray-600 hover:bg-slate-50 hover:text-primary transition-all shrink-0">{{ $cat }}</button>
            @endforeach
        </div>
        @endif
    </div>

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden min-h-[400px]">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse" id="medicinesTable">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center w-20">ID</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">الصنف الدوائي</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">التصنيف</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">السعر</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">حالة المخزون</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-wider text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 relative">

                    <tr id="notFoundRow" class="hidden absolute inset-0 w-full bg-white h-full z-10">
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-filter-circle-xmark text-3xl text-gray-300"></i>
                                </div>
                                <h4 class="text-lg font-black text-slate-700 mb-1">لا توجد نتائج مطابقة لخيارات الفلترة</h4>
                                <button onclick="clearAllFilters()" class="mt-4 text-primary font-bold text-sm hover:underline">إعادة ضبط الفلاتر</button>
                            </div>
                        </td>
                    </tr>

                    @forelse($myMedicines as $item)
                        @php
                            $rowClass = 'bg-white hover:bg-slate-50/50';
                            if ($item->pivot->status == 'out_of_stock' || $item->pivot->quantity == 0) {
                                $rowClass = 'bg-rose-50/30 hover:bg-rose-50/60 animate-pulse-slow';
                            } elseif ($item->pivot->status == 'hidden') {
                                $rowClass = 'bg-slate-50 opacity-70 hover:opacity-100 grayscale-[20%] hover:grayscale-0';
                            }
                        @endphp

                        <tr class="{{ $rowClass }} transition-all duration-300 group med-row"
                            data-category="{{ $item->category->name ?? 'غير مصنف' }}"
                            data-status="{{ $item->pivot->status }}">

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center bg-slate-100 text-slate-500 text-[10px] md:text-xs font-mono font-black px-2.5 py-1 rounded-lg border border-slate-200 shadow-sm">
                                    #{{ $item->id }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-white border border-gray-200 overflow-hidden shrink-0 flex items-center justify-center p-1 shadow-sm">
                                        <img src="{{ $item->image_url ?? asset('Dashboard/images/logo.png') }}" alt="{{ $item->name }}" class="w-full h-full object-contain rounded-xl">
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-slate-800 med-name">{{ $item->name }}</h4>
                                        <p class="text-[10px] text-gray-500 font-bold mt-0.5 max-w-[150px] truncate med-desc" title="{{ $item->description }}">{{ $item->description ?? 'لا يوجد وصف' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="bg-white text-slate-600 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-slate-200 shadow-sm med-category">
                                    {{ $item->category->name ?? 'غير مصنف' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1 w-max">
                                    <div class="flex items-end gap-1 font-mono">
                                        <span class="text-lg font-black text-slate-800">{{ number_format($item->pivot->price, 2) }}</span>
                                        <span class="text-[10px] text-gray-500 font-bold mb-1">ج.م</span>
                                    </div>
                                    @if($item->is_price_fixed)
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-700 border border-amber-200 self-start"><i class="fa-solid fa-lock text-[8px]"></i> جبرية</span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1 w-24">
                                    <div class="flex items-center justify-between text-[10px] font-black">
                                        <span class="{{ $item->pivot->quantity <= 5 ? 'text-rose-600' : 'text-slate-700' }}">{{ $item->pivot->quantity }} عبوة</span>
                                        @if($item->pivot->status == 'hidden')
                                            <span class="text-slate-400"><i class="fa-solid fa-eye-slash"></i> مخفي</span>
                                        @endif
                                    </div>
                                    @php
                                        $percent = min(($item->pivot->quantity / 50) * 100, 100);
                                        $barColor = $item->pivot->quantity == 0 ? 'bg-rose-500' : ($item->pivot->quantity <= 5 ? 'bg-amber-500' : 'bg-emerald-500');
                                    @endphp
                                    <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                        <div class="h-full {{ $barColor }} rounded-full transition-all duration-500" style="width: {{ $item->pivot->quantity == 0 ? '100%' : $percent.'%' }}"></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                            onclick='openEditModal({{ $item->pivot->id }}, @json($item->name), {{ $item->pivot->price ?? 0 }}, {{ $item->pivot->quantity ?? 0 }}, "{{ $item->pivot->status }}", {{ $item->is_price_fixed ? "true" : "false" }}, {{ $item->official_price ?? 0 }})'
                                            class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-colors tooltip shadow-sm" title="تحديث البيانات">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </button>

                                    <button type="button"
                                            onclick='openDeleteModal({{ $item->pivot->id }}, @json($item->name))'
                                            class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-colors tooltip shadow-sm" title="حذف نهائي">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyStateRow">
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100 shadow-sm">
                                        <i class="fa-solid fa-box-open text-3xl text-gray-300"></i>
                                    </div>
                                    <h4 class="text-lg font-black text-slate-700 mb-1">مخزونك فارغ حالياً</h4>
                                    <p class="text-sm text-gray-500 font-medium mb-4">ابدأ بإضافة الأدوية المتوفرة في صيدليتك لتظهر للمرضى.</p>
                                    <button onclick="openAddModal()" class="bg-primary hover:bg-primaryDark text-white rounded-xl px-5 py-2.5 text-sm font-black transition-all shadow-md">
                                        إضافة أول دواء
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="addMedicineModal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4 sm:p-6">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeAddModal()"></div>
    <div class="relative bg-white rounded-[2rem] w-full max-w-xl shadow-2xl animate-scale-up" style="overflow: visible;">

        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-slate-50/50 rounded-t-[2rem]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-bolt text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-800">إضافة سريعة للمخزون</h3>
                    <p class="text-[10px] text-gray-500 font-bold mt-0.5">ابحث، حدد الكمية والسعر، ثم احفظ.</p>
                </div>
            </div>
            <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-xl bg-white border border-gray-200 text-gray-500 hover:bg-rose-50 hover:text-rose-500 hover:border-rose-200 transition-all flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="addMedicineForm" action="{{ route('pharmacy.medicines.store') }}" method="POST" class="p-6 bg-white rounded-b-[2rem]">
            @csrf
            <input type="hidden" name="_form_type" value="add">

            <div id="addFormErrorWrapper">
                @if ($errors->any() && old('_form_type') === 'add')
                    <div class="mb-4 bg-rose-50 text-rose-600 p-3 rounded-xl border border-rose-200 text-xs font-bold">
                        <ul class="list-disc list-inside px-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="relative z-50">
                    <label class="block text-xs font-black text-slate-700 mb-2">الدواء <span class="text-rose-500">*</span></label>
                    <div dir="rtl" class="relative">
                        <select name="medicine_id" id="medicineSelect" placeholder="ابحث باسم الدواء..." class="{{ $errors->has('medicine_id') && old('_form_type') === 'add' ? 'border-rose-500' : '' }}">
                            <option value=""></option>
                            @foreach($allMedicines as $med)
                                <option value="{{ $med->id }}" data-category="{{ $med->category->name ?? 'عام' }}" data-price="{{ $med->official_price }}" data-fixed="{{ $med->is_price_fixed ? '1' : '0' }}" {{ old('medicine_id') == $med->id ? 'selected' : '' }}>
                                    {{ $med->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-2 flex justify-between items-center">
                            <span>سعر البيع (ج.م) <span class="text-rose-500">*</span></span>
                            <span id="addPriceHint" class="text-[10px] text-amber-600 bg-amber-50 px-2 py-0.5 rounded hidden"><i class="fa-solid fa-lock"></i> تسعيرة جبرية</span>
                        </label>
                        <input type="number" step="0.01" name="price" id="addPrice" min="0" placeholder="0.00" value="{{ old('_form_type') === 'add' ? old('price') : '' }}"
                            class="w-full bg-slate-50 border {{ $errors->has('price') && old('_form_type') === 'add' ? 'border-rose-500 bg-rose-50 focus:border-rose-500 focus:ring-rose-500/10' : 'border-gray-200 focus:border-primary focus:ring-primary/10' }} rounded-xl px-4 py-3 text-lg font-black text-slate-800 focus:ring-4 outline-none font-mono transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-2">الكمية المتوفرة <span class="text-rose-500">*</span></label>
                        <input type="number" name="quantity" id="addQty" min="0" placeholder="0" value="{{ old('_form_type') === 'add' ? old('quantity') : '' }}"
                            class="w-full bg-slate-50 border {{ $errors->has('quantity') && old('_form_type') === 'add' ? 'border-rose-500 bg-rose-50 focus:border-rose-500 focus:ring-rose-500/10' : 'border-gray-200 focus:border-primary focus:ring-primary/10' }} rounded-xl px-4 py-3 text-lg font-black text-slate-800 focus:ring-4 outline-none font-mono transition-all">
                    </div>
                </div>
                <input type="hidden" name="status" value="available">
            </div>

            <div class="mt-8 flex items-center justify-between pt-5 border-t border-gray-100">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <div class="relative flex items-center justify-center">
                        <input type="checkbox" id="keepOpenCheckbox" class="peer sr-only" checked>
                        <div class="w-5 h-5 rounded border-2 border-gray-300 bg-white peer-checked:bg-primary peer-checked:border-primary transition-all"></div>
                        <i class="fa-solid fa-check text-white text-[10px] absolute opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-500 group-hover:text-slate-700 transition-colors">ابقِ النافذة مفتوحة لإضافة المزيد</span>
                </label>

                <div class="flex gap-3">
                    <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors">إلغاء</button>
                    <button type="submit" id="addSubmitBtn" class="submit-btn px-6 py-2.5 text-sm font-black text-white bg-slate-800 hover:bg-black rounded-xl shadow-md transition-all flex items-center gap-2">
                        <span class="btn-text">حفظ الدواء</span>
                        <i class="fa-solid fa-arrow-left text-[10px] btn-icon"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="editMedicineModal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4 sm:p-6">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('editMedicineModal')"></div>
    <div class="relative bg-white rounded-[2rem] w-full max-w-xl shadow-2xl overflow-hidden animate-scale-up">

        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-blue-50/50 rounded-t-[2rem]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-800">تحديث المخزون</h3>
                    <p id="editMedName" class="text-[10px] font-bold text-blue-600 mt-0.5 truncate max-w-[200px]">{{ old('_form_type') === 'edit' ? old('edit_name') : '' }}</p>
                </div>
            </div>
            <button onclick="toggleModal('editMedicineModal')" class="w-8 h-8 rounded-xl bg-white border border-gray-200 text-gray-500 hover:bg-rose-50 hover:text-rose-500 hover:border-rose-200 transition-all flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editForm" action="{{ old('_form_type') === 'edit' && old('edit_id') ? route('pharmacy.medicines.update', old('edit_id')) : '' }}" method="POST" class="p-6 bg-white rounded-b-[2rem]" onsubmit="return handleFormSubmit(this, 'editSubmitBtn')">
            @csrf
            @method('PUT')
            <input type="hidden" name="_form_type" value="edit">
            <input type="hidden" name="edit_id" id="editIdInput" value="{{ old('edit_id') }}">
            <input type="hidden" name="edit_name" id="editNameInput" value="{{ old('edit_name') }}">

            @if ($errors->any() && old('_form_type') === 'edit')
                <div class="mb-4 bg-rose-50 text-rose-600 p-3 rounded-xl border border-rose-200 text-xs font-bold">
                    <ul class="list-disc list-inside px-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-2 flex justify-between items-center">
                            <span>السعر الجديد (ج.م) <span class="text-rose-500">*</span></span>
                            <span id="editPriceHint" class="text-[10px] text-amber-600 bg-amber-50 px-2 py-0.5 rounded hidden"><i class="fa-solid fa-lock"></i> تسعيرة جبرية</span>
                        </label>
                        <input type="number" step="0.01" name="price" id="editPrice" min="0" value="{{ old('_form_type') === 'edit' ? old('price') : '' }}"
                            class="w-full bg-slate-50 border {{ $errors->has('price') && old('_form_type') === 'edit' ? 'border-rose-500 bg-rose-50 focus:border-rose-500 focus:ring-rose-500/10' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500/10' }} rounded-xl px-4 py-3 text-lg font-black text-slate-800 focus:ring-4 outline-none font-mono transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-2">الكمية الحالية <span class="text-rose-500">*</span></label>
                        <input type="number" name="quantity" id="editQty" min="0" value="{{ old('_form_type') === 'edit' ? old('quantity') : '' }}"
                            class="w-full bg-slate-50 border {{ $errors->has('quantity') && old('_form_type') === 'edit' ? 'border-rose-500 bg-rose-50 focus:border-rose-500 focus:ring-rose-500/10' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500/10' }} rounded-xl px-4 py-3 text-lg font-black text-slate-800 focus:ring-4 outline-none font-mono transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-2">تحديث الحالة <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="status" id="editStatus_available" value="available" class="peer sr-only" {{ old('status') == 'available' ? 'checked' : '' }}>
                            <div class="p-3 text-center rounded-xl border border-gray-200 bg-white peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition-all shadow-sm peer-checked:shadow-emerald-500/20">
                                <i class="fa-solid fa-check-circle block mb-1 text-lg"></i>
                                <span class="text-[10px] font-black block">متوفر</span>
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="status" id="editStatus_out_of_stock" value="out_of_stock" class="peer sr-only" {{ old('status') == 'out_of_stock' ? 'checked' : '' }}>
                            <div class="p-3 text-center rounded-xl border border-gray-200 bg-white peer-checked:bg-rose-50 peer-checked:border-rose-500 peer-checked:text-rose-700 transition-all shadow-sm peer-checked:shadow-rose-500/20">
                                <i class="fa-solid fa-ban block mb-1 text-lg"></i>
                                <span class="text-[10px] font-black block">نفد</span>
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="status" id="editStatus_hidden" value="hidden" class="peer sr-only" {{ old('status') == 'hidden' ? 'checked' : '' }}>
                            <div class="p-3 text-center rounded-xl border border-gray-200 bg-white peer-checked:bg-slate-100 peer-checked:border-slate-500 peer-checked:text-slate-700 transition-all shadow-sm peer-checked:shadow-slate-500/20">
                                <i class="fa-solid fa-eye-slash block mb-1 text-lg"></i>
                                <span class="text-[10px] font-black block">مخفي</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 pt-5 border-t border-gray-100">
                <button type="button" onclick="toggleModal('editMedicineModal')" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors">إلغاء</button>
                <button type="submit" id="editSubmitBtn" class="submit-btn px-6 py-2.5 text-sm font-black text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/30 transition-all flex items-center gap-2">
                    <span class="btn-text">حفظ التعديلات</span>
                    <i class="fa-solid fa-check btn-icon text-[10px]"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div id="deleteMedicineModal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4 sm:p-6">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('deleteMedicineModal')"></div>
    <div class="relative bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden animate-scale-up text-center">

        <div class="p-8">
            <div class="w-20 h-20 rounded-full bg-rose-50 text-rose-500 mx-auto flex items-center justify-center mb-5 shadow-sm border border-rose-100">
                <i class="fa-solid fa-trash-can text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2">تأكيد الحذف</h3>
            <p class="text-sm text-gray-500 font-medium leading-relaxed">
                هل أنت متأكد من إزالة<br>
                <span id="deleteMedName" class="font-black text-rose-600 text-base bg-rose-50 px-2 py-0.5 rounded inline-block mt-2 mb-1 max-w-[250px] truncate align-middle border border-rose-100"></span><br>
                من مخزونك بشكل نهائي؟
            </p>
        </div>

        <div class="bg-slate-50 p-4 flex items-center justify-center gap-3 border-t border-gray-100">
            <button type="button" onclick="toggleModal('deleteMedicineModal')" class="px-6 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-200 rounded-xl transition-colors">تراجع</button>
            <form id="deleteForm" method="POST" onsubmit="return handleFormSubmit(this, 'deleteSubmitBtn')">
                @csrf
                @method('DELETE')
                <button type="submit" id="deleteSubmitBtn" class="submit-btn px-6 py-2.5 text-sm font-black text-white bg-rose-500 hover:bg-rose-600 rounded-xl shadow-md shadow-rose-500/30 transition-all flex items-center gap-2">
                    <span class="btn-text">نعم، احذفه</span>
                    <i class="fa-solid fa-trash-can btn-icon text-[10px]"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // --- محرك الفلترة الهجين (Hybrid Search Engine) ---
    let searchTimeout;
    let currentCategory = 'all';
    let currentStatus = 'all';

    function setCategoryFilter(category, btnElement) {
        currentCategory = category;
        document.querySelectorAll('.cat-btn').forEach(btn => {
            btn.classList.remove('border-primary', 'bg-primary', 'text-white', 'active-cat');
            btn.classList.add('border-gray-200', 'bg-white', 'text-gray-600');
        });
        btnElement.classList.add('border-primary', 'bg-primary', 'text-white', 'active-cat');
        btnElement.classList.remove('border-gray-200', 'bg-white', 'text-gray-600');
        applySmartFilters();
    }

    function setStatusFilter(status, btnElement) {
        currentStatus = status;
        document.querySelectorAll('.status-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'shadow-sm', 'text-primary', 'active-status');
        });
        btnElement.classList.add('bg-white', 'shadow-sm', 'text-primary', 'active-status');
        applySmartFilters();
    }

    function clearAllFilters() {
        document.getElementById("searchInput").value = "";
        setCategoryFilter('all', document.querySelector('.cat-btn'));
        setStatusFilter('all', document.querySelector('.status-btn'));
    }

    function applySmartFilters() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            let searchInput = document.getElementById("searchInput").value.toLowerCase();
            let clearBtn = document.getElementById("clearSearchBtn");
            let rows = document.querySelectorAll("#medicinesTable tbody tr.med-row");
            let notFoundRow = document.getElementById("notFoundRow");
            let emptyStateRow = document.getElementById("emptyStateRow");

            let hasVisibleRows = false;
            if(searchInput.length > 0) clearBtn.classList.remove('hidden'); else clearBtn.classList.add('hidden');
            if(emptyStateRow) return;

            rows.forEach(row => {
                let medName = row.querySelector(".med-name") ? row.querySelector(".med-name").innerText.toLowerCase() : "";
                let medDesc = row.querySelector(".med-desc") ? row.querySelector(".med-desc").innerText.toLowerCase() : "";
                let rowCategory = row.getAttribute('data-category');
                let rowStatus = row.getAttribute('data-status');

                let matchesSearch = medName.includes(searchInput) || medDesc.includes(searchInput);
                let matchesCategory = (currentCategory === 'all') || (rowCategory === currentCategory);
                let matchesStatus = (currentStatus === 'all') || (rowStatus === currentStatus);

                if (matchesSearch && matchesCategory && matchesStatus) {
                    row.style.display = "";
                    hasVisibleRows = true;
                } else {
                    row.style.display = "none";
                }
            });

            if (!hasVisibleRows) {
                notFoundRow.classList.remove('hidden');
            } else {
                notFoundRow.classList.add('hidden');
            }
        }, 200);
    }

    function clearSearch() {
        document.getElementById("searchInput").value = "";
        applySmartFilters();
        document.getElementById("searchInput").focus();
    }

    // --- منع الإرسال المزدوج وعرض حالة التحميل ---
    function handleFormSubmit(form, btnId) {
        if (form.id === 'addMedicineForm' && document.getElementById('keepOpenCheckbox').checked) {
            return false;
        }

        let btn = document.getElementById(btnId);
        if(!btn) return true;
        let btnText = btn.querySelector('.btn-text');
        let btnIcon = btn.querySelector('.btn-icon');

        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        if(btnText) btnText.innerText = 'جاري التنفيذ...';
        if(btnIcon) {
            btnIcon.classList.remove('fa-arrow-left', 'fa-check', 'fa-trash-can');
            btnIcon.classList.add('fa-spinner', 'fa-spin');
        }
        return true;
    }

    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if(modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    // --- دوال فتح النوافذ ---
    let tomSelectInstance;
    let needsReloadOnClose = false;

    // الدالة الذكية لفتح نافذة التعديل مع حماية السعر الإجباري
    function openEditModal(pivotId, name, price, qty, status, isFixed, officialPrice) {
        let form = document.getElementById('editForm');
        form.action = `/pharmacy/medicines/${pivotId}`;

        document.getElementById('editIdInput').value = pivotId;
        document.getElementById('editNameInput').value = name;
        document.getElementById('editMedName').innerText = name;
        document.getElementById('editQty').value = qty;

        // منطق السعر الإجباري (تسعيرة جبرية)
        let priceInput = document.getElementById('editPrice');
        let priceHint = document.getElementById('editPriceHint');

        if (isFixed) {
            priceInput.value = parseFloat(officialPrice).toFixed(2); // إجبار السعر ليكون السعر الرسمي
            priceInput.readOnly = true;
            priceInput.classList.add('bg-slate-200', 'text-slate-500', 'cursor-not-allowed');
            priceInput.classList.remove('bg-slate-50', 'text-slate-800');
            priceHint.classList.remove('hidden');
        } else {
            priceInput.value = price; // إبقاء سعر الصيدلي الذي قام بإدخاله
            priceInput.readOnly = false;
            priceInput.classList.remove('bg-slate-200', 'text-slate-500', 'cursor-not-allowed');
            priceInput.classList.add('bg-slate-50', 'text-slate-800');
            priceHint.classList.add('hidden');
        }

        let statusRadio = document.getElementById('editStatus_' + status);
        if(statusRadio) statusRadio.checked = true;

        toggleModal('editMedicineModal');
    }

    function openDeleteModal(pivotId, name) {
        let form = document.getElementById('deleteForm');
        form.action = `/pharmacy/medicines/${pivotId}`;
        document.getElementById('deleteMedName').innerText = name;
        toggleModal('deleteMedicineModal');
    }

    function openAddModal() {
        if(tomSelectInstance) {
            tomSelectInstance.clear();
        }

        let priceInput = document.getElementById('addPrice');
        let priceHint = document.getElementById('addPriceHint');

        // تصفير الخانات وإعادتها للحالة المرنة كوضع افتراضي
        priceInput.value = "";
        priceInput.readOnly = false;
        priceInput.classList.remove('bg-slate-200', 'text-slate-500', 'cursor-not-allowed');
        priceInput.classList.add('bg-slate-50', 'text-slate-800');
        if(priceHint) priceHint.classList.add('hidden');

        document.getElementById('addQty').value = "";

        let errorWrapper = document.getElementById('addFormErrorWrapper');
        if(errorWrapper) errorWrapper.innerHTML = '';
        document.querySelectorAll('#addMedicineForm .border-rose-500').forEach(el => {
            el.classList.remove('border-rose-500', 'bg-rose-50');
        });
        if(tomSelectInstance) tomSelectInstance.control.classList.remove('border-rose-500', 'bg-rose-50');

        toggleModal('addMedicineModal');
    }

    function closeAddModal() {
        toggleModal('addMedicineModal');
        if(needsReloadOnClose) {
            window.location.reload();
        }
    }

    // --- الإعداد والمكتبات ---
    document.addEventListener("DOMContentLoaded", () => {
        // تفعيل Tom Select مع ربط بيانات السعر
        if(document.getElementById('medicineSelect')) {
            tomSelectInstance = new TomSelect("#medicineSelect", {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "ابحث عن دواء...",
                onChange: function(value) {
                    if (!value) return;

                    // استخراج الـ Option الذي تم اختياره من الـ DOM للحصول على بيانات الـ Data-*
                    let optionEl = document.querySelector('#medicineSelect option[value="' + value + '"]');
                    if (!optionEl) return;

                    let officialPrice = optionEl.dataset.price;
                    let isFixed = optionEl.dataset.fixed === '1';

                    let priceInput = document.getElementById('addPrice');
                    let priceHint = document.getElementById('addPriceHint');

                    priceInput.value = parseFloat(officialPrice).toFixed(2);

                    if (isFixed) {
                        priceInput.readOnly = true;
                        priceInput.classList.add('bg-slate-200', 'text-slate-500', 'cursor-not-allowed');
                        priceInput.classList.remove('bg-slate-50', 'text-slate-800');
                        priceHint.classList.remove('hidden');
                    } else {
                        priceInput.readOnly = false;
                        priceInput.classList.remove('bg-slate-200', 'text-slate-500', 'cursor-not-allowed');
                        priceInput.classList.add('bg-slate-50', 'text-slate-800');
                        priceHint.classList.add('hidden');
                    }
                },
                render: {
                    item: function(data, escape) {
                        return '<div class="flex items-center gap-2"><div class="w-6 h-6 rounded bg-primary/10 flex items-center justify-center shrink-0"><i class="fa-solid fa-pills text-[10px] text-primary"></i></div><span class="font-black text-slate-800">' + escape(data.text) + '</span></div>';
                    },
                    option: function(data, escape) {
                        const category = data.category ? escape(data.category) : 'عام';
                        return '<div class="flex flex-col py-1"><span class="font-black text-slate-800">' + escape(data.text) + '</span><span class="text-[10px] text-gray-400 font-bold mt-0.5">' + category + '</span></div>';
                    },
                    no_results: function(data, escape) {
                        return '<div class="no-results text-sm font-bold text-rose-500 p-3 text-right">لا توجد أدوية مطابقة لـ "'+escape(data.input)+'"</div>';
                    }
                }
            });

            if (document.getElementById('medicineSelect').classList.contains('border-rose-500')) {
                tomSelectInstance.control.classList.add('border-rose-500', 'bg-rose-50');
            }
        }

        @if ($errors->any())
            @if (old('_form_type') === 'add')
                toggleModal('addMedicineModal');
            @elseif (old('_form_type') === 'edit')
                toggleModal('editMedicineModal');
            @endif
        @endif

        const addForm = document.getElementById('addMedicineForm');
        if(addForm) {
            addForm.addEventListener('submit', function(e) {
                const keepOpen = document.getElementById('keepOpenCheckbox').checked;
                if(keepOpen) {
                    e.preventDefault();
                    const saveBtn = document.getElementById('addSubmitBtn');
                    const btnText = saveBtn.querySelector('.btn-text');
                    const btnIcon = saveBtn.querySelector('.btn-icon');

                    const originalText = btnText.innerHTML;
                    btnText.innerHTML = 'جاري التنفيذ...';
                    btnIcon.classList.remove('fa-arrow-left');
                    btnIcon.classList.add('fa-spinner', 'fa-spin');
                    saveBtn.disabled = true;

                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(async response => {
                        if(response.ok) {
                            Toast.fire({ icon: 'success', title: 'تمت الإضافة بنجاح!' });
                            tomSelectInstance.clear();

                            let priceInput = document.getElementById('addPrice');
                            let priceHint = document.getElementById('addPriceHint');

                            priceInput.value = "";
                            priceInput.readOnly = false;
                            priceInput.classList.remove('bg-slate-200', 'text-slate-500', 'cursor-not-allowed');
                            priceInput.classList.add('bg-slate-50', 'text-slate-800');
                            if(priceHint) priceHint.classList.add('hidden');

                            document.getElementById('addQty').value = "";
                            tomSelectInstance.focus();

                            document.getElementById('addFormErrorWrapper').innerHTML = '';
                            document.querySelectorAll('#addMedicineForm .border-rose-500').forEach(el => {
                                el.classList.remove('border-rose-500', 'bg-rose-50');
                            });
                            if(tomSelectInstance) tomSelectInstance.control.classList.remove('border-rose-500', 'bg-rose-50');
                            needsReloadOnClose = true;

                        } else if (response.status === 422) {
                            const data = await response.json();
                            let errorHtml = '<div class="mb-4 bg-rose-50 text-rose-600 p-3 rounded-xl border border-rose-200 text-xs font-bold"><ul class="list-disc list-inside px-2">';
                            for(let key in data.errors) {
                                errorHtml += `<li>${data.errors[key][0]}</li>`;
                            }
                            errorHtml += '</ul></div>';
                            document.getElementById('addFormErrorWrapper').innerHTML = errorHtml;
                        } else {
                            Toast.fire({ icon: 'error', title: 'حدث خطأ غير متوقع. جرب مرة أخرى.' });
                        }
                    })
                    .catch(() => Toast.fire({ icon: 'error', title: 'حدث خطأ في الاتصال بالخادم.' }))
                    .finally(() => {
                        btnText.innerHTML = originalText;
                        btnIcon.classList.remove('fa-spinner', 'fa-spin');
                        btnIcon.classList.add('fa-arrow-left');
                        saveBtn.disabled = false;
                    });
                }
            });
        }
    });
</script>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    .animate-pulse-slow { animation: pulseSlow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulseSlow {
        0%, 100% { opacity: 1; }
        50% { opacity: .7; }
    }

    .animate-scale-up { animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes scaleUp {
        0% { opacity: 0; transform: scale(0.95) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }

    .ts-wrapper { direction: rtl; text-align: right; }
    .ts-control {
        border-radius: 0.75rem !important;
        padding: 0.75rem 1rem 0.75rem 2.5rem !important;
        border: 1px solid #e5e7eb !important;
        background-color: #f8fafc !important;
        font-size: 0.875rem !important;
        font-weight: 700 !important;
        color: #334155 !important;
        box-shadow: none !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%239ca3af' d='M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: left 1rem center !important;
        background-size: 14px !important;
    }
    .ts-control.border-rose-500 {
        border-color: #f43f5e !important;
        background-color: #fff1f2 !important;
    }
    .ts-wrapper.single .ts-control::after { display: none !important; }
    .ts-control.focus {
        border-color: #0d9488 !important;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1) !important;
        background-color: #ffffff !important;
    }
    .ts-dropdown {
        border-radius: 0.75rem !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
        margin-top: 8px;
        z-index: 9999 !important;
    }
    .ts-dropdown .option {
        padding: 0.75rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
    }
    .ts-dropdown .option.active, .ts-dropdown .option:hover { background-color: #f0fdfa !important; }
</style>
@endsection
