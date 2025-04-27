<?php

namespace App\Filament\Resources\OpenTicketResource\Pages;

use App\Filament\Resources\ListOpenTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOpenTickets extends ListRecords
{
    protected static string $resource = ListOpenTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
