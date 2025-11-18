@extends('layouts.admin')

@section('title', 'تنبيهات الكمية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تنبيهات الكمية</h3>
                </div>
                <div class="card-body">
                    @if($items->count() > 0)
                        <div class="table-wrap">
                            <table class="min-w-full w-full">
                                <thead class="sticky">
                                    <tr>
                                        <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">اسم المنتج</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">الكمية الحالية</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">الحد الأدنى</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $item->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $item->minimum_stock }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                                منخفض
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-green-600 text-lg mb-2">
                                <svg class="w-16 h-16 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-gray-600">جميع المنتجات في كميات كافية</p>
                            <p class="text-sm text-gray-500 mt-2">لا توجد منتجات تحتاج إلى إعادة تخزين</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
