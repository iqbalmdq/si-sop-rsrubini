<?php

namespace App\Filament\Direktur\Resources;

use App\Filament\Direktur\Resources\UserBidangResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserBidangResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Manajemen Akun';

    protected static ?string $navigationLabel = 'User Bidang';

    protected static ?string $modelLabel = 'User Bidang';

    protected static ?string $pluralModelLabel = 'User Bidang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('bidang_bagian')
                            ->label('Bidang/Bagian')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Keamanan')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->minLength(8)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->same('password')
                            ->dehydrated(false)
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bidang_bagian')
                    ->label('Bidang/Bagian')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('resetPassword')
                    ->label('Reset Password')
                    ->icon('heroicon-o-key')
                    ->form([
                        Forms\Components\TextInput::make('new_password')
                            ->label('Password Baru')
                            ->password()
                            ->minLength(8)
                            ->required(),
                        Forms\Components\TextInput::make('confirm_password')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->same('new_password')
                            ->required(),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->password = $data['new_password'];
                        $record->save();
                    })
                    ->modalHeading('Reset Password User')
                    ->modalSubmitActionLabel('Simpan'),
                Tables\Actions\Action::make('toggleActive')
                    ->label('Toggle Aktif')
                    ->icon('heroicon-o-power')
                    ->requiresConfirmation()
                    ->action(function (User $record): void {
                        $record->is_active = ! $record->is_active;
                        $record->save();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 'bidang');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserBidangs::route('/'),
            'create' => Pages\CreateUserBidang::route('/create'),
            'edit' => Pages\EditUserBidang::route('/{record}/edit'),
        ];
    }
}
