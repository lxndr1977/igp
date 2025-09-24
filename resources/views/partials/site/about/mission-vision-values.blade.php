@php
// Definindo as variáveis dentro da própria view
$sectionBgClass = 'bg-neutral-50';

$missionVision = [
    'mission' => [
        'title' => 'Missão',
        'content' => 'Nossa missão é inovar, instruir e inspirar as organizações, ajudando-as a encontrar os melhores talentos para suas necessidades de negócios.'
    ],
    'vision' => [
        'title' => 'Visão',
        'content' => 'Temos a visão de ser líderes reconhecidos no mercado, impulsionando a transformação por meio do desenvolvimento das pessoas. Acreditamos que toda inovação nas empresas começa com o crescimento e o empoderamento dos indivíduos.'
    ]
];

$values = [
    'title' => 'Valores',
    'description' => 'Os princípios que guiam nossa atuação e definem nossa identidade organizacional.',
    'items' => [
        [
            'number' => 1,
            'title' => 'Desenvolvimento Sustentável',
            'description' => 'Valorizamos o crescimento contínuo das pessoas para impulsionar a inovação nas empresas.'
        ],
        [
            'number' => 2,
            'title' => 'Inovação Criativa',
            'description' => 'Estimulamos soluções inovadoras e criativas, desafiando paradigmas.'
        ],
        [
            'number' => 3,
            'title' => 'Inspiração e Motivação',
            'description' => 'Buscamos inspirar e motivar pessoas a explorar seu potencial máximo, superando desafios com confiança.'
        ],
        [
            'number' => 4,
            'title' => 'Parceria Colaborativa',
            'description' => 'Construímos parcerias colaborativas, baseadas em confiança e respeito, para promover a cocriação.'
        ],
        [
            'number' => 5,
            'title' => 'Excelência Orientada pelo Propósito',
            'description' => 'Buscamos a excelência, com foco no propósito de impactar positivamente a vida das pessoas e o sucesso das organizações.'
        ]
    ]
];
@endphp

<section class="py-20 md:py-24 lg:py-32 {{ $sectionBgClass }}">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
         <div class="grid lg:grid-cols-2 gap-16 ">
            <!-- Missão e Visão -->
            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
               <div class="flex-1">
                  <h3 class="text-3xl font-medium mb-6 text-neutral-800">{{ $missionVision['mission']['title'] }}</h3>
                  <p class="md:text-lg text-neutral-900 leading-relaxed mb-12">
                     {{ $missionVision['mission']['content'] }}
                  </p>

                  <h3 class="text-3xl font-medium mb-6 text-neutral-800">{{ $missionVision['vision']['title'] }}</h3>
                  <p class="md:text-lg text-neutral-900 leading-relaxed">
                     {{ $missionVision['vision']['content'] }}
                  </p>
               </div>
            </div>

            <!-- Valores -->
            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
               <div class="flex-1">
                  <h3 class="text-3xl font-medium mb-6 text-neutral-800">{{ $values['title'] }}</h3>

                  <p class="text-lg text-neutral-900 mb-10">
                     {{ $values['description'] }}
                  </p>

                  <div class="space-y-6">
                     @foreach($values['items'] as $value)
                        <div class="flex items-start gap-4">
                           <span class="flex-shrink-0 w-8 h-8 bg-primary-600 text-secondary-600 rounded-full flex items-center justify-center font-medium text-sm">
                              {{ $value['number'] }}
                           </span>
                           <div>
                              <span class="text-xl font-medium text-neutral-900 block mb-1">{{ $value['title'] }}</span>
                              <span class="text-neutral-900 md:text-lg">{{ $value['description'] }}</span>
                           </div>
                        </div>
                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>