@extends('dashboard.layout.master')

@section('content')
<div class="p-4 md:p-6 lg:p-8 relative">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">رسائل اتصل بنا</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">إدارة رسائل واستفسارات الزوار</p>
        </div>
        <form action="{{ route('admin.contact_messages.read_all') }}" method="POST">
            @csrf
            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:-translate-y-1 transition-all flex items-center gap-2 group">
                <i class="fa-solid fa-check-double"></i> تعيين الكل كمقروء
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-xl mb-6 font-bold shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100">
                        <th class="p-4 font-bold text-slate-600 text-sm">الاسم</th>
                        <th class="p-4 font-bold text-slate-600 text-sm">البريد الإلكتروني</th>
                        <th class="p-4 font-bold text-slate-600 text-sm">مقتطف من الرسالة</th>
                        <th class="p-4 font-bold text-slate-600 text-sm">التاريخ</th>
                        <th class="p-4 font-bold text-slate-600 text-sm">الحالة</th>
                        <th class="p-4 font-bold text-slate-600 text-sm">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                    <tr class="border-b border-gray-50 hover:bg-slate-50/50 transition-colors {{ !$message->is_read ? 'bg-blue-50/30' : '' }}">
                        <td class="p-4 text-sm font-bold text-slate-800">{{ $message->name }}</td>
                        <td class="p-4 text-sm text-gray-600" dir="ltr">{{ $message->email }}</td>
                        <td class="p-4 text-sm text-gray-600 max-w-xs truncate">{{ Str::limit($message->message, 50) }}</td>
                        <td class="p-4 text-xs font-mono text-gray-500">{{ $message->created_at->format('Y-m-d h:i A') }}</td>
                        <td class="p-4">
                            @if($message->is_read)
                                <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black px-2 py-1 rounded-lg">مقروءة</span>
                            @else
                                <span class="bg-rose-100 text-rose-700 text-[10px] font-black px-2 py-1 rounded-lg">جديدة</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.contact_messages.show', $message->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-100 flex items-center justify-center transition-colors tooltip" title="عرض التفاصيل">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <form action="{{ route('admin.contact_messages.destroy', $message->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-100 flex items-center justify-center transition-colors tooltip" title="حذف">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-500 font-bold">لا توجد رسائل حالياً</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection
