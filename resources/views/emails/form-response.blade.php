<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>{{ $response->respondent_name ?: 'Resposta do Formulário' }}</title>
   <style>
      body {
         font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
         line-height: 1.6;
         color: #003028;
         max-width: 800px;
         margin: 0 auto;
         padding: 20px;
         background-color: #f4f7f5;
      }

      .container {
         background-color: #ffffff;
         border-radius: 12px;
         padding: 30px;
         border: 1px solid #e2e8f0;
      }

      .header {
         border-bottom: 2px solid #84ed7a;
         padding-bottom: 20px;
         margin-bottom: 30px;
      }

      h1 {
         color: #003028;
         margin: 0 0 10px 0;
         font-size: 26px;
         font-weight: 700;
      }

      .info-section {
         background-color: #f9fbfa;
         border-left: 4px solid #84ed7a;
         padding: 18px;
         margin-bottom: 25px;
         border-radius: 6px;
      }

      .info-row {
         display: flex;
         margin-bottom: 10px;
      }

      .info-label {
         font-weight: 600;
         color: #003028;
         min-width: 150px;
      }

      .info-value {
         color: #333;
      }

      .message-box {
         background-color: #e8f9ee;
         border-left: 4px solid #84ed7a;
         padding: 15px;
         margin-bottom: 25px;
         border-radius: 6px;
      }

      .section {
         margin-bottom: 30px;
      }

      .section-title {
         color: #003028;
         font-size: 20px;
         font-weight: 600;
         margin-bottom: 15px;
         padding-bottom: 8px;
         border-bottom: 2px solid #e5e7eb;
      }

      .field-item {
         background-color: #f9fbfa;
         border: 1px solid #e2e8f0;
         border-radius: 8px;
         padding: 15px;
         margin-bottom: 12px;
      }

      .field-label {
         font-weight: 600;
         color: #003028;
         margin-bottom: 5px;
         font-size: 14px;
      }

      .field-type {
         display: inline-block;
         background-color: #84ed7a;
         color: #003028;
         padding: 2px 8px;
         border-radius: 4px;
         font-size: 11px;
         font-weight: 600;
         margin-left: 8px;
      }

      .field-value {
         color: #333;
         margin-top: 8px;
         font-size: 15px;
      }

      .rating-stars {
         color: #84ed7a;
         font-size: 18px;
      }

      .footer {
         margin-top: 40px;
         padding-top: 20px;
         border-top: 1px solid #e5e7eb;
         text-align: center;
         color: #6b7280;
         font-size: 13px;
      }

      .badge {
         display: inline-block;
         background-color: #003028;
         color: #ffffff;
         padding: 4px 10px;
         border-radius: 20px;
         font-size: 13px;
         margin-right: 6px;
         margin-bottom: 6px;
      }

      .not-answered {
         color: #94a3b8;
         font-style: italic;
      }

      a {
         color: #003028;
         text-decoration: none;
      }

      a:hover {
         color: #84ed7a;
      }
   </style>
</head>

<body>
   <div class="container">
      <div class="header">
         <h1>{{ $response->respondent_name ?: 'Resposta do Formulário' }}</h1>
         <p style="color: #6b7280; margin: 0; font-size: 14px;">
            Submetido em
            {{ $response->submitted_at ? $response->submitted_at->format('d/m/Y H:i:s') : 'Data não disponível' }}
         </p>
      </div>

      @if ($customMessage)
         <div class="message-box">
            <strong>Mensagem:</strong>
            <p style="margin: 8px 0 0 0;">{{ $customMessage }}</p>
         </div>
      @endif

      <div class="info-section">
         <h2 style="margin-top: 0; font-size: 18px; color: #003028;">Informações da Vaga</h2>

         <div class="info-row">
            <span class="info-label">Vaga:</span>
            <span class="info-value">{{ $response->jobVacancy->title ?? '' }}</span>
         </div>

         <div class="info-row">
            <span class="info-label">Vaga:</span>
            <span class="info-value">{{ $response->jobVacancy->title ?? '' }}</span>
         </div>
      </div>

      {{-- Informações do Respondente --}}
      <div class="info-section">
         <h2 style="margin-top: 0; font-size: 18px; color: #003028;">Informações do Candidato</h2>

         @if ($response->respondent_name)
            <div class="info-row">
               <span class="info-label">Nome:</span>
               <span class="info-value">{{ $response->respondent_name }}</span>
            </div>
         @endif

         @if ($response->respondent_email)
            <div class="info-row">
               <span class="info-label">Email:</span>
               <span class="info-value">
                  <a href="mailto:{{ $response->respondent_email }}">
                     {{ $response->respondent_email }}
                  </a>
               </span>
            </div>
         @endif

         @if ($response->company_name)
            <div class="info-row">
               <span class="info-label">Empresa:</span>
               <span class="info-value">{{ $response->company_name }}</span>
            </div>
         @endif

         <div class="info-row">
            <span class="info-label">Data de Submissão:</span>
            <span class="info-value">
               {{ $response->submitted_at ? $response->submitted_at->format('d/m/Y H:i:s') : 'Não disponível' }}
            </span>
         </div>
      </div>

      {{-- Respostas do Formulário --}}
      @foreach ($groupedResponses as $sectionTitle => $fieldResponses)
         <div class="section">
            <h2 class="section-title">{{ $sectionTitle }}</h2>

            @foreach ($fieldResponses->sortBy(fn($r) => $r->formTemplateField->order) as $fieldResponse)
               @php
                  $field = $fieldResponse->formTemplateField;
                  $value = $fieldResponse->value;
               @endphp

               <div class="field-item">
                  <div class="field-label">
                     {{ $field->label }}
                     @if ($field->is_required)
                        <span style="color: #dc2626;">*</span>
                     @endif
                     <span class="field-type">{{ $field->field_type }}</span>
                  </div>

                  @if ($field->help_text)
                     <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                        {{ $field->help_text }}
                     </div>
                  @endif

                  <div class="field-value">
                     @if ($value !== null && $value !== '' && !(is_array($value) && empty($value)))
                        {{-- Rating --}}
                        @if ($field->field_type === 'rating')
                           <span class="rating-stars">
                              @for ($i = 1; $i <= 5; $i++)
                                 {{ $i <= (int) $value ? '★' : '☆' }}
                              @endfor
                           </span>
                           <span style="color: #6b7280; font-size: 13px;">({{ $value }}/5)</span>

                           {{-- Scale --}}
                        @elseif($field->field_type === 'scale')
                           <strong>{{ $value }}/10</strong>

                           {{-- Checkbox / select múltiplo --}}
                        @elseif(in_array($field->field_type, ['select_multiple', 'checkbox']) && is_array($value))
                           <div>
                              @foreach (array_keys($value) as $item)
                                 <span class="badge">{{ $item }}</span>
                              @endforeach
                           </div>

                           {{-- Date --}}
                        @elseif($field->field_type === 'date')
                           {{ \Carbon\Carbon::parse($value)->format('d/m/Y') }}

                           {{-- Email --}}
                        @elseif($field->field_type === 'email')
                           <a href="mailto:{{ $value }}">{{ $value }}</a>

                           {{-- Telefone --}}
                        @elseif($field->field_type === 'tel')
                           <a href="tel:{{ $value }}">{{ $value }}</a>

                           {{-- Textarea --}}
                        @elseif($field->field_type === 'textarea')
                           <div
                              style="white-space: pre-wrap; background-color: #fff; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px;">
                              {{ $value }}
                           </div>

                           {{-- Padrão --}}
                        @else
                           {{ is_array($value) ? implode(', ', array_keys($value)) : $value }}
                        @endif
                     @else
                        <span class="not-answered">Não respondido</span>
                     @endif
                  </div>
               </div>
            @endforeach
         </div>
      @endforeach

      <div class="footer">
         <p>Este email foi gerado automaticamente pelo sistema de formulários.</p>
         <p style="margin-top: 5px;">
            © {{ date('Y') }} - Todos os direitos reservados
         </p>
      </div>
   </div>
</body>

</html>
