@extends('layouts.admin')

@section('title', 'إضافة وحدة جديدة')

@section('content')
<style>
    .form-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 2px 12px rgba(15,23,42,.04); }
    .form-header { padding:14px 16px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; }
    .form-body { padding:16px; }
    .input { border:1px solid #e2e8f0; border-radius:12px; padding:.5rem .75rem; background:#fff; width:100%; }
    .select { border:1px solid #e2e8f0; border-radius:12px; padding:.5rem .75rem; background:#fff; width:100%; }
    .label { font-weight:600; color:#334155; margin-bottom:6px; display:block; }
    .hint { color:#64748b; font-size:.85rem; margin-top:6px; }
    .error { color:#dc2626; font-size:.85rem; margin-top:4px; }
</style>

<div class="form-card">
    <div class="form-header">
        <h3 class="text-lg font-bold text-gray-900">إضافة وحدة جديدة</h3>
        <a href="{{ route('admin.units.index') }}" class="text-sm text-blue-600">رجوع للقائمة</a>
    </div>
    <div class="form-body">
        <form action="{{ route('admin.units.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- اسم الوحدة -->
            <div>
                <label for="name" class="label">اسم الوحدة <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="مثال: قطعة، متر، لتر، كيلو" class="input" required>
                @error('name')<div class="error">{{ $message }}</div>@enderror
                <div class="hint">اختر اسمًا واضحًا يسهل استخدامه في المنتجات.</div>
            </div>

            <!-- الحالة -->
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">الحالة <span class="text-red-500">*</span></label>
                <select name="status" id="status" required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach(\App\UnitStatus::cases() as $statusCase)
                        <option value="{{ strtolower($statusCase->name) }}" {{ old('status', 'active') == strtolower($statusCase->name) ? 'selected' : '' }}>
                            {{ $statusCase->label() }}
                        </option>
                    @endforeach
                </select>
                @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- الوصف -->
            <div>
                <label for="description" class="label">الوصف</label>
                <textarea name="description" id="description" rows="3" placeholder="وصف مختصر للوحدة..." class="input">{{ old('description') }}</textarea>
                @error('description')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit" class="btn-success" style="width:120px; height:40px;">حفظ الوحدة</button>
                <a href="{{ route('admin.units.index') }}" class="btn-secondary" style="width:120px; height:40px; text-align:center; line-height:40px;">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection









