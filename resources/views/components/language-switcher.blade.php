<div class="relative inline-block text-left" x-data="{ open: false }">
    <div>
        <button type="button" 
                @click="open = !open" 
                class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                id="language-menu" 
                aria-expanded="true" 
                aria-haspopup="true">
            @if($currentLocale === 'ar')
                <span class="mr-2">🇸🇦</span> {{ $locales[$currentLocale] ?? 'English' }}
            @else
                <span class="mr-2">🇺🇸</span> {{ $locales[$currentLocale] ?? 'English' }}
            @endif
            <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-10 w-40 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" 
         role="menu" 
         aria-orientation="vertical" 
         aria-labelledby="language-menu" 
         tabindex="-1">
        <div class="py-1" role="none">
            @foreach($locales as $locale => $name)
                @if($locale !== $currentLocale)
                    <a href="{{ route('lang.switch', $locale) }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" 
                       role="menuitem" 
                       tabindex="-1"
                       @if($locale === 'ar') dir="rtl" @endif>
                        @if($locale === 'ar')
                            <span class="mr-2">🇸🇦</span>
                        @else
                            <span class="mr-2">🇺🇸</span>
                        @endif
                        {{ $name }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>