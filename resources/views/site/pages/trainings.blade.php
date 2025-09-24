<x-layouts.site>
   @php
      $sectionTitle = 'Nossos Treinamentos';
      $sectionDescription = 'Soluções completas para o desenvolvimento organizacional';

      $trainings = [
          [
              'icon' => 'tabler-users',
              'title' => 'Escuta e Acolhimento aos Funcionários',
              'route' => route('site.trainings.employee-listing'),
              'description' =>
                  'Desenvolva habilidades essenciais para criar um ambiente de trabalho mais acolhedor e produtivo',
          ],
          [
              'icon' => 'tabler-shield-check',
              'title' => 'NR-1: Mais saúde para sua equipe',
              'route' => route('site.trainings.nr-1'),
              'description' =>
                  'Adequação inteligente à norma, protegendo sua equipe, reduzindo riscos e impulsionando resultados',
          ],
      ];
   @endphp

   <section
      x-data="{ headerHeight: 0 }"
      x-init="const updateHeight = () => {
          const header = document.querySelector('header');
          headerHeight = header ? header.offsetHeight : 0;
      };
      updateHeight();
      window.addEventListener('resize', updateHeight);"
      class="px-4 sm:px-6 lg:px-8 pt-16 md:pt-20 lg:pt-24">

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
         <x-site.breadcrumbs
            :breadcrumb="[
                ['label' => 'Início', 'url' => route('site.home')],
                ['label' => 'Treinamentos'],
            ]"
            slim="true"
            navClass="py-6 mb-6"
            justify="center" />

         <div class="text-center mb-16">
            <h2 class="ext-2xl md:text-3xl lg:text-5xl font-bold mb-4 text-secondary-600 text-balance">
               {{ $sectionTitle }}
            </h2>
            <p class="text-xl text-neutral-600 max-w-2xl mx-auto">
               {{ $sectionDescription }}
            </p>
         </div>

         <div class="grid md:grid-cols-2 gap-8 mx-auto">
            @foreach ($trainings as $training)
               <div
                  class="bg-white p-8 rounded-lg border border-neutral-200 hover:shadow-lg hover:-translate-y-1 transition duration-300">
                  <div class="w-12 h-12 bg-neutral-100 rounded-xl flex items-center justify-center mb-6">
                     <x-dynamic-component
                        :component="$training['icon']"
                        class="w-7 h-7 text-secondary-600" />
                  </div>
                  <h3 class="text-xl font-bold mb-4 text-neutral-800">
                     {{ $training['title'] }}
                  </h3>
                  <p class="text-neutral-600 mb-6 md:text-lg">
                     {{ $training['description'] }}
                  </p>
                  <x-mary-button link="{{ $training['route'] }}"
                     class="btn btn-primary w-full text-base px-12 py-6"
                     label="Saiba mais" />
               </div>
            @endforeach
         </div>
      </div>
   </section>

   @include ('partials.site.cta')

</x-layouts.site>
