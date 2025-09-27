<section
   class="bg-secondary-600 text-white pb-20"
   x-data="{ headerHeight: 0 }"
   x-init="headerHeight = document.querySelector('header').offsetHeight + 60  || 0;
   $nextTick(() => {
       window.addEventListener('resize', () => {
           headerHeight = document.querySelector('header').offsetHeight + 60  || 0;
       });
   });"
   :style="`padding-top: ${headerHeight}px`">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row items-center justify-center gap-12">
         <!-- Coluna de Texto -->
         <div class="flex-1 text-center md:text-left">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
               Inovação em Gestão de Pessoas
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">
               Nós Inovamos, instruímos e inspiramos organizações, ajudando-as a encontrar os melhores talentos para suas
               necessidades de negócios
            </p>
            <x-mary-button label="Fale conosco" link="#contato" class="btn btn-primary btn-lg" />
         </div>

         <!-- Coluna da Foto -->
         <div class="flex-1">
            <div class="bg-white/10 rounded-lg p-8 backdrop-blur-sm">
               <div class="aspect-square bg-white/20 rounded-lg flex items-center justify-center">
                  <svg class="w-24 h-24 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                     </path>
                  </svg>
               </div>
               <p class="text-center text-white/80 mt-4 text-sm">
                  [Substitua por uma imagem da equipe ou escritório]
               </p>
            </div>
         </div>
      </div>
   </div>
</section>
