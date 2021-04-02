<?php

namespace Petaak\Workdays\HolidaysProvider;

use DateTime;
use Petaak\Workdays\Holiday;

/**
 * Description of Cze
 *
 * @author Petr Vácha <petr.vacha@ulozenka.cz>
 */
class Cze extends BaseHolidaysProvider implements IHolidaysProvider
{

    const PROVIDER_COUNTRY_CODE = 'CZE';

    /**
     *
     * @param int $year
     * @return Holiday[]
     */
    public function getHolidaysByYear($year)
    {
        $holidays = [];

        $holidays[] = new Holiday(new DateTime($year . '-01-01'), 'Den obnovy samostatného českého státu');
        if ($year >= 2016) {
            $holidays[] = new Holiday($this->getGoodFriday($year), 'Velký pátek');
        }
        $holidays[] = new Holiday(new DateTime($year . '-05-01'), 'Svátek práce');
        $holidays[] = new Holiday(new DateTime($year . '-05-08'), 'Den vítězství');
        $holidays[] = new Holiday(new DateTime($year . '-07-05'), 'Den slovanských věrozvěstů Cyrila a Metoděje');
        $holidays[] = new Holiday(new DateTime($year . '-07-06'), 'Den upálení mistra Jana Husa');
        $holidays[] = new Holiday(new DateTime($year . '-09-28'), 'Den české státnosti');
        $holidays[] = new Holiday(new DateTime($year . '-10-28'), 'Den vzniku samostatného československého státu');
        $holidays[] = new Holiday(new DateTime($year . '-11-17'), 'Den boje za svobodu a demokracii');
        $holidays[] = new Holiday(new DateTime($year . '-12-24'), 'Štědrý den');
        $holidays[] = new Holiday(new DateTime($year . '-12-25'), '1. svátek vánoční');
        $holidays[] = new Holiday(new DateTime($year . '-12-26'), '2. svátek vánoční');
        $holidays[] = new Holiday($this->getEasterMonday($year), 'Velikonoční pondělí');

        return $holidays;
    }
}
