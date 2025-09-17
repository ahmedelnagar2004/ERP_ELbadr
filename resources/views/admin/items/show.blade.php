@extends('layouts.admin')

@section('title', 'عرض منتج')
@section('page-title', 'تفاصيل المنتج')
@section('page-subtitle', 'عرض تفاصيل المنتج: {{ $item->name }}')

@section('content')

<!-- في رأس الصفحة -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<div class="card p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold text-slate-800 mb-2">البيانات الأساسية</h3>
            <p><span class="text-slate-500">الاسم:</span> {{ $item->name }}</p>
            <p><span class="text-slate-500">الكود:</span> {{ $item->code }}</p>
            <p><span class="text-slate-500">الفئة:</span> {{ $item->category->name ?? 'غير محدد' }}</p>
            <p><span class="text-slate-500">الوحدة:</span> {{ $item->unit->name ?? 'غير محدد' }}</p>
            <p><span class="text-slate-500">السعر:</span> {{ $item->price }} ج.م</p>
            <p><span class="text-slate-500">الكمية:</span> {{ $item->quantity }}</p>
            <p><span class="text-slate-500">المتجر:</span> {{ $item->is_shown_in_store ? 'نعم' : 'لا' }}</p>
            <p><span class="text-slate-500">الكسور:</span> {{ $item->allow_decimal ? 'نعم' : 'لا' }}</p>
        </div>
        <div>
            <div>
                <h3 class="font-semibold text-slate-800 mb-2">الصور</h3>
                @if($item->gallery->count())
                    <div class="swiper mySwiper" style="width:350px;">
                        <div class="swiper-wrapper">
                            @foreach($item->gallery as $photo)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $photo->path) }}" alt="صورة المنتج"
                                         class="rounded-lg border border-gray-300 shadow-md"
                                         style="width:100%; height:200px; object-fit:cover;">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            new Swiper('.mySwiper', {
                                slidesPerView: 1,
                                spaceBetween: 10,
                                loop: true,
                                pagination: { el: '.swiper-pagination', clickable: true },
                                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                            });
                        });
                    </script>
                @else
                    <p class="text-slate-500">لا توجد صور</p>
                @endif
            </div>
        </div>
    </div>
    <div class="mt-6 flex gap-3">
        @can('edit-items')
        <a href="{{ route('admin.items.edit', $item) }}" class="btn-primary">تعديل</a>
        @endcan
        <a href="{{ route('admin.items.index') }}" class="btn-secondary">عودة</a>
    </div>
   
</div>
@endsection


