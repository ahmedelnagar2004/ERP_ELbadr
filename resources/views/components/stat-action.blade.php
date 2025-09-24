@props([
    'href' => '#',
    'color' => 'indigo',
    'permission' => null,
])

@php
    $can = $permission ? auth()->check() && auth()->user()->can($permission) : true;
@endphp

@if($can)
    <a href="{{ $href }}" class="stat-action stat-action--{{ $color }}">
        {{ $slot }}
        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M7 5l5 5-5 5V5z"/></svg>
    </a>
@else
    <span class="stat-action stat-action--disabled" title="غير مصرح">
        {{ $slot }}
        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M7 5l5 5-5 5V5z"/></svg>
    </span>
@endif
