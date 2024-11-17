<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Filament\Resources\MateriaResource\Pages;
use App\Filament\Resources\MateriaResource\RelationManagers;

use App\Models\Materia;
use App\Models\AnioLectivo;
use App\Models\Curso;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class MateriaResource extends Resource
{
    protected static ?string $model = Materia::class;

    protected static ?string $modelLabel = 'Materia';

    protected static ?string $pluralModelLabel = 'Materias';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $navigationGroup = 'Año Lectivo';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->label('Denominación'),
                TextColumn::make('descripcion')->label('Descripción'),
                TextColumn::make('created_at')->label('Creado el...')->dateTime('F Y'),
            ])
            ->filters([
                // Filtro para Año Lectivo y cursos correspondientes
                Filter::make('anio_lectivo_curso')
                    ->form([
                        // Selección del Año Lectivo
                        Select::make('anio_lectivo')
                            ->label('Año Lectivo')
                            ->options(
                                AnioLectivo::pluck('anio', 'id')
                            )
                            ->default(AnioLectivo::where('anio', '>=', Carbon::now()->year)->first()->id) // Por defecto el año actual
                            ->reactive(), // Hace reactivo el select para actualizar el siguiente select

                        // Selección del Curso (depende del Año Lectivo)
                        Select::make('curso')
                            ->label('Curso')
                            ->options(function (callable $get) {
                                $anioLectivoId = $get('anio_lectivo');
                                return $anioLectivoId
                                    ? Curso::where('anio_lectivo_id', $anioLectivoId)
                                        ->get()
                                        ->mapWithKeys(fn ($curso) => [$curso->id => "{$curso->nombre} {$curso->division}"])
                                    : [];
                            })
                            ->hidden(fn (callable $get) => empty($get('anio_lectivo'))), // Oculta si no hay un año lectivo seleccionado
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
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMaterias::route('/'),
            'create' => Pages\CreateMateria::route('/create'),
            'edit' => Pages\EditMateria::route('/{record}/edit'),
        ];
    }
}
