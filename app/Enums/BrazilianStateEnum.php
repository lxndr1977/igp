<?php

namespace App\Enums;

enum BrazilianStateEnum: string
{
   case AC = 'AC';
   case AL = 'AL';
   case AP = 'AP';
   case AM = 'AM';
   case BA = 'BA';
   case CE = 'CE';
   case DF = 'DF';
   case ES = 'ES';
   case GO = 'GO';
   case MA = 'MA';
   case MT = 'MT';
   case MS = 'MS';
   case MG = 'MG';
   case PA = 'PA';
   case PB = 'PB';
   case PR = 'PR';
   case PE = 'PE';
   case PI = 'PI';
   case RJ = 'RJ';
   case RN = 'RN';
   case RS = 'RS';
   case RO = 'RO';
   case RR = 'RR';
   case SC = 'SC';
   case SP = 'SP';
   case SE = 'SE';
   case TO = 'TO';

   public static function options(): array
   {
      return array_column(array_map(fn($state) => ['label' => $state->name, 'value' => $state->value], self::cases()), 'value', 'label');
   }

   public static function forMarySelect(): array
   {
      return array_map(fn($state) => [
         'label' => $state->name,
         'value' => $state->value,
      ], self::cases());
   }
}
