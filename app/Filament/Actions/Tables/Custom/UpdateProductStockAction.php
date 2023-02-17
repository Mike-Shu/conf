<?php

namespace App\Filament\Actions\Tables\Custom;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components;
use Filament\Support\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class UpdateProductStockAction extends Action
{
    use CanCustomizeProcess;

    /**
     * @return string|null
     */
    public static function getDefaultName(): ?string
    {
        return 'updateProductStock';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label("");

        $this->modalHeading(__('Update product stock'));

        $this->modalButton(__('Update'));

        $this->successNotificationTitle(__('filament-support::actions/edit.single.messages.saved'));

        $this->icon('heroicon-o-refresh');

        $this->tooltip(__('Update product stock'));

        $this->mountUsing(function (ComponentContainer $form, Model $record): void {
            $data['new_stock'] = $record->stock;
            $form->fill($data);
        });

        $this->form([
            Components\TextInput::make('new_stock')
                ->label(__('New product stock'))
                ->numeric()
                ->minValue(0)
                ->autofocus()
                ->required(),
        ]);

        $this->action(function (): void {
            $this->process(function (array $data, Model $record) {
                $record->setStock($data['new_stock']);
                $this->success();
            });
        });
    }
}
