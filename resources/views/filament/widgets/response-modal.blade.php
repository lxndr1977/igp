<div class="space-y-4">
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-medium text-gray-900 mb-2">Informações do Campo</h4>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700">Campo:</span>
                <span class="text-gray-900">{{ $field->label }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Tipo:</span>
                <span class="text-gray-900">{{ ucfirst($field->type) }}</span>
            </div>
        </div>
    </div>

    <div class="bg-blue-50 rounded-lg p-4">
        <h4 class="font-medium text-gray-900 mb-2">Resposta</h4>
        <div class="text-sm text-gray-900 whitespace-pre-wrap">{{ $value ?: 'Sem resposta' }}</div>
    </div>

    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-medium text-gray-900 mb-2">Informações do Respondente</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700">Nome:</span>
                <span class="text-gray-900">{{ $response->respondent_name ?: 'Não informado' }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Email:</span>
                <span class="text-gray-900">{{ $response->respondent_email ?: 'Não informado' }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Data de Envio:</span>
                <span class="text-gray-900">{{ $response->submitted_at->format('d/m/Y H:i:s') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">IP:</span>
                <span class="text-gray-900">{{ $response->ip_address ?: 'Não registrado' }}</span>
            </div>
        </div>
    </div>

    @if($response->user_agent)
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-medium text-gray-900 mb-2">User Agent</h4>
            <div class="text-xs text-gray-600 break-all">{{ $response->user_agent }}</div>
        </div>
    @endif
</div>

