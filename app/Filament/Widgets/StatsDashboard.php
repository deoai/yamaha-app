<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $totalTickets = Ticket::count();
        $totalOpenTickets = Ticket::where('status', 'open')->count();
        $totalPendingTickets = Ticket::where('status', 'on_proccess')->count();
        $totalClosedTickets = Ticket::where('status', 'closed')->count();
        return [
            Stat::make('Total Tickets', $totalTickets),
            Stat::make('Total Open Tickets', $totalOpenTickets),
            Stat::make('Total Pending Tickets', $totalPendingTickets),
            Stat::make('Total Closed Tickets', $totalClosedTickets),
        ];
    }
}
