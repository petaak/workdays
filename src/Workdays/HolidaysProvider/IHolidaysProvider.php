<?php

namespace Petaak\Workdays\HolidaysProvider;

/**
 *
 * @author Petr Vácha <petr.vacha@ulozenka.cz>
 */
interface IHolidaysProvider
{

    /**
     *
     * @param int $year
     * @return Holiday[] Array of holidays in the given year
     */
    public function getHolidaysByYear($year);
}
