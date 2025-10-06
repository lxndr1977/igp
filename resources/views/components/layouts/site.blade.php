<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
   @include('partials.head')
</head>

<body class="min-h-screen bg-white">

   <x-mary-toast position="toast-top toast-center" />

   @include('partials.site.header')

   {{ $slot }}

   @include('partials.site.footer')

   @livewireScripts

   @push('scripts')
      <script>
         // Scroll para o topo quando mudar de etapa
         Livewire.on('stepChanged', () => {
            window.scrollTo({
               top: 0,
               behavior: 'smooth'
            });
         });
      </script>
   @endpush
</body>

</html>
