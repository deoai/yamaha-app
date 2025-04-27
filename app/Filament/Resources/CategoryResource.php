<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Joaopaulolndev\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Super Admin Features';

    public static function canViewAny(): bool
    {
        return Auth::user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->autofocus()
                    ->label('Category Name'),
                FileUpload::make('pdf_path')
                    ->label('Upload Pdf'),
                PdfViewerField::make('pdf_path')
                    ->label('View the PDF')
                    ->minHeight('40svh')
                    ->fileUrl(fn($record) => $record && $record->pdf_path
                    ? url('/view-pdf/' . $record->pdf_path)
                    : null)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('pdf_path')
                    ->label('Pdf Name')
            ])
            ->filters([
                //
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
                PdfViewerEntry::make('pdf_path')
                    ->label('View the PDF')
                    ->minHeight('40svh'),
                Infolists\Components\TextEntry::make('name')
                    ->label('Name'),
                Infolists\Components\TextEntry::make('pdf_path')
                    ->label('Pdf Path'),

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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
