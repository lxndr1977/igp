<x-layouts.site>
   <x-site.page-header
      title="Fale conosco"
      subtitle="Saiba como podemos impulsionar o seu crescimento"
      :breadcrumb="[['label' => 'Início', 'url' => route('site.home')], ['label' => 'Contato']]" />

   <section class="py-16 ">
      <div class="container mx-auto px-6">
         <div class="max-w-4xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12">
               <div class="space-y-8 md:space-y-12">
                  <h2 class="text-xl md:text-2xl lg:text-4xl font-medium text-balance leading-[1.2] tracking-tight mb-4">
                     Inovação em gestão de pessoas ao seu alcance
                  </h2>
                  <p class="mb-6 md:mb-12 text-neutral-900">
                     Entre em contato com a InRoche e descubra como podemos transformar a performance da sua equipe e os
                     resultados da sua empresa.
                  </p>

                  <div class="flex items-start justify-start gap-4">
                     <div class="w-12 h-12 bg-neutral-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <x-tabler-phone class="w-6 h-6 text-secondary-600" />
                     </div>
                     <div>
                        <h4 class="font-medium text-neutral-800 mb-1">Telefone</h4>
                        <p class="text-neutral-900">(54) 99683-1871</p>
                     </div>
                  </div>

                  <div class="flex items-start gap-4">
                     <div class="w-12 h-12 bg-neutral-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <x-tabler-mail class="w-6 h-6 text-secondary-600" />
                     </div>
                     <div>
                        <h4 class="font-medium text-neutral-800 mb-1">E-mail</h4>
                        <p class="text-neutral-900">contato@empresa.com</p>
                     </div>
                  </div>

                  <div class="flex items-start gap-4">
                     <div class="w-12 h-12 bg-neutral-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <x-tabler-map-pin class="w-6 h-6 text-secondary-600" />
                     </div>
                     <div>
                        <h4 class="font-medium text-neutral-800 mb-1">Endereço</h4>
                        <p class="text-neutral-900">
                           Avenida Júlio de Castilhos, 1481 - Sala 13<br>
                           Nossa Sra. de Lourdes<br>
                           Caxias do Sul, RS, 95.010-003
                        </p>
                     </div>
                  </div>

                  <div class="flex items-start gap-4">
                     <div class="w-12 h-12 bg-neutral-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <x-tabler-clock class="w-6 h-6 text-secondary-600" />
                     </div>
                     <div>
                        <h4 class="font-medium text-neutral-800 mb-1">Atendimento</h4>
                        <p class="text-neutral-900">Segunda à Sexta: 8h às 18h</p>
                     </div>
                  </div>
               </div>

               <div class="flex justify-center">
                  <div
                     class="bg-neutral-50 rounded-2xl border border-neutral-200 p-10 max-w-md w-full h-full flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition duration-300">
                     <div class="text-center flex-1 flex flex-col justify-center">
                        <div
                           class="w-24 h-24 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                           <img src="{{ asset('images/rochelle-machado-1x1.webp') }}"
                              alt="Foto de Rochelle Machado"
                              loading="lazy"
                              class="rounded-full aspect-square">
                        </div>
                        <h3 class="text-3xl font-medium text-neutral-800 mb-3">Rochelle Machado</h3>
                        <p class="text-lg text-neutral-900 mb-8">Diretora</p>

                        <div class="space-y-4 text-left">
                           <div class="flex items-center gap-3 text-neutral-700">
                              <x-tabler-check class="w-5 h-5 text-green-600" />
                              <span>Atendimento imediato</span>
                           </div>
                           <div class="flex items-center gap-3 text-neutral-700">
                              <x-tabler-check class="w-5 h-5 text-green-600" />
                              <span>Suporte personalizado</span>
                           </div>
                        </div>
                     </div>

                     <div class="mt-8">
                        <x-mary-button
                           link="https://web.whatsapp.com/send?phone=5554996831871"
                           icon="tabler.brand-whatsapp"
                           class="btn btn-primary w-full py-6 md:text-base"
                           label="Conversar no WhatsApp" />

                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</x-layouts.site>
