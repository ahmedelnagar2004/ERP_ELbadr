<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول :attribute',
    'accepted_if' => 'يجب قبول :attribute عندما يكون :other هو :value.',
    'active_url' => 'حقل :attribute لا يُمثّل رابطًا صحيحًا',
    'after' => 'يجب على حقل :attribute أن يكون تاريخاً لاحقاً للتاريخ :date.',
    'after_or_equal' => 'حقل :attribute يجب أن يكون تاريخاً لاحقاً أو مطابقاً للتاريخ :date.',
    'alpha' => 'يجب أن لا يحتوي حقل :attribute سوى على حروف',
    'alpha_dash' => 'يجب أن لا يحتوي حقل :attribute على حروف، أرقام ومطّات فقط.',
    'alpha_num' => 'يجب أن يحتوي :attribute على حروفٍ وأرقامٍ فقط',
    'array' => 'يجب أن يكون حقل :attribute ًمصفوفة',
    'before' => 'يجب على حقل :attribute أن يكون تاريخاً سابقاً للتاريخ :date.',
    'before_or_equal' => 'حقل :attribute يجب أن يكون تاريخاً سابقاً أو مطابقاً للتاريخ :date',
    'between' => [
        'array' => 'يجب أن يحتوي حقل :attribute على عدد من العناصر بين :min و :max',
        'file' => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت',
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max',
        'string' => 'يجب أن يكون طول النص :attribute بين :min و :max حرفاً',
    ],
    'boolean' => 'يجب أن تكون قيمة الحقل :attribute إما true أو false',
    'confirmed' => 'حقل التأكيد غير متطابق مع الحقل :attribute',
    'current_password' => 'كلمة المرور غير صحيحة',
    'date' => 'حقل :attribute ليس تاريخًا صحيحًا',
    'date_equals' => 'يجب أن يكون تاريخ :attribute مطابقاً للتاريخ :date.',
    'date_format' => 'لا يتوافق حقل :attribute مع الشكل :format.',
    'declined' => 'يجب رفض :attribute',
    'declined_if' => 'يجب رفض :attribute عندما يكون :other هو :value.',
    'different' => 'يجب أن يكون الحقلان :attribute و :other مُختلفين',
    'digits' => 'يجب أن يحتوي حقل :attribute على :digits رقمًا/أرقام',
    'digits_between' => 'يجب أن يحتوي حقل :attribute على :min إلى :max رقم/أرقام',
    'dimensions' => 'الـ :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct' => 'للحقل :attribute قيمة مُكرّرة',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صحيح',
    'ends_with' => 'يجب أن ينتهي حقل :attribute بأحد القيم التالية: :values',
    'enum' => 'القيمة المحددة :attribute غير صالحة.',
    'exists' => 'الحقل :attribute غير موجود',
    'file' => 'حقل :attribute يجب أن يكون ملفًا',
    'filled' => 'حقل :attribute إجباري',
    'gt' => [
        'array' => 'يجب أن يحتوي حقل :attribute على أكثر من :value عنصر',
        'file' => 'يجب أن يكون حجم الملف :attribute أكبر من :value كيلوبايت',
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من :value',
        'string' => 'يجب أن يكون طول النص :attribute أكثر من :value حرفاً',
    ],
    'gte' => [
        'array' => 'يجب أن يحتوي حقل :attribute على :value عنصر أو أكثر',
        'file' => 'يجب أن يكون حجم الملف :attribute على الأقل :value كيلوبايت',
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية أو أكبر من :value',
        'string' => 'يجب أن يكون طول النص :attribute على الأقل :value حرفاً',
    ],
    'image' => 'يجب أن يكون حقل :attribute صورة',
    'in' => 'حقل :attribute غير موجود',
    'in_array' => 'حقل :attribute غير موجود في :other',
    'integer' => 'يجب أن يكون :attribute عددًا صحيحًا',
    'ip' => 'يجب أن يكون :attribute عنوان IP صحيحًا',
    'ipv4' => 'يجب أن يكون :attribute عنوان IPv4 صحيحًا',
    'ipv6' => 'يجب أن يكون :attribute عنوان IPv6 صحيحًا',
    'json' => 'يجب أن يكون حقل :attribute نصًا من نوع JSON',
    'lt' => [
        'array' => 'يجب أن يحتوي حقل :attribute على أقل من :value عنصر',
        'file' => 'يجب أن يكون حجم الملف :attribute أصغر من :value كيلوبايت',
        'numeric' => 'يجب أن تكون قيمة :attribute أصغر من :value',
        'string' => 'يجب أن يكون طول النص :attribute أقل من :value حرفاً',
    ],
    'lte' => [
        'array' => 'يجب أن لا يحتوي حقل :attribute على أكثر من :value عناصر',
        'file' => 'يجب أن لا يتجاوز حجم الملف :attribute :value كيلوبايت',
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية أو أصغر من :value',
        'string' => 'يجب أن لا يتجاوز طول النص :attribute :value حرفاً',
    ],
    'mac_address' => 'حقل :attribute يجب أن يكون عنوان MAC صالحًا',
    'max' => [
        'array' => 'يجب أن لا يحتوي حقل :attribute على أكثر من :max عنصر',
        'file' => 'يجب أن لا يتجاوز حجم الملف :attribute :max كيلوبايت',
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية أو أصغر من :max',
        'string' => 'يجب أن لا يتجاوز طول النص :attribute :max حرفاً',
    ],
    'mimes' => 'يجب أن يكون حقل :attribute ملفًا من نوع: :values',
    'mimetypes' => 'يجب أن يكون حقل :attribute ملفًا من نوع: :values',
    'min' => [
        'array' => 'يجب أن يحتوي حقل :attribute على الأقل على :min عنصر',
        'file' => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت',
        'numeric' => 'يجب أن تكون قيمة :attribute على الأقل :min',
        'string' => 'يجب أن يكون طول النص :attribute على الأقل :min حرفاً',
    ],
    'multiple_of' => 'يجب أن تكون قيمة :attribute من مضاعفات :value',
    'not_in' => 'الحقل المحدد :attribute غير صالح',
    'not_regex' => 'صيغة :attribute غير صالحة',
    'numeric' => 'يجب أن يكون :attribute رقمًا',
    'password' => [
        'letters' => 'يجب أن تحتوي كلمة المرور على حرف واحد على الأقل',
        'mixed' => 'يجب أن تحتوي كلمة المرور على حرف كبير وحرف صغير على الأقل',
        'numbers' => 'يجب أن تحتوي كلمة المرور على رقم واحد على الأقل',
        'symbols' => 'يجب أن تحتوي كلمة المرور على رمز واحد على الأقل',
        'uncompromised' => 'كلمة المرور هذه ظهرت في تسريب بيانات. يرجى اختيار كلمة مرور مختلفة',
    ],
    'present' => 'يجب تقديم حقل :attribute',
    'prohibited' => 'حقل :attribute محظور',
    'prohibited_if' => 'حقل :attribute محظور عندما يكون :other هو :value',
    'prohibited_unless' => 'حقل :attribute محظور ما لم يكن :other في :values',
    'prohibits' => 'حقل :attribute يحظر وجود :other',
    'regex' => 'صيغة حقل :attribute غير صحيحة',
    'required' => 'حقل :attribute مطلوب',
    'required_array_keys' => 'الحقل :attribute يجب أن يحتوي على مدخلات لـ: :values',
    'required_if' => 'حقل :attribute مطلوب عندما يكون :other هو :value',
    'required_unless' => 'حقل :attribute مطلوب إلا إذا كان :other ضمن :values',
    'required_with' => 'حقل :attribute مطلوب عند وجود :values',
    'required_with_all' => 'حقل :attribute مطلوب عند وجود :values',
    'required_without' => 'حقل :attribute مطلوب عند عدم وجود :values',
    'required_without_all' => 'حقل :attribute مطلوب عند عدم وجود :values',
    'same' => 'حقل :attribute و :other يجب أن يكونا متطابقين',
    'size' => [
        'array' => 'يجب أن يحتوي حقل :attribute على :size عنصر',
        'file' => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت',
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية لـ :size',
        'string' => 'يجب أن يحتوي النص :attribute على :size حرفاً',
    ],
    'starts_with' => 'يجب أن يبدأ حقل :attribute بأحد القيم التالية: :values',
    'string' => 'يجب أن يكون حقل :attribute نصاً',
    'timezone' => 'يجب أن يكون :attribute نطاقًا زمنيًا صحيحاً',
    'unique' => 'قيمة حقل :attribute مُستخدمة من قبل',
    'uploaded' => 'فشل في تحميل الـ :attribute',
    'url' => 'صيغة الرابط :attribute غير صحيحة',
    'uuid' => 'حقل :attribute يجب أن يكون بصيغة UUID سليمة',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // Add your attribute translations here
    ],
];
