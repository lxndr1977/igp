<!-- Campos do Formulário -->
<div class="space-y-2" wire:key="field-{{ $field->id }}">
   <label class="block text-sm font-medium text-gray-700">
      @if ($field->field_type != 'checkbox')
         {{ $field->label }}
         @if (!$field->is_required)
            <span class="text-gray-500">(opcional)</span>
         @endif
      @endif
   </label>

   <!-- Campo Text -->
   @if ($field->field_type === 'text')

      <x-mary-input
         type="text"
         wire:model="formData.{{ $field->id }}"
         wire:key="formData.{{ $field->id }}"
         placeholder="{{ $field->placeholder }}" />

      <!-- Campo Textarea -->
   @elseif($field->field_type === 'textarea')
      <x-mary-textarea
         wire:model="formData.{{ $field->id }}"
         placeholder="{{ $field->placeholder }}"
         rows="4" />

      <!-- Campo Email -->
   @elseif($field->field_type === 'email')
      <x-mary-input
         type="email"
         wire:model="formData.{{ $field->id }}"
         placeholder="{{ $field->placeholder }}" />

      <!-- Campo Number -->
   @elseif($field->field_type === 'number')
      <x-mary-input
         type="number"
         wire:model="formData.{{ $field->id }}"
         placeholder="{{ $field->placeholder }}" />

      <!-- Campo Tel -->
   @elseif($field->field_type === 'tel')
      <x-mary-input
         type="tel"
         wire:model="formData.{{ $field->id }}"
         placeholder="{{ $field->placeholder }}" />

      <!-- Campo Date -->
   @elseif($field->field_type === 'date')
      <x-mary-datetime type="date"
         wire:model="formData.{{ $field->id }}" />

      <!-- Select Single -->
   @elseif($field->field_type === 'select_single')
      <x-mary-select
         wire:model="formData.{{ $field->id }}"
         :options="$field->formatted_options"
         option-value="id"
         option-label="name"
         placeholder="{{ $field->placeholder ?? 'Selecione uma opção' }}" />

      <!-- Select Multiple -->
   @elseif($field->field_type === 'select_multiple')
      @foreach ($field->formatted_options as $option)
         <div class="space-y-2" wire:key="formData.{{ $field->id }}.{{ $loop->index }}">
            <label class="flex items-center space-x-2">
               <x-mary-checkbox
                  label="{{ $option['id'] }}"
                  value="{{ $option['name'] }}"
                  wire:model="formData.{{ $field->id }}.{{ $option['id'] }}.{{ $loop->index }}" />
            </label>
         </div>
      @endforeach

      <!-- Radio Buttons -->
   @elseif($field->field_type === 'radio')
      <div class="space-y-2">
         <x-mary-radio
            type="radio"
            wire:model="formData.{{ $field->id }}"
            :options="$field->formatted_options"
            option-value="id"
            option-label="name" />
      </div>

      <!-- Checkboxes -->
   @elseif($field->field_type === 'checkbox')
      <div class="flex flex-row items-center gap-2">
         <x-mary-checkbox
            wire:model="formData.{{ $field->id }}"
            label="{{ $field->label }}" />

         @if ($field->is_required == false)
            <span class="text-sm text-gray-600 font-medium">(opcional)</span>
         @endif
      </div>

      <!-- Rating (Estrelas) -->
   @elseif($field->field_type === 'rating')
      @php
         $maxRating = $field->field_config['max_rating'] ?? 5;
      @endphp
      <div class="flex space-x-1">
         @for ($i = 1; $i <= $maxRating; $i++)
            <button type="button"
               wire:click="$set('formData.{{ $field->id }}', {{ $i }})"
               class="text-2xl {{ isset($formData[$field->id]) && $formData[$field->id] >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors">
               ★
            </button>
         @endfor
         @if (isset($formData[$field->id]) && $formData[$field->id])
            <span class="ml-2 text-sm text-gray-600">{{ $formData[$field->id] }}/{{ $maxRating }}</span>
         @endif
      </div>

      <!-- Scale (1-10) -->
   @elseif($field->field_type === 'scale')
      @php
         $minValue = $field->field_config['min_value'] ?? 1;
         $maxValue = $field->field_config['max_value'] ?? 10;
         $minLabel = $field->field_config['min_label'] ?? '';
         $maxLabel = $field->field_config['max_label'] ?? '';
      @endphp
      <div class="space-y-2">
         @if ($minLabel || $maxLabel)
            <div class="flex justify-between text-sm text-gray-600">
               <span>{{ $minLabel }}</span>
               <span>{{ $maxLabel }}</span>
            </div>
         @endif

         <x-mary-radio
            :options="$this->getScaleOptionsWithLabels($minValue, $maxValue)"
            wire:model="formData.{{ $field->id }}"
            inline />
      </div>
   @endif

   @if ($field->help_text)
      <p class="text-sm text-gray-500">{{ $field->help_text }}</p>
   @endif
</div>
