@extends('dashboard.layout.master')
@section('content')
    <div class="p-4 md:p-6 lg:p-8 relative">
        <div id="toast-container"
            class="fixed top-4 left-4 right-4 md:left-auto md:right-6 md:top-6 z-[9999] flex flex-col gap-3 pointer-events-none">
            @if (session('success'))
                <div
                    class="animate-toast pointer-events-auto bg-white border-r-4 border-emerald-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto">
                    <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600 shrink-0">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">تمت
                            العملية</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()"
                        class="text-slate-300 hover:text-slate-500 transition-colors shrink-0"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">إدارة الأدوية</h2>
                <p class="text-sm text-gray-500 font-medium mt-1">عرض وإدارة جميع الأدوية والمنتجات الطبية</p>
            </div>
            <button onclick="toggleModal('createMedicineModal')"
                class="bg-primary hover:bg-primaryDark text-white px-6 py-3.5 rounded-2xl font-bold transition-all shadow-sm shadow-primary/20 flex items-center gap-2 shrink-0 w-full sm:w-auto justify-center">
                <i class="fa-solid fa-plus"></i>
                إضافة دواء جديد
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
            <div
                class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-primary/20 group">
                <div
                    class="w-14 h-14 md:w-16 md:h-16 bg-teal-50 text-primary rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-pills"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">إجمالي الأدوية</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['total'] }}</h3>
                </div>
            </div>

            <div
                class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-lime-200 group">
                <div
                    class="w-14 h-14 md:w-16 md:h-16 bg-lime-50 text-accent rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">أدوية متاحة (نشطة)</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['active'] }}</h3>
                </div>
            </div>

            <div
                class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-red-200 group">
                <div
                    class="w-14 h-14 md:w-16 md:h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">أدوية غير متاحة</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['inactive'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden transition-all">

            <div
                class="p-5 md:p-6 border-b border-gray-50 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 bg-slate-50/30">

                <div class="flex items-center gap-3 shrink-0">
                    <h3 class="font-black text-slate-800 text-lg tracking-tight">قائمة الأدوية</h3>
                    <span id="resultCounter"
                        class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full transition-all">
                        {{ count($medicines) }} دواء
                    </span>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto flex-wrap">

                    <div class="relative w-full sm:w-64">
                        <input type="text" id="searchInput" placeholder="ابحث باسم الدواء..."
                            class="w-full bg-slate-50 border border-gray-200 rounded-2xl px-5 py-3 pr-11 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-slate-700 shadow-sm">
                        <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <div class="relative w-full sm:w-auto">
                        <select id="categoryFilter"
                            class="w-full sm:w-auto bg-slate-50 border border-gray-200 rounded-2xl py-3 pr-10 pl-10 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none appearance-none text-slate-600 shadow-sm font-medium cursor-pointer">
                            <option value="all">جميع الأقسام</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <i
                            class="fa-solid fa-layer-group absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        <i
                            class="fa-solid fa-chevron-down absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>



                    <div class="relative w-full sm:w-auto">
                        <select id="statusFilter"
                            class="w-full sm:w-auto bg-slate-50 border border-gray-200 rounded-2xl py-3 pr-10 pl-10 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none appearance-none text-slate-600 shadow-sm font-medium cursor-pointer">
                            <option value="all">جميع الحالات</option>
                            <option value="1">المتاح فقط</option>
                            <option value="0">الغير متاح فقط</option>
                        </select>
                        <i
                            class="fa-solid fa-toggle-on absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        <i
                            class="fa-solid fa-chevron-down absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-right min-w-[900px]">
                    <thead
                        class="bg-slate-50 border-b border-gray-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <tr>
                            <th class="p-5 w-16">#</th>
                            <th class="p-5 w-24">صورة</th>
                            <th class="p-5">الدواء والوصف</th>
                            <th class="p-5 w-40">القسم</th>
                            <th class="p-5 w-32">الحالة</th>
                            <th class="p-5 w-32 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="tableBody">
                        @forelse ($medicines as $index => $medicine)
                            <tr class="medicine-row hover:bg-slate-50/80 transition-all duration-300 {{ $medicine->status == 0 ? 'opacity-60 grayscale-[20%]' : '' }}"
                                data-name="{{ mb_strtolower($medicine->name) }}"
                                data-category="{{ $medicine->category_id }}" data-status="{{ $medicine->status }}">

                                <td class="p-5 font-bold text-slate-400 text-sm">{{ $index + 1 }}</td>
                                <td class="p-5">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white flex items-center justify-center overflow-hidden shadow-sm border border-slate-200 p-0.5">
                                        @if ($medicine->image)
                                            <img src="{{ $medicine->image_url }}" alt="{{ $medicine->name }}"
                                                class="w-full h-full object-cover rounded-full">
                                        @else
                                            <div
                                                class="w-full h-full bg-slate-100 rounded-full flex items-center justify-center">
                                                <i class="fa-solid fa-prescription-bottle-medical text-slate-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-slate-800 text-sm md:text-base mb-0.5">{{ $medicine->name }}</span>
                                        <span class="text-xs text-gray-500 line-clamp-1 max-w-xs"
                                            title="{{ $medicine->description }}">{{ $medicine->description }}</span>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-[11px] font-bold border border-slate-200">
                                        <i class="fa-solid fa-layer-group text-[10px] text-primary"></i>
                                        {{ $medicine->category->name ?? 'غير محدد' }}
                                    </span>
                                </td>
                                <td class="p-5">
                                    <label class="relative inline-flex items-center cursor-pointer group/switch">
                                        <input type="checkbox" onchange="toggleMedicineStatus({{ $medicine->id }}, this)"
                                            class="sr-only peer" {{ $medicine->status == 1 ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-emerald-500 transition-colors shadow-inner group-hover/switch:bg-gray-300 peer-checked:group-hover/switch:bg-emerald-600">
                                        </div>
                                        <div
                                            class="absolute right-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:-translate-x-5 shadow-sm">
                                        </div>
                                    </label>
                                </td>
                                <td class="p-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            onclick="openEditModal({{ $medicine }}, '{{ $medicine->image_url }}')"
                                            class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all flex items-center justify-center shadow-sm"
                                            title="تعديل الدواء">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        <button onclick="openDeleteModal({{ $medicine->id }})"
                                            class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all flex items-center justify-center shadow-sm"
                                            title="حذف الدواء">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="defaultEmptyRow">
                                <td colspan="6" class="p-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-pills text-4xl mb-4 text-gray-300"></i>
                                        <p class="font-bold text-lg text-slate-600">لا توجد أدوية مضافة بعد</p>
                                        <p class="text-sm mt-1">قم بإضافة أول دواء للنظام بالنقر على الزر بالأعلى.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        <tr id="noResultsRow" style="display: none;">
                            <td colspan="6" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-magnifying-glass text-4xl mb-4 text-gray-200"></i>
                                    <p class="font-bold text-lg text-slate-600">لا توجد نتائج مطابقة لبحثك</p>
                                    <p class="text-sm mt-1">حاول استخدام كلمات بحث أخرى أو تغيير الفلاتر المحددة.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="md:hidden p-4 bg-slate-50 text-center border-t border-gray-100">
                <p class="text-[10px] text-gray-500 font-bold flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrows-left-right"></i>
                    اسحب لليسار لعرض المزيد من البيانات
                </p>
            </div>
        </div>

    </div>

    <div id="createMedicineModal" class="fixed inset-0 z-50 hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
            onclick="toggleModal('createMedicineModal')"></div>
        <div
            class="relative bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden animate-scale-up flex flex-col max-h-[90vh]">

            <div class="p-6 md:p-8 border-b border-gray-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                <h3 class="text-xl font-black text-slate-800">إضافة دواء جديد</h3>
                <button type="button" onclick="toggleModal('createMedicineModal')"
                    class="w-10 h-10 rounded-2xl bg-white border border-gray-200 text-slate-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition-all flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('medicines.store') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <input type="hidden" name="form_type" value="create">

                <div class="p-6 md:p-8 space-y-6 overflow-y-auto scrollbar-thin">

                    <div class="flex flex-col items-center justify-center">
                        <label class="text-sm font-bold text-slate-700 mb-3 text-center">صورة الدواء <span
                                class="text-red-500">*</span></label>
                        <div
                            class="relative w-32 h-32 rounded-full border-4 border-dashed {{ $errors->has('image') && old('form_type') == 'create' ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-slate-50' }} flex flex-col items-center justify-center text-center hover:border-primary/50 transition-colors group shadow-inner">
                            <input type="file" name="image" id="createImageInput"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*"
                                onchange="previewImage(this, 'createImagePreview', 'createPreviewContainer', 'createDefaultState')">

                            <div id="createDefaultState"
                                class="absolute inset-0 flex flex-col items-center justify-center transition-opacity duration-300 z-10 pointer-events-none">
                                <i
                                    class="fa-solid fa-camera text-3xl {{ $errors->has('image') && old('form_type') == 'create' ? 'text-red-400' : 'text-gray-400' }} group-hover:text-primary transition-colors mb-1"></i>
                                <span class="text-[10px] font-bold text-gray-500">اختر صورة</span>
                            </div>

                            <div id="createPreviewContainer"
                                class="absolute inset-0 hidden w-full h-full bg-white rounded-full z-10 pointer-events-none">
                                <img id="createImagePreview" class="w-full h-full object-cover rounded-full"
                                    alt="Preview">
                                <div
                                    class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-[1px] rounded-full">
                                    <i class="fa-solid fa-pen text-white text-xl mb-1"></i>
                                    <span class="text-white font-bold text-[10px]">تغيير</span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('image') && old('form_type') == 'create')
                            <p class="text-red-500 text-xs font-bold mt-3 bg-red-50 px-3 py-1 rounded-lg"><i
                                    class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $errors->first('image') }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">اسم الدواء <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name"
                            value="{{ old('form_type') == 'create' ? old('name') : '' }}"
                            class="w-full bg-slate-50 border {{ $errors->has('name') && old('form_type') == 'create' ? 'border-red-400 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-primary/20 focus:border-primary' }} rounded-2xl px-5 py-3.5 text-sm focus:bg-white focus:ring-2 transition-all outline-none shadow-sm"
                            placeholder="مثال: بانادول اكسترا">
                        @if ($errors->has('name') && old('form_type') == 'create')
                            <p class="text-red-500 text-xs font-bold mt-2 ml-1"><i
                                    class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">القسم (التصنيف) <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="category_id"
                                class="w-full bg-slate-50 border {{ $errors->has('category_id') && old('form_type') == 'create' ? 'border-red-400 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-primary/20 focus:border-primary' }} rounded-2xl px-5 py-3.5 text-sm focus:bg-white focus:ring-2 transition-all outline-none appearance-none shadow-sm cursor-pointer">
                                <option value="" disabled selected>اختر القسم المناسب...</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('form_type') == 'create' && old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <i
                                class="fa-solid fa-chevron-down absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        </div>
                        @if ($errors->has('category_id') && old('form_type') == 'create')
                            <p class="text-red-500 text-xs font-bold mt-2 ml-1"><i
                                    class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $errors->first('category_id') }}
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">وصف الدواء <span
                                class="text-red-500">*</span></label>
                        <textarea name="description" rows="3"
                            class="w-full bg-slate-50 border {{ $errors->has('description') && old('form_type') == 'create' ? 'border-red-400 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-primary/20 focus:border-primary' }} rounded-2xl px-5 py-3.5 text-sm focus:bg-white focus:ring-2 transition-all outline-none resize-none shadow-sm"
                            placeholder="اكتب وصفاً لدواعي الاستعمال...">{{ old('form_type') == 'create' ? old('description') : '' }}</textarea>
                        @if ($errors->has('description') && old('form_type') == 'create')
                            <p class="text-red-500 text-xs font-bold mt-2 ml-1"><i
                                    class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $errors->first('description') }}
                            </p>
                        @endif
                    </div>

                    <div
                        class="flex items-center justify-between bg-emerald-50/50 p-4 rounded-2xl border border-emerald-100">
                        <div class="flex flex-col">
                            <label class="text-sm font-bold text-emerald-800">إتاحة الدواء للبيع</label>
                            <span class="text-[10px] text-emerald-600 font-medium mt-1">سيظهر للمرضى ويمكن طلبه
                                فوراً</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer group/switch">
                            <input type="checkbox" name="status" value="1" class="sr-only peer"
                                {{ old('form_type') == 'create' && !old('status') ? '' : 'checked' }}>
                            <div
                                class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-emerald-500 transition-colors shadow-inner group-hover/switch:bg-gray-400 peer-checked:group-hover/switch:bg-emerald-600">
                            </div>
                            <div
                                class="absolute right-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:-translate-x-5 shadow-sm">
                            </div>
                        </label>
                    </div>
                </div>

                <div
                    class="p-6 md:p-8 bg-slate-50/80 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" onclick="toggleModal('createMedicineModal')"
                        class="px-6 py-3.5 rounded-2xl font-bold text-slate-500 bg-white border border-gray-200 hover:bg-slate-50 transition-colors text-sm shadow-sm">إلغاء</button>
                    <button type="submit"
                        class="px-8 py-3.5 rounded-2xl font-bold text-white bg-primary hover:bg-primaryDark shadow-lg shadow-primary/30 transition-all text-sm flex items-center gap-2">
                        <i class="fa-solid fa-check"></i>
                        حفظ الدواء
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="editMedicineModal" class="fixed inset-0 z-50 hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
            onclick="toggleModal('editMedicineModal')"></div>
        <div
            class="relative bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden animate-scale-up flex flex-col max-h-[90vh]">

            <div class="p-6 md:p-8 border-b border-gray-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                <h3 class="text-xl font-black text-slate-800">تعديل الدواء</h3>
                <button type="button" onclick="toggleModal('editMedicineModal')"
                    class="w-10 h-10 rounded-2xl bg-white border border-gray-200 text-slate-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition-all flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="editMedicineForm" action="{{ old('form_type') == 'edit' ? old('update_url') : '' }}"
                method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_type" value="edit">
                <input type="hidden" id="editUpdateUrl" name="update_url" value="{{ old('update_url') }}">

                <div class="p-6 md:p-8 space-y-6 overflow-y-auto scrollbar-thin">

                    <div class="flex flex-col items-center justify-center">
                        <label class="text-sm font-bold text-slate-700 mb-3 text-center">تحديث صورة الدواء</label>
                        <div
                            class="relative w-32 h-32 rounded-full border-4 border-dashed {{ $errors->has('image') && old('form_type') == 'edit' ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-slate-50' }} flex flex-col items-center justify-center text-center hover:border-primary/50 transition-colors group shadow-inner">
                            <input type="file" id="editImageInput" name="image"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*"
                                onchange="previewImage(this, 'editImagePreview', 'editPreviewContainer', 'editDefaultState')">

                            <div id="editDefaultState"
                                class="absolute inset-0 flex flex-col items-center justify-center transition-opacity duration-300 z-10 pointer-events-none {{ old('image_preview_url') && old('form_type') == 'edit' ? 'hidden' : '' }}">
                                <i
                                    class="fa-solid fa-image text-3xl {{ $errors->has('image') && old('form_type') == 'edit' ? 'text-red-400' : 'text-gray-400' }} group-hover:text-primary transition-colors mb-1"></i>
                                <span class="text-[10px] font-bold text-gray-500">تغيير الصورة</span>
                            </div>

                            <div id="editPreviewContainer"
                                class="absolute inset-0 {{ old('image_preview_url') && old('form_type') == 'edit' ? '' : 'hidden' }} w-full h-full bg-white rounded-full z-10 pointer-events-none">
                                <input type="hidden" id="editImagePreviewUrl" name="image_preview_url"
                                    value="{{ old('image_preview_url') }}">
                                <img id="editImagePreview"
                                    src="{{ old('form_type') == 'edit' ? old('image_preview_url') : '' }}"
                                    class="w-full h-full object-cover rounded-full" alt="Preview">
                                <div
                                    class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-[1px] rounded-full">
                                    <i class="fa-solid fa-pen text-white text-xl mb-1"></i>
                                    <span class="text-white font-bold text-[10px]">تغيير</span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('image') && old('form_type') == 'edit')
                            <p class="text-red-500 text-xs font-bold mt-3 bg-red-50 px-3 py-1 rounded-lg"><i
                                    class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $errors->first('image') }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">اسم الدواء <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="editNameInput" name="name"
                            value="{{ old('form_type') == 'edit' ? old('name') : '' }}"
                            class="w-full bg-slate-50 border {{ $errors->has('name') && old('form_type') == 'edit' ? 'border-red-400 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-primary/20 focus:border-primary' }} rounded-2xl px-5 py-3.5 text-sm focus:bg-white focus:ring-2 transition-all outline-none shadow-sm">
                        @if ($errors->has('name') && old('form_type') == 'edit')
                            <p class="text-red-500 text-xs font-bold mt-2 ml-1"><i
                                    class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">القسم (التصنيف) <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="editCategorySelect" name="category_id"
                                class="w-full bg-slate-50 border {{ $errors->has('category_id') && old('form_type') == 'edit' ? 'border-red-400 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-primary/20 focus:border-primary' }} rounded-2xl px-5 py-3.5 text-sm focus:bg-white focus:ring-2 transition-all outline-none appearance-none shadow-sm cursor-pointer">
                                <option value="" disabled>اختر القسم المناسب...</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('form_type') == 'edit' && old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <i
                                class="fa-solid fa-chevron-down absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        </div>
                        @if ($errors->has('category_id') && old('form_type') == 'edit')
                            <p class="text-red-500 text-xs font-bold mt-2 ml-1"><i
                                    class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $errors->first('category_id') }}
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">وصف الدواء <span
                                class="text-red-500">*</span></label>
                        <textarea id="editDescInput" name="description" rows="3"
                            class="w-full bg-slate-50 border {{ $errors->has('description') && old('form_type') == 'edit' ? 'border-red-400 focus:ring-red-500/20 focus:border-red-500' : 'border-gray-200 focus:ring-primary/20 focus:border-primary' }} rounded-2xl px-5 py-3.5 text-sm focus:bg-white focus:ring-2 transition-all outline-none resize-none shadow-sm">{{ old('form_type') == 'edit' ? old('description') : '' }}</textarea>
                        @if ($errors->has('description') && old('form_type') == 'edit')
                            <p class="text-red-500 text-xs font-bold mt-2 ml-1"><i
                                    class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $errors->first('description') }}
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center justify-between bg-blue-50/50 p-4 rounded-2xl border border-blue-100">
                        <div class="flex flex-col">
                            <label class="text-sm font-bold text-blue-800">إتاحة الدواء للبيع</label>
                            <span class="text-[10px] text-blue-600 font-medium mt-1">تحديد ما إذا كان متوفراً للمرضى</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer group/switch">
                            <input type="checkbox" id="editStatusInput" name="status" value="1"
                                class="sr-only peer" {{ old('form_type') == 'edit' && !old('status') ? '' : 'checked' }}>
                            <div
                                class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-blue-500 transition-colors shadow-inner group-hover/switch:bg-gray-400 peer-checked:group-hover/switch:bg-blue-600">
                            </div>
                            <div
                                class="absolute right-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:-translate-x-5 shadow-sm">
                            </div>
                        </label>
                    </div>
                </div>

                <div
                    class="p-6 md:p-8 bg-slate-50/80 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" onclick="toggleModal('editMedicineModal')"
                        class="px-6 py-3.5 rounded-2xl font-bold text-slate-500 bg-white border border-gray-200 hover:bg-slate-50 transition-colors text-sm shadow-sm">إلغاء</button>
                    <button type="submit"
                        class="px-8 py-3.5 rounded-2xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all text-sm flex items-center gap-2">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        تحديث البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteMedicineModal" class="fixed inset-0 z-50 hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
            onclick="toggleModal('deleteMedicineModal')"></div>
        <div
            class="relative bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-scale-up text-center p-8 md:p-10">
            <div
                class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500 text-4xl shadow-inner border-[6px] border-red-100">
                <i class="fa-solid fa-trash-can animate-bounce-short"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2">تأكيد الحذف</h3>
            <p class="text-sm text-gray-500 font-medium mb-8 leading-relaxed">هل أنت متأكد من رغبتك في حذف هذا الدواء
                نهائياً؟<br><span class="text-red-400">لا يمكن التراجع عن هذا الإجراء!</span></p>

            <form id="deleteMedicineForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <button type="button" onclick="toggleModal('deleteMedicineModal')"
                        class="w-full sm:flex-1 px-6 py-4 rounded-2xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-sm">إلغاء
                        الأمر</button>
                    <button type="submit"
                        class="w-full sm:flex-1 px-6 py-4 rounded-2xl font-bold text-white bg-red-500 hover:bg-red-600 shadow-lg shadow-red-500/30 transition-all text-sm">نعم،
                        احذف</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Toggler
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Keep Modal Open on Validation Failure
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                @if (old('form_type') == 'create')
                    toggleModal('createMedicineModal');
                @elseif (old('form_type') == 'edit')
                    toggleModal('editMedicineModal');
                @endif
            @endif

            // تهيئة العداد عند تحميل الصفحة
            filterTableRows();
        });

        // Live Image Preview Function
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

        // Setup Edit Modal data
        function openEditModal(medicine, imageUrl) {
            let form = document.getElementById('editMedicineForm');
            let formUrl = `/admin/medicines/${medicine.id}`;

            form.action = formUrl;
            document.getElementById('editUpdateUrl').value = formUrl;
            document.getElementById('editImagePreviewUrl').value = imageUrl;

            document.getElementById('editNameInput').value = medicine.name;
            document.getElementById('editDescInput').value = medicine.description || '';
            document.getElementById('editCategorySelect').value = medicine.category_id;
            document.getElementById('editStatusInput').checked = (medicine.status == 1);

            let previewImage = document.getElementById('editImagePreview');
            let previewContainer = document.getElementById('editPreviewContainer');
            let defaultState = document.getElementById('editDefaultState');

            if (medicine.image) {
                previewImage.src = imageUrl;
                previewContainer.classList.remove('hidden');
                defaultState.classList.add('hidden');
            } else {
                previewImage.src = '';
                previewContainer.classList.add('hidden');
                defaultState.classList.remove('hidden');
            }

            toggleModal('editMedicineModal');
        }

        // Setup Delete Modal
        function openDeleteModal(id) {
            let form = document.getElementById('deleteMedicineForm');
            form.action = `/admin/medicines/${id}`;
            toggleModal('deleteMedicineModal');
        }

        // Dynamic Toast function
        function showDynamicToast(type, message) {
            let container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.style.marginBottom = '0.75rem';

            if (type === 'success') {
                toast.className =
                    'animate-toast pointer-events-auto bg-white border-r-4 border-emerald-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto text-right';
                toast.innerHTML = `
                    <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600 shrink-0"><i class="fa-solid fa-circle-check text-xl"></i></div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">تمت العملية</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">${message}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0"><i class="fa-solid fa-xmark"></i></button>
                `;
            } else {
                toast.className =
                    'animate-toast pointer-events-auto bg-white border-r-4 border-rose-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto text-right';
                toast.innerHTML = `
                    <div class="bg-rose-100 p-2 rounded-xl text-rose-600 shrink-0"><i class="fa-solid fa-circle-exclamation text-xl"></i></div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">خطأ في النظام</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">${message}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0"><i class="fa-solid fa-xmark"></i></button>
                `;
            }

            container.appendChild(toast);
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-10px)';
                    toast.style.transition = 'all 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 3000);
        }

        // AJAX Status Toggle
        function toggleMedicineStatus(medicineId, checkbox) {
            const csrfToken = '{{ csrf_token() }}';
            const row = checkbox.closest('tr');

            fetch(`/admin/medicines/${medicineId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        row.setAttribute('data-status', data.status);

                        if (data.status == 1) {
                            row.classList.remove('opacity-60', 'grayscale-[20%]');
                        } else {
                            row.classList.add('opacity-60', 'grayscale-[20%]');
                        }
                        showDynamicToast('success', data.message || 'تم تحديث حالة الدواء بنجاح.');

                        // إعادة تشغيل الفلتر لتحديث الجدول والعداد إذا كان المستخدم مفعل فلتر الحالة
                        filterTableRows();
                    } else {
                        checkbox.checked = !checkbox.checked;
                        showDynamicToast('error', 'فشل في تحديث الحالة.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    checkbox.checked = !checkbox.checked;
                    showDynamicToast('error', 'حدث خطأ في الاتصال بالخادم.');
                });
        }

        // Advanced Filter & Counter Logic
        function filterTableRows() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const categoryFilter = document.getElementById('categoryFilter').value;

            const rows = document.querySelectorAll('.medicine-row');
            const noResultsRow = document.getElementById('noResultsRow');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const status = row.getAttribute('data-status');
                const category = row.getAttribute('data-category');

                const matchesSearch = name.includes(searchInput);
                const matchesStatus = statusFilter === 'all' || status === statusFilter;
                const matchesCategory = categoryFilter === 'all' || category === categoryFilter;

                if (matchesSearch && matchesStatus && matchesCategory) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // تحديث عداد النتائج في الهيدر
            const counterElement = document.getElementById('resultCounter');
            if (counterElement) {
                counterElement.innerHTML = visibleCount > 0 ? visibleCount + ' دواء' : 'لا يوجد';

                // إضافة لمسة جمالية للعداد عند تغيير الرقم
                counterElement.classList.add('scale-110', 'bg-blue-200');
                setTimeout(() => {
                    counterElement.classList.remove('scale-110', 'bg-blue-200');
                }, 200);
            }

            if (visibleCount === 0) {
                noResultsRow.style.display = '';
                const defaultEmpty = document.getElementById('defaultEmptyRow');
                if (defaultEmpty) defaultEmpty.style.display = 'none';
            } else {
                noResultsRow.style.display = 'none';
            }
        }

        document.getElementById('searchInput').addEventListener('input', filterTableRows);
        document.getElementById('statusFilter').addEventListener('change', filterTableRows);
        document.getElementById('categoryFilter').addEventListener('change', filterTableRows);
    </script>

    <style>
        /* Animations */
        .animate-scale-up {
            animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes scaleUp {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-toast {
            animation: toastSlideIn 0.4s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        @keyframes toastSlideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-bounce-short {
            animation: bounceShort 1s ease-in-out infinite;
        }

        @keyframes bounceShort {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10%);
            }
        }
    </style>
@endsection
