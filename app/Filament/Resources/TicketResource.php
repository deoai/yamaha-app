<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Category;
use App\Models\Ticket;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'User Features';
    protected static ?string $navigationLabel = 'Open Ticket';
    public static function canViewAny(): bool
    {
        return Auth::user()->role === 'user';
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(Auth::id()),
                Select::make('category_id')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->label('Category'),
                Textarea::make('description')
                    ->required()
                    ->placeholder('Enter Description')
                    ->autosize()
                    ->label('Description'),
                FileUpload::make('file')
                    ->label('Upload File')
                    ->multiple()
                    ->image()
                    ->disk('public')
                    ->imageEditor(),
                Hidden::make('status')
                    ->default('open'),
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
                TextColumn::make('description'),
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
                    ->dateTime(),
            ])
            ->defaultSort('created_at', 'asc')
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
