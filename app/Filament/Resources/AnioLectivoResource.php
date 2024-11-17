<?php

namespace App\Filament\Resources;



use App\Filament\Resources\AnioLectivoResource\Pages;
use App\Filament\Resources\AnioLectivoResource\RelationManagers\MatriculaRelationManager;

use App\Models\AnioLectivo;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextEntry;
use Filament\Forms\Components\Select;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class AnioLectivoResource extends Resource
{
    protected static ?string $model = AnioLectivo::class;

    protected static ?string $modelLabel = 'Año lectivo';

    protected static ?string $pluralModelLabel = 'Años lectivos';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $navigationGroup = 'Año Lectivo';

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('anio')
                    ->label('Año lectivo')
                    ->options(function () {
                        $currentYear = Carbon::now()->year;
                        $futureYears = range($currentYear + 1, $currentYear + 5);

                        // Genera las opciones en el formato [año => año]
                        return array_combine($futureYears, $futureYears);
                    })
                    ->disabledOn('edit')
                    ->required(),
                DatePicker::make('fecha_inicio')
                    ->native(false)
                    ->label('Fecha de Inicio')
                    ->minDate(Carbon::now()->addYear()->startOfYear())
                    ->maxDate(Carbon::now()->addYear()->endOfYear())
                    ->displayFormat('d/m/Y')
                    ->closeOnDateSelection()
                    ->disabledOn('edit')
                    ->required(),

                DatePicker::make('fecha_fin')
                    ->native(false)
                    ->label('Fecha de Fin')
                    ->minDate(Carbon::now()->addYear()->startOfYear())
                    ->maxDate(Carbon::now()->addYear()->endOfYear())
                    ->displayFormat('d/m/Y')
                    ->closeOnDateSelection()
                    ->disabledOn('edit')
                    ->required(),

                Textarea::make('descripcion')
                    ->autosize()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('anio')
                    ->label('Año lectivo'),

                TextColumn::make('fecha_inicio')
                    ->label('Fecha de Inicio')
                    ->dateTime('d/m/Y'),

                TextColumn::make('fecha_fin')
                    ->label('Fecha de Fin')
                    ->dateTime('d/m/Y'),
            ])
            ->defaultSort('anio', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->anio >= Carbon::now()->year),
                Tables\Actions\ViewAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MatriculaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnioLectivos::route('/'),
            'create' => Pages\CreateAnioLectivo::route('/create'),
            'edit' => Pages\EditAnioLectivo::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            AnioLectivoResource\Widgets\AniosLectivosOverview::class,
        ];
    }
}
