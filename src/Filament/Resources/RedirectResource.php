<?php

namespace Qubiqx\QcommerceCore\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Qubiqx\QcommerceCore\Models\Redirect;
use Qubiqx\QcommerceCore\Filament\Resources\RedirectResource\Pages\EditRedirect;
use Qubiqx\QcommerceCore\Filament\Resources\RedirectResource\Pages\ListRedirects;
use Qubiqx\QcommerceCore\Filament\Resources\RedirectResource\Pages\CreateRedirect;

class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;
    protected static ?string $recordTitleAttribute = 'from';

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Redirects';
    protected static ?string $label = 'Redirect';
    protected static ?string $pluralLabel = 'Redirects';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Section::make('content')
                        ->schema(
                            array_merge([
                                Forms\Components\TextInput::make('from')
                                    ->required()
                                    ->label('Vanaf welke URL moet er een redirect komen?')
                                    ->helperText('Bijv: /dit-is-een-oude-url'),
                                Forms\Components\TextInput::make('to')
                                    ->required()
                                    ->label('Naar welke URL moet deze redirect verwijzen?')
                                    ->helperText('Bijv: /dit-is-een-nieuwe-url of https://qubiqx.com/wij-programmeren-kei-goed'),
                                Forms\Components\Select::make('sort')
                                    ->required()
                                    ->label('Type redirect')
                                    ->default('301')
                                    ->options([
                                        '301' => 'Permanente redirect',
                                        '302' => 'Tijdelijke redirect',
                                    ]),
                                Forms\Components\DatePicker::make('delete_redirect_after')
                                    ->label('Verwijder redirect na een datum')
                                    ->default(now()->addMonths(3)),
                            ])
                        ), ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('from')
                    ->url(fn ($record) => url($record->from))
                    ->openUrlInNewTab()
                    ->label('Oude URL')
                    ->searchable(),
                TextColumn::make('to')
                    ->url(fn ($record) => $record->to)
                    ->openUrlInNewTab()
                    ->label('Nieuwe URL')
                    ->searchable(),
                TextColumn::make('sort')
                    ->label('Soort redirect'),
                TextColumn::make('delete_redirect_after')
                    ->label('Delete redirect na')
                    ->getStateUsing(fn ($record) => $record->delete_redirect_after ? $record->delete_redirect_after->format('d-m-Y') : 'Niet verwijderen'),
            ])
            ->filters([
                //
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
            'index' => ListRedirects::route('/'),
            'create' => CreateRedirect::route('/create'),
            'edit' => EditRedirect::route('/{record}/edit'),
        ];
    }
}