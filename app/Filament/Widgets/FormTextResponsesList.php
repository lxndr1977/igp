<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\FormFieldResponse;
use App\Models\FormTemplateField;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;

class FormTextResponsesList extends BaseWidget
{
    protected static ?string $heading = 'Respostas de Texto';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    
    public ?int $fieldId = null;
    public ?FormTemplateField $field = null;


    // Em App\Filament\Pages\Dashboard.php ou config/filament.php
public static function canView(): bool
{
    return false;
}


    public function table(Table $table): Table
    {
        if (!$this->fieldId) {
            return $table->query(FormFieldResponse::query()->whereRaw('1 = 0'));
        }

        $this->field = FormTemplateField::find($this->fieldId);
        
        if (!$this->field || $this->canGenerateChart($this->field->type)) {
            return $table->query(FormFieldResponse::query()->whereRaw('1 = 0'));
        }

        return $table
            ->query(
                FormFieldResponse::query()
                    ->where('form_template_field_id', $this->fieldId)
                    ->whereNotNull('value')
                    ->with(['formResponse'])
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('formatted_value')
                    ->label('Resposta')
                    ->getStateUsing(function (FormFieldResponse $record): string {
                        if (is_array($record->value)) {
                            return implode(', ', $record->value);
                        }
                        return $record->value ?: 'Sem resposta';
                    })
                    ->wrap()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $query) use ($search) {
                            $query->where('value', 'like', "%{$search}%")
                                  ->orWhereRaw("JSON_EXTRACT(value, '$[*]') LIKE ?", ["%{$search}%"]);
                        });
                    }),
                    
                Tables\Columns\TextColumn::make('formResponse.respondent_name')
                    ->label('Nome do Respondente')
                    ->placeholder('Não informado')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('formResponse.respondent_email')
                    ->label('Email do Respondente')
                    ->placeholder('Não informado')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('formResponse.submitted_at')
                    ->label('Data de Envio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('formResponse.ip_address')
                    ->label('IP')
                    ->placeholder('Não registrado')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_respondent_info')
                    ->label('Com informações do respondente')
                    ->query(fn (Builder $query): Builder => $query->whereHas('formResponse', function (Builder $query) {
                        $query->where(function (Builder $query) {
                            $query->whereNotNull('respondent_name')
                                  ->orWhereNotNull('respondent_email');
                        });
                    })),
                    
                Tables\Filters\Filter::make('recent')
                    ->label('Últimos 7 dias')
                    ->query(fn (Builder $query): Builder => $query->whereHas('formResponse', function (Builder $query) {
                        $query->where('submitted_at', '>=', now()->subDays(7));
                    })),
            ])
            // ->actions([
            //     Action::make('view_full_response')
            //         ->label('Ver Resposta Completa')
            //         ->icon('heroicon-o-eye')
            //         ->modalHeading('Resposta Completa')
            //         ->modalContent(function (FormFieldResponse $record) {
            //             $value = $record->value;
            //             if (is_array($value)) {
            //                 $value = implode(', ', $value);
            //             }
                        
            //             return view('filament.widgets.response-modal', [
            //                 'field' => $record->formTemplateField,
            //                 'value' => $value,
            //                 'response' => $record->formResponse,
            //             ]);
            //         })
            //         ->modalSubmitAction(false)
            //         ->modalCancelActionLabel('Fechar'),
            // ])
            ->defaultSort('formResponse.submitted_at', 'desc')
            ->paginated([10, 25, 50])
            ->poll('30s');
    }

    protected function canGenerateChart(string $fieldType): bool
    {
        return in_array($fieldType, [
            'select',
            'radio',
            'checkbox',
            'boolean',
            'rating'
        ]);
    }

    public function setField(int $fieldId): static
    {
        $this->fieldId = $fieldId;
        return $this;
    }

    protected function getTableHeading(): ?string
    {
        if ($this->field) {
            return "Respostas: {$this->field->label}";
        }
        
        return static::$heading;
    }
}

