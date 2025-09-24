<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\FormResponse;

class FormResponseDetails extends Page
{
    protected static bool $isDiscovered = false;
    protected string $view = 'filament.pages.form-response-details';

    public FormResponse $response;

    public function mount(int $responseId)
    {
        $this->response = FormResponse::with([
            'formFieldResponses.formTemplateField.section',
            'subject'
        ])->findOrFail($responseId);
    }

    public function getGroupedResponses()
    {
        return $this->response->formFieldResponses
            ->groupBy(function ($fieldResponse) {
                return $fieldResponse->formTemplateField->section->title ?? 'Sem Seção';
            })
            ->sortBy(function ($responses, $sectionTitle) {
                $firstResponse = $responses->first();
                return $firstResponse->formTemplateField->section->order ?? 999;
            });
    }

    public function formatFieldValue($fieldResponse): string
    {
        $field = $fieldResponse->formTemplateField;
        $value = $fieldResponse->value;

        if ($value === null || $value === '') {
            return 'Não respondido';
        }

        switch ($field->field_type) {
            case 'rating':
                $stars = str_repeat('★', $value) . str_repeat('☆', 5 - $value);
                return "{$stars} ({$value}/5)";
                
            case 'scale':
                return "{$value}/10";
                
            case 'select_multiple':
            case 'checkbox':
                if (is_array($value)) {
                    return implode(', ', $value);
                }
                return $value;
                
            case 'date':
                try {
                    return \Carbon\Carbon::parse($value)->format('d/m/Y');
                } catch (\Exception $e) {
                    return $value;
                }
                
            default:
                return is_array($value) ? implode(', ', $value) : $value;
        }
    }

    public function getFieldTypeColor(string $fieldType): string
    {
        return match($fieldType) {
            'email' => 'info',
            'tel' => 'success',
            'date' => 'warning',
            'rating', 'scale' => 'danger',
            'select_multiple', 'checkbox' => 'primary',
            default => 'gray'
        };
    }

    public function shouldShowAsBadge(string $fieldType): bool
    {
        return in_array($fieldType, ['select_multiple', 'checkbox']);
    }
}