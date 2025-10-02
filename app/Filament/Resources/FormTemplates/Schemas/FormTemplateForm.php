<?php

namespace App\Filament\Resources\FormTemplates\Schemas;

use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class FormTemplateForm
{
   public static function configure(Schema $schema): Schema
   {
      return $schema
         ->schema([
            Section::make('Informações Básicas')
               ->description('Configure as informações principais do formulário')
               ->columnSpanFull()
               ->columns(2)
               ->schema([
                  TextInput::make('name')
                     ->label('Nome do Formulário')
                     ->required()
                     ->columnSpanFull()
                     ->maxLength(255)
                     ->live(onBlur: true)
                     ->afterStateUpdated(
                        fn(string $operation, $state, callable $set) =>
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                     )
                     ->placeholder('Ex: Formulário de Contato')
                     ->helperText('Nome identificador do formulário'),


                  Textarea::make('description')
                     ->label('Descrição')
                     ->rows(3)
                     ->maxLength(500)
                     ->columnSpanFull()
                     ->placeholder('Descreva o propósito deste formulário...')
                     ->helperText('Descrição opcional para identificar o formulário'),

                  Toggle::make('is_vacancy_form')
                     ->label('Formulário de Vagas')
                     ->columnSpanFull()
                     ->default(false)
                     ->helperText('Marque se este formulário for para candidaturas de emprego'),
               ]),

            Section::make('Configurações de Coleta')
               ->description('Defina quais dados serão coletados')
               ->columnSpanFull()
               ->columns(4)
               ->schema([
                  Toggle::make('is_active')
                     ->label('Formulário Ativo')
                     ->default(true)
                     ->helperText('Desative para pausar temporariamente'),

                  Toggle::make('collect_name')
                     ->label('Coletar Nome')
                     ->default(true)
                     ->helperText('Campo obrigatório de nome'),

                  Toggle::make('collect_email')
                     ->label('Coletar E-mail')
                     ->default(true)
                     ->helperText('Campo obrigatório de e-mail'),


                  Toggle::make('collect_phone')
                     ->label('Coletar Whatsapp')
                     ->default(true)
                     ->helperText('Campo obrigatório de telefone'),
               ]),

            Section::make('Pós-Envio')
               ->description('Configure o que acontece após o envio do formulário')
               ->columnSpanFull()
               ->schema([
                  TextInput::make('title_success_message')
                     ->label('Título da nensagem de sucesso')
                     ->maxLength(255)
                     ->columnSpanFull()
                     ->default('Enviado com sucesso.')
                     ->placeholder('Digite o título da mensagem que será exibida após o envio...')
                     ->helperText('Título da mensagem exibida quando o formulário for enviado com sucesso'),

                  Textarea::make('success_message')
                     ->label('Mensagem de sucesso')
                     ->rows(4)
                     ->maxLength(1000)
                     ->columnSpanFull()
                     ->default('Obrigado! Sua mensagem foi enviada com sucesso. Entraremos em contato em breve.')
                     ->placeholder('Digite a mensagem que será exibida após o envio...')
                     ->helperText('Mensagem exibida quando o formulário for enviado com sucesso'),

                  TextInput::make('redirect_url')
                     ->label('URL de Redirecionamento')
                     ->url()
                     ->maxLength(500)
                     ->placeholder('https://exemplo.com/obrigado')
                     ->helperText('Opcional: Redirecionar para uma página específica após o envio')
                     ->suffixIcon('heroicon-m-link'),
               ]),
         ]);
   }
}
