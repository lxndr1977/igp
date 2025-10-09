<section
   class="text-white bg-gradient-to-t from-secondary-600/90 via-secondary-600 to-secondary-600"
   x-data="{ headerHeight: 0 }"
   x-init="headerHeight = document.querySelector('header').offsetHeight   || 0;
   $nextTick(() => {
       window.addEventListener('resize', () => {
           headerHeight = document.querySelector('header').offsetHeight   || 0;
       });
   });"
   :style="`padding-top: ${headerHeight}px; `">
   <div class="max-w-7xl mx-auto px-6 lg:px-8 pt-12 pb-24">
      <div class="flex flex-col md:flex-row items-center gap-12 lg:gap-12">

         <!-- Texto -->
         <div class="w-full md:w-[57%] text-center md:text-left space-y-6">
            <h1 class="text-4xl md:text-5xl text-balance font-medium leading-tighter">
               Inovação em Gestão de Pessoas: impulsionando empresas e profissionais </h1>
            <p class="text-lg md:text-xl  max-w-xl md:max-w-none mx-auto md:mx-0 mb-12">
               Oferecemos soluções inovadoras em gestão de pessoas para organizações e apoiamos profissionais no
               crescimento de suas carreiras.
            </p>
            
            <div class="flex flex-col md:flex-row gap-6">
               <a href="https://web.whatsapp.com/send?phone=5554996831871" class="btn btn-primary w-auto btn-lg py-4 px-8 flex items-center gap-2">
                  <x-tabler-brand-whatsapp class="w-5 h-5" />
                  <span >Fale conosco</span>
               </a>
               <a href="{{ route('site.job-vacancies') }}" class="btn btn-outline w-auto btn-lg py-4 px-8  flex items-center gap-2">
                  <x-tabler-users class="w-5 h-5" />
                  <span >Ver Vagas</span>
               </a>
            </div>
         </div>

         <!-- Imagem -->
         <div class="w-full md:w-[43%] flex justify-center md:justify-end">
            <img src="{{ asset('images/hero3.webp') }}"
               alt="Hero"
               class="w-full object-contain rounded-xl">
         </div>

      </div>
   </div>
</section>
