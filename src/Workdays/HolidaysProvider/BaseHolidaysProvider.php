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
     * Based on https://github.com/steinger/easter-date
     * @param int $year
     * @return DateTime
     */
    protected function getEasterSunday($year)
    {
        $J = date("Y", mktime(0, 0, 0, 1, 1, $year));
        $K = floor($J / 100);
        $M = 15 + floor((3 * $K + 3) / 4) - floor((8 * $K + 13) / 25);
        $S = 2 - floor((3 * $K + 3) / 4);
        $A = $J % 19;
        $D = (19 * $A + $M) % 30;
        $R = floor($D / 29) + (floor($D / 28) - floor($D / 29)) * floor($A / 11);
        $OG = 21 + $D - $R; // March date of Easter full moon (= 14. days of the first month in the moon calendar, called Nisanu)
        $SZ = 7 - (($J + floor($J / 4) + $S) % 7); // Date first Sunday of March
        $OE = 7 - (($OG - $SZ) % 7);
        $OS = $OG + $OE;
        $easter = mktime(0, 0, 0, 3, $OS, $J);
        $date = new DateTime();
        $date->setTimestamp($easter);
        return $date;
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
