@php
   $sectionTitle = 'Nossos Serviços';
   $sectionDescription = 'Soluções completas para o desenvolvimento organizacional';

   $services = [
       [
           'icon' => 'tabler-chalkboard-teacher',
           'title' => 'Treinamentos e Desenvolvimentos',
           'route' => route('site.services.consulting'),
           'description' =>
               'Programas estruturados para aprimorar competências, fortalecer habilidades e elevar a performance organizacional',
       ],
       [
           'icon' => 'tabler-user-check',
           'title' => 'Assessoria e Consultoria',
           'route' => route('site.services.consulting'),
           'description' =>
               'Orientação especializada para otimizar processos da sua empresa e impuslionar o crescimento e a excelência do seu negócio',
       ],
       [
           'icon' => 'tabler-presentation',
           'title' => 'Palestras',
           'route' => route('site.services.consulting'),
           'description' =>
               'Conteúdos técnicos e motivacionais, alinhados às necessidades estratégicas da sua empresa, para inspirar e transformar resultados',
       ],
       [
           'icon' => 'tabler-shield-check',
           'title' => 'NR-1 para Empresas',
           'route' => route('site.services.consulting'),
           'description' =>
               'Adequação inteligente à norma, protegendo sua equipe, reduzindo riscos e impulsionando resultados sustentáveis',
       ],
   ];
@endphp

<!-- Serviços -->
<section class="py-20 md:py-24 lg:py-32 bg-primary-50">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
         <h2 class="text-3xl md:text-4xl font-bold mb-4 text-secondary-600">
            {{ $sectionTitle }}
         </h2>
         <p class="text-xl text-neutral-600 max-w-2xl mx-auto">
            {{ $sectionDescription }}
         </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
         @foreach ($services as $service)
            <div class="bg-white p-8 rounded-lg hover:shadow-lg hover:-translate-y-1 transition transition-duration-300"">
               <div class="w-16 h-16 bg-secondary-600 rounded-full flex items-center justify-center mb-24">
                  <x-dynamic-component :component="$service['icon']" class="w-7 h-7 text-white" />
               </div>
               <h3 class="text-lg md:text-2xl font-bold mb-3 text-neutral-800">{{ $service['title'] }}</h3>
               <p class="text-secondary-600 text-base md:text-lg mb-6">{{ $service['description'] }}</p>

               <x-mary-button link="{{ $service['route'] }}"
                  class="btn btn-primary"
                  label="Saiba mais" />
               </a>
            </div>
         @endforeach
      </div>
   </div>
</section>
