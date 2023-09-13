<?php declare(strict_types=1);

namespace h4kuna\Workdays\HolidaysProvider;

use DateTimeImmutable;
use h4kuna\DataType\Date\Easter;

class Cze extends BaseProvider
{
	protected function holidaysInYear(int $year): array
	{
		$holidays = [];
		$holidays[] = new Holiday(new DateTimeImmutable($year . '-01-01'), 'Den obnovy samostatného českého státu');
		if ($year >= 2016) {
			$holidays[] = new Holiday(Easter::friday($year), 'Velký pátek');
		}
		$holidays[] = new Holiday(Easter::monday($year), 'Velikonoční pondělí');
		$holidays[] = new Holiday(new DateTimeImmutable($year . '-05-01'), 'Svátek práce');
		$holidays[] = new Holiday(new DateTimeImmutable($year . '-05-08'), 'Den vítězství');
		$holidays[] = new Holiday(new DateTimeImmutable($year . '-07-05'), 'Den slovanských věrozvěstů Cyrila a Metoděje');
		$holidays[] = new Holiday(new DateTimeImmutable($year . '-07-06'), 'Den upálení mistra Jana Husa');
		$holidays[] = new Holiday(new DateTimeImmutable($year . '-09-28'), 'Den české státnosti');
		$holidays[] = new Holiday(new DateTimeImmutable($year . '-10-28'), 'Den vzniku samostatného československého státu');
		$holidays[] = new Holiday(new DateTimeImmutable($year . '-11-17'), 'Den boje za svobodu a demokracii');
		$holidays[] = new Holiday(new DateTimeImmutable($year . '-12-24'), 'Štědrý den');
		$holidays[] = new Holiday(new DateTimeImmutable($year . '-12-25'), '1. svátek vánoční');
		$holidays[] = new Holiday(new DateTimeImmutable($year . '-12-26'), '2. svátek vánoční');

		return $holidays;
	}
}
