<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\FormFieldTypeEnum;

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
      'field_type'       => FormFieldTypeEnum::class, 
   ];

   public function section(): BelongsTo
   {
      return $this->belongsTo(FormTemplateSection::class, 'form_template_section_id', 'id');
   }

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
      return in_array($this->field_type->value, [
         FormFieldTypeEnum::SelectSingle->value,
         FormFieldTypeEnum::SelectMultiple->value,
         FormFieldTypeEnum::Radio->value,
         FormFieldTypeEnum::Checkbox->value,
      ]);
   }

   public function isMultipleSelection(): bool
   {
      return in_array($this->field_type->value, [
         FormFieldTypeEnum::SelectMultiple->value,
         FormFieldTypeEnum::Checkbox->value,
      ]);
   }
public function getValidationRules(): array
{
    $rules = [];

    if ($this->is_required) {
        $rules[] = 'required';
    } else {
        $rules[] = 'nullable';
    }

    // Converte para string se for Enum
    $fieldType = $this->field_type instanceof \BackedEnum 
        ? $this->field_type->value 
        : $this->field_type;

    switch ($fieldType) {
        case FormFieldTypeEnum::Email->value:
            $rules[] = 'email';
            break;

        case FormFieldTypeEnum::Number->value:
            $rules[] = 'numeric';
            
            if (isset($this->field_config['min_value'])) {
                $rules[] = 'min:' . $this->field_config['min_value'];
            }
            if (isset($this->field_config['max_value'])) {
                $rules[] = 'max:' . $this->field_config['max_value'];
            }
            break;

        case FormFieldTypeEnum::Date->value:
            $rules[] = 'date';
            break;

        case FormFieldTypeEnum::Text->value:
        case FormFieldTypeEnum::Textarea->value:
        case FormFieldTypeEnum::Tel->value:
            $rules[] = 'string';
            
            if (isset($this->field_config['min_length'])) {
                $rules[] = 'min:' . $this->field_config['min_length'];
            }
            if (isset($this->field_config['max_length'])) {
                $rules[] = 'max:' . $this->field_config['max_length'];
            }

            if (isset($this->field_config['validation_pattern'])) {
                $pattern = $this->field_config['validation_pattern'];
                
                switch ($pattern) {
                    case 'cpf':
                        $rules[] = new \App\Rules\ValidCpf();
                        break;
                    case 'cnpj':
                        $rules[] = new \App\Rules\ValidCnpj();
                        break;
                    case 'cep':
                        $rules[] = 'regex:/^\d{5}-?\d{3}$/';
                        break;
                    case 'phone_br':
                        $rules[] = 'regex:/^\(\d{2}\)\s?\d{4,5}-?\d{4}$/';
                        break;
                    case 'custom':
                        if (isset($this->field_config['custom_regex'])) {
                            $rules[] = 'regex:' . $this->field_config['custom_regex'];
                        }
                        break;
                }
            }
            break;
    }

    return $rules;
}

   public function formatValue($value)
   {
      switch ($this->field_type) {
         case FormFieldTypeEnum::SelectMultiple:
         case FormFieldTypeEnum::Checkbox:
            return is_array($value) ? $value : [$value];
         case FormFieldTypeEnum::Rating:
         case FormFieldTypeEnum::Scale:
         case FormFieldTypeEnum::Number:
            return (int) $value;
         default:
            return $value;
      }
   }

   protected function formattedOptions(): Attribute
   {
      return Attribute::make(
         get: function () {
            $options = $this->options ?? [];

            if (!is_array($options) || empty($options)) {
               return [];
            }

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
         get: fn() => $this->field_type?->getLabel() ?? 'Desconhecido'
      );
   }

   
}
