@extends('layouts.admin')

@section('title', 'العملاء')
@section('page-title', 'إضافة عميل جديد')
@section('page-subtitle', 'قم بإدخال بيانات العميل ثم الحفظ')

@section('content')
<style>
    .form-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 2px 12px rgba(15,23,42,.04); }
    .form-header { padding:14px 16px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; }
    .form-body { padding:16px; }
    .input { border:1px solid #e2e8f0; border-radius:12px; padding:.5rem .75rem; background:#fff; width:100%; }
    .label { font-weight:600; color:#334155; margin-bottom:6px; display:block; }
    .error { color:#dc2626; font-size:.85rem; margin-top:4px; }
</style>

<div class="form-card">
    <div class="form-header">
        <h3 class="text-lg font-bold text-gray-900">بيانات العميل</h3>
        <a href="{{ route('admin.clients.index') }}" class="text-sm text-blue-600">رجوع للقائمة</a>
    </div>
    <div class="form-body">
        <form method="POST" action="{{ route('admin.clients.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="label" for="name">الاسم</label>
                <input id="name" name="name" type="text" class="input" value="{{ old('name') }}" required>
                @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="label" for="email">البريد الإلكتروني</label>
                <input id="email" name="email" type="email" class="input" value="{{ old('email') }}" required>
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="label" for="phone">رقم الهاتف</label>
                <input id="phone" name="phone" type="text" class="input" value="{{ old('phone') }}" required>
                @error('phone')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="label" for="address">العنوان</label>
                <textarea id="address" name="address" rows="3" class="input" required>{{ old('address') }}</textarea>
                @error('address')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="label" for="status">الحالة</label>
                <select id="status" name="status" class="input" required>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
                @error('status')<div class="error">{{ $message }}</div>@enderror
            </div>
            
        
            <div>
                <label class="label" for="balance">balance</label>
                <textarea id="balance" name="balance" rows="3" class="input">{{ old('balance') }}</textarea>
                @error('balance')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit" class="btn-success" style="width: 100px; height: 40px;;">حفظ العميل</button>
                <a href="{{ route('admin.clients.index') }}" class="btn-secondary" style="width: 100px; height: 40px; text-align: center;" >إلغاء</a>
            </div>
        </form>
    </div>
    </div>
@endsection
