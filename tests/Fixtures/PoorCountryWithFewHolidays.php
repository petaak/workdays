<?php declare(strict_types=1);

namespace h4kuna\Workdays\Tests\Fixtures;

use DateTimeImmutable;
use h4kuna\Workdays\HolidaysProvider;

class PoorCountryWithFewHolidays extends HolidaysProvider\BaseProvider
{

	protected function holidaysInYear(int $year): array
	{
		return [
			new HolidaysProvider\Holiday(new DateTimeImmutable($year . '-12-24'), 'Christmas'),
			new HolidaysProvider\Holiday(new DateTimeImmutable($year . '-12-23'), 'pre Christmas', true),
			new HolidaysProvider\Holiday(new DateTimeImmutable($year . '-12-24'), 'Not save, because is vacation', true),
		];
	}
}
