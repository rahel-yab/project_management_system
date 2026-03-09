<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['task.project', 'user']);
        $user = Auth::user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->role === 'manager') {
            return $query->whereHas('task.project', fn (Builder $builder) => $builder->where('created_by', $user->id));
        }

        if ($user->role === 'developer') {
            return $query->whereHas('task', fn (Builder $builder) => $builder->where('assigned_to', $user->id));
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        $user = Auth::user();

        return $form->schema([
            Forms\Components\Select::make('task_id')
                ->label('Task')
                ->relationship(
                    'task',
                    'title',
                    fn (Builder $query) => match ($user?->role) {
                        'manager' => $query->whereHas('project', fn (Builder $projectQuery) => $projectQuery->where('created_by', $user->id)),
                        'developer' => $query->where('assigned_to', $user->id),
                        default => $query,
                    }
                )
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\Textarea::make('content')
                ->required()
                ->minLength(3)
                ->columnSpanFull(),
            Forms\Components\Hidden::make('user_id')->default(Auth::id()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('task.title')
                    ->label('Task')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable(),
                Tables\Columns\TextColumn::make('content')
                    ->limit(80)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('viewAny', Comment::class);
    }

    public static function canCreate(): bool
    {
        return Gate::allows('create', Comment::class);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
        ];
    }
}
