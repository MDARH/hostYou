<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HostingPlanResource\Pages;
use App\Filament\Resources\HostingPlanResource\RelationManagers;
use App\Models\HostingPlan;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HostingPlanResource extends Resource
{
    protected static ?string $model = HostingPlan::class;
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationLabel = 'Hosting Package';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('plan_name')->required()->columnSpan(2),
                TextInput::make('capacity')->required(),
                TextInput::make('price')->required()->numeric()->inputMode('decimal'),
                Textarea::make('plan_details')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plan_name'),
                TextColumn::make('capacity'),
                TextColumn::make('price'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListHostingPlans::route('/'),
            'create' => Pages\CreateHostingPlan::route('/create'),
            'edit' => Pages\EditHostingPlan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
