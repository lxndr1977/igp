{{-- resources/views/components/site/services-section.blade.php --}}

@props([
    'title' => 'Nossos Serviços',
    'description' => 'Soluções completas para o desenvolvimento organizacional',
    'services' => [],
    'showBreadcrumb' => false,
    'breadcrumbs' => [],
    'backgroundColor' => 'bg-neutral-100', // bg-neutral-100, bg-white, etc.
    'maxWidth' => 'max-w-5xl',
    'highlightFirst' => false, // Se deve destacar o primeiro serviço
    'containerClass' => 'py-20 md:py-24 lg:py-32',
    'headerPadding' => false, // Para páginas internas que precisam do padding do header
])

@php
    // Serviços padrão caso não sejam fornecidos
    $defaultServices = [
        [
            'icon' => 'tabler-users',
            'title' => 'Recrutamento Estratégico',
            'route' => route('site.services.recruitment'),
            'description' => 'Processo de Recrutamento e Seleção estruturado, estratégico e humanizado, visando atrair talentos alinhados à cultura organizacional',
            'featured' => true,
        ],
        [
            'icon' => 'tabler-chalkboard-teacher',
            'title' => 'Treinamentos e Desenvolvimentos',
            'route' => route('site.services.consulting'),
            'description' => 'Programas estruturados para aprimorar competências, fortalecer habilidades e elevar a performance organizacional',
        ],
        [
            'icon' => 'tabler-user-check',
            'title' => 'Assessoria e Consultoria',
            'route' => route('site.services.consulting'),
            'description' => 'Orientação especializada para otimizar processos da sua empresa e impulsionar o crescimento e a excelência do seu negócio',
        ],
        [
            'icon' => 'tabler-presentation',
            'title' => 'Palestras',
            'route' => route('site.services.consulting'),
            'description' => 'Conteúdos técnicos e motivacionais, alinhados às necessidades estratégicas da sua empresa, para inspirar e transformar resultados',
        ],
        [
            'icon' => 'tabler-shield-check',
            'title' => 'NR-1 para Empresas',
            'route' => route('site.services.consulting'),
            'description' => 'A NR 1 é a porta de entrada para a segurança e saúde no trabalho: obrigatória por lei e essencial para proteger vidas e fortalecer resultados',
        ],
    ];
    
    $servicesList = empty($services) ? $defaultServices : $services;
@endphp

<section 
    @if($headerPadding)
        x-data="{ headerHeight: 0 }"
        x-init="const updateHeight = () => {
            const header = document.querySelector('header');
            headerHeight = header ? header.offsetHeight : 0;
        };
        updateHeight();
        window.addEventListener('resize', updateHeight);"
        class="{{ $backgroundColor }} px-4 sm:px-6 lg:px-8 pt-16 md:pt-20 lg:pt-24"
    @else
        class="{{ $backgroundColor }} {{ $containerClass }}"
    @endif
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb (opcional) --}}
        @if($showBreadcrumb && !empty($breadcrumbs))
            <x-site.breadcrumbs
                :breadcrumb="$breadcrumbs"
                slim="true"
                navClass="py-6 mb-6"
                justify="center" />
        @endif

        {{-- Título e descrição da seção --}}
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 text-secondary-600 text-balance">
                {{ $title }}
            </h2>
            <p class="text-xl text-neutral-600 max-w-2xl mx-auto">
                {{ $description }}
            </p>
        </div>

        {{-- Container dos serviços --}}
        <div class="{{ $maxWidth }} mx-auto">
            
            {{-- Primeiro serviço em destaque (se habilitado) --}}
            @if($highlightFirst && count($servicesList) > 0)
                @php $firstService = $servicesList[0]; @endphp
                <div class="bg-white {{ $backgroundColor === 'bg-white' ? 'border border-neutral-200' : '' }} text-secondary-600 p-10 md:p-12 rounded-xl hover:shadow-lg hover:-translate-y-1 transition duration-300 mb-8">
                    <div class="flex flex-col  md:items-center md:justify-center gap-8">
                        <div class="w-12 md:w-16 w-12 md:h-16 mx-0 md:mx-auto bg-neutral-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <x-dynamic-component :component="$firstService['icon']" class="w-7 md:w-9 h7 md:h-9 text-secondary-600" />
                        </div>
                        <div class="flex-1 text-start md:text-center">
                            <h3 class="text-lg md:text-3xl font-bold mb-3 text-neutral-800">{{ $firstService['title'] }}</h3>
                            <p class="text-lg md:text-xl mb-6 text-neutral-900 leading-relaxed">{{ $firstService['description'] }}</p>
                            <x-mary-button 
                                link="{{ $firstService['route'] }}"
                                class="btn btn-primary text-base px-12 py-6 w-full md:w-96"
                                label="Saiba mais" />
                        </div>
                    </div>
                </div>
            @endif

            {{-- Grid de serviços --}}
            <div class="grid md:grid-cols-2 lg:grid-cols-2 gap-8">
                @foreach ($servicesList as $index => $service)
                    {{-- Pula o primeiro se estiver destacado --}}
                    @if($highlightFirst && $index === 0)
                        @continue
                    @endif
                    
                    <div class="bg-white p-8 rounded-lg {{ $backgroundColor === 'bg-white' ? 'border border-neutral-200' : '' }} hover:shadow-lg hover:-translate-y-1 transition duration-300">
                        <div class="w-12 h-12 bg-neutral-100 rounded-xl flex items-center justify-center mb-6">
                            <x-dynamic-component :component="$service['icon']" class="w-7 h-7 text-secondary-600" />
                        </div>
                        <h3 class="text-lg md:text-2xl font-bold mb-3 text-neutral-800">{{ $service['title'] }}</h3>
                        <p class="text-secondary-600 text-base md:text-lg mb-6">{{ $service['description'] }}</p>
                        <x-mary-button 
                            link="{{ $service['route'] }}"
                            class="btn btn-primary w-full text-base px-12 py-6"
                            label="Saiba mais" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>