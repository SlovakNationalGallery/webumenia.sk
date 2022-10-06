<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Authority;

class AuthorityEvent extends Component
{
   private function formatEventDates($start_date, $end_date)
    {
        if (!$start_date && !$end_date) {
            return '';
        }
        if (!$start_date) {
            return $end_date;
        }
        if (!$end_date) {
            return $start_date;
        }
        if ($start_date === $end_date) {
            return $start_date;
        }
        return $start_date . ' - ' . $end_date;
    }

    public function formatEvent($event)
    {
        $activityTranslation = Authority::formatMultiAttribute($event->event);
        $filteredActivity = in_array($activityTranslation, ['pôsobenie', 'activity'])
            ? null
            : $activityTranslation;
        $event_dates = self::formatEventDates($event->start_date, $event->end_date);

        if (!$filteredActivity && !$event_dates) {
            return '';
        }
        if (!$filteredActivity) {
            return add_brackets($event_dates);
        }
        if (!$event_dates) {
            return add_brackets($filteredActivity);
        }
        return add_brackets($filteredActivity . ': ' . $event_dates);
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.authority-event');
    }
}
