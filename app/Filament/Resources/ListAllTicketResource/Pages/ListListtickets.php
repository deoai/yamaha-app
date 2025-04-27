<?php

namespace App\Filament\Resources\ListAllTicketResource\Pages;

use App\Filament\Resources\ListAllTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListtickets extends ListRecords
{
    protected static string $resource = ListAllTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
