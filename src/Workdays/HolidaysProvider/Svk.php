<?php

namespace Petaak\Workdays\HolidaysProvider;

use DateTime;
use Petaak\Workdays\Holiday;

/**
 * Description of Cze
 *
 * @author Petr Vácha <petr.vacha@ulozenka.cz>
 */
class Svk extends BaseHolidaysProvider implements IHolidaysProvider
{

    /**
     *
     * @param int $year
     * @return Holiday[]
     */
    public function getHolidaysByYear($year)
    {
        $holidays = [];

        $holidays[] = new Holiday(new DateTime($year . '-01-01'), 'Deň vzniku Slovenskej republiky');
        $holidays[] = new Holiday(new DateTime($year . '-01-06'), 'Zjavenie Pána');
        $holidays[] = new Holiday($this->getGoodFriday($year), 'Veľký piatok');
        $holidays[] = new Holiday($this->getEasterMonday($year), 'Veľkonočný pondelok');
        $holidays[] = new Holiday(new DateTime($year . '-05-01'), 'Sviatok práce');
        $holidays[] = new Holiday(new DateTime($year . '-05-08'), 'Deň víťazstva nad fašizmom');
        $holidays[] = new Holiday(new DateTime($year . '-07-05'), 'Sviatok svätého Cyrila a svätého Metoda');
        $holidays[] = new Holiday(new DateTime($year . '-08-29'), 'Výročie Slovenského národného povstania');
        $holidays[] = new Holiday(new DateTime($year . '-09-01'), 'Deň Ústavy Slovenskej republiky');
        $holidays[] = new Holiday(new DateTime($year . '-09-15'), 'Sedembolestná Panna Mária');
        $holidays[] = new Holiday(new DateTime($year . '-11-01'), 'Sviatok Všetkých svätých');
        $holidays[] = new Holiday(new DateTime($year . '-11-17'), 'Deň boja za slobodu a demokraciu');
        $holidays[] = new Holiday(new DateTime($year . '-12-24'), 'Štedrý deň');
        $holidays[] = new Holiday(new DateTime($year . '-12-25'), 'Prvý sviatok vianočný');
        $holidays[] = new Holiday(new DateTime($year . '-12-26'), 'Druhý sviatok vianočný');

        return $holidays;
    }
}
