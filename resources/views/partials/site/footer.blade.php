@php
   $links = [
       [
           'label' => 'Serviços',
           'route' => route('site.services'),
       ],
       [
           'label' => 'Treinamentos',
           'route' => route('site.trainings'),
       ],
       [
           'label' => 'Vagas',
           'route' => route('site.job-vacancies'),
       ],
       [
           'label' => 'Sobre',
           'route' => route('site.about'),
       ],
   ];
@endphp

<footer class="bg-secondary text-secondary-content">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex flex-col md:flex-row justify-between gap-12 md:gap-0 text-center md:text-left">
      <div class="flex flex-col items-center md:items-start">
         <a href="/" aria-label="Página inicial - InRoche" class="flex items-center justify-center md:justify-start">
            <img
               src="{{ asset('images/logo-inroche-inovacao-gestao-pessoas.webp') }}"
               alt="Logo da InRoche - Inovação e Gestão de Pessoas"
               class="h-12 md:h-18 w-auto mb-12">
         </a>

         <div>
            <a href="https://instagram.com/inovacao.rh">
               <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center mb-12 md:mb-24 mx-auto md:mx-0">
                  <x-tabler-brand-instagram class="w-auto h-6 text-secondary-600" />
               </div>
            </a>
         </div>
      </div>

      <div class="flex flex-col md:flex-row gap-12 md:gap-24 lg:gap-64 w-full md:w-auto items-center md:items-start">
         <div class="flex-0">
            <h2 class="font-medium text-primary-600 mb-6">Informações</h2>

            @if (isset($links) && $links)
               <ul class="space-y-6">
                  @foreach ($links as $link)
                     <li><a href="{{ $link['route'] }}">{{ $link['label'] }}</a></li>
                  @endforeach
               </ul>
            @endif
         </div>

         <div class="flex-1">
            <h2 class="font-medium text-primary-600 mb-6">Atendimento</h2>

            <ul class="space-y-6">
               <li>
                  <a href="https://web.whatsapp.com/send?phone=5554996831871"
                     target="_blank"
                     class="flex flex-row justify-center md:justify-start gap-2 text-lg items-center font-medium">
                     <x-tabler-brand-whatsapp class="w-5 h-5" />
                     <span>(54) 99683-1871</span>
                  </a>
               </li>

               <li class="text-sm space-y-1">
                  <p>Avenida Júlio de Castilhos, 1481 - Sala 13</p>
                  <p>Nossa Sra. de Lourdes</p>
                  <p>Caxias do Sul, RS, 95.020-576</p>
                  <a href="https://www.google.com/maps?q=Avenida+J%C3%BAlio+de+Castilhos,+1481+-+Caxias+do+Sul+-+RS"
                     target="_blank"
                     class="flex flex-row justify-center md:justify-start items-center gap-2 text-sm mt-2">
                     <x-tabler-map-pin class="w-auto h-4" />
                     Ver no mapa
                  </a>
               </li>

               <li class="flex flex-row justify-center md:justify-start items-center gap-2 text-sm">
                  <x-tabler-clock class="w-auto h-4" />
                  <p>Seg. a sex. das 8h às 18h</p>
               </li>
            </ul>
         </div>
      </div>
   </div>

   <div class="max-w-7xl flex flex-col md:flex-row mx-auto px-4 sm:px-6 lg:px-8 py-6 justify-between items-center gap-4 text-sm border-t border-white/20 text-center md:text-left">
      <p>&copy; {{ date('Y') }} - InRoche Inovação em Gestão de Pessoas. CNPJ 25.025.357/0001-55. Todos os direitos reservados.</p>

      <a href="{{ route('site.privacy-policy') }}">Política de Privacidade</a>
   </div>
</footer>
