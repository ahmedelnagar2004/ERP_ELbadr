@extends('layouts.admin')

@section('title', 'العملاء')
@section('page-title', 'عرض بيانات العميل')
@section('page-subtitle', 'تفاصيل العميل المختار')

@section('content')
<style>
    .detail-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 2px 12px rgba(15,23,42,.04); }
    .detail-header { padding:14px 16px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; }
    .detail-body { padding:16px; }
    .row { display:flex; gap:12px; padding:8px 0; border-bottom:1px dashed #e2e8f0; }
    .row:last-child { border-bottom:0; }
    .label { min-width:140px; color:#475569; font-weight:600; }
    .value { color:#111827; }
</style>

<div class="detail-card">
    <div class="detail-header">
        <h3 class="text-lg font-bold text-gray-900">{{ $client->name }}</h3>
        <div class="flex gap-2">
            <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-primary">تعديل</a>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">رجوع</a>
        </div>
    </div>
    <div class="detail-body">
        <div class="row">
            <div class="label">البريد الإلكتروني</div>
            <div class="value">{{ $client->email }}</div>
        </div>
        <div class="row">
            <div class="label">رقم الهاتف</div>
            <div class="value">{{ $client->phone }}</div>
        </div>
        <div class="row">
            <div class="label">العنوان</div>
            <div class="value">{{ $client->address }}</div>
        </div>
        <div class="row">
            <div class="label">تاريخ الإنشاء</div>
            <div class="value">{{ optional($client->created_at)->format('Y-m-d H:i') }}</div>
        </div>
        <div class="row">
            <div class="label">آخر تحديث</div>
            <div class="value">{{ optional($client->updated_at)->format('Y-m-d H:i') }}</div>
        </div>
    </div>
</div>
@endsection
