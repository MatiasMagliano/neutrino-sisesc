<?php

namespace App\Filament\Resources\AnioLectivoResource\RelationManagers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\Curso;
use App\Models\AnioLectivo;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MatriculaRelationManager extends RelationManager
{
    protected static string $relationship = 'matricula';

    // título de la tabla relacionada
    protected static ?string $title = 'Cursos relacionados';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('1. Año lectivo')
                    ->description('Seleccione el año lectivo para el cual se registrarán los cursos.')
                    ->schema([
                        Select::make('anio_lectivo_id')
                            // ->relationship('anioLectivo', 'anio')
                            ->options(AnioLectivo::where('anio', '>=', Carbon::now()->year)
                            ->pluck('anio', 'id'))
                            ->label('Año lectivo')
                            ->required(),
                    ]),
                Section::make('2. Curso')
                    ->description('Ingrese los datos del curso.')
                    ->schema([
                        Select::make('ciclo')
                                ->options([
                                    'CB' => 'CB',
                                    'CE' => 'CE',
                                ])
                                ->label('Ciclo')
                                ->reactive()
                                ->required(),
                    ]),
                Section::make('3. Denominación')
                    ->description('Ingrese los datos de la denominación del curso.')
                    ->schema([
                        Select::make('nombre')
                            ->options(function (callable $get) {
                                $ciclo = $get('ciclo');
                                if ($ciclo === 'CB') {
                                    return [
                                        '1º Año' => '1º Año',
                                        '2º Año' => '2º Año',
                                        '3º Año' => '3º Año',
                                    ];
                                } elseif ($ciclo === 'CE') {
                                    return [
                                        '4º Año' => '4º Año',
                                        '5º Año' => '5º Año',
                                        '6º Año' => '6º Año',
                                    ];
                                }
                            })
                            ->label('Curso')
                            ->required(),

                        Select::make('division')
                            ->options([
                                'A' => 'A',
                                'B' => 'B',
                                'C' => 'C',
                                'D' => 'D',
                                'E' => 'E',
                                'F' => 'F',
                            ]),
                        Select::make('turno')
                            ->options([
                                'mañana' => 'Mañana',
                                'tarde'  => 'Tarde'])
                    ])->columns(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                Tables\Columns\TextColumn::make('ciclo')
                    ->label('Ciclo')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nombre.curso')
                    ->label('Denominación')
                    ->state(function (Curso $curso): string {
                        return $curso->nombre . ' ' . $curso->division . ' - turno ' . $curso->turno;
                    }),
                // Tables\Columns\TextColumn::make('division')->label('División'),
                // Tables\Columns\TextColumn::make('turno')->label('Turno'),
                Tables\Columns\TextColumn::make('materias_count')->counts('materias')->label('Materias'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ciclo')
                    ->options([
                        'CB' => 'Ciclo básico',
                        'CE' => 'Ciclo especialización'
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
