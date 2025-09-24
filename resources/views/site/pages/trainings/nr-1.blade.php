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
                   ['label' => 'NR-1: Mais saúde e performance'],
               ]"
               slim="true"
               navClass="py-6 mb-6"
               justify="center" />

            <div class="max-w-5xl mx-auto text-center">
               <h2
                  class="text-2xl md:text-3xl lg:text-5xl font-medium text-secondary-600 text-balance tracking-tight leading-[1.1] mb-6">
                  NR-1: Mais saúde para sua equipe, mais performance para o seu negócio
               </h2>

               <p class="text-lg md:text-xl text-neutral-900 leading-relaxed text-balance mb-12">
                  A <strong>Norma Regulamentadora nº 1 (NR-1)</strong> estabelece as diretrizes para o
                  <strong>Gerenciamento de Riscos Ocupacionais</strong>, incluindo os fatores psicossociais. Mais que um
                  requisito legal, ela é uma oportunidade de criar um ambiente de trabalho saudável, produtivo e
                  sustentável.
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
                  <x-tabler-shield-check />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Proteção integral</h3>
               <p class="text-neutral-600">
                  Reduza afastamentos e proteja a saúde física e mental dos colaboradores.
               </p>
            </div>

            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-xl hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-scale />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Segurança jurídica</h3>
               <p class="text-neutral-600">
                  Evite multas, passivos trabalhistas e garanta conformidade com a legislação.
               </p>
            </div>

            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-xl hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-trending-up />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Mais performance</h3>
               <p class="text-neutral-600">
                  Colaboradores saudáveis e engajados, impulsionando resultados e inovação.
               </p>
            </div>
         </div>

         <!-- Seção de Detalhes -->
         <div class="p-8 md:p-12">
            <div class="grid lg:grid-cols-2 gap-12 items-start">
               <div>
                  <h3
                     class="mb-6 text-2xl md:text-3xl lg:text-4xl font-medium text-neutral-900 text-balance tracking-tight leading-[1.2]">
                     Os 10 principais fatores de riscos psicossociais
                  </h3>

                  <ul class="space-y-4 text-neutral-700 md:text-lg">
                     <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-7 h-7 mt-0.5 bg-primary-600 rounded-full flex items-center justify-center">
                           <span class="text-secondary-600 font-bold text-sm">1</span>
                        </div>
                        <div>
                           <strong>Clima organizacional:</strong> qualidade das relações, confiança e ambiente de
                           trabalho.
                        </div>
                     </li>
                     <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-7 h-7 mt-0.5 bg-primary-600 rounded-full flex items-center justify-center">
                           <span class="text-secondary-600 font-bold text-sm">2</span>
                        </div>
                        <div>
                           <strong>Carga excessiva de trabalho:</strong> tarefas e prazos incompatíveis com a
                           capacidade.
                        </div>
                     </li>
                     <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-7 h-7 mt-0.5 bg-primary-600 rounded-full flex items-center justify-center">
                           <span class="text-secondary-600 font-bold text-sm">3</span>
                        </div>
                        <div>
                           <strong>Reconhecimento e recompensas:</strong> valorização, feedback e incentivos.
                        </div>
                     </li>
                     <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-7 h-7 mt-0.5 bg-primary-600 rounded-full flex items-center justify-center">
                           <span class="text-secondary-600 font-bold text-sm">4</span>
                        </div>
                        <div>
                           <strong>Assédio moral e sexual:</strong> comportamentos humilhantes ou de cunho sexual.
                        </div>
                     </li>
                     <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-7 h-7 mt-0.5 bg-primary-600 rounded-full flex items-center justify-center">
                           <span class="text-secondary-600 font-bold text-sm">5</span>
                        </div>
                        <div>
                           <strong>Autonomia e controle:</strong> liberdade para decidir sobre as tarefas.
                        </div>
                     </li>
                     <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-7 h-7 mt-0.5 bg-primary-600 rounded-full flex items-center justify-center">
                           <span class="text-secondary-600 font-bold text-sm">6</span>
                        </div>
                        <div>
                           <strong>Pressão e metas:</strong> intensidade das cobranças e realismo dos objetivos.
                        </div>
                     </li>
                     <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-7 h-7 mt-0.5 bg-primary-600 rounded-full flex items-center justify-center">
                           <span class="text-secondary-600 font-bold text-sm">7</span>
                        </div>
                        <div>
                           <strong>Insegurança e ameaças:</strong> medo de demissão ou instabilidade organizacional.
                        </div>
                     </li>
                     <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-7 h-7 mt-0.5 bg-primary-600 rounded-full flex items-center justify-center">
                           <span class="text-secondary-600 font-bold text-sm">8</span>
                        </div>
                        <div>
                           <strong>Conflitos e comunicação:</strong> falhas de relacionamento e troca de informações.
                        </div>
                     </li>
                     <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-7 h-7 mt-0.5 bg-primary-600 rounded-full flex items-center justify-center">
                           <span class="text-secondary-600 font-bold text-sm">9</span>
                        </div>
                        <div>
                           <strong>Equilíbrio vida pessoal vs. profissional:</strong> impacto das exigências na vida
                           privada.
                        </div>
                     </li>
                     <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-7 h-7 mt-0.5 bg-primary-600 rounded-full flex items-center justify-center">
                           <span class="text-secondary-600 font-bold text-sm">10</span>
                        </div>
                        <div>
                           <strong>Qualidade da liderança:</strong> competência, comportamento e suporte dos gestores.
                        </div>
                     </li>
                  </ul>
               </div>

               <div class="relative">
                  <div class="bg-white rounded-2xl p-8 border-1 border-neutral-200 shadow-lg">
                     <div class="text-center">
                        <div
                           class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 mb-6 mx-auto">
                           <x-tabler-heartbeat class="w-10 h-10 text-secondary-600" />
                        </div>
                        <h4 class="text-xl font-semibold text-neutral-900 mb-2">Saúde e Sustentabilidade</h4>
                        <p class="text-neutral-600 md:text-lg">
                           Investir na adequação à NR-1 é investir no bem-estar da sua equipe e na sustentabilidade do
                           seu negócio. Mais saúde, menos riscos e maior performance.
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
