<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Filament\Resources\CursoResource\Pages;
use App\Filament\Resources\CursoResource\RelationManagers;
use App\Filament\Resources\CursoResource\RelationManagers\MateriasRelationManager;

use App\Models\Curso;
use App\Models\Materia;
use App\Models\AnioLectivo;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CursoResource extends Resource
{
    protected static ?string $model = Curso::class;

    protected static ?string $modelLabel = 'Curso';

    protected static ?string $pluralModelLabel = 'Cursos';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $navigationGroup = 'Año Lectivo';

    protected static ?string $navigationIcon = 'heroicon-o-at-symbol';

    public static function form(Form $form): Form
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
                    ->description('Ingrese el ciclo del curso.')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ciclo')->label('Ciclo')->sortable(),
                TextColumn::make('nombre')->label('Curso'),
                TextColumn::make('division')->label('División'),
                TextColumn::make('turno')->label('Turno'),
                TextColumn::make('materias_count')->counts('materias')->label('Materias'),
            ])
            ->filters([
                SelectFilter::make('anio_lectivo')
                    ->relationship('anioLectivo', 'anio')
                    ->default()
                    ->label('Año lectivo'),

                SelectFilter::make('ciclo')
                    ->options([
                        'CB' => 'Ciclo básico',
                        'CE' => 'Ciclo especialización'
                    ]),
            ])
            ->actions([
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('nueva_materia')
                    ->label('Agregar Materia')
                    ->modalHeading('Nueva Materia')
                    ->modalWidth('lg')
                    ->form([
                        Forms\Components\Section::make('Año lectivo y curso')
                            ->description('Datos del año lectivo y el curso afectado')
                            ->schema([
                                Forms\Components\Select::make('anio_lectivo_id')
                                ->label('Año lectivo')
                                ->options(AnioLectivo::pluck('anio', 'id'))
                                ->default(fn ($record) => $record->anioLectivo?->id)
                                ->disabled(),

                                Forms\Components\Select::make('curso_id')
                                ->label('Curso')
                                ->options(Curso::pluck('nombre', 'id'))
                                ->afterStateHydrated(function (Forms\Components\Select $component, $state) {
                                    if ($state) {
                                        $curso = Curso::find($state);
                                        if ($curso) {
                                            $component->helperText('Turno: ' . $curso->turno);
                                        }
                                    }
                                })
                                ->default(fn ($record) => $record?->id)
                                ->disabled()
                            ])
                            ->columns(2),
                        Forms\Components\Section::make('Materia')
                            ->label('Materia')
                            ->schema([
                                Forms\Components\TextInput::make('nombre')
                                    ->label('Nombre de la Materia')
                                    ->required(),
                            ]),
                    ])
                    ->action(function (array $data, $record) {
                        try {
                            $data['curso_id'] = $record->id;
                            Materia::create($data);
                            Notification::make()
                                ->title('Materia guardada con éxito')
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Error: '. $e->getMessage())
                                ->failure()
                                ->send();
                        }
                    })
                    ->icon('heroicon-o-book-open')
                    // ->url(fn ($record) => route('filament.neutrinoadmin.resources.materias.create', [
                    //     'anio_lectivo' => $record->anio_lectivo_id,
                    // ]))
            ])
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
            MateriasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCursos::route('/'),
            'create' => Pages\CreateCurso::route('/create'),
            'edit' => Pages\EditCurso::route('/{record}/edit'),
        ];
    }
}
