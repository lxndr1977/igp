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
         <div class="max-w-7xl mx-auto  ">
            <x-site.breadcrumbs
               :breadcrumb="[
                   ['label' => 'Início', 'url' => route('site.home')],
                   ['label' => 'Serviços', 'url' => route('site.services')],
                   ['label' => 'Treinamentos'],
               ]"
               slim="true"
               navClass="py-6 mb-6"
               justify="center" />

            <div class="max-w-5xl mx-auto text-center">
               <h2
                  class="text-2xl md:text-3xl lg:text-5xl font-medium text-secondary-600 text-balance tracking-tight leading-[1.1] mb-6">
                  Investir em pessoas é garantir o futuro da empresa.
               </h2>

               <p class="text-lg md:text-xl text-neutral-900 leading-relaxed text-balance  mb-12   ">
                  O treinamento fortalece competências e prepara equipes para novos desafios. Na InRoche, acreditamos
                  que desenvolver pessoas é desenvolver empresas.
               </p>

               <div class="flex flex-col sm:flex-row gap-4 justify-center">
                  <x-mary-button link="#contato"
                     icon="tabler.brand-whatsapp"
                     class="btn btn-primary px-12 py-6 text-base "
                     label="Fale conosco" />
               </div>
            </div>
         </div>
      </div>

      <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 md:pt-20 lg:pt-24">
         <p class="text-xl md:text-2xl mb-6 font-medium text-secondary-600 text-balance">
            Com programas personalizados, alinhamos o desenvolvimento humano às metas organizacionais, promovendo
            crescimento e resultados consistentes.
         </p>

         <p class="text-lg text-base leading-relaxed mb-6">
            Os treinamentos aumentam a motivação, o engajamento e tornam o ambiente de trabalho mais saudável e
            colaborativo.
         </p>

         <p class="text-lg text-base leading-relaxed mb-6">
            Empresas que capacitam continuamente suas equipes têm maior capacidade de adaptação, inovação e
            competitividade.
         </p>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 md:pt-20 lg:pt-24">

         <!-- Cards de Benefícios -->
         <div class="grid md:grid-cols-3 gap-8 mb-20">
            <div
               class="bg-white rounded-2xl p-8 border border-neutra-200 hover:shadow-lg hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-bulb />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Inovação e engajamento</h3>
               <p class="text-neutral-900">
                  Programas personalizados que combinam metodologias modernas para máximo engajamento e retenção do
                  aprendizado.
               </p>
            </div>

            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-xl hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-tools />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Competências técnicas</h3>
               <p class="text-neutral-900">
                  Fortalecimento de habilidades técnicas e comportamentais essenciais para o crescimento profissional.
               </p>
            </div>

            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-xl hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-rocket />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Preparação para o futuro</h3>
               <p class="text-neutral-900">
                  Equipes preparadas para os desafios futuros com foco em liderança, comunicação e produtividade.
               </p>
            </div>
         </div>

         <!-- Seção de Detalhes -->
         <div class="p-8 md:p-12">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
               <div>
                  <h3
                     class="mb-6 text-2xl md:text-3xl lg:text-4xl font-medium text-neutral-900 text-balance tracking-tight leading-[1.2]">
                     Programas estruturados para
                     resultados reais
                  </h3>

                  <div class="space-y-4 mb-8">
                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Aumento significativo da produtividade</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Melhoria na comunicação interna</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Potencialização da liderança em todos os níveis</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Ambiente de aprendizado contínuo</p>
                     </div>
                  </div>
               </div>

               <div class="relative">
                  <div class="bg-white rounded-2xl p-8 border-1 border-neutral-200 shadow-lg">
                     <div class="text-center">
                        <div
                           class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 mb-6 mx-auto">
                           <x-tabler-trending-up class="w-10 h-10 text-secondary-600" />
                        </div>
                        <h4 class="text-xl font-semibold text-neutral-900 mb-2">Resultados Comprovados</h4>
                        <p class="text-neutral-900 md:text-lg">
                           Transforme sua empresa em um ambiente de alta performance com equipes mais motivadas,
                           preparadas e orientadas para resultados.
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
