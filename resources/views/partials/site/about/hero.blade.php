 <section
    class="bg-secondary-600 text-white pb-20"
    x-data="{ headerHeight: 0 }"
    x-init="headerHeight = document.querySelector('header').offsetHeight + 60 || 0;
    $nextTick(() => {
        window.addEventListener('resize', () => {
            headerHeight = document.querySelector('header').offsetHeight + 60 || 0;
        });
    });"
    :style="`padding-top: ${headerHeight}px`">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
       <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center text-white">
          <div class="order-2 md:order-1">
             <h2 class="mb-2 md:mb-4 text-2xl md:text-5xl font-medium tracking-tight text-balance leading-[1.2]">
                Desenvolvendo Pessoas, Impulsionando Organizações
             </h2>

             <p class="text-base md:text-lg leading-relaxed mb-3 ">Na InRoche, acreditamos
                que a verdadeira inovação nasce das pessoas. Atuamos na gestão estratégica de pessoas, desenvolvimento
                organizacional e formação de talentos estratégicos — ajudando empresas a alinhar resultados de negócio
                com o crescimento humano.
             </p>
             <p class="text-sm leading-relaxed mb-12">Rochele Machado | Diretora</p>

             <x-mary-button link="#contato"
                icon="tabler.brand-whatsapp"
                class="btn btn-primary text-base"
                label="Fale conosco" />
          </div>

          <div class="order-1 md:order-2">
             <img src="{{ asset('images/rochelle-machado.webp') }}"
                alt="Foto Rochelle Machado"
                loading="lazy"
                class="rounded-lg max-w-md w-full mx-auto">
          </div>
       </div>
    </div>
 </section>
