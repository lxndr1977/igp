<?php

namespace App\Livewire\Site;

use Exception;
use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\JobVacancy;
use App\Models\CompanyForm;
use App\Models\FormResponse;
use App\Models\FormFieldResponse;
use App\Enums\JobVacancyStatusEnum;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class CompanyFormPage extends Component
{
   use Toast;

   public $company;
   public $form;
   public $jobVacancy;
   public $type;
   public $formData = [];
   public $respondent_name = '';
   public $respondent_email = '';
   public $respondent_phone = '';
   public $submitted = false;

   public $isJobVacancy = false;
   public $currentStep = 1;
   public $showIntroScreen = true;

   protected $listeners = ['formSubmitted'];

   public function mount(string $companyId, string $formSlug, ?string $type = null)
   {
      $this->type = $type;

      $this->company = \App\Models\Company::where('id', $companyId)->first();
      if (!$this->company) {
         abort(404, 'Empresa não encontrada');
      }

      if ($this->type === 'vagas') {
         $this->loadJobVacancyForm($companyId, $formSlug);
      } else {
         $this->loadRegularForm($companyId, $formSlug);
      }

      $this->initializeFormData();
   }

   /**
    * Carrega formulário para vaga de emprego
    */
   private function loadJobVacancyForm(string $companyId, string $formSlug)
   {

      $this->jobVacancy = JobVacancy::where('company_id', $companyId)
         ->where('slug', $formSlug)
         ->where('status', JobVacancyStatusEnum::Active)
         ->with([
            'company',
            'formTemplate'
         ])
         ->first();

      if (!$this->jobVacancy || !$this->jobVacancy->formTemplate) {
         abort(404, 'Vaga não encontrada ou sem formulário de candidatura');
      }

      $this->form = $this->jobVacancy->formTemplate;

      $this->isJobVacancy = true;
   }

   /**
    * Carrega formulário regular
    */
   private function loadRegularForm(string $companyId, string $formSlug)
   {
      $this->form = \App\Models\CompanyForm::withCompanyForm()
         ->with([
            'company',
            'formTemplate',
            'formTemplate.fields'
         ])
         ->where('company_id', $companyId)
         ->where('slug', $formSlug)
         ->active()
         ->first();

      if (!$this->form) {
         abort(404, 'Formulário não encontrado');
      }

      $this->isJobVacancy = false;
   }

   /**
    * Inicializa os dados do formulário
    */
   private function initializeFormData()
   {
      $fields = $this->getAllFields();

      foreach ($fields as $field) {
         if (method_exists($field, 'isMultipleSelection') && $field->isMultipleSelection()) {
            $this->formData[$field->id] = [];
         } else {
            $this->formData[$field->id] = '';
         }
      }
   }


   /**
    * Inicia o formulário (sai da tela de introdução)
    */
   public function startForm()
   {
      $this->showIntroScreen = false;
      $this->currentStep = 1;

      // $this->dispatch('scroll-to-top');
   }

   /**
    * Volta para a tela de introdução
    */
   public function backToIntro()
   {
      $this->showIntroScreen = true;
      $this->currentStep = 0;
   }

   /**
    * Retorna o número total de passos (incluindo a tela de introdução)
    */
   public function getTotalSteps()
   {
      return $this->form->sections->count() + 1; // +1 para a tela de introdução
   }

   /**
    * Retorna informações do formulário regular
    */
   public function getFormInfo()
   {
      if ($this->isJobVacancy) {
         return null;
      }

      return [
         'title' => $this->form->title,
         'description' => $this->form->description,
         'company' => $this->company->name,
      ];
   }

   /**
    * Retorna informações da vaga
    */
   public function getJobVacancyInfo()
   {
      if (!$this->isJobVacancy || !$this->jobVacancy) {
         return null;
      }

      return [
         'title' => $this->jobVacancy->title,
         'description' => $this->jobVacancy->description,
         'requirements' => $this->jobVacancy->requirements,
         'benefits' => $this->jobVacancy->benefits,
         'employment_type' => $this->jobVacancy->employment_type_label,
         'work_location' => $this->jobVacancy->work_location_label,
         'formatted_salary' => $this->jobVacancy->formatted_salary,
         'department' => $this->jobVacancy->department,
         'level' => $this->jobVacancy->level,
         'city' => $this->jobVacancy->city,
         'state' => $this->jobVacancy->state,
         'application_deadline' => $this->jobVacancy->application_deadline,
         'company' => $this->jobVacancy->company_name,
      ];
   }

   /**
    * Navega para a etapa anterior no formulário.
    */
  public function previousStep()
{
    if ($this->currentStep > 1) {
        $this->currentStep--;
        $this->dispatch('stepChanged');
    } elseif ($this->currentStep === 1) {
        $this->backToIntro();
    }
}

   /**
    * Valida a etapa atual e avança para a próxima.
    */
   public function nextStep()
{
    // Pega as regras da etapa atual
    $rules = $this->getRulesForCurrentStep();

    // Valida apenas se houver regras
    if (!empty($rules)) {
        try {
            $this->validate($rules, $this->getMessages());
        } catch (ValidationException $e) {
            // Pega o primeiro erro para mostrar no toast
            $firstError = collect($e->errors())->flatten()->first();
            
            $this->toast(
                type: 'error',
                title: 'Erro de validação',
                description:"Preencha corretamente os campos do formulário",
                position: 'toast-top toast-center',
                icon: 'o-exclamation-triangle',
                css: 'alert-error',
                timeout: 5000
            );
            
            throw $e; // Re-lança a exceção para manter os erros nos campos
        }
    }

    // Avança para a próxima etapa, mesmo que não haja regras
    if ($this->currentStep < $this->form->sections->count()) {
        $this->currentStep++;
    } else {
        // Última etapa atingida - chama submit
        $this->submit();
        return;
    }

    $this->dispatch('stepChanged');
}


   public function rules()
   {
      $rules = [];

      if ($this->shouldCollect('name')) {
         $rules['respondent_name'] = 'required|string|max:255';
      }

      if ($this->shouldCollect('email')) {
         $rules['respondent_email'] = 'required|email|max:255';
      }

      if ($this->shouldCollect('phone')) {
         $rules['respondent_phone'] = 'required';
      }

      $fields = $this->getAllFields();

      foreach ($fields as $field) {
         $fieldRules = $field->getValidationRules();
         if (!empty($fieldRules)) {
            $rules["formData.{$field->id}"] = $fieldRules;
         }
      }

      return $rules;
   }

   /**
    * Define as regras de validação apenas para a etapa atual.
    */
   public function getRulesForCurrentStep()
   {
      $rules = [];

      if ($this->currentStep === 1) {
         if ($this->shouldCollect('name')) {
            $rules['respondent_name'] = 'required|string|max:255';
         }
         if ($this->shouldCollect('email')) {
            $rules['respondent_email'] = 'required|email|max:255';
         }
         if ($this->shouldCollect('phone')) {
            $rules['respondent_phone'] = 'required';
         }
      }

      if (
         $this->form->sections->count() > 0 &&
         $this->currentStep > 0 &&
         isset($this->form->sections[$this->currentStep - 1])
      ) {

         $currentSection = $this->form->sections[$this->currentStep - 1];

         if ($currentSection && $currentSection->fields) {
            foreach ($currentSection->fields as $field) {
               $fieldRules = $field->getValidationRules();
               if (!empty($fieldRules)) {
                  $rules["formData.{$field->id}"] = $fieldRules;
               }
            }
         }
      }

      return $rules;
   }

   private function hasValidationRules(): bool
   {
      $hasCollectFields = $this->shouldCollect('name')
         || $this->shouldCollect('email')
         || $this->shouldCollect('phone');

      $fields = $this->getAllFields();

      return $hasCollectFields || $fields->where('required', true)->isNotEmpty();
   }
   /**
    * Define as mensagens de erro customizadas para a validação.
    */
   public function getMessages()
   {
      $messages = [
         'respondent_name.required' => 'O nome é obrigatório.',
         'respondent_email.required' => 'O e-mail é obrigatório.',
         'respondent_email.email' => 'O e-mail deve ser um endereço válido.',
         'respondent_phone.required' => 'O Whatsapp é obrigatório.',
      ];

      $fields = $this->getAllFields();

      foreach ($fields as $field) {
         $fieldKey = "formData.{$field->id}";

         // Mensagem personalizada se existir
         if (isset($field->field_config['validation_message'])) {
            $customMessage = $field->field_config['validation_message'];
            $messages["{$fieldKey}.required"] = $customMessage;
            $messages["{$fieldKey}.min"] = $customMessage;
            $messages["{$fieldKey}.max"] = $customMessage;
            $messages["{$fieldKey}.regex"] = $customMessage;
         } else {
            // Mensagens padrão
            $messages["{$fieldKey}.required"] = "O campo '{$field->label}' é obrigatório.";
            $messages["{$fieldKey}.email"] = "O campo '{$field->label}' deve ser um e-mail válido.";
            $messages["{$fieldKey}.numeric"] = "O campo '{$field->label}' deve ser um número.";
            $messages["{$fieldKey}.date"] = "O campo '{$field->label}' deve ser uma data válida.";
            $messages["{$fieldKey}.min"] = "O campo '{$field->label}' deve ter no mínimo :min caracteres/valor.";
            $messages["{$fieldKey}.max"] = "O campo '{$field->label}' deve ter no máximo :max caracteres/valor.";
            $messages["{$fieldKey}.regex"] = "O campo '{$field->label}' está em formato inválido.";
         }

         // As mensagens do CPF e CNPJ já vêm das próprias Rules
      }

      return $messages;
   }

   /**
    * Processa a submissão final do formulário.
    */
   public function submit()
   {
      try {
         $this->checkRateLimit();
         $this->validateForm();

         $template = $this->getFormTemplate();

         $formResponse = FormResponse::create([
            'subject_id' => $this->isJobVacancy ? $this->jobVacancy->id : $this->form->id,
            'subject_type' => $this->isJobVacancy ? JobVacancy::class : CompanyForm::class,
            'respondent_name' => $this->shouldCollect('name') ? $this->respondent_name : null,
            'respondent_email' => $this->shouldCollect('email') ? $this->respondent_email : null,
            'respondent_phone' => $this->shouldCollect('phone') ? $this->respondent_phone : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'submitted_at' => now(),
         ]);

         $fields = $this->getAllFields();

         foreach ($fields as $field) {
            $value = $this->formData[$field->id] ?? null;

            if ($field->field_type === 'checkbox') {
               $isChecked = is_array($value) && !empty(array_filter($value));
               $valueToSave = $isChecked ? 'on' : 'off';

               FormFieldResponse::create([
                  'form_response_id' => $formResponse->id,
                  'form_template_field_id' => $field->id,
                  'value' => $field->formatValue($valueToSave),
               ]);
            } else {
               if ($field->isMultipleSelection() && is_array($value)) {
                  $value = array_filter($value);
               }

               FormFieldResponse::create([
                  'form_response_id' => $formResponse->id,
                  'form_template_field_id' => $field->id,
                  'value' => $field->formatValue($value),
               ]);
            }
         }

         $this->clearRateLimit();
        $this->submitted = true;

        // Toast de sucesso
        $this->toast(
            type: 'success',
            title: 'Enviado com sucesso!',
            description: 'Seu formulário foi enviado com sucesso.',
            position: 'toast-top toast-center',
            icon: 'o-check-circle',
            css: 'alert-success',
            timeout: 3000
        );

        $redirectUrl = $this->getFormTemplate()->redirect_url ?? null;

        if ($redirectUrl) {
            return redirect($redirectUrl);
        }
    } catch (ValidationException $e) {
        // Já tratado no validateForm()
        throw $e;
    } catch (Exception $e) {
        $this->toast(
            type: 'error',
            title: 'Erro ao enviar',
            description: $e->getMessage(),
            position: 'toast-top toast-center',
            icon: 'o-x-circle',
            css: 'alert-error',
            timeout: 5000
        );
        
        session()->flash('error', $e->getMessage());
    }
}


  private function validateForm()
{
    $rules = [];
    $messages = [];
    $attributes = [];

    // Validação dos campos básicos
    if ($this->shouldCollect('name')) {
        $rules['respondent_name'] = 'required|string|max:255';
        $messages['respondent_name.required'] = 'Nome é obrigatório.';
        $attributes['respondent_name'] = 'Nome';
    }

    if ($this->shouldCollect('email')) {
        $rules['respondent_email'] = 'required|email';
        $messages['respondent_email.required'] = 'E-mail é obrigatório.';
        $messages['respondent_email.email'] = 'E-mail deve ser válido.';
        $attributes['respondent_email'] = 'E-mail';
    }

    if ($this->shouldCollect('phone')) {
        $rules['respondent_phone'] = 'required';
        $messages['respondent_phone.required'] = 'Whatsapp é obrigatório.';
        $attributes['respondent_phone'] = 'Whatsapp';
    }

    // Validação dinâmica dos campos personalizados
    $fields = $this->getAllFields();

    foreach ($fields as $field) {
        $fieldRules = $field->getValidationRules();

        if (!empty($fieldRules)) {
            $fieldKey = 'formData.' . $field->id;
            $rules[$fieldKey] = $fieldRules;

            foreach ($fieldRules as $rule) {
                // Tratamento especial para ValidationRule objects
                if (is_object($rule)) {
                    continue;
                }
                
                $ruleName = explode(':', $rule)[0];
                $messageKey = $fieldKey . '.' . $ruleName;

                $messages[$messageKey] = match ($ruleName) {
                    'required' => $field->label . ' é obrigatório.',
                    'email' => $field->label . ' deve ser um e-mail válido.',
                    'numeric' => $field->label . ' deve ser um número.',
                    'date' => $field->label . ' deve ser uma data válida.',
                    'min' => $field->label . ' não atende ao tamanho mínimo.',
                    'max' => $field->label . ' excede o tamanho máximo.',
                    'regex' => $field->label . ' está em formato inválido.',
                    default => null
                };
            }

            $attributes[$fieldKey] = $field->label;
        }
    }

    if (!empty($rules)) {
        try {
            $this->validate($rules, $messages, $attributes);
        } catch (ValidationException $e) {
            // Pega o primeiro erro para mostrar no toast
            dd();
            $this->toast(
                type: 'error',
                title: 'Erro de validação',
                description: "Preencha corretamente os campos do formulário",
                position: 'toast-top toast-center',
                icon: 'o-exclamation-triangle',
                css: 'alert-error',
                timeout: 5000
            );
            
            throw $e; // Re-lança a exceção para manter os erros nos campos
        }
    }

    if ($this->emailAlreadyUsed()) {
        $this->toast(
            type: 'error',
            title: 'E-mail já utilizado',
            description: 'Este e-mail já foi usado para enviar este formulário.',
            position: 'toast-top toast-center',
            icon: 'o-exclamation-triangle',
            css: 'alert-error',
            timeout: 5000
        );
        
        throw ValidationException::withMessages([
            'respondent_email' => 'Este e-mail já foi usado para enviar este formulário. Cada e-mail pode enviar apenas uma vez.'
        ]);
    }
}
   public function getScaleOptions($minValue, $maxValue)
   {
      return collect(range($minValue, $maxValue))
         ->mapWithKeys(fn($i) => [$i => $i])
         ->toArray();
   }

   public function getScaleOptionsWithLabels($minValue, $maxValue)
   {
      return collect(range($minValue, $maxValue))
         ->map(fn($i) => ['id' => $i, 'name' => "$i"])
         ->toArray();
   }

   public function render()
   {
      return view('livewire.site.pages.company-form.index');
   }


   private function checkRateLimit()
   {
      $ip = request()->ip();
      $userAgent = request()->userAgent();
      $formId = $this->form->id;

      // 1. Fingerprint único por sessão/browser/formulário
      $fingerprint = $this->generateFingerprint($ip, $userAgent, $formId);
      $fingerprintKey = 'form-submit-fp:' . $fingerprint;

      // 2. Rate limit por email + formulário (mais restritivo)
      if ($this->hasValidEmail()) {
         $emailKey = 'form-submit-email:' . $formId . ':' . strtolower(trim($this->respondent_email));

         if (RateLimiter::tooManyAttempts($emailKey, 3)) { // 3 por email por form
            $seconds = RateLimiter::availableIn($emailKey);
            $timeFormatted = $this->formatWaitTime($seconds);
            throw ValidationException::withMessages([
               'respondent_email' => "Muitas tentativas com este email neste formulário. Aguarde {$timeFormatted}."
            ]);
         }

         RateLimiter::hit($emailKey, 900); // 15 minutos
      }

      // 3. Rate limit por fingerprint + formulário (moderado)
      if (RateLimiter::tooManyAttempts($fingerprintKey, 8)) { // 8 por browser/sessão por form
         $seconds = RateLimiter::availableIn($fingerprintKey);
         $timeFormatted = $this->formatWaitTime($seconds);
         throw ValidationException::withMessages([
            'rate_limit' => "Muitas tentativas neste formulário. Aguarde {$timeFormatted}."
         ]);
      }

      RateLimiter::hit($fingerprintKey, 600); // 10 minutos

      // 4. Rate limit por IP + formulário (permissivo)
      $ipKey = 'form-submit-ip:' . $formId . ':' . $ip;
      $ipLimit = $this->hasValidEmail() ? 50 : 25; // Mais permissivo com email

      if (RateLimiter::tooManyAttempts($ipKey, $ipLimit)) {
         $seconds = RateLimiter::availableIn($ipKey);
         $timeFormatted = $this->formatWaitTime($seconds);
         throw ValidationException::withMessages([
            'rate_limit' => "Limite de tentativas deste formulário excedido. Aguarde {$timeFormatted}."
         ]);
      }

      RateLimiter::hit($ipKey, 1800); // 30 minutos

      // 5. Rate limit global por IP (proteção geral)
      $globalIpKey = 'form-submit-global-ip:' . $ip;

      if (RateLimiter::tooManyAttempts($globalIpKey, 100)) { // Limite alto para todos os forms
         $seconds = RateLimiter::availableIn($globalIpKey);
         $timeFormatted = $this->formatWaitTime($seconds);
         throw ValidationException::withMessages([
            'rate_limit' => "Limite global de tentativas excedido. Aguarde {$timeFormatted}."
         ]);
      }

      RateLimiter::hit($globalIpKey, 3600); // 1 hora
   }

   private function generateFingerprint($ip, $userAgent, $formId): string
   {
      // Cria um fingerprint único baseado em múltiplos fatores + formulário
      $sessionId = session()->getId();
      $components = [
         substr($ip, 0, -1) . 'X', // Mascara último octeto do IP
         substr(md5($userAgent), 0, 8), // Hash parcial do User-Agent
         $sessionId,
         $formId // Inclui ID do formulário
      ];

      return hash('sha256', implode('|', $components));
   }


   private function hasValidEmail(): bool
   {
      return $this->form->collect_email &&
         !empty($this->respondent_email) &&
         filter_var($this->respondent_email, FILTER_VALIDATE_EMAIL);
   }

   private function formatWaitTime(int $seconds): string
   {
      if ($seconds < 60) {
         return "{$seconds} segundo" . ($seconds !== 1 ? 's' : '');
      }

      $minutes = intval($seconds / 60);
      $remainingSeconds = $seconds % 60;

      if ($remainingSeconds === 0) {
         return "{$minutes} minuto" . ($minutes !== 1 ? 's' : '');
      }

      $minuteText = "{$minutes} minuto" . ($minutes !== 1 ? 's' : '');
      $secondText = "{$remainingSeconds} segundo" . ($remainingSeconds !== 1 ? 's' : '');

      return "{$minuteText} e {$secondText}";
   }

   private function clearRateLimit()
   {
      // ESTRATÉGIA: Só limpa alguns rate limits específicos, não todos

      $ip = request()->ip();
      $userAgent = request()->userAgent();
      $formId = $this->form->id;
      $fingerprint = $this->generateFingerprint($ip, $userAgent, $formId);

      // 1. SEMPRE mantém rate limit por email (nunca limpa)
      // Previne múltiplos envios com mesmo email no mesmo form

      // 2. Limpa fingerprint apenas se for primeiro sucesso recente
      $fingerprintKey = 'form-submit-fp:' . $fingerprint;
      $successKey = 'form-success-fp:' . $fingerprint;

      if (!RateLimiter::tooManyAttempts($successKey, 1)) {
         // Primeira submissão bem-sucedida recente - pode limpar
         RateLimiter::clear($fingerprintKey);
         RateLimiter::hit($successKey, 300); // Marca sucesso por 5min
      }
      // Se já teve sucesso recente, mantém rate limit ativo

      // 3. NUNCA limpa IP rate limit (proteção da rede e global)
   }

   public function getAllFields()
   {
      if ($this->isJobVacancy) {
         $fields = collect();
         if ($this->form && $this->form->sections) {
            foreach ($this->form->sections as $section) {
               if ($section->fields) {
                  $fields = $fields->merge($section->fields);
               }
            }
         }
         return $fields;
      } else {
         return $this->form->activeFormFields ?? collect();
      }
   }

   private function emailAlreadyUsed(): bool
   {
      if (!$this->hasValidEmail()) {
         return false;
      }

      $email = strtolower(trim($this->respondent_email));
      $subjectId = $this->isJobVacancy ? $this->jobVacancy->id : $this->form->id;
      $subjectType = $this->isJobVacancy ? JobVacancy::class : CompanyForm::class;

      return FormResponse::where('subject_id', $subjectId)
         ->where('subject_type', $subjectType)
         ->where('respondent_email', $email)
         ->exists();
   }

   private function getFormTemplate()
   {
      return $this->isJobVacancy
         ? $this->form
         : $this->form->formTemplate ?? $this->form;
   }

   private function shouldCollect(string $field): bool
   {
      $template = $this->getFormTemplate();
      $property = "collect_{$field}";

      return $template->$property ?? false;
   }
}
