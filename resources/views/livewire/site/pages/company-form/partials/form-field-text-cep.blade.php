<x-mary-input
   type="text"
   class="input-lg"
   wire:model.defer="formData.{{ $field->id }}"
   wire:key="formData.{{ $field->id }}"
   placeholder="{{ $field->placeholder }}"
   x-data="{
       formatValue(value) {
           if (!value) return '';
           let v = value.replace(/\D/g, '').substring(0, 8);
           if (v.length >= 5) {
               v = v.replace(/(\d{5})(\d{0,3})/, '$1-$2');
           }
           return v;
       }
   }"
   x-init="$nextTick(() => {
       let input = $el.querySelector('input');
       if (input && input.value) {
           input.value = formatValue(input.value);
       }
   })"
   x-on:keydown="
      let input = $event.target;
      if ($event.key === 'Backspace' || $event.key === 'Delete') {
         let cursorPos = input.selectionStart;
         let value = input.value;
         
         if ($event.key === 'Backspace' && cursorPos > 0) {
            if (value[cursorPos - 1] === '-' || value[cursorPos - 1] === '.') {
               input.setSelectionRange(cursorPos - 1, cursorPos - 1);
            }
         }
      }
   "
   x-on:input="
      let v = formatValue($event.target.value);
      $event.target.value = v;
      
      $el.closest('fieldset').querySelector('.text-error')?.classList.add('hidden');
      let label = $el.closest('label.input');
      if(label) label.classList.remove('!input-error');
   "
   inputmode="numeric"
/>