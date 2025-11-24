@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">
                <i class="fas fa-cog mr-2"></i>
                تعديل الإعدادات
            </h2>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Site Name -->
                <div class="col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">اسم الموقع</label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name', $settings->name ?? config('app.name')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" 
                           value="{{ old('email', $settings->email ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                    <input type="text" name="phone" id="phone" 
                           value="{{ old('phone', $settings->phone ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           >
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                    <textarea name="address" id="address" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                              >{{ old('address', $settings->address ?? '') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Logo Upload -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">شعار الموقع</label>
                    <div class="mt-1 flex items-center space-x-4">
                        <div class="flex-shrink-0 h-16 w-16 rounded-full overflow-hidden bg-gray-100">
                            @if(isset($settings->logo) && $settings->logo)
                                <img src="{{ Storage::url($settings->logo) }}" alt="Logo" class="h-full w-full object-cover">
                            @else
                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <input type="file" name="logo" id="logo" class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-indigo-50 file:text-indigo-700
                                  hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                    @error('logo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <hr class="my-6 border-gray-200">

            <h3 class="text-lg font-medium text-gray-900 mb-4">إعدادات المبيعات</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Decimal Quantities -->
                <div class="col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="allow_decimal_quantities" id="allow_decimal_quantities" value="1" 
                               {{ $salesSettings->allow_decimal_quantities ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="allow_decimal_quantities" class="mr-2 block text-sm text-gray-900">
                            السماح بالكميات العشرية في البيع (مثلاً: 0.5 كجم)
                        </label>
                    </div>
                </div>

                <!-- Default Discount Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">طريقة تطبيق الخصم الافتراضية</label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input type="radio" name="default_discount_type" id="discount_percentage" value="percentage"
                                   {{ $salesSettings->default_discount_type === 'percentage' ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <label for="discount_percentage" class="mr-2 block text-sm text-gray-700">نسبة مئوية (%)</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="default_discount_type" id="discount_fixed" value="fixed"
                                   {{ $salesSettings->default_discount_type === 'fixed' ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <label for="discount_fixed" class="mr-2 block text-sm text-gray-700">مبلغ ثابت</label>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تفعيل طرق الدفع المتاحة</label>
                    <div class="mt-2 space-y-2">
                        @foreach(['cash' => 'نقدى', 'credit' => 'اجل', 'card' => 'بطاقة', 'bank' => 'تحويل بنكي'] as $key => $label)
                            <div class="flex items-center">
                                <input type="checkbox" name="enabled_payment_methods[]" id="payment_{{ $key }}" value="{{ $key }}"
                                       {{ in_array($key, $salesSettings->enabled_payment_methods) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="payment_{{ $key }}" class="mr-2 block text-sm text-gray-700">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection