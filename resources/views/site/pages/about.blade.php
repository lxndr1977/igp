<x-layouts.site>
   @include('partials.site.about.hero')

   @include('partials.site.about.mission-vision-values')

<x-site.services-section 
    title="Como Podemos Ajudar"
    description="Conheça nossas principais áreas de atuação"
    backgroundColor="bg-primary-50"
    :highlightFirst="true"
/>   
</x-layouts.site>
