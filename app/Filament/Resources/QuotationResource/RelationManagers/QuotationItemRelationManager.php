<?php

namespace App\Filament\Resources\QuotationResource\RelationManagers;

use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuotationItemRelationManager extends RelationManager
{
    protected static string $relationship = 'QuotationItems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->label('Item')
                    ->options(Item::query()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()->default(1)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('item.name'),
                Tables\Columns\TextColumn::make('item.price')
                    ->label('Price')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('totalPrice')
                    ->money('IDR', locale: 'id')
                    ->getStateUsing(
                        fn ($record): ?string => $record->item->price * $record->quantity
                    ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['quotation_id'] = request()->segment(3);

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalHeading(fn ($record) => "Edit {$record->item->name}"),
                Tables\Actions\DeleteAction::make()->modalHeading(fn ($record) => "Delete {$record->item->name}"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
