<?php

namespace Petaak\Workdays\HolidaysProvider;

use Petaak\Workdays\Holiday;

/**
 * Description of PoorCountryWithNoHolidays
 *
 * @author Petr Vácha <petr.vacha@ulozenka.cz>
 */
class PoorCountryWithNoHolidays extends BaseHolidaysProvider implements IHolidaysProvider
{

    /**
     *
     * @param int $year
     * @return Holiday[]
     */
    public function getHolidaysByYear($year)
    {
        return [];
    }
}
