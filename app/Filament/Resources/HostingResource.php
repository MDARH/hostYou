<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HostingResource\Pages;
use App\Filament\Resources\HostingResource\RelationManagers;
use App\Models\Customer;
use App\Models\Hosting;
use App\Models\HostingPlan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ramsey\Uuid\Type\Decimal;

class HostingResource extends Resource
{
    protected static ?string $model = Hosting::class;
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customer_id')
                    ->label('Select Customer Name')
                    ->relationship('customer', 'full_name')
                    ->options(Customer::all()->pluck('full_name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('plan_id')
                    ->label('Select Hosting Plan')
                    ->relationship('hostingPlan', 'plan_name')
                    ->options(HostingPlan::all()->pluck('plan_name', 'id'))
                    ->required()
                    ->searchable(),
                TextInput::make('domain_name')
                    ->label('Domain Name')
                    ->prefix('https://'),
                DatePicker::make('next_renewal_date')
                    ->label('Next Renewal Date'),
                TextInput::make('payment_amount')
                    ->label('Payment Amount')
                    ->numeric()
                    ->inputMode('decimal'),
                Select::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'Paid' => 'Paid',
                        'Unpaid' => 'Unpaid'
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.full_name'),
                TextColumn::make('domain_name'),
                TextColumn::make('hostingPlan.plan_name'),
                TextColumn::make('next_renewal_date'),
                BadgeColumn::make('payment_status')
                ->colors([
                    'danger' => 'Unpaid',
                    'success' => 'Paid'
                ])
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
            'index' => Pages\ListHostings::route('/'),
            'create' => Pages\CreateHosting::route('/create'),
            'edit' => Pages\EditHosting::route('/{record}/edit'),
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
