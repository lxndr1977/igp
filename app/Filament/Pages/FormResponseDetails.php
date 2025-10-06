<?php

namespace App\Filament\Pages;

use App\Enums\FormFieldTypeEnum;
use App\Models\Company;
use Filament\Pages\Page;
use App\Models\FormResponse;
use Filament\Actions\Action;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Select;
use App\Mail\JobVacancyApplicationMail;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class FormResponseDetails extends Page
{
   protected static bool $isDiscovered = false;
   protected string $view = 'filament.pages.form-response-details';

   protected static ?string $title = 'Detalhes da resposta';
   
   public FormResponse $response;

   public function mount(int $responseId): void
   {
      $this->response = FormResponse::with([
         'formFieldResponses.formTemplateField.section',
         'subject'
      ])->findOrFail($responseId);
   }

   protected function getHeaderActions(): array
   {
      return [
         $this->makeSendEmailAction(),
      ];
   }

   protected function makeSendEmailAction(): Action
   {
      return Action::make('sendEmail')
         ->label('Enviar por Email')
         ->icon('heroicon-m-envelope')
         ->color('success')
         ->schema([
            Select::make('recipient_email')
               ->label('Email do Destinatário')
               ->options(
                  Company::find($this->response->company_id)->contactEmails()
               )
               ->required()
               ->helperText('Email para onde o currículo será enviado'),

            TextInput::make('subject')
               ->label('Assunto')
               ->required()
               ->default('Currículo - ' . ($this->response->respondent_name ?: 'Candidato'))
               ->maxLength(255),

            Textarea::make('message')
               ->label('Mensagem (Opcional)')
               ->rows(4)
               ->helperText('Mensagem adicional que será incluída no email')
               ->placeholder('Digite uma mensagem personalizada...'),
         ])
         ->action(function (array $data): void {
            try {
               Mail::to($data['recipient_email'])
                  ->send(new JobVacancyApplicationMail(
                     $this->response,
                     $data['subject'],
                     $data['message'] ?? null
                  ));

               Notification::make()
                  ->title('Email enviado com sucesso!')
                  ->success()
                  ->body("O currículo foi enviado para {$data['recipient_email']}")
                  ->send();
            } catch (\Exception $e) {
               Notification::make()
                  ->title('Erro ao enviar email')
                  ->danger()
                  ->body('Ocorreu um erro ao tentar enviar o email. Por favor, tente novamente.')
                  ->send();
            }
         });
   }

   public function getGroupedResponses()
   {
      return $this->response->formFieldResponses
         ->groupBy(
            fn($fieldResponse) =>
            $fieldResponse->formTemplateField->section->title ?? 'Sem Seção'
         )
         ->sortBy(
            fn($responses) =>
            $responses->first()->formTemplateField->section->order ?? 999
         );
   }

   public function formatFieldValue($fieldResponse): string
   {
      $field = $fieldResponse->formTemplateField;
      $value = $fieldResponse->value;

      if ($value === null || $value === '' || (is_array($value) && empty($value))) {
         return 'Não respondido';
      }

      return match ($field->field_type) {
         'rating' => $this->formatRating((int)$value),
         'scale' => "{$value}/10",
         'select_multiple' => $this->formatMultipleSelect($value),
         'date' => $this->formatDate($value),
         default => is_array($value)
            ? json_encode($value, JSON_UNESCAPED_UNICODE)
            : (string) $value,
      };
   }

   protected function formatRating(int $value): string
   {
      $stars = str_repeat('★', $value) . str_repeat('☆', 5 - $value);
      return "{$stars} ({$value}/5)";
   }

   protected function formatMultipleSelect($value): string
   {
      if (is_array($value)) {
         return implode(', ', array_keys($value));
      }
      return (string) $value;
   }

   protected function formatDate($value): string
   {
      try {
         return Carbon::parse($value)->format('d/m/Y');
      } catch (\Exception $e) {
         return (string) $value;
      }
   }

   public function shouldShowAsBadge( $fieldType): bool
   {
      return $fieldType === FormFieldTypeEnum::SelectMultiple;
   }
}
