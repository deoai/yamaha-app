<?php

namespace App\Filament\Resources\OpenTicketResource\Pages;

use App\Filament\Resources\ListOpenTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOpenTicket extends CreateRecord
{
    protected static string $resource = ListOpenTicketResource::class;
}
