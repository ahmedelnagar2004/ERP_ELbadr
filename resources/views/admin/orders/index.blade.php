@extends('layouts.admin')

@section('title', 'الطلبات')
@section('page-title', 'إدارة الطلبات')
@section('page-subtitle', 'عرض وإدارة الطلبات')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-800">قائمة الطلبات</h3>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            إضافة طلب جديد
        </button>
    </div>

    <div class="text-center py-8">
        <p class="text-gray-500">لا توجد طلبات حالياً</p>
        <p class="text-sm text-gray-400 mt-2">سيتم إضافة وظائف إدارة الطلبات قريباً</p>
    </div>
</div>
@endsection