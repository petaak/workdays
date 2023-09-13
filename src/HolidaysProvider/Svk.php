<?php declare(strict_types=1);

namespace h4kuna\Workdays\HolidaysProvider;

use DateTimeImmutable;
use h4kuna\DataType\Date\Easter;

class Svk extends BaseProvider
{
	protected function holidaysInYear(int $year): array
	{
		return [
			new Holiday(new DateTimeImmutable($year . '-01-01'), 'Deň vzniku Slovenskej republiky'),
			new Holiday(new DateTimeImmutable($year . '-01-06'), 'Zjavenie Pána'),
			new Holiday(Easter::friday($year), 'Veľký piatok'),
			new Holiday(Easter::monday($year), 'Veľkonočný pondelok'),
			new Holiday(new DateTimeImmutable($year . '-05-01'), 'Sviatok práce'),
			new Holiday(new DateTimeImmutable($year . '-05-08'), 'Deň víťazstva nad fašizmom'),
			new Holiday(new DateTimeImmutable($year . '-07-05'), 'Sviatok svätého Cyrila a svätého Metoda'),
			new Holiday(new DateTimeImmutable($year . '-08-29'), 'Výročie Slovenského národného povstania'),
			new Holiday(new DateTimeImmutable($year . '-09-01'), 'Deň Ústavy Slovenskej republiky'),
			new Holiday(new DateTimeImmutable($year . '-09-15'), 'Sedembolestná Panna Mária'),
			new Holiday(new DateTimeImmutable($year . '-11-01'), 'Sviatok Všetkých svätých'),
			new Holiday(new DateTimeImmutable($year . '-11-17'), 'Deň boja za slobodu a demokraciu'),
			new Holiday(new DateTimeImmutable($year . '-12-24'), 'Štedrý deň'),
			new Holiday(new DateTimeImmutable($year . '-12-25'), 'Prvý sviatok vianočný'),
			new Holiday(new DateTimeImmutable($year . '-12-26'), 'Druhý sviatok vianočný'),
		];
	}

}
