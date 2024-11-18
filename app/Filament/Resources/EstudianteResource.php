<?php

namespace App\Filament\Resources;

use App\Enums\Status;

use App\Filament\Resources\EstudianteResource\Pages;
use App\Filament\Resources\EstudianteResource\RelationManagers;

use App\Models\AnioLectivo;
use App\Models\Estudiante;
use App\Models\Curso;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstudianteResource extends Resource
{
    protected static ?string $model = Estudiante::class;

    protected static ?string $modelLabel = 'Estudiante';

    protected static ?string $pluralModelLabel = 'Estudiantes';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $navigationGroup = 'Estudiantes';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Radio::make('activo')
                    ->options(Status::class)
                    ->required(),

                Forms\Components\Select::make('anio_lectivo_id')
                    ->label('Año Lectivo')
                    ->options(AnioLectivo::activo()->pluck('anio', 'id'))
                    ->default(AnioLectivo::activo()->first()?->id)
                    ->required(),
                Forms\Components\TextInput::make('curso_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('dni')
                    ->required()
                    ->maxLength(8),
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('apellido')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('f_nacimiento')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefono')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('direccion')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Toggle::make('egresado')
                    ->required(),
                Forms\Components\Textarea::make('observaciones')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('curso.nombre')
                    ->label('Curso')
                    ->state(function ($record) {
                         return $record->curso->nombre . ' ' . $record->curso->division . ' - turno ' . $record->curso->turno;;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('dni')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),

                Tables\Columns\TextColumn::make('apellido')
                    ->searchable(),

                Tables\Columns\TextColumn::make('f_nacimiento')
                    ->label('Fecha de Nacimiento')
                    ->dateTime('d F Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('direccion')
                    ->searchable(),

                Tables\Columns\TextColumn::make('activo')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),

                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),

                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('anio_lectivo_curso')
                    ->form([
                        // Selección del Año Lectivo
                        Forms\Components\Select::make('anio_lectivo')
                            ->label('Año Lectivo')
                            ->options(AnioLectivo::pluck('anio', 'id'))
                            ->default(fn () => AnioLectivo::activo()->first()?->id)
                            ->reactive(), // Hace reactivo el select para actualizar el siguiente select

                        // Selección del Curso (depende del Año Lectivo)
                        Forms\Components\Select::make('curso')
                            ->label('Curso')
                            ->options(function (callable $get) {
                                $anioLectivoId = $get('anio_lectivo');
                                return $anioLectivoId
                                    ? Curso::where('anio_lectivo_id', $anioLectivoId)
                                        ->get()
                                        ->mapWithKeys(fn ($curso) => [$curso->id => "{$curso->nombre} {$curso->division}"])
                                    : [];
                            })
                            //->hidden(fn (callable $get) => empty($get('anio_lectivo'))), // Oculta si no hay un año lectivo seleccionado
                    ])
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if (isset($data['anio_lectivo']) && $anio = AnioLectivo::find($data['anio_lectivo'])) {
                            $indicators['anio_lectivo'] = 'Año Lectivo: ' . $anio->anio;
                        }

                        if (isset($data['curso']) && $curso = Curso::find($data['curso'])) {
                            $indicators['curso'] = 'Curso: ' . $curso->nombre . ' ' . $curso->division;
                        }

                        return $indicators;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        // Aplica los filtros condicionalmente
                        return $query
                            ->when(
                                $data['anio_lectivo'] ?? null,
                                fn (Builder $query, int $anioLectivoId) =>
                                    $query->whereHas('curso', fn (Builder $query) =>
                                        $query->where('anio_lectivo_id', $anioLectivoId)
                                    )
                            )
                            ->when(
                                $data['curso'] ?? null,
                                fn (Builder $query, int $cursoId) =>
                                    $query->where('curso_id', $cursoId)
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                ]),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEstudiantes::route('/'),
            'create' => Pages\CreateEstudiante::route('/create'),
            'edit' => Pages\EditEstudiante::route('/{record}/edit'),
        ];
    }
}
