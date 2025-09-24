<!-- Vagas -->
<section class="py-20 md:py-24 lg:py-32 bg-secondary-600 text-white">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
         <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Oportunidades de Carreira
         </h2>

         <p class="text-xl max-w-2xl mx-auto">
            Encontre a chance de colocar em prática seus talentos, desenvolver novas habilidades e alcançar o futuro que
            você sempre desejou
         </p>
      </div>

      <div
         class="grid gap-6 
            @switch(count($featuredJobVacancies))
               @case(1)
                     grid-cols-1 max-w-md mx-auto
                     @break
               @case(2)
                     grid-cols-1 md:grid-cols-2 max-w-4xl mx-auto
                     @break
               @default
                     grid-cols-1 lg:grid-cols-3
            @endswitch
         ">
         @foreach ($featuredJobVacancies as $job)
            <x-site.job-vacancy-card :job=$job />
         @endforeach
      </div>

      <div class="text-center mt-12">
         <x-mary-button link="/vagas"
            icon="tabler.clipboard-list"
            class="btn btn-primary text-base px-12 py-6"
            label="Ver Todas as Vagas" />
      </div>
   </div>
</section>



