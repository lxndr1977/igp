<?php

namespace App\Mail;

use App\Models\FormResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobVacancyApplicationMail extends Mailable
{
   use Queueable, SerializesModels;

   public FormResponse $response;
   public ?string $customMessage;
   public string $emailSubject;

   public function __construct(FormResponse $response, string $subject, ?string $customMessage = null)
   {
      $this->response = $response->load([
         'formFieldResponses.formTemplateField.section'
      ]);
      $this->emailSubject = $subject;
      $this->customMessage = $customMessage;
   }

   public function envelope(): Envelope
   {
      return new Envelope(
         subject: $this->emailSubject,
      );
   }

   public function content(): Content
   {
      return new Content(
         view: 'emails.form-response',
         with: [
            'response' => $this->response,
            'customMessage' => $this->customMessage,
            'groupedResponses' => $this->getGroupedResponses(),
         ]
      );
   }

   public function attachments(): array
   {
      return [];
   }

   protected function getGroupedResponses()
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
}
