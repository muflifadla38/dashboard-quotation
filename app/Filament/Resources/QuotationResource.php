<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotationResource\Pages;
use App\Filament\Resources\QuotationResource\RelationManagers\PaymentRelationManager;
use App\Filament\Resources\QuotationResource\RelationManagers\QuotationItemRelationManager;
use App\Models\Client;
use App\Models\Quotation;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General')
                    ->description('General Info')
                    ->collapsible()
                    ->schema([
                        TextInput::make('title')
                            ->autocomplete()
                            ->required(),
                        Select::make('client_id')
                            ->label('Client')
                            ->options(fn () => Client::pluck('name', 'id'))
                            ->required(),
                        DatePicker::make('date')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                'no',
                                now()->parse($state)->format('Ymd-1')
                            ))
                            ->required(),
                        TextInput::make('no')
                            ->readOnly(),
                    ]),
                Section::make('Additional')
                    ->description('Additional Info')
                    ->collapsible()
                    ->schema([
                        TextInput::make('tax')
                            ->placeholder('2')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->suffix('%'),
                        TextInput::make('termin')
                            ->placeholder('50')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->suffix('%'),
                        TextInput::make('maintenance')
                            ->placeholder('90')
                            ->numeric()
                            ->minValue(1)
                            ->suffix('days'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('client.name')->formatStateUsing(fn ($state) => Str::limit($state, 20)),
                TextColumn::make('date')->date('j M Y'),
                TextColumn::make('quotationItems.item')
                    ->label('Total')
                    ->money('IDR', locale: 'id')
                    ->getStateUsing(
                        fn (Model $record): ?string => $record->quotationItems->sum(fn ($item) => $item->quantity * $item->item->price)
                    ),
                TextColumn::make('no')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ReplicateAction::make()->button(),
                Tables\Actions\Action::make('print')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-m-printer')
                    ->url(fn (Quotation $quotation): string => route('prints.quotation', $quotation))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()->button(),
                Tables\Actions\DeleteAction::make()->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            QuotationItemRelationManager::class,
            PaymentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}
