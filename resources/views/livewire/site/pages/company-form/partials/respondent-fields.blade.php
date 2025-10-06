@php
    // Determina o template correto baseado no tipo de formulário
    $template = $isJobVacancy ? $form : ($form->formTemplate ?? $form);
@endphp

@if ($template->collect_name || $template->collect_email || $template->collect_phone)
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Seus dados</h3>
        <div class="grid grid-cols-1 gap-4">
            @if ($template->collect_name)
                <div class="space-y-2">
                    <label for="respondent_name" class="block text-sm font-medium text-gray-700">Nome</label>
                    <x-mary-input 
                        type="text" 
                        id="respondent_name" 
                        class="input-lg"
                        wire:model.defer="respondent_name"
                        x-data
                        x-on:input="
                            $el.closest('fieldset').querySelector('.text-error')?.classList.add('hidden');
                            let label = $el.closest('label.input');
                            if(label) label.classList.remove('!input-error');
                        " />
                     <p class="text-sm text-neutral-500">Informe o seu nome completo</p>

                </div>
            @endif

            @if ($template->collect_email)
                <div class="space-y-2">
                    <label for="respondent_email" class="block text-sm font-medium text-gray-700">E-mail</label>
                    <x-mary-input 
                        type="email" 
                        id="respondent_email" 
                        class="input-lg"
                        wire:model.defer="respondent_email"
                        x-data
                        x-on:input="
                            $el.closest('fieldset').querySelector('.text-error')?.classList.add('hidden');
                            let label = $el.closest('label.input');
                            if(label) label.classList.remove('!input-error');
                        " />
                     <p class="text-sm text-neutral-500">Informe o email que você acessa com frequência</p>

                </div>
            @endif

            @if ($template->collect_phone)
                <div class="space-y-2">
                    <label for="respondent_phone" class="block text-sm font-medium text-gray-700">Whatsapp</label>
                    <x-mary-input 
                        type="text" 
                        id="respondent_phone" 
                        class="input-lg"
                        placeholder="(99) 99999-9999"
                        wire:model.defer="respondent_phone"
                        x-data="{
                            formatPhone(value) {
                                if (!value) return '';
                                let v = value.replace(/\D/g, '').substring(0, 11);
                                if (v.length >= 11) {
                                    v = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                                } else if (v.length >= 10) {
                                    v = v.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                                } else if (v.length >= 6) {
                                    v = v.replace(/(\d{2})(\d{4})/, '($1) $2');
                                } else if (v.length >= 2) {
                                    v = v.replace(/(\d{2})/, '($1) ');
                                }
                                return v;
                            }
                        }"
                        x-init="$nextTick(() => {
                            let input = $el.querySelector('input');
                            if (input && input.value) {
                                input.value = formatPhone(input.value);
                            }
                        })"
                        x-on:input="
                            $el.closest('fieldset').querySelector('.text-error')?.classList.add('hidden');
                            let label = $el.closest('label.input');
                            if(label) label.classList.remove('!input-error');
                            let v = formatPhone($event.target.value);
                            $event.target.value = v;
                            $wire.set('respondent_phone', v);
                        " />
                     <p class="text-sm text-neutral-500">Informe o número do seu Whatsapp (com DDD)</p>

                </div>
            @endif
        </div>
    </div>
@endif