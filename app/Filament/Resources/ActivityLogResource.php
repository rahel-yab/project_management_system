<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Activity Logs';

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('action')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Actor')
                    ->default('System')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-')
                    ->badge(),
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Subject ID')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options(fn () => ActivityLog::query()->distinct()->pluck('action', 'action')->toArray()),
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->role === 'admin';
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'admin';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
