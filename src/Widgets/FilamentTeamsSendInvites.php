<?php

namespace JeffGreco13\FilamentTeams\Widgets;

use Filament\Widgets\Widget;
use Filament\Forms;
use JeffGreco13\FilamentTeams\FilamentTeams;
use JeffGreco13\FilamentTeams\Models\TeamInvite;
use Illuminate\Support\HtmlString;

class FilamentTeamsSendInvites extends Widget implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $view = "filament-teams::filament.widgets.filament-teams-send-invites";

    public $currentTeamInvites = [];
    public $email;

    public static function canView(): bool
    {
        return !is_null(auth()->user()->currentTeam) && auth()->user()->isOwnerOfCurrentTeam();
    }

    protected function refreshInvites()
    {
        $this->currentTeamInvites = auth()->user()->currentTeam ? auth()->user()->currentTeam?->invites : [];
    }
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(3)->schema([
                Forms\Components\TextInput::make("email")
                    ->placeholder("newmember@yourteam.com")
                    ->disableLabel()
                    ->email()
                    ->required()
                    ->columnSpan(2),
                Forms\Components\Placeholder::make("submitbtn")
                    ->disableLabel()
                    ->content(
                        new HtmlString(
                            '<button type="submit" class="inline-flex items-center justify-center font-medium tracking-tight rounded-lg focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 h-9 px-4 text-white shadow focus:ring-white"><span>Send</span></button>'
                        )
                    ),
            ]),
        ];
    }

    public function mount()
    {
        $this->refreshInvites();
    }

    public function sendInvite()
    {
        $this->currentTeamInvites->push(
            FilamentTeams::inviteToTeam($this->email)
        );
    }
    public function cancelInvite(TeamInvite $invite)
    {
        FilamentTeams::denyInvite($invite);
        $this->refreshInvites();
    }
}
