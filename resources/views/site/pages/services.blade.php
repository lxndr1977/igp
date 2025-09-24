<x-layouts.site 
    title="Nossos Serviços" 
    description="Descubra os serviços incríveis que oferecemos para impulsionar seu negócio.">

    <x-site.services-section
      :showBreadcrumb="true"
      :breadcrumbs="[['label' => 'Início', 'url' => route('site.home')], ['label' => 'Serviços']]"
      backgroundColor="bg-white"
      :headerPadding="true"
      :highlightFirst="true" />

   @include('partials.site.cta')
</x-layouts.site>
