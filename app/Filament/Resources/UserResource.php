<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\TextInput::make('name')->required(),
        Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
        Forms\Components\TextInput::make('password')
            ->password()
            ->dehydrated(fn ($state) => filled($state))
            ->required(fn (string $context): bool => $context === 'create'),
        Forms\Components\Select::make('role')
            ->options([
                'admin' => 'Admin',
                'manager' => 'Manager',
                'developer' => 'Developer',
            ])->required(),
    ]);
}

public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('name')->searchable(),
        Tables\Columns\TextColumn::make('email'),
        Tables\Columns\TextColumn::make('role')->badge(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
{
    return Auth::user()->role === 'admin';
}
}
