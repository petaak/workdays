<?php

namespace Petaak\Workdays\HolidaysProvider;

use DateTime;

/**
 * Description of BaseHolidaysProvider
 *
 * @author Petr Vácha <petr.vacha@ulozenka.cz>
 */
class BaseHolidaysProvider
{

    /**
     *
     * @param int $year
     * @return DateTime
     */
    protected function getEasterSunday($year)
    {
        return new DateTime($year . '-03-21 + ' . (easter_days($year)) . ' DAYS');
    }

    /**
     *
     * @param int $year
     * @return DateTime
     */
    protected function getEasterMonday($year)
    {
        return $this->getEasterSunday($year)->modify('+1 DAY');
    }

    /**
     *
     * @param int $year
     * @return DateTime
     */
    protected function getGoodFriday($year)
    {
        return $this->getEasterSunday($year)->modify('-2 DAYS');
    }
}
