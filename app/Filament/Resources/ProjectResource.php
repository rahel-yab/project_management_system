<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    if (Auth::user()->role === 'manager') {
        return $query->where('created_by', Auth::user()->id);
    }
    return $query;
}

public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\TextInput::make('name')->required(),
        Forms\Components\Textarea::make('description'),
        Forms\Components\DatePicker::make('deadline')->required(),
        Forms\Components\Select::make('status')
            ->options(['active' => 'Active', 'completed' => 'Completed'])
            ->default('active'),
        // Hidden field to auto-set the creator
        Forms\Components\Hidden::make('created_by')->default(Auth::user() ? Auth::user()->id : null),
    ]);
}

public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('name')->searchable(),
        Tables\Columns\TextColumn::make('deadline')->date(),
        Tables\Columns\TextColumn::make('status')->badge(),
        Tables\Columns\TextColumn::make('creator.name')->label('Created By'),
    ]);
}

public static function shouldRegisterNavigation(): bool
{
    return in_array(Auth::user()->role, ['admin', 'manager']);
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
