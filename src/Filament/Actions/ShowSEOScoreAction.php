<?php

namespace Dashed\DashedCore\Filament\Actions;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Dashed\DashedCore\Models\Customsetting;
use Filament\Actions\Concerns\CanCustomizeProcess;

class ShowSEOScoreAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'view-seo-score';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $seoScore = $this->record->seoScores->first();

        $this->label('Bekijk SEO score ('.($seoScore->score ?? 0).')');

        $this->modalHeading('Bekijk de SEO score');

        //        $this->modalSubmitActionLabel(__('filament-actions::delete.single.modal.actions.delete.label'));
        //
        //        $this->successNotificationTitle(__('filament-actions::delete.single.notifications.deleted.title'));
        $this->modalSubmitAction(false);
        $this->modalCancelActionLabel('Sluiten');

        if ($seoScore) {
            $this->color($seoScore->score < 70 ? 'danger' : ($seoScore->score < 90 ? 'warning' : 'success'));
        } else {
            $this->color('danger');
        }

        $this->groupedIcon('heroicon-o-chart-bar-square');

        $this->modalIcon('heroicon-o-chart-bar-square');

        $this->visible((bool) Customsetting::get('seo_check_models', null, false));

        $this->modalContent(fn ($record) => view('dashed-core::actions.show-seo-score', [
            'seoScore' => $seoScore,
            'record' => $record,
        ]));

        $this->action(function (): void {
            $result = $this->process(static fn (Model $record) => $record->delete());

            if (! $result) {
                $this->failure();

                return;
            }

            $this->success();
        });
    }
}
