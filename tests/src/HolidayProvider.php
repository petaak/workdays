<?php

namespace Petaak\Workdays\HolidaysProvider;

use DateTime;
use Petaak\Workdays\Holiday;

class HolidayProvider extends BaseHolidaysProvider implements IHolidaysProvider
{

    /**
     * @inheritDoc
     */
    public function getHolidaysByYear($year)
    {
        return [];
    }

    /**
     * @param $year
     * @return DateTime
     */
    public function getEasterSundayTester($year)
    {
        return $this->getEasterSunday($year);
    }
}
