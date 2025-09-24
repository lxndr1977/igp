<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $value);
        
        if (strlen($cnpj) != 14) {
            $fail('O CNPJ informado não é válido.');
            return;
        }
        
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            $fail('O CNPJ informado não é válido.');
            return;
        }
        
        // Calcula os dígitos verificadores
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        
        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            $fail('O CNPJ informado não é válido.');
            return;
        }
        
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        
        $resto = $soma % 11;
        if ($cnpj[13] != ($resto < 2 ? 0 : 11 - $resto)) {
            $fail('O CNPJ informado não é válido.');
            return;
        }
    }
}