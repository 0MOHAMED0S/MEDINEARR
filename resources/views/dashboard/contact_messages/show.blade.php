@extends('dashboard.layout.master')

@section('content')
<div class="p-4 md:p-6 lg:p-8 relative">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">تفاصيل الرسالة</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">عرض تفاصيل استفسار الزائر</p>
        </div>
        <a href="{{ route('admin.contact_messages.index') }}" class="bg-slate-100 text-slate-600 px-6 py-3 rounded-2xl font-bold hover:bg-slate-200 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-arrow-right"></i> عودة للقائمة
        </a>
    </div>

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden p-6 md:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-b border-gray-50 pb-8">
            <div>
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-2">اسم المرسل</label>
                <div class="text-lg font-black text-slate-800">{{ $message->name }}</div>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-2">البريد الإلكتروني</label>
                <div class="text-lg font-bold text-primary" dir="ltr"><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></div>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-2">تاريخ الإرسال</label>
                <div class="text-md font-mono text-slate-600">{{ $message->created_at->format('Y-m-d h:i A') }}</div>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-2">حالة الرسالة</label>
                @if($message->is_read)
                    <span class="bg-emerald-100 text-emerald-700 text-xs font-black px-3 py-1.5 rounded-xl">مقروءة</span>
                @else
                    <span class="bg-rose-100 text-rose-700 text-xs font-black px-3 py-1.5 rounded-xl">جديدة</span>
                @endif
            </div>
        </div>

        <div>
            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-4">نص الرسالة</label>
            <div class="bg-slate-50 border border-slate-100 p-6 rounded-2xl text-slate-700 leading-relaxed whitespace-pre-wrap">
                {{ $message->message }}
            </div>
        </div>
        
        <div class="mt-8 flex gap-4">
            <a href="mailto:{{ $message->email }}" class="bg-blue-500 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:-translate-y-1 transition-all flex items-center gap-2">
                <i class="fa-solid fa-reply"></i> الرد عبر البريد
            </a>
            <form action="{{ route('admin.contact_messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                @csrf @method('DELETE')
                <button type="submit" class="bg-rose-50 text-rose-500 px-6 py-3 rounded-xl font-bold hover:bg-rose-100 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-trash-can"></i> حذف الرسالة
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
