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
                   ['label' => 'Serviços', 'url' => route('site.services')],
                   ['label' => 'Assessoria e Consultoria'],
               ]"
               slim="true"
               navClass="py-6 mb-6"
               justify="center" />

            <div class="max-w-5xl mx-auto text-center">
               <h2
                  class="text-2xl md:text-3xl lg:text-5xl font-medium text-secondary-600 text-balance tracking-tight leading-[1.1] mb-6">
                  Orientação especializada para otimizar processos e garantir resultados consistentes
               </h2>

               <p class="text-lg md:text-xl text-neutral-900 leading-relaxed text-balance mb-12">
                  Impulsione o sucesso da sua empresa com nossos <strong>serviços de assessoria e consultoria especializados</strong>. Nossa equipe experiente oferece soluções personalizadas para alcançar resultados excepcionais, desde o planejamento estratégico até a otimização de processos.
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
                  <x-tabler-target />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Planejamento estratégico</h3>
               <p class="text-neutral-600">
                  Definição de metas claras e direcionamento eficaz para o crescimento sustentável do seu negócio.
               </p>
            </div>

            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-xl hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-settings />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Otimização de processos</h3>
               <p class="text-neutral-600">
                  Identificação e melhoria de fluxos internos para aumentar eficiência e reduzir custos operacionais.
               </p>
            </div>

            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-xl hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-briefcase />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Resultados consistentes</h3>
               <p class="text-neutral-600">
                  Acompanhamento contínuo e soluções práticas que garantem impacto real e duradouro.
               </p>
            </div>
         </div>

         <!-- Seção de Detalhes -->
         <div class="p-8 md:p-12">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
               <div>
                  <h3
                     class="mb-6 text-2xl md:text-3xl lg:text-4xl font-medium text-neutral-900 text-balance tracking-tight leading-[1.2]">
                     Consultoria sob medida para seu negócio
                  </h3>

                  <div class="space-y-4 mb-8">
                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Planos personalizados para cada realidade empresarial</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Melhoria contínua dos processos internos</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Alinhamento estratégico para alta performance</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Resultados reais com impacto no crescimento empresarial</p>
                     </div>
                  </div>
               </div>

               <div class="relative">
                  <div class="bg-white rounded-2xl p-8 border-1 border-neutral-200 shadow-lg">
                     <div class="text-center">
                        <div
                           class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 mb-6 mx-auto">
                           <x-tabler-trophy class="w-10 h-10 text-secondary-600" />
                        </div>
                        <h4 class="text-xl font-semibold text-neutral-900 mb-2">Excelência em Resultados</h4>
                        <p class="text-neutral-600 md:text-lg">
                           Conte com nossa expertise para maximizar o desempenho da sua organização e alcançar resultados excepcionais de forma consistente e sustentável.
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
