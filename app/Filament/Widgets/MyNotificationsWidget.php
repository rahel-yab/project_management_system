<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class MyNotificationsWidget extends BaseWidget
{
    protected static ?string $heading = 'My Notifications';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::check();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('message')
                    ->label('Message')
                    ->getStateUsing(fn (DatabaseNotification $record): string => $record->data['message'] ?? 'Notification')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Task')
                    ->getStateUsing(fn (DatabaseNotification $record): string => $record->data['title'] ?? '-')
                    ->searchable(),
                TextColumn::make('project')
                    ->label('Project')
                    ->getStateUsing(fn (DatabaseNotification $record): string => $record->data['project_name'] ?? '-'),
                BadgeColumn::make('read_at')
                    ->label('Status')
                    ->formatStateUsing(fn ($state): string => $state ? 'Read' : 'Unread')
                    ->colors([
                        'success' => fn ($state): bool => (bool) $state,
                        'warning' => fn ($state): bool => ! $state,
                    ]),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->since(),
            ])
            ->actions([
                Action::make('mark_read')
                    ->label('Mark as read')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (DatabaseNotification $record): bool => is_null($record->read_at))
                    ->action(function (DatabaseNotification $record): void {
                        $record->markAsRead();
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10, 25]);
    }

    protected function getTableQuery(): Builder
    {
        return DatabaseNotification::query()
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', Auth::id());
    }
}
