<?php

namespace Acme\Demo\HolidaysProvider;

use DateTime;
use Petaak\Workdays\Holiday;
use Petaak\Workdays\HolidaysProvider\BaseHolidaysProvider;
use Petaak\Workdays\HolidaysProvider\IHolidaysProvider;

/**
 * Description of PoorCountryWithFewHolidays
 */
class CustomHolidaysProvider extends BaseHolidaysProvider implements IHolidaysProvider
{

    /**
     * @param int $year
     * @return Holiday[]
     */
    public function getHolidaysByYear($year)
    {
        $holidays = [];
        $holidays[] = new Holiday(new DateTime($year . '-01-01'), 'Neujahr');
        $holidays[] = new Holiday(new DateTime($year . '-10-03'), 'Tag der Deutschen Einheit');
        $holidays[] = new Holiday(new DateTime($year . '-12-25'), 'Erster Weihnachtstag');
        $holidays[] = new Holiday(new DateTime($year . '-12-26'), 'Zweiter Weihnachtstag');
        return $holidays;
    }
}
