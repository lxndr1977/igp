<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormTemplateSection extends Model
{
   protected $fillable = [
      'form_template_id',
      'title',
      'description',
      'order',
      'is_active',
   ];

   protected $casts = [
      'is_active' => 'boolean',
   ];

   public function template()
   {
      return $this->belongsTo(FormTemplate::class, 'form_template_id');
   }

   public function fields()
   {
      return $this->hasMany(FormTemplateField::class, 'form_template_section_id', 'id')
         ->orderBy('order');
   }
}
