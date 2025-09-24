<x-layouts.site>
   <div
      x-data="{ headerHeight: 0 }"
      x-init="const updateHeight = () => {
          const header = document.querySelector('header');
          headerHeight = header ? header.offsetHeight : 0;
      };
      updateHeight();
      window.addEventListener('resize', updateHeight);">

      <div class="bg-neutral-100 mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 lg:py-24">
         <div class="max-w-7xl mx-auto">
            <x-site.breadcrumbs
               :breadcrumb="[
                   ['label' => 'Início', 'url' => route('site.home')],
                   ['label' => 'Treinamentos', 'url' => route('site.trainings')],
                   ['label' => 'Escuta e Acolhimento aos Funcionários'],
               ]"
               slim="true"
               navClass="py-6 mb-6"
               justify="center" />

            <div class="max-w-5xl mx-auto text-center">
               <h2
                  class="text-2xl md:text-3xl lg:text-5xl font-medium text-secondary-600 text-balance tracking-tight leading-[1.1] mb-6">
                  Escuta e Acolhimento aos Funcionários
               </h2>

               <p class="text-lg md:text-xl text-neutral-900 leading-relaxed text-balance mb-12">
                  A <strong>pesquisa de clima humanizada</strong> é essencial para compreender o ambiente de trabalho e as reais necessidades dos colaboradores. Vai além dos números, capturando emoções, percepções e experiências que impactam diretamente no desempenho organizacional.
               </p>

               <div class="flex flex-col sm:flex-row gap-4 justify-center">
                  <x-mary-button link="#contato"
                     icon="tabler.brand-whatsapp"
                     class="btn btn-primary px-12 py-6 text-base"
                     label="Fale conosco" />
               </div>
            </div>
         </div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 md:pt-20 lg:pt-24">

         <!-- Cards de Benefícios -->
         <div class="grid md:grid-cols-3 gap-8 mb-20">
            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-lg hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-users />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Ambiente saudável</h3>
               <p class="text-neutral-600">
                  Estratégias que fortalecem a confiança, o engajamento e o bem-estar dos colaboradores.
               </p>
            </div>

            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-xl hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-brain />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Insights profundos</h3>
               <p class="text-neutral-600">
                  Identificação de percepções e emoções que impactam a cultura e a produtividade.
               </p>
            </div>

            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-xl hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-growth />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Crescimento sustentável</h3>
               <p class="text-neutral-600">
                  Valorização genuína das pessoas, gerando maior engajamento e resultados consistentes.
               </p>
            </div>
         </div>

         <!-- Seção de Detalhes -->
         <div class="p-8 md:p-12">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
               <div>
                  <h3
                     class="mb-6 text-2xl md:text-3xl lg:text-4xl font-medium text-neutral-900 text-balance tracking-tight leading-[1.2]">
                     Uma abordagem humanizada para o sucesso organizacional
                  </h3>

                  <div class="space-y-4 mb-8">
                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Análise de clima organizacional além dos números</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Identificação de necessidades reais dos colaboradores</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Fortalecimento da cultura de escuta e acolhimento</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Ambiente de trabalho mais produtivo e engajado</p>
                     </div>
                  </div>
               </div>

               <div class="relative">
                  <div class="bg-white rounded-2xl p-8 border-1 border-neutral-200 shadow-lg">
                     <div class="text-center">
                        <div
                           class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 mb-6 mx-auto">
                           <x-tabler-heart class="w-10 h-10 text-secondary-600" />
                        </div>
                        <h4 class="text-xl font-semibold text-neutral-900 mb-2">Valorização genuína</h4>
                        <p class="text-neutral-600 md:text-lg">
                           Demonstre que sua empresa valoriza o bem-estar de cada colaborador e promova um ambiente de confiança, engajamento e alta performance.
                        </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      @include ('partials.site.cta')
   </div>
</x-layouts.site>
