@extends('layouts.admin')

@section('title', 'العملاء')
@section('page-title', 'إدارة العملاء')
@section('page-subtitle', 'عرض وإدارة بيانات العملاء')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-800">قائمة العملاء</h3>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            إضافة عميل جديد
        </button>
    </div>

    <div class="text-center py-8">
        <p class="text-gray-500">لا توجد بيانات عملاء حال<|im_start|></p>
        <p class="text-sm text-gray-400 mt-2">سيتم إضافة وظائف إدارة العملاء قريباً</p>
    </div>
</div>
@endsection