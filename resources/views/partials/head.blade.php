{{-- resources/views/partials/head.blade.php --}}

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

{{-- 
  AQUI ESTÁ A MUDANÇA PRINCIPAL:
  Se $title existir, use-a. Senão, use o nome do app como padrão.
--}}
<title>{{ $title ?? config('app.name') }}</title>

{{-- 
  O mesmo para a descrição. Se $description existir, use-a.
  Senão, use uma string padrão.
--}}
<meta name="description" content="{{ $description ?? 'Descrição padrão do seu site.' }}">

{{-- Tags Open Graph usando a mesma lógica --}}
<meta property="og:title" content="{{ $title ?? config('app.name') }}">
<meta property="og:description" content="{{ $description ?? 'Descrição padrão do seu site.' }}">

{{-- ... resto das suas tags ... --}}
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="canonical" href="{{ url()->current() }}" />

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles