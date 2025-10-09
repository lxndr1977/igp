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
                   ['label' => 'NR-1 para Empresas'],
               ]"
               slim="true"
               navClass="py-6 mb-6"
               justify="center" />

            <div class="max-w-5xl mx-auto text-center">
               <h2
                  class="text-2xl md:text-3xl lg:text-5xl font-medium text-secondary-600 text-balance tracking-tight leading-[1.1] mb-6">
                  Adequação inteligente à NR-1, protegendo sua equipe e impulsionando resultados
               </h2>

               <p class="text-lg md:text-xl text-neutral-900 leading-relaxed text-balance mb-12">
                  A InRoche é especialista na <strong>implementação da NR-1</strong>, oferecendo soluções completas para
                  o <strong>gerenciamento de riscos ocupacionais</strong>, incluindo a identificação e prevenção de
                  fatores psicossociais. Atuamos desde o diagnóstico inicial até a capacitação das equipes, garantindo
                  conformidade legal, segurança dos colaboradores e aumento da produtividade.
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

      <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 md:pt-20 lg:pt-24">
         <p class="text-xl md:text-2xl mb-6 font-medium text-secondary-600 text-balance">
            A NR 1 estabelece as diretrizes fundamentais de segurança e saúde no trabalho, sendo obrigatória para todas
            as empresas que buscam atender à legislação trabalhista.
         </p>

         <p class="text-lg text-base leading-relaxed mb-6">
            Ela define responsabilidades, direitos e deveres, além de orientar a implementação do Gerenciamento de
            Riscos Ocupacionais (GRO) e do Programa de Gerenciamento de Riscos (PGR).
         </p>

         <p class="text-lg text-base leading-relaxed mb-6">
            Cumprir a NR 1 não é apenas atender à lei, mas também investir em prevenção, reduzindo acidentes,
            afastamentos e custos para a organização.
         </p>

         <p class="text-lg text-base leading-relaxed mb-6">
            Com profissionais especializados, é possível aplicar o mapeamento de riscos psicossociais, treinamentos e
            medidas de prevenção que tornam o ambiente de trabalho mais seguro e saudável.
         </p>

         <p class="text-lg text-base leading-relaxed mb-6">
            Na InRoche, oferecemos suporte completo em consultoria e treinamentos de NR 1, garantindo conformidade legal
            e valorizando o bem-estar das pessoas. 
         </p>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 md:pt-20 lg:pt-24">

         <!-- Cards de Benefícios -->
         <div class="grid md:grid-cols-3 gap-8 mb-20">
            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-lg hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-shield-check />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Conformidade legal</h3>
               <p class="text-neutral-600">
                  Adequação completa à NR-1, reduzindo riscos de penalidades e garantindo segurança jurídica.
               </p>
            </div>

            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-xl hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-heartbeat />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Segurança e saúde</h3>
               <p class="text-neutral-600">
                  Identificação e prevenção de fatores de risco, protegendo a saúde física e mental dos colaboradores.
               </p>
            </div>

            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-xl hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-trending-up />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Produtividade sustentável</h3>
               <p class="text-neutral-600">
                  Equipes mais preparadas, engajadas e produtivas em um ambiente de trabalho seguro e saudável.
               </p>
            </div>
         </div>

         <!-- Seção de Detalhes -->
         <div class="p-8 md:p-12">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
               <div>
                  <h3
                     class="mb-6 text-2xl md:text-3xl lg:text-4xl font-medium text-neutral-900 text-balance tracking-tight leading-[1.2]">
                     Implementação da NR-1 de forma prática e estratégica
                  </h3>

                  <div class="space-y-4 mb-8">
                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Diagnóstico completo de riscos ocupacionais</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Planos de ação alinhados à legislação</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Capacitação contínua para equipes</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Ambiente de trabalho mais seguro e produtivo</p>
                     </div>
                  </div>
               </div>

               <div class="relative">
                  <div class="bg-white rounded-2xl p-8 border-1 border-neutral-200 shadow-lg">
                     <div class="text-center">
                        <div
                           class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 mb-6 mx-auto">
                           <x-tabler-award class="w-10 h-10 text-secondary-600" />
                        </div>
                        <h4 class="text-xl font-semibold text-neutral-900 mb-2">Excelência em Compliance</h4>
                        <p class="text-neutral-600 md:text-lg">
                           Garanta a conformidade da sua empresa com a NR-1, reduza riscos legais e promova um ambiente
                           de trabalho mais saudável e produtivo para todos.
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
