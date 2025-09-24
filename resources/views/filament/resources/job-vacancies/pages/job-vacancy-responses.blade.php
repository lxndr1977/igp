<x-filament-panels::page>
    <div class="space-y-6">
        <h2 class="text-xl font-bold">Detalhes da Resposta</h2>

        <div class="grid grid-cols-2 gap-4">
            <div><strong>Nome:</strong> {{ $response->respondent_name }}</div>
            <div><strong>Email:</strong> {{ $response->respondent_email }}</div>
            <div><strong>IP:</strong> {{ $response->ip_address }}</div>
            <div><strong>User Agent:</strong> {{ $response->user_agent }}</div>
            <div><strong>Enviado em:</strong> {{ $response->submitted_at?->format('d/m/Y H:i') }}</div>
        </div>

        <h3 class="text-lg font-semibold mt-6">Respostas do formulário</h3>
        <div class="space-y-2">
            @foreach ($response->formFieldResponses as $fieldResponse)
                <div class="p-2 border-b">
                    <strong>{{ $fieldResponse->formTemplateField->label }}:</strong>
                    <span>{{ $fieldResponse->formatted_value }}</span>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
