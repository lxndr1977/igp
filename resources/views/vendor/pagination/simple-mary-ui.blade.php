@if ($paginator->hasPages())
   <nav role="navigation" aria-label="{!! __('Pagination Navigation') !!}" class="flex justify-between">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
         <span
            class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-500 bg-white border border-neutral-300 cursor-default leading-5 rounded-md">
            {!! __('pagination.previous') !!}
         </span>
      @else
         <x-mary-button 
            icon="o-arrow-left"
            link="{{ $paginator->previousPageUrl() }}"
            rel="prev"
            label="{!! __('pagination.previous') !!}"
            class="btn" />
      @endif

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
         <x-mary-button
            icon-right="o-arrow-right"
            link="{{ $paginator->nextPageUrl() }}"
            rel="prev"
            label="{!! __('pagination.next') !!}"
            class="btn" />

         </a>
      @else
         <span
            class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-500 bg-white border border-neutral-300 cursor-default leading-5 rounded-md">
            {!! __('pagination.next') !!}
         </span>
      @endif
   </nav>
@endif
