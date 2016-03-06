<?php

namespace Petaak\Workdays\HolidaysProvider;

use DateTime;
use Petaak\Workdays\Holiday;

/**
 * Description of PoorCountryWithFewHolidays
 *
 * @author Petr Vácha <petr.vacha@ulozenka.cz>
 */
class PoorCountryWithFewHolidays extends BaseHolidaysProvider implements IHolidaysProvider
{

    /**
     *
     * @param int $year
     * @return Holiday[]
     */
    public function getHolidaysByYear($year)
    {
        $holidays = [];
        if ($year % 10 === 0) {
            $holidays[] = new Holiday(new DateTime($year . '-12-24'), 'Christmas');
        }
        return $holidays;
    }
}
