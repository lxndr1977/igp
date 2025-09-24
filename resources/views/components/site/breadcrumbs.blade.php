@props([
    'breadcrumb' => [],
    'navClass' => 'mb-4',
    'olClass' => 'flex items-center space-x-2 text-sm text-gray-500',
    'linkClass' => 'hover:underline transition-colors duration-200 hover:text-gray-700',
    'activeClass' => 'font-medium text-gray-800',
    'separatorClass' => 'ml-2 select-none text-gray-400',
    'separator' => '/',
    'justify' => 'start' 
])

@if (!empty($breadcrumb))
   <nav aria-label="Breadcrumb" class="{{ $navClass }}">
      <ol @class([
          $olClass,
          'justify-start' => $justify === 'start',
          'justify-center' => $justify === 'center'
      ])>
         @foreach ($breadcrumb as $item)
            <li class="flex items-center">
               @if (!empty($item['url']) && !$loop->last)
                  <a href="{{ $item['url'] }}" class="{{ $linkClass }}">
                     {{ $item['label'] }}
                  </a>
               @else
                  <span class="{{ $loop->last ? $activeClass : '' }}">
                     {{ $item['label'] }}
                  </span>
               @endif

               @if (!$loop->last)
                  <span class="{{ $separatorClass }}">{{ $separator }}</span>
               @endif
            </li>
         @endforeach
      </ol>
   </nav>
@endif