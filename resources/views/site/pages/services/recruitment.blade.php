<x-layouts.site>
   <div
      x-data="{ headerHeight: 0 }"
      x-init="const updateHeight = () => {
          const header = document.querySelector('header');
          headerHeight = header ? header.offsetHeight : 0;
      };
      updateHeight();
      window.addEventListener('resize', updateHeight);">

      <!-- Intro -->
      <div class="bg-neutral-100 mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 lg:py-24">
         <div class="max-w-7xl mx-auto">
            <x-site.breadcrumbs
               :breadcrumb="[
                   ['label' => 'Início', 'url' => route('site.home')],
                   ['label' => 'Serviços', 'url' => route('site.services')],
                   ['label' => 'Recrutamento e Seleção de Pessoal'],
               ]"
               slim="true"
               navClass="py-6 mb-6"
               justify="center" />

            <div class="max-w-5xl mx-auto text-center">
               <h2
                  class="text-2xl md:text-3xl lg:text-5xl font-medium text-secondary-600 text-balance tracking-tight leading-[1.1] mb-6">
                  Pessoas certas nos lugares certos: Recrutamento e Seleção de Pessoal
               </h2>

               <p class="text-lg md:text-xl text-neutral-900 leading-relaxed text-balance mb-12">
                  Na <strong>InRoche Inovação em Gestão de Pessoas</strong>, oferecemos um processo de
                  <strong>Recrutamento e Seleção estruturado, estratégico e humanizado</strong>. Mais do que preencher
                  vagas, ajudamos sua empresa a atrair talentos alinhados à cultura organizacional, reduzindo turnover e
                  fortalecendo a performance da equipe.
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

      <!-- Benefícios -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 md:pt-20 lg:pt-24">
         <div class="grid md:grid-cols-3 gap-8 mb-20">
            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-lg hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-user-search />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Talentos alinhados</h3>
               <p class="text-neutral-600">
                  Selecionamos profissionais que compartilham os valores e objetivos da sua empresa, fortalecendo a
                  cultura organizacional.
               </p>
            </div>

            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-xl hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-user-check />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Processo humanizado</h3>
               <p class="text-neutral-600">
                  Metodologia que valoriza competências técnicas e comportamentais, proporcionando contratações mais
                  assertivas.
               </p>
            </div>

            <div
               class="bg-white rounded-2xl p-8 border border-neutral-200 hover:shadow-xl hover:-translate-y-1 transition duration-300">
               <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6">
                  <x-tabler-trending-up />
               </div>
               <h3 class="text-xl font-semibold text-neutral-900 mb-4">Seleção estratégica</h3>
               <p class="text-neutral-600">
                  Profissionais certos geram maior engajamento, produtividade e retenção de talentos estratégicos.
               </p>
            </div>
         </div>


      <!-- Como trabalhamos -->
      <div class="bg-neutral-50 py-16 md:py-20 lg:py-24">
         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center mb-12">
               <h3 class="text-2xl md:text-3xl lg:text-4xl font-medium text-neutral-900 mb-4">
                  Como trabalhamos
               </h3>
               <p class="text-lg text-neutral-700">
                  Nosso processo de <strong>Recrutamento e Seleção estratégico</strong> combina análise técnica, visão
                  comportamental e alinhamento cultural, garantindo escolhas assertivas e sustentáveis para sua empresa.
               </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
               <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-sm hover:shadow-md transition">
                  <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6 font-bold">
                     1
                  </div>
                  <h4 class="font-semibold text-lg text-neutral-900 mb-2">Levantamento de perfil</h4>
                  <p class="text-neutral-600">Entendemos em profundidade as necessidades da vaga, a cultura
                     organizacional e os objetivos da empresa.</p>
               </div>

               <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-sm hover:shadow-md transition">
                  <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6 font-bold">
                     2
                  </div>
                  <h4 class="font-semibold text-lg text-neutral-900 mb-2">Divulgação estratégica</h4>
                  <p class="text-neutral-600">Atraímos candidatos por meio dos canais mais adequados ao perfil buscado,
                     garantindo alcance e relevância.</p>
               </div>

               <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-sm hover:shadow-md transition">
                  <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6 font-bold">
                     3
                  </div>
                  <h4 class="font-semibold text-lg text-neutral-900 mb-2">Triagem criteriosa</h4>
                  <p class="text-neutral-600">Analisamos currículos e pré-selecionamos candidatos de acordo com
                     requisitos técnicos e comportamentais.</p>
               </div>

               <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-sm hover:shadow-md transition">
                  <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6 font-bold">
                     4
                  </div>
                  <h4 class="font-semibold text-lg text-neutral-900 mb-2">Entrevistas por competências</h4>
                  <p class="text-neutral-600">Avaliamos habilidades técnicas, comportamentos, valores e potencial de
                     crescimento de cada candidato.</p>
               </div>

               <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-sm hover:shadow-md transition">
                  <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6 font-bold">
                     5
                  </div>
                  <h4 class="font-semibold text-lg text-neutral-900 mb-2">Testes e dinâmicas</h4>
                  <p class="text-neutral-600">Utilizamos ferramentas psicológicas e avaliações específicas para maior
                     precisão na escolha final.</p>
               </div>

               <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-sm hover:shadow-md transition">
                  <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-6 font-bold">
                     6
                  </div>
                  <h4 class="font-semibold text-lg text-neutral-900 mb-2">Apresentação de finalistas</h4>
                  <p class="text-neutral-600">Encaminhamos os candidatos mais qualificados, facilitando a decisão final
                     do gestor.</p>
               </div>
            </div>

         </div>
      </div>


      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
         <div class="p-8 md:p-12">

            <div class="grid lg:grid-cols-2 gap-12 items-center">
               <div>
                  <h3
                     class="mb-6 text-2xl md:text-3xl lg:text-4xl font-medium text-neutral-900 text-balance tracking-tight leading-[1.2]">
                     Por que escolher a InRoche?
                  </h3>

                  <div class="space-y-4 mb-8">
                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg"> Especialização em Gestão de Pessoas com mais de 18 anos de
                           experiência</p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">
                           Atendimento personalizado e próximo à realidade de cada cliente
                        </p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Processo ágil e assertivo, reduzindo custos com turnover
                        </p>
                     </div>

                     <div class="flex items-start gap-4">
                        <div
                           class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                           <x-tabler-check class="w-4 h-4 text-secondary-600" />
                        </div>
                        <p class="text-neutral-700 text-lg">Compromisso com ética, confidencialidade e resultados de
                           longo
                           prazo
                        </p>
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
                        <h4 class="text-xl font-semibold text-neutral-900 mb-2">Construindo equipes de alta performance
                        </h4>
                        <p class="text-neutral-600 md:text-lg">
                           Mais do que contratar, ajudamos a estruturar times alinhados à cultura organizacional,
                           capazes
                           de
                           impulsionar os resultados e fortalecer a marca empregadora.
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
