<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpenTicketResource\Pages;
use App\Filament\Resources\OpenTicketResource\RelationManagers;
use App\Models\Category;
use App\Models\OpenTicket;
use App\Models\Ticket;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Columns\ImageColumn;

class ListOpenTicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Super Admin Features';
    protected static ?string $navigationLabel = 'Tickets Open';
    public static function canViewAny(): bool
    {
        return Auth::user()->role === 'admin';
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'open')->count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->required()
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('Select User')
                    ->label('User'),
                Select::make('category_id')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->placeholder('Select Category')
                    ->label('Category'),
                Textarea::make('description')
                    ->required()
                    ->placeholder('Enter Description')
                    ->autosize()
                    ->label('Description'),
                Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'on_proccess' => 'On Proccess',
                        'closed' => 'Closed',
                    ])
                    ->default('open')
                    ->placeholder('Select Status')
                    ->label('Status'),
                FileUpload::make('file')
                    ->label('Upload File')
                    ->multiple()
                    ->image()
                    ->disk('public')
                    ->imageEditor(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('description')
                    ->wrap(),
                ImageColumn::make('file')
                    ->disk('public'),
                TextColumn::make('status')
                    ->formatStateUsing(fn(string $state): string => strtoupper($state))
                    ->color(fn(string $state): string => match ($state) {
                        'open' => 'info',
                        'on_proccess' => 'warning',
                        'closed' => 'success',
                    }),
                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
            ])
            ->defaultSort('created_at', 'asc')
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'open'))
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'open' => 'Open',
                        'on_proccess' => 'On Proccess',
                        'closed' => 'Closed',
                    ])
                    ->placeholder('Select Status'),
                SelectFilter::make('category_id')
                    ->multiple()
                    ->options(Category::all()->pluck('name', 'id'))
                    ->label('Category')
                    ->placeholder('Select Category'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('user.name')
                    ->label('User'),
                Infolists\Components\TextEntry::make('user.email')
                    ->label('Email'),
                Infolists\Components\TextEntry::make('user.no_hp')
                    ->label('No HP'),
                Infolists\Components\TextEntry::make('category.name')
                    ->label('Category'),
                Infolists\Components\TextEntry::make('description')
                    ->label('Description'),
                Infolists\Components\ImageEntry::make('file')
                    ->disk('public')
                    ->label('Attachments'),
                Infolists\Components\TextEntry::make('status')
                    ->formatStateUsing(fn(string $state): string => strtoupper($state))
                    ->color(fn(string $state): string => match ($state) {
                        'open' => 'info',
                        'on_proccess' => 'warning',
                        'closed' => 'success',
                    }),
                Infolists\Components\TextEntry::make('created_at')
                    ->dateTime()
                    ->label('Created At'),
                Infolists\Components\TextEntry::make('updated_at')
                    ->dateTime()
                    ->label('Updated At'),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOpenTickets::route('/'),
            'create' => Pages\CreateOpenTicket::route('/create'),
            'edit' => Pages\EditOpenTicket::route('/{record}/edit'),
        ];
    }
}
