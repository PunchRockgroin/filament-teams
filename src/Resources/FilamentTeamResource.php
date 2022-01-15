<?php

namespace JeffGreco13\FilamentTeams\Resources;

use JeffGreco13\FilamentTeams\Resources\FilamentTeamResource\Pages;
use JeffGreco13\FilamentTeams\Resources\FilamentTeamResource\RelationManagers;
use JeffGreco13\FilamentTeams\Models\FilamentTeam;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class FilamentTeamResource extends Resource
{
    //protected static ?string $model = FilamentTeam::class;
    public static function getModel(): string
    {
        return config("filament-teams.team_model");
    }

    protected static function getNavigationIcon(): string
    {
        return config("filament-teams.team_navigation_icon");
    }

    protected static function getNavigationGroup(): ?string
    {
        return config("filament-teams.team_navigation_group");
    }

    protected static function getNavigationLabel(): string
    {
        return config("filament-teams.team_navigation_label");
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make("name")
                ->label("Team name")
                ->required(),
            Forms\Components\BelongsToSelect::make("owner_id")
                ->searchable()
                ->relationship("owner", "name"),
            Forms\Components\BelongsToManyMultiSelect::make("users")
                ->label("Team users")
                ->helperText(
                    "No invitation emails will be sent by updating this value."
                )
                ->relationship("users", "name"),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name"),
                Tables\Columns\BadgeColumn::make("owner_id")
                    ->label("Owner")
                    ->colors(["success"])
                    ->getStateUsing(
                        fn($record) => $record->owner?->name
                    ),
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
            "index" => Pages\ListFilamentTeams::route("/"),
            "create" => Pages\CreateFilamentTeam::route("/create"),
            "edit" => Pages\EditFilamentTeam::route("/{record}/edit"),
        ];
    }
}
