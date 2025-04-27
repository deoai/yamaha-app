<?php

namespace App\Filament\Resources\ListAllTicketResource\Pages;

use App\Filament\Resources\ListAllTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListticket extends EditRecord
{
    protected static string $resource = ListAllTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
