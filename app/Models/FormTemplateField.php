<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormTemplateField extends Model
{
   protected $fillable = [
      'form_template_section_id',
      'field_type',
      'label',
      'placeholder',
      'help_text',
      'is_required',
      'order',
      'options',
      'validation_rules',
      'field_config',
      'is_active',
   ];

   protected $casts = [
      'is_required'      => 'boolean',
      'is_active'        => 'boolean',
      'options'          => 'array',
      'validation_rules' => 'array',
      'field_config'     => 'array',
   ];

   public function section()
   {
      return $this->belongsTo(FormTemplateSection::class, 'form_template_section_id', 'id');
   }

   public const FIELD_TYPES = [
      'text' => 'Texto',
      'textarea' => 'Área de Texto',
      'email' => 'E-mail',
      'number' => 'Número',
      'tel' => 'Telefone',
      'select_single' => 'Seleção Única',
      'select_multiple' => 'Seleção Múltipla',
      'radio' => 'Radio Button',
      'checkbox' => 'Checkbox',
      'date' => 'Data',
      'rating' => 'Avaliação (Estrelas)',
      'scale' => 'Escala (1-10)',
   ];


   public function formFieldResponses(): HasMany
   {
      return $this->hasMany(FormFieldResponse::class);
   }

   public function scopeActive($query)
   {
      return $query->where('is_active', true);
   }

   public function scopeOrdered($query)
   {
      return $query->orderBy('order');
   }

   public function hasOptions(): bool
   {
      return in_array($this->field_type, ['select_single', 'select_multiple', 'radio', 'checkbox']);
   }

   public function isMultipleSelection(): bool
   {
      return in_array($this->field_type, ['select_multiple', 'checkbox']);
   }

   public function getValidationRules(): array
   {
      $rules = [];

      if ($this->is_required) {
         $rules[] = 'required';
      }

      switch ($this->field_type) {
         case 'email':
            $rules[] = 'email';
            break;
         case 'number':
            $rules[] = 'numeric';
            break;
         case 'date':
            $rules[] = 'date';
            break;
         case 'tel':
            $rules[] = 'string';
            break;
      }

      if ($this->validation_rules && is_array($this->validation_rules)) {
         $rules = array_merge($rules, $this->validation_rules);
      }

      return $rules;
   }

   public function formatValue($value)
   {
      switch ($this->field_type) {
         case 'select_multiple':
         case 'checkbox':
            return is_array($value) ? $value : [$value];
         case 'rating':
         case 'scale':
         case 'number':
            return (int) $value;
         default:
            return $value;
      }
   }

   protected function formattedOptions(): Attribute
   {
      return Attribute::make(
         get: function () {
            // Pega o valor original da coluna 'options'
            $options = $this->options ?? [];

            // Se não for um array ou estiver vazio, retorna um array vazio.
            if (!is_array($options) || empty($options)) {
               return [];
            }

            // Transforma o array simples no formato que o MaryUI precisa.
            return collect($options)->map(function ($optionValue) {
               return [
                  'id'   => $optionValue,
                  'name' => $optionValue,
               ];
            })->all();
         }
      );
   }

   protected function fieldTypeLabel(): Attribute
   {
      return Attribute::make(
         get: fn() => self::FIELD_TYPES[$this->field_type] ?? 'Desconhecido'
      );
   }
}
