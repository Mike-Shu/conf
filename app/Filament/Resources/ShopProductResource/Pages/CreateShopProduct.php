<?php

namespace App\Filament\Resources\ShopProductResource\Pages;

use App\Filament\Resources\ShopProductResource;
use Filament\Forms\Components;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class CreateShopProduct extends CreateRecord
{
    protected static string $resource = ShopProductResource::class;

    /**
     * @return array
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->context('create')
                ->model($this->getModel())
                ->schema($this->getFormSchema())
                ->columns(4)
                ->statePath('data')
                ->inlineLabel(config('filament.layout.forms.have_inline_labels')),
        ];
    }

    /**
     * @return array
     */
    protected function getFormSchema(): array
    {
        return [
            Components\Group::make()
                ->schema([
                    Components\Card::make()
                        ->schema([
                            Components\TextInput::make('title')
                                ->label(__('Title'))
                                ->autofocus()
                                ->required(),

                            Components\RichEditor::make('description')
                                ->label(__('Description'))
                                ->disableAllToolbarButtons()
                                ->enableToolbarButtons([
                                    'bold',
                                    'italic',
                                    'strike',
                                    'bulletList',
                                    'redo',
                                    'undo',
                                ])
                                ->nullable(),

                            Components\Grid::make()
                                ->schema([
                                    Components\TextInput::make('price')
                                        ->label(__('Price'))
                                        ->numeric()
                                        ->minValue(0)
                                        ->required(),

                                    Components\TextInput::make('stock')
                                        ->label(__('Amount'))
                                        ->numeric()
                                        ->minValue(0)
                                        ->required(),
                                ]),
                        ]),
                ])
                ->columnSpan(['lg' => 3]),

            Components\Group::make()
                ->schema([])
                ->columnSpan(['lg' => 1]),
        ];
    }

    /**
     * @param array $data
     * @return Model
     */
    protected function handleRecordCreation(array $data): Model
    {
        $stock = (int)Arr::pull($data, 'stock');
        $product = $this->getModel()::create($data);
        $product->mutateStock($stock);

        return $product;
    }
}
