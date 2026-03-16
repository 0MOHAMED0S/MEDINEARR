@extends('dashboard.layout.master')
@section('content')
    <div class="p-4 md:p-6 lg:p-8 relative z-0">

        <div id="ajax-toast-container" class="fixed top-4 left-1/2 -translate-x-1/2 md:left-auto md:translate-x-0 md:right-8 z-[999999] flex flex-col gap-3 pointer-events-none w-max max-w-[90vw]">
            @if (session('success'))
                <div class="animate-toast pointer-events-auto bg-white border-r-4 border-emerald-500 shadow-[0_10px_40px_rgba(0,0,0,0.1)] rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm text-right mb-3">
                    <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600 shrink-0">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">تمت العملية</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">{{ session('success') }}</p>
                    </div>
                    <button type="button" onclick="this.closest('.animate-toast').remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if (session('error') || $errors->any())
                <div class="animate-toast pointer-events-auto bg-white border-r-4 border-rose-500 shadow-[0_10px_40px_rgba(0,0,0,0.1)] rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm text-right mb-3">
                    <div class="bg-rose-100 p-2 rounded-xl text-rose-600 shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">خطأ في النظام</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">
                            {{ session('error') ?? $errors->first() }}
                        </p>
                    </div>
                    <button type="button" onclick="this.closest('.animate-toast').remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 md:mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">إدارة الحسابات</h2>
                <p class="text-xs md:text-sm text-gray-500 font-medium mt-1">عرض وتعديل حسابات المدراء، الصيدليات، والمستخدمين العاديين</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-8">
            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md hover:border-primary/20 group">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-50 text-blue-600 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform"><i class="fa-solid fa-users"></i></div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-sm text-gray-500 font-bold truncate mb-1">إجمالي الحسابات</p>
                    <h3 class="text-xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['total'] }}</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md hover:border-emerald-200 group">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-emerald-50 text-emerald-500 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform"><i class="fa-solid fa-user-check"></i></div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-sm text-gray-500 font-bold truncate mb-1">حسابات نشطة</p>
                    <h3 id="stat-active" class="text-xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['active'] }}</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md hover:border-rose-200 group">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-rose-50 text-rose-500 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform"><i class="fa-solid fa-user-lock"></i></div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-sm text-gray-500 font-bold truncate mb-1">حسابات محظورة</p>
                    <h3 id="stat-inactive" class="text-xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['blocked'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden transition-all">
            <div class="p-5 md:p-6 border-b border-gray-50 flex flex-col xl:flex-row items-start xl:items-center justify-between gap-6 bg-slate-50/30">

                <div class="flex flex-col gap-1 shrink-0">
                    <div class="flex items-center gap-3">
                        <h3 class="font-black text-slate-800 text-base md:text-lg tracking-tight">سجل الأعضاء</h3>
                        <span class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1.5 rounded-full transition-all uppercase tracking-wider">{{ $users->total() }} نتيجة</span>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row items-center gap-4 w-full xl:w-auto">

                    <div class="relative shrink-0">
                        @php $currentStatus = request('status', 'all'); @endphp
                        <select onchange="setServerFilter('status', this.value)" class="appearance-none bg-white border border-gray-200 text-slate-600 text-xs font-bold rounded-xl px-4 py-2.5 pr-8 outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary shadow-sm cursor-pointer transition-all">
                            <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>جميع الحالات</option>
                            <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>حسابات نشطة</option>
                            <option value="blocked" {{ $currentStatus === 'blocked' ? 'selected' : '' }}>حسابات محظورة</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                        @php $currentRole = request('role', 'all'); @endphp

                        <button type="button" onclick="setServerFilter('role', 'all')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ $currentRole === 'all' ? 'bg-primary text-white shadow-sm shadow-primary/30 border border-primary' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50' }}">الكل</button>

                        <button type="button" onclick="setServerFilter('role', 'admin')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ $currentRole === 'admin' ? 'bg-primary text-white shadow-sm shadow-primary/30 border border-primary' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50' }}">المدراء</button>

                        <button type="button" onclick="setServerFilter('role', 'pharmacy')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ $currentRole === 'pharmacy' ? 'bg-primary text-white shadow-sm shadow-primary/30 border border-primary' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50' }}">الصيدليات</button>

                        <button type="button" onclick="setServerFilter('role', 'user')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ $currentRole === 'user' ? 'bg-primary text-white shadow-sm shadow-primary/30 border border-primary' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50' }}">المستخدمين</button>
                    </div>

                    <div class="relative w-full lg:w-64 shrink-0">
                        <input type="text" id="serverSearchInput" value="{{ request('search') }}" placeholder="ابحث بالاسم، البريد..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-xs focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none text-slate-700 shadow-sm">
                        <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        @if(request('search'))
                            <button type="button" onclick="setServerFilter('search', '')" class="absolute left-3 top-1/2 -translate-y-1/2 text-rose-400 hover:text-rose-600 text-[10px] font-bold">إلغاء</button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-right min-w-[900px]">
                    <thead class="bg-slate-50 border-b border-gray-100 text-slate-500 text-[10px] md:text-[11px] uppercase tracking-wider font-bold">
                        <tr>
                            <th class="p-4 md:p-5 w-16 text-center">#</th>
                            <th class="p-4 md:p-5">المستخدم</th>
                            <th class="p-4 md:p-5">معلومات الاتصال</th>
                            <th class="p-4 md:p-5 w-32 text-center">تاريخ الانضمام</th>
                            <th class="p-4 md:p-5 w-32 text-center">حالة الحساب</th>
                            <th class="p-4 md:p-5 w-36 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $index => $user)
                            @php
                                $isActive = $user->is_active ?? true;
                                $avatar = $user->photo ?? $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f1f5f9&color=475569';
                                $isNew = $user->created_at && $user->created_at->diffInHours(now()) <= 24;

                                if($user->role === 'admin') {
                                    $roleColor = 'bg-purple-50 text-purple-600 border-purple-200';
                                    $roleText = 'مدير نظام';
                                } elseif($user->role === 'pharmacy') {
                                    $roleColor = 'bg-teal-50 text-teal-600 border-teal-200';
                                    $roleText = 'صيدلية شريكة';
                                } else {
                                    $roleColor = 'bg-blue-50 text-blue-600 border-blue-200';
                                    $roleText = 'مستخدم عادي';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50 transition-all duration-300 {{ !$isActive ? 'bg-rose-50/30' : '' }}" data-status="{{ $isActive ? '1' : '0' }}">
                                <td class="p-4 md:p-5 font-bold text-slate-400 text-xs text-center">{{ $users->firstItem() + $index }}</td>

                                <td class="p-4 md:p-5">
                                    <div class="flex items-center gap-3 md:gap-4">
                                        <img src="{{ $avatar }}" alt="{{ $user->name }}" class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover border border-gray-200 shadow-sm shrink-0">
                                        <div>
                                            <h4 class="font-black text-slate-800 text-sm mb-1 flex items-center flex-wrap gap-2">
                                                {{ $user->name }}
                                                @if($isNew)
                                                    <span class="flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 text-amber-600 border border-amber-200 text-[9px] font-black shadow-sm" title="مستخدم جديد">
                                                        <span class="relative flex h-1.5 w-1.5">
                                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                          <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-amber-500"></span>
                                                        </span>
                                                        جديد
                                                    </span>
                                                @endif
                                                @if($user->provider_type) <i class="fa-brands fa-{{ $user->provider_type }} text-[10px] text-gray-400" title="مسجل عبر {{ ucfirst($user->provider_type) }}"></i> @endif
                                                @if($user->role === 'admin') <i class="fa-solid fa-shield-halved text-[10px] text-purple-500" title="حساب محمي"></i> @endif
                                            </h4>
                                            <span class="text-[9px] md:text-[10px] font-bold px-2 py-0.5 rounded-md border {{ $roleColor }}">{{ $roleText }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-4 md:p-5">
                                    <div class="flex flex-col space-y-1">
                                        @if($user->email)
                                            <div class="flex items-center gap-2 text-xs text-slate-600 font-medium font-mono" dir="ltr">
                                                <i class="fa-solid fa-envelope text-gray-400 w-3 text-center"></i>
                                                <span class="truncate max-w-[150px]" title="{{ $user->email }}">{{ $user->email }}</span>
                                            </div>
                                        @endif
                                        @if($user->phone)
                                            <div class="flex items-center gap-2 text-xs text-slate-600 font-medium font-mono" dir="ltr">
                                                <i class="fa-solid fa-phone text-gray-400 w-3 text-center"></i>
                                                <span>{{ $user->phone }}</span>
                                            </div>
                                        @endif
                                        @if(!$user->email && !$user->phone)
                                            <span class="text-xs text-gray-400 italic">لا توجد وسيلة تواصل</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="p-4 md:p-5 text-center">
                                    <span class="text-xs font-bold text-slate-500 font-mono">{{ $user->created_at->format('Y-m-d') }}</span>
                                </td>

                                <td class="p-4 md:p-5 text-center">
                                    @if($user->role === 'admin')
                                        <span class="text-[10px] font-black uppercase text-slate-400 bg-slate-100 px-2 py-1 rounded-md border border-slate-200">غير قابل للحظر</span>
                                    @else
                                        <button type="button"
                                                id="toggle-btn-{{ $user->id }}"
                                                onclick="openToggleConfirmModal({{ $user->id }}, '{{ explode(' ', $user->name)[0] }}', {{ $isActive ? 'true' : 'false' }})"
                                                class="relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none {{ $isActive ? 'bg-emerald-500' : 'bg-rose-500' }}">
                                            <span id="toggle-knob-{{ $user->id }}" class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 ease-in-out {{ $isActive ? '-translate-x-7' : '-translate-x-1' }} flex items-center justify-center shadow-sm">
                                                  <i id="toggle-icon-{{ $user->id }}" class="fa-solid {{ $isActive ? 'fa-check text-emerald-500' : 'fa-lock text-rose-500' }} text-[8px]"></i>
                                            </span>
                                        </button>
                                    @endif
                                </td>

                                <td class="p-4 md:p-5 text-center">
                                    <div class="flex items-center justify-center gap-1.5 md:gap-2">
                                        <button onclick='openShowModal(@json($user), "{{ $avatar }}")' class="w-8 h-8 md:w-9 md:h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-teal-600 hover:bg-teal-50 hover:border-teal-200 transition-all flex items-center justify-center shadow-sm tooltip" title="عرض التفاصيل">
                                            <i class="fa-solid fa-eye text-xs md:text-sm"></i>
                                        </button>

                                        <button onclick='openEditModal(@json($user), "{{ $avatar }}")' class="w-8 h-8 md:w-9 md:h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition-all flex items-center justify-center shadow-sm tooltip" title="تعديل">
                                            <i class="fa-solid fa-pen-to-square text-xs md:text-sm"></i>
                                        </button>

                                        @if($user->role === 'admin')
                                            <button type="button" class="w-8 h-8 md:w-9 md:h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-300 cursor-not-allowed flex items-center justify-center tooltip" title="حساب محمي من الحذف">
                                                <i class="fa-solid fa-trash-can text-xs md:text-sm"></i>
                                            </button>
                                        @else
                                            <button onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')" class="w-8 h-8 md:w-9 md:h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-all flex items-center justify-center shadow-sm tooltip" title="حذف نهائي">
                                                <i class="fa-solid fa-trash-can text-xs md:text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-16 md:p-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        @if(request('search') || request('role') || request('status'))
                                            <i class="fa-solid fa-magnifying-glass text-3xl md:text-4xl mb-3 md:mb-4 text-gray-200"></i>
                                            <p class="font-bold text-base md:text-lg text-slate-600">لا توجد نتائج مطابقة لبحثك</p>
                                            <a href="{{ route('users.index') }}" class="mt-4 text-sm text-primary hover:underline">إلغاء جميع الفلاتر</a>
                                        @else
                                            <i class="fa-solid fa-users-slash text-3xl md:text-4xl mb-3 md:mb-4 text-gray-200"></i>
                                            <p class="font-bold text-base md:text-lg text-slate-600">لا يوجد مستخدمين مسجلين</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <p class="text-xs font-bold text-slate-500">
                            عرض <span class="text-slate-800">{{ $users->firstItem() }}</span> إلى <span class="text-slate-800">{{ $users->lastItem() }}</span> من إجمالي <span class="text-slate-800">{{ $users->total() }}</span> نتيجة
                        </p>
                        <div class="pagination-wrapper custom-pagination">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div id="showUserModal" class="fixed inset-0 z-50 hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('showUserModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden animate-scale-up flex flex-col">

            <div id="show_header_color" class="absolute top-0 inset-x-0 h-32 transition-colors z-0"></div>

            <div class="relative z-10 flex flex-col">
                <div class="p-4 flex justify-start">
                    <button type="button" onclick="toggleModal('showUserModal')" class="w-10 h-10 rounded-2xl bg-white/20 text-white hover:bg-white hover:text-rose-500 transition-all flex items-center justify-center backdrop-blur-sm shadow-sm">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="px-8 pb-8 text-center mt-2">
                    <img id="show_avatar" src="" class="w-28 h-28 rounded-full border-[6px] border-white shadow-xl object-cover mx-auto bg-white mb-4 relative z-20">
                    <h3 id="show_name" class="text-2xl font-black text-slate-800 mb-2"></h3>
                    <div class="flex justify-center mb-6">
                        <span id="show_role" class="inline-flex items-center gap-1.5 text-[10px] font-bold px-3 py-1.5 rounded-xl border"></span>
                    </div>

                    <div class="space-y-4 text-right">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-slate-400 shrink-0"><i class="fa-solid fa-envelope text-lg"></i></div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-0.5">البريد الإلكتروني</p>
                                <p id="show_email" class="text-sm font-bold text-slate-700 truncate font-mono" dir="ltr"></p>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-slate-400 shrink-0"><i class="fa-solid fa-phone text-lg"></i></div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-0.5">رقم الهاتف</p>
                                <p id="show_phone" class="text-sm font-bold text-slate-700 font-mono" dir="ltr"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100 flex flex-col items-center justify-center text-center">
                                <i class="fa-regular fa-calendar text-slate-400 mb-2"></i>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">تاريخ الانضمام</p>
                                <p id="show_date" class="text-xs font-bold text-slate-700 font-mono"></p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100 flex flex-col items-center justify-center text-center">
                                <i class="fa-solid fa-right-to-bracket text-slate-400 mb-2"></i>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">طريقة التسجيل</p>
                                <p id="show_provider" class="text-xs font-bold text-slate-700 capitalize"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editUserModal" class="fixed inset-0 z-50 hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('editUserModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden animate-scale-up flex flex-col max-h-[90vh]">

            <div id="edit_header_color" class="absolute top-0 inset-x-0 h-32 transition-colors duration-500 z-0">
                <div class="absolute inset-0 bg-black/10"></div>
            </div>

            <form id="editUserForm" action="" method="POST" class="relative z-10 flex flex-col flex-1 overflow-hidden" onsubmit="disableSubmitButton(this)">
                @csrf @method('PUT')

                <div class="p-4 flex justify-start shrink-0">
                    <button type="button" onclick="toggleModal('editUserModal')" class="w-10 h-10 rounded-2xl bg-white/20 text-white hover:bg-white hover:text-rose-500 transition-all flex items-center justify-center backdrop-blur-sm shadow-sm">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="px-8 pb-4 text-center shrink-0">
                    <img id="edit_avatar" src="" class="w-24 h-24 rounded-full border-[6px] border-white shadow-xl object-cover mx-auto bg-white relative z-30 mb-3">
                    <h3 class="text-xl font-black text-slate-800">تعديل بيانات الحساب</h3>
                </div>

                <div class="px-6 md:px-8 pb-6 space-y-6 overflow-y-auto scrollbar-thin text-right flex-grow">
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-3">نوع الصلاحية <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="cursor-pointer group">
                                <input type="radio" name="role" value="admin" id="role_admin" class="peer sr-only" onchange="updateEditHeaderColor()">
                                <div class="h-full p-3 bg-white border-2 border-gray-100 rounded-2xl text-center peer-checked:border-purple-500 peer-checked:bg-purple-50 hover:border-purple-300 transition-all">
                                    <i class="fa-solid fa-shield-halved text-xl text-gray-300 peer-checked:text-purple-500 group-hover:text-purple-400 mb-2 transition-colors"></i>
                                    <p class="font-bold text-[11px] text-gray-500 peer-checked:text-purple-700">مدير نظام</p>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="role" value="pharmacy" id="role_pharmacy" class="peer sr-only" onchange="updateEditHeaderColor()">
                                <div class="h-full p-3 bg-white border-2 border-gray-100 rounded-2xl text-center peer-checked:border-teal-500 peer-checked:bg-teal-50 hover:border-teal-300 transition-all">
                                    <i class="fa-solid fa-store text-xl text-gray-300 peer-checked:text-teal-500 group-hover:text-teal-400 mb-2 transition-colors"></i>
                                    <p class="font-bold text-[11px] text-gray-500 peer-checked:text-teal-700">صيدلية</p>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="role" value="user" id="role_user" class="peer sr-only" onchange="updateEditHeaderColor()">
                                <div class="h-full p-3 bg-white border-2 border-gray-100 rounded-2xl text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-blue-300 transition-all">
                                    <i class="fa-solid fa-user text-xl text-gray-300 peer-checked:text-blue-500 group-hover:text-blue-400 mb-2 transition-colors"></i>
                                    <p class="font-bold text-[11px] text-gray-500 peer-checked:text-blue-700">مستخدم</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-2 uppercase tracking-wide">الاسم بالكامل <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="text" id="edit_name" name="name" required class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3.5 pr-10 text-sm focus:bg-white focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                            <i class="fa-regular fa-user absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-2 uppercase tracking-wide">البريد الإلكتروني</label>
                            <div class="relative">
                                <input type="email" id="edit_email" name="email" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3.5 pl-10 text-sm focus:bg-white focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all outline-none font-mono text-left" dir="ltr">
                                <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-2 uppercase tracking-wide">رقم الهاتف</label>
                            <div class="relative">
                                <input type="text" id="edit_phone" name="phone" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3.5 pl-10 text-sm focus:bg-white focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all outline-none font-mono text-left" dir="ltr">
                                <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-gray-200">
                        <label class="block text-xs font-black text-slate-700 mb-3 uppercase tracking-wide"><i class="fa-solid fa-map-location-dot mr-1"></i> بيانات الموقع (الإحداثيات)</label>
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <label class="block text-[10px] text-gray-500 mb-1">خط العرض (Latitude)</label>
                                <input type="text" id="edit_lat" name="latitude" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none font-mono text-left" dir="ltr" oninput="updateGovernorateDisplay()">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-500 mb-1">خط الطول (Longitude)</label>
                                <input type="text" id="edit_lng" name="longitude" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none font-mono text-left" dir="ltr" oninput="updateGovernorateDisplay()">
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mt-2 pt-3 border-t border-gray-200">
                            <span class="text-xs text-gray-500">المحافظة التقريبية:</span>
                            <span id="edit_governorate_display" class="bg-indigo-50 text-indigo-600 px-2 py-1 rounded text-xs font-bold">جاري التحديد...</span>
                        </div>
                    </div>

                    <div class="bg-amber-50 p-4 rounded-xl border border-amber-100">
                        <label class="block text-xs font-black text-amber-800 mb-2 uppercase tracking-wide"><i class="fa-solid fa-key mr-1"></i> إعادة تعيين كلمة المرور</label>
                        <input type="password" name="password" placeholder="اتركه فارغاً لعدم التغيير..." minlength="8" class="w-full bg-white border border-amber-200 rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none placeholder:text-amber-300">
                    </div>

                </div>

                <div class="p-6 md:px-8 bg-slate-50 border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" onclick="toggleModal('editUserModal')" class="px-6 py-3.5 rounded-xl font-bold text-slate-500 bg-white border border-gray-200 hover:bg-slate-50 transition-colors text-sm">إلغاء</button>
                    <button type="submit" class="px-8 py-3.5 rounded-xl font-bold text-white bg-slate-800 hover:bg-black shadow-lg shadow-slate-800/30 transition-all text-sm flex items-center gap-2">
                        <i class="fa-solid fa-check"></i> حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="toggleConfirmModal" class="fixed inset-0 z-[110] hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('toggleConfirmModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-scale-up text-center p-8">
            <div id="tc-icon-bg" class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5 text-3xl shadow-inner border-[6px]">
                <i id="tc-icon" class="fa-solid animate-bounce-short"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2" id="tc-title">تأكيد الإجراء</h3>
            <p id="tc-message" class="text-sm text-gray-500 font-medium mb-8 leading-relaxed"></p>

            <div class="flex items-center justify-center gap-3">
                <button type="button" onclick="toggleModal('toggleConfirmModal')" class="flex-1 px-4 py-3.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-sm">إلغاء الأمر</button>
                <button type="button" id="tc-confirm-btn" onclick="executeToggle()" class="flex-1 px-4 py-3.5 rounded-xl font-bold text-white shadow-lg transition-all text-sm">تأكيد</button>
            </div>
        </div>
    </div>

    <div id="deleteUserModal" class="fixed inset-0 z-50 hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('deleteUserModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-scale-up text-center p-8">
            <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-5 text-rose-500 text-3xl shadow-inner border-[6px] border-rose-100">
                <i class="fa-solid fa-user-xmark animate-bounce-short text-rose-500"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">حذف الحساب نهائياً؟</h3>
            <p id="del-message" class="text-sm text-gray-500 font-medium mb-8 leading-relaxed"></p>

            <form id="deleteUserForm" action="" method="POST" onsubmit="disableSubmitButton(this)">
                @csrf @method('DELETE')
                <div class="flex items-center justify-center gap-3">
                    <button type="button" onclick="toggleModal('deleteUserModal')" class="flex-1 px-4 py-3.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-sm">تراجع</button>
                    <button type="submit" class="flex-1 px-4 py-3.5 rounded-xl font-bold text-white bg-rose-500 hover:bg-rose-600 shadow-lg shadow-rose-500/30 transition-all text-sm">حذف نهائي</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentToggleId = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        // 1. نظام الفلترة والبحث عبر السيرفر (Server-side)
        function setServerFilter(param, value) {
            const url = new URL(window.location.href);

            // التعامل مع القيم الافتراضية "الكل" أو الحقول الفارغة لحذفها من الرابط
            if (value === 'all' && (param === 'role' || param === 'status')) {
                url.searchParams.delete(param);
            } else if (value === '' && param === 'search') {
                url.searchParams.delete('search');
            } else {
                url.searchParams.set(param, value);
            }

            url.searchParams.delete('page'); // تصفير رقم الصفحة عند تغيير الفلتر
            window.location.href = url.href;
        }

        // تفعيل زر Enter في مربع البحث
        document.getElementById('serverSearchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                setServerFilter('search', this.value);
            }
        });


        // 2. خريطة المحافظات والذكاء الجغرافي
        const governorates = {
            'cairo': { name: 'القاهرة', lat: 30.0444, lng: 31.2357, radius: 50 },
            'giza': { name: 'الجيزة', lat: 30.0131, lng: 31.2089, radius: 60 },
            'alexandria': { name: 'الإسكندرية', lat: 31.2001, lng: 29.9187, radius: 60 },
            'qalyubia': { name: 'القليوبية', lat: 30.4069, lng: 31.1862, radius: 40 },
            'sharqia': { name: 'الشرقية', lat: 30.5877, lng: 31.5020, radius: 60 },
            'dakahlia': { name: 'الدقهلية', lat: 31.0379, lng: 31.3815, radius: 60 },
            'gharbia': { name: 'الغربية', lat: 30.8754, lng: 31.0335, radius: 50 },
            'menofia': { name: 'المنوفية', lat: 30.5972, lng: 30.9876, radius: 50 },
            'kafr_el_sheikh': { name: 'كفر الشيخ', lat: 31.1107, lng: 30.9388, radius: 50 },
            'beheira': { name: 'البحيرة', lat: 31.0409, lng: 30.4700, radius: 70 },
            'damietta': { name: 'دمياط', lat: 31.4165, lng: 31.8133, radius: 40 },
            'port_said': { name: 'بورسعيد', lat: 31.2565, lng: 32.2841, radius: 40 },
            'ismailia': { name: 'الإسماعيلية', lat: 30.5965, lng: 32.2715, radius: 60 },
            'suez': { name: 'السويس', lat: 29.9668, lng: 32.5498, radius: 60 },
            'fayoum': { name: 'الفيوم', lat: 29.3084, lng: 30.8428, radius: 60 },
            'beni_suef': { name: 'بني سويف', lat: 29.0661, lng: 31.0994, radius: 60 },
            'minya': { name: 'المنيا', lat: 28.0871, lng: 30.7618, radius: 80 },
            'assiut': { name: 'أسيوط', lat: 27.1810, lng: 31.1837, radius: 70 },
            'sohag': { name: 'سوهاج', lat: 26.5591, lng: 31.6957, radius: 70 },
            'qena': { name: 'قنا', lat: 26.1551, lng: 32.7160, radius: 70 },
            'luxor': { name: 'الأقصر', lat: 25.6872, lng: 32.6396, radius: 50 },
            'aswan': { name: 'أسوان', lat: 24.0889, lng: 32.8998, radius: 100 },
            'red_sea': { name: 'البحر الأحمر', lat: 27.2579, lng: 33.8116, radius: 200 },
            'new_valley': { name: 'الوادي الجديد', lat: 25.4514, lng: 30.5463, radius: 200 },
            'matrouh': { name: 'مطروح', lat: 31.3525, lng: 27.2373, radius: 200 },
            'north_sinai': { name: 'شمال سيناء', lat: 31.1316, lng: 33.7984, radius: 150 },
            'south_sinai': { name: 'جنوب سيناء', lat: 28.2364, lng: 33.6254, radius: 150 }
        };

        function calculateDistance(lat1, lon1, lat2, lon2) {
            if (!lat1 || !lon1 || !lat2 || !lon2) return Infinity;
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a =
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        function updateGovernorateDisplay() {
            const lat = parseFloat(document.getElementById('edit_lat').value);
            const lng = parseFloat(document.getElementById('edit_lng').value);
            const displayEl = document.getElementById('edit_governorate_display');

            if (lat && lng && !isNaN(lat) && !isNaN(lng)) {
                let closestGovName = 'غير محدد';
                let minDistance = Infinity;

                for (const [key, gov] of Object.entries(governorates)) {
                    const dist = calculateDistance(gov.lat, gov.lng, lat, lng);
                    if (dist < minDistance && dist <= gov.radius) {
                        minDistance = dist;
                        closestGovName = gov.name;
                    }
                }
                displayEl.innerText = closestGovName;
                displayEl.className = closestGovName === 'غير محدد'
                    ? 'bg-rose-50 text-rose-600 px-2 py-1 rounded text-xs font-bold'
                    : 'bg-emerald-50 text-emerald-600 px-2 py-1 rounded text-xs font-bold';
            } else {
                displayEl.innerText = 'الإحداثيات غير متوفرة';
                displayEl.className = 'bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs font-bold';
            }
        }


        // 3. إدارة الموديلات (Modals)
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
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin ml-2"></i> جاري...';
            }
            return true;
        }

        const roleStyles = {
            admin: { bg: 'bg-gradient-to-r from-purple-600 to-purple-800', badge: 'bg-purple-50 text-purple-600 border-purple-200', text: 'مدير نظام', icon: 'fa-shield-halved' },
            pharmacy: { bg: 'bg-gradient-to-r from-teal-500 to-teal-700', badge: 'bg-teal-50 text-teal-600 border-teal-200', text: 'صيدلية شريكة', icon: 'fa-store' },
            user: { bg: 'bg-gradient-to-r from-blue-500 to-blue-700', badge: 'bg-blue-50 text-blue-600 border-blue-200', text: 'مستخدم عادي', icon: 'fa-user' }
        };

        function openShowModal(user, avatarUrl) {
            const role = user.role || 'user';
            const style = roleStyles[role];

            document.getElementById('show_header_color').className = `absolute top-0 inset-x-0 h-32 transition-colors z-0 ${style.bg}`;
            document.getElementById('show_avatar').src = avatarUrl;
            document.getElementById('show_name').innerText = user.name;

            const roleEl = document.getElementById('show_role');
            roleEl.innerHTML = `<i class="fa-solid ${style.icon}"></i> ${style.text}`;
            roleEl.className = `inline-flex items-center gap-1.5 text-[10px] font-bold px-3 py-1.5 rounded-xl border mb-6 ${style.badge}`;

            document.getElementById('show_email').innerText = user.email || 'غير متوفر';
            document.getElementById('show_phone').innerText = user.phone || 'غير متوفر';

            let date = new Date(user.created_at);
            document.getElementById('show_date').innerText = date.toLocaleDateString('en-CA');
            document.getElementById('show_provider').innerText = user.provider_type || 'تسجيل عادي';

            toggleModal('showUserModal');
        }

        function openEditModal(user, avatarUrl) {
            const form = document.getElementById('editUserForm');
            form.action = `/admin/users/${user.id}`;

            document.getElementById('edit_avatar').src = avatarUrl;
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_phone').value = user.phone || '';

            document.getElementById('edit_lat').value = user.latitude || '';
            document.getElementById('edit_lng').value = user.longitude || '';

            const role = user.role || 'user';
            document.getElementById(`role_${role}`).checked = true;
            updateEditHeaderColor();

            updateGovernorateDisplay();

            toggleModal('editUserModal');
        }

        function updateEditHeaderColor() {
            const selectedRole = document.querySelector('input[name="role"]:checked').value;
            const style = roleStyles[selectedRole];
            document.getElementById('edit_header_color').className = `absolute top-0 inset-x-0 h-32 transition-colors duration-500 z-0 ${style.bg}`;
        }

        function openDeleteModal(id, name) {
            document.getElementById('deleteUserForm').action = `/admin/users/${id}`;
            document.getElementById('del-message').innerHTML = `سيتم مسح جميع بيانات الحساب <strong>${name}</strong>. هذا الإجراء لا يمكن التراجع عنه.`;
            toggleModal('deleteUserModal');
        }


        // 4. نظام الحظر والتفعيل (Toggle Status AJAX)
        function openToggleConfirmModal(id, name, isCurrentlyActive) {
            currentToggleId = id;
            const iconBg = document.getElementById('tc-icon-bg');
            const icon = document.getElementById('tc-icon');
            const title = document.getElementById('tc-title');
            const message = document.getElementById('tc-message');
            const confirmBtn = document.getElementById('tc-confirm-btn');

            if (isCurrentlyActive) {
                iconBg.className = 'w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5 text-3xl shadow-inner border-[6px] bg-rose-50 text-rose-500 border-rose-100';
                icon.className = 'fa-solid fa-user-lock animate-bounce-short';
                title.innerText = 'حظر المستخدم';
                message.innerHTML = `هل أنت متأكد أنك تريد حظر حساب <strong>${name}</strong>؟<br>لن يتمكن من تسجيل الدخول للمنصة.`;
                confirmBtn.className = 'flex-1 px-4 py-3.5 rounded-xl font-bold text-white shadow-lg transition-all text-sm bg-rose-500 hover:bg-rose-600 shadow-rose-500/30';
                confirmBtn.innerText = 'نعم، قم بالحظر';
            } else {
                iconBg.className = 'w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5 text-3xl shadow-inner border-[6px] bg-emerald-50 text-emerald-500 border-emerald-100';
                icon.className = 'fa-solid fa-user-check animate-bounce-short';
                title.innerText = 'تفعيل المستخدم';
                message.innerHTML = `هل أنت متأكد أنك تريد إعادة تفعيل حساب <strong>${name}</strong>؟<br>سيتمكن من الدخول للمنصة بشكل طبيعي.`;
                confirmBtn.className = 'flex-1 px-4 py-3.5 rounded-xl font-bold text-white shadow-lg transition-all text-sm bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/30';
                confirmBtn.innerText = 'نعم، قم بالتفعيل';
            }

            toggleModal('toggleConfirmModal');
        }

        function executeToggle() {
            const btn = document.getElementById('tc-confirm-btn');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري...';
            btn.disabled = true;

            fetch(`/admin/users/${currentToggleId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                toggleModal('toggleConfirmModal');

                if (data.success) {
                    showDynamicToast('success', data.message);
                    updateSwitchUI(currentToggleId, data.is_active);
                    updateStatsCounters(data.is_active);
                } else {
                    showDynamicToast('error', data.message || 'إجراء غير مصرح به.');
                }
            })
            .catch(error => {
                console.error("Error executing toggle:", error);
                toggleModal('toggleConfirmModal');
                showDynamicToast('error', 'حدث خطأ في الاتصال بالخادم.');
            })
            .finally(() => {
                btn.innerHTML = 'تأكيد';
                btn.disabled = false;
            });
        }

        function updateSwitchUI(id, isNowActive) {
            const btn = document.getElementById(`toggle-btn-${id}`);
            const knob = document.getElementById(`toggle-knob-${id}`);
            const icon = document.getElementById(`toggle-icon-${id}`);
            const row = btn.closest('tr');

            row.setAttribute('data-status', isNowActive ? '1' : '0');

            if(isNowActive) {
                row.classList.remove('bg-rose-50/30');
                btn.className = "relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none bg-emerald-500";
                knob.className = "inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 ease-in-out -translate-x-7 flex items-center justify-center shadow-sm";
                icon.className = "fa-solid fa-check text-emerald-500 text-[8px]";
            } else {
                row.classList.add('bg-rose-50/30');
                btn.className = "relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none bg-rose-500";
                knob.className = "inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 ease-in-out -translate-x-1 flex items-center justify-center shadow-sm";
                icon.className = "fa-solid fa-lock text-rose-500 text-[8px]";
            }

            const userName = row.getAttribute('data-name')?.split(' ')[0] || 'المستخدم';
            btn.setAttribute('onclick', `openToggleConfirmModal(${id}, '${userName}', ${isNowActive ? 'true' : 'false'})`);
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
            const container = document.getElementById('ajax-toast-container');
            const toast = document.createElement('div');

            const colorClass = type === 'success' ? 'emerald' : 'rose';
            const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
            const title = type === 'success' ? 'تمت العملية' : 'تنبيه';

            toast.className = `animate-toast pointer-events-auto bg-white border-r-4 border-${colorClass}-500 shadow-[0_10px_40px_rgba(0,0,0,0.1)] rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm text-right mt-3 mx-auto md:mr-auto md:ml-0`;
            toast.innerHTML = `
                <div class="bg-${colorClass}-100 p-2 rounded-xl text-${colorClass}-600 shrink-0">
                    <i class="fa-solid ${icon} text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">${title}</p>
                    <p class="text-sm font-black text-slate-800 leading-tight">${message}</p>
                </div>
                <button type="button" onclick="this.closest('.animate-toast').remove()" class="text-slate-300 hover:text-${colorClass}-500 transition-colors shrink-0">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                if(toast.parentElement) {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 4000);
        }

        // إخفاء إشعارات الـ Backend تلقائياً
        setTimeout(() => {
            document.querySelectorAll('.animate-toast').forEach(t => {
                t.style.opacity = '0';
                t.style.transition = 'opacity 0.5s ease';
                setTimeout(() => t.remove(), 500);
            });
        }, 5000);
    </script>

    <style>
        .animate-scale-up { animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes scaleUp { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .animate-toast { animation: toastSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes toastSlideIn { 0% { opacity: 0; transform: translateY(-20px); } 100% { opacity: 1; transform: translateY(0); } }
        .animate-bounce-short { animation: bounceShort 1.5s infinite; }
        @keyframes bounceShort { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10%); } }
        .custom-pagination nav svg { width: 1.25rem; height: 1.25rem; }
        .custom-pagination nav > div:first-child { display: none; }
        @media (min-width: 768px) { .custom-pagination nav > div:first-child { display: flex; } }
    </style>
@endsection
