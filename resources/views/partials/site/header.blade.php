  <header x-data="{
      mobileMenuOpen: false,
      lastScrollY: 0,
      headerVisible: true,
  
      menuItems: [{
              name: 'Serviços',
              href: '{{ route('site.services') }}',
              active: false,
              hasSubmenu: true,
              submenu: [
                  { name: 'Treinamentos', href: '{{ route('site.services.trainings') }}' },
                  { name: 'Consultoria', href: '{{ route('site.services.consulting') }}' },
                  { name: 'Palestras', href: '{{ route('site.services.talks') }}' },
                  { name: 'NR-1 para Empresas', href: '{{ route('site.services.nr-1') }}' },
                  { name: 'Recrutamento Estratégico', href: '{{ route('site.services.recruitment') }}' },
              ]
          },
          {
              name: 'Treinamentos',
              href: '{{ route('site.trainings') }}',
              active: false,
              hasSubmenu: true,
              submenu: [
                  { name: 'NR-1', href: '{{ route('site.trainings.nr-1') }}' },
                  { name: 'Escuta e Acolhimento', href: '{{ route('site.trainings.employee-listing') }}' },
              ]
          },
          {
              name: 'Vagas',
              href: '{{ route('site.job-vacancies') }}',
              active: false,
          },
          {
              name: 'Sobre',
              href: '{{ route('site.about') }}',
              active: false
          },
          {
              name: 'Contato',
              href: '{{ route('site.contact') }}',
              active: false
          }
      ],
  
      // Controle de submenus abertos
      openSubmenu: null,
  
      // Configurações do contato
      contactButton: {
          text: 'Fale conosco',
          href: 'https://web.whatsapp.com/send?phone=5554996831871'
      },
  
      // Configurações da logo
      logo: {
          text: 'InRoche',
          href: '/',
          icon: 'I'
      },
  
      init() {
          this.lastScrollY = window.scrollY;
  
          window.addEventListener('scroll', () => {
              const currentScrollY = window.scrollY;
  
              if (currentScrollY <= 10) {
                  this.headerVisible = true;
              } else if (currentScrollY > this.lastScrollY && currentScrollY > 100) {
                  this.headerVisible = false;
              } else if (currentScrollY < this.lastScrollY) {
                  this.headerVisible = true;
              }
  
              this.lastScrollY = currentScrollY;
          }, { passive: true });
      },
  
      navigateTo(href) {
          this.mobileMenuOpen = false;
          this.openSubmenu = null;
          window.location.href = href;
      },
  
      toggleSubmenu(itemName) {
          this.openSubmenu = this.openSubmenu === itemName ? null : itemName;
      },
  
      closeSubmenu() {
          this.openSubmenu = null;
      }
  }"
     class="bg-secondary-600 fixed top-0 left-0 right-0 z-50 transition-transform duration-300"
     :class="{ '-translate-y-full': !headerVisible, 'translate-y-0': headerVisible }">

     <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-1 border-secondary-500">
        <div class="flex justify-between items-center py-3">

           <!-- Logo -->
           <div class="flex-shrink-0 flex items-center">
              <a :href="logo.href" aria-label="Página inicial - InRoche" class="flex items-center">
                 <img
                    src="{{ asset('images/logo-inroche-inovacao-gestao-pessoas.webp') }}"
                    alt="Logo da InRoche - Inovação e Gestão de Pessoas"
                    class="h-12 md:h-14 w-auto">
              </a>
           </div>

           <!-- Menu Desktop - Centralizado -->
           <nav class="hidden md:flex items-center space-x-8">
              <template x-for="item in menuItems" :key="item.name">
                 <div class="relative"
                    @mouseenter="item.hasSubmenu ? openSubmenu = item.name : null"
                    @mouseleave="item.hasSubmenu ? openSubmenu = null : null">

                    <!-- Item principal -->
                    <div class="flex items-center">
                       <a :href="item.href"
                          class="color-secondary-content px-3 py-2 rounded-lg font-medium transition-all duration-200 relative flex items-center space-x-1 hover:bg-primary-600"
                          :class="{
                              'bg-primary-600 text-secondary-600': item.active || openSubmenu === item.name,
                              'text-white hover:text-secondary-600': !item.active && openSubmenu !== item.name,
                              'text-white': !item.active && openSubmenu !== item.name
                          }">
                          <span x-text="item.name"></span>

                          <!-- Ícone seta para submenu -->
                          <svg x-show="item.hasSubmenu"
                             class="w-4 h-4 transform transition-transform duration-200"
                             :class="{ 'rotate-180': openSubmenu === item.name }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                             </path>
                          </svg>
                       </a>
                    </div>

                    <!-- Submenu Desktop -->
                    <div x-show="item.hasSubmenu && openSubmenu === item.name"
                       x-transition:enter="transition ease-out duration-200"
                       x-transition:enter-start="opacity-0 transform -translate-y-2"
                       x-transition:enter-end="opacity-100 transform translate-y-0"
                       x-transition:leave="transition ease-in duration-150"
                       x-transition:leave-start="opacity-100 transform translate-y-0"
                       x-transition:leave-end="opacity-0 transform -translate-y-2"
                       x-cloak
                       class="absolute top-full left-0 mt-2 w-80 bg-white rounded-xl border border-neutral-100 py-6 z-50"
                       style="box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
                       <template x-for="subitem in item.submenu" :key="subitem.name">
                          <div class="px-4">
                             <a :href="subitem.href"
                                class="block px-4 py-3 text-neutral-700 hover:bg-primary-600 hover:text-secondary-600 transition-colors duration-200 text-sm lg:text-base rounded">
                                <span x-text="subitem.name"></span>
                             </a>
                          </div>
                       </template>
                    </div>
                 </div>
              </template>
           </nav>

           <!-- Botão Contato Desktop -->
           <div class="hidden md:flex items-center">
              <a :href="contactButton.href" class="btn btn-primary flex items-center gap-2">
                 <x-tabler-brand-whatsapp class="w-5 h-5" />
                 <span x-text="contactButton.text"></span>
              </a>
           </div>

           <!-- Botão Menu Mobile -->
           <div class="md:hidden">
              <button
                 @click="mobileMenuOpen = !mobileMenuOpen"
                 class="color-secondary-content hover:bg-primary-600 hover:bg-opacity-20 focus:outline-none p-2 rounded-lg transition-colors duration-200"
                 :class="{ 'bg-primary-600 bg-opacity-20': mobileMenuOpen }"
                 :aria-expanded="mobileMenuOpen">
                 <svg
                    class="h-6 w-6 transform transition-transform duration-200"
                    :class="{ 'text-secondary-600': mobileMenuOpen, 'text-white': !mobileMenuOpen, 'rotate-90': mobileMenuOpen }"
                    stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                       d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                       d="M6 18L18 6M6 6l12 12" />
                 </svg>
              </button>
           </div>
        </div>
     </div>

     <!-- Menu Mobile -->
     <div x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="md:hidden bg-zinc-50 text-secondary-600">
        <div class="px-4 pt-4 pb-6 space-y-2">
           <template x-for="item in menuItems" :key="item.name">
              <div>
                 <!-- Item principal mobile -->
                 <div class="flex items-center justify-between">
                    <a :href="item.hasSubmenu ? '#' : item.href"
                       @click="item.hasSubmenu ? (openSubmenu = openSubmenu === item.name ? null : item.name) : navigateTo(item.href)"
                       class="flex-1 block px-4 py-3 color-secondary-content hover:bg-white hover:bg-opacity-20 rounded-xl text-base font-medium transition-all duration-200"
                       :class="{ 'bg-primary-600 text-secondary-600 bg-opacity-10': item.active || openSubmenu === item.name }">
                       <div class="flex items-center justify-between">
                          <span x-text="item.name"></span>
                          <!-- Ícone seta para submenu mobile -->
                          <svg x-show="item.hasSubmenu"
                             class="w-5 h-5 transform transition-transform duration-200"
                             :class="{ 'rotate-180': openSubmenu === item.name }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                             </path>
                          </svg>
                       </div>
                    </a>
                 </div>

                 <!-- Submenu Mobile -->
                 <div x-show="item.hasSubmenu && openSubmenu === item.name"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-1"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-1"
                    class="ml-4 mt-2 space-y-1">
                    <template x-for="subitem in item.submenu" :key="subitem.name">
                       <a :href="subitem.href"
                          @click="navigateTo(subitem.href)"
                          class="block px-4 py-2 color-secondary-content opacity-80 hover:opacity-100 hover:bg-white hover:bg-opacity-10 rounded-lg text-sm transition-all duration-200">
                          <span x-text="subitem.name"></span>
                       </a>
                    </template>
                 </div>
              </div>
           </template>

           <!-- Botão Contato Mobile -->
           <div class="pt-4 mt-4">
              <a :href="contactButton.href"
                 @click="navigateTo(contactButton.href)"
                 class="block w-full bg-primary-600 text-secondary-600 py-3 rounded-xl text-base font-semibold text-center hover:bg-opacity-90 transition-all duration-200"
                 x-text="contactButton.text">
              </a>
           </div>
        </div>
     </div>
  </header>
