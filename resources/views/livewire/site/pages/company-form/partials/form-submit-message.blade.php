 <div class=" px-4 sm:px-6 lg:px-8 py-20 bg-white border border-neutral-200 rounded-lg text-center">
    <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
       <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
       </svg>
    </div>
   
    <h2 class="text-2xl font-bold text-neutral-900 mb-2">
      {{ $form->title_success_message ?: 'Enviado com sucesso.' }}
   </h2>
   
   <p class="text-neutral-600">
       {{ $form->success_message ?: 'Sua resposta foi enviada com sucesso.' }}
    </p>
 </div>
