<?php

namespace App\Filament\Resources\ListAllTicketResource\Pages;

use App\Filament\Resources\ListAllTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateListticket extends CreateRecord
{
    protected static string $resource = ListAllTicketResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
