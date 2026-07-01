<x-mail::message>
# أهلاً بك، {{ $application->owner_name }}

@if($application->status === 'approved')
يسعدنا إخبارك بأنه **تم قبول طلب صيدليتك** ({{ $application->pharmacy_name }}) للانضمام إلى منصة ميدينير! 🎉

لقد تم تفعيل حسابك، ويمكنك الآن تسجيل الدخول والبدء في تلقي الطلبات وتقديم خدماتك لعملائنا بكل سهولة.

<x-mail::button :url="url('/pharmacy/dashboard')" color="success">
الذهاب إلى لوحة التحكم
</x-mail::button>

نتمنى لك التوفيق والنجاح معنا.
@else
نأسف لإخبارك بأنه **تم رفض طلب صيدليتك** ({{ $application->pharmacy_name }}) في الوقت الحالي.

**سبب الرفض:**
<x-mail::panel>
{{ $application->admin_notes ?: 'لم يتم تقديم سبب محدد. يرجى مراجعة الشروط والتأكد من تطابق جميع المستندات.' }}
</x-mail::panel>

إذا كنت تعتقد أن هناك خطأ ما، أو قمت بتجهيز الأوراق المطلوبة، يمكنك التواصل معنا أو تقديم طلب جديد بعد استيفاء الشروط.
@endif

شكراً لك,<br>
فريق عمل {{ config('app.name', 'ميدينير') }}
</x-mail::message>
