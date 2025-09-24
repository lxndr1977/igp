@props(['title' => null, 'subtitle' => null, 'breadcrumb' => [], 'slim' => false, 'noPaddingTop' => false])

<div 
   x-data="{ headerHeight: 0 }"
   x-init="
      const updateHeight = () => {
         const header = document.querySelector('header');
         headerHeight = header ? header.offsetHeight : 0;
      };
      updateHeight();
      window.addEventListener('resize', updateHeight);
   "
   :style="!{{ $noPaddingTop ? 'true' : 'false' }} ? `padding-top: ${headerHeight * {{ $slim ? 1 : 2 }}}px` : ''"
   @class([
       'text-white bg-secondary text-center' => !$slim && !$attributes->has('class'),
       'text-gray-900 bg-white text-left' => $slim && !$attributes->has('class'),
       'py-20' => !$slim && !$attributes->has('class'),
       'py-8' => $slim && !$attributes->has('class'),
       $attributes->get('class', '') => $attributes->has('class')
   ])
   {{ $attributes->except(['class', 'container-class']) }}
>
   <div @class([
       $attributes->get('container-class', 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8')
   ])>
      
      {{-- Breadcrumb --}}
      @if(!empty($breadcrumb))
         <nav aria-label="Breadcrumb" class="mb-4">
            <ol @class([
                'flex items-center space-x-2 text-sm',
                'justify-center text-gray-200' => !$slim,
                'justify-start text-gray-500' => $slim
            ])>
               @foreach($breadcrumb as $item)
                  <li class="flex items-center">
                     @if(!empty($item['url']) && !$loop->last)
                        <a href="{{ $item['url'] }}" @class([
                            'hover:underline transition-colors duration-200',
                            'hover:text-white' => !$slim,
                            'hover:text-gray-700' => $slim
                        ])>
                           {{ $item['label'] }}
                        </a>
                     @else
                        <span @class([
                            'font-medium' => $loop->last,
                            'text-gray-100' => !$slim && $loop->last,
                            'text-gray-800' => $slim && $loop->last
                        ])>
                           {{ $item['label'] }}
                        </span>
                     @endif
                     
                     @if(!$loop->last)
                        <span class="ml-2 select-none">/</span>
                     @endif
                  </li>
               @endforeach
            </ol>
         </nav>
      @endif

      {{-- Title --}}
      @if($title)
         <h1 @class([
             'font-medium text-balance leading-tight',
             'text-4xl md:text-6xl' => !$slim,
             'text-2xl md:text-3xl' => $slim
         ])>
            {{ $title }}
         </h1>
      @endif

      {{-- Subtitle --}}
      @if($subtitle)
         <p @class([
             'mt-4 text-balance leading-relaxed',
             'text-base md:text-xl opacity-90' => !$slim,
             'text-sm md:text-base text-gray-600' => $slim
         ])>
            {{ $subtitle }}
         </p>
      @endif

      {{-- Slot para conteúdo adicional --}}
      @if($slot->isNotEmpty())
         <div @class([
             'mt-6' => !$slim,
             'mt-4' => $slim
         ])>
            {{ $slot }}
         </div>
      @endif
   </div>
</div>