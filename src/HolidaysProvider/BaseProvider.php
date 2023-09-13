<?php declare(strict_types=1);

namespace h4kuna\Workdays\HolidaysProvider;

use DateTimeInterface;

abstract class BaseProvider
{

	/**
	 * @var array<int, array<string, Holiday>>
	 */
	private array $holidays = [];


	public function get(DateTimeInterface $date): ?Holiday
	{
		['year' => $year, 'day' => $day] = self::keys($date);
		$this->addToCache($year);

		return $this->holidays[$year][$day] ?? null;
	}


	/**
	 * @return array<Holiday>
	 */
	abstract protected function holidaysInYear(int $year): array;


	private function addToCache(int $year): void
	{
		if (isset($this->holidays[$year])) {
			return;
		}
		$holidays = $this->holidaysInYear($year);
		self::sortHolidays($holidays);

		foreach ($holidays as $holiday) {
			['year' => $year, 'day' => $day] = self::keys($holiday->date);
			$this->holidays[$year][$day] = $holiday;
		}
	}


	/**
	 * @param array<Holiday> $holidays
	 */
	private static function sortHolidays(array &$holidays): void
	{
		usort($holidays, function (Holiday $first, Holiday $second) {
			return $first->date <=> $second->date;
		});
	}


	/**
	 * @return array{year: int, day: string}
	 */
	private static function keys(DateTimeInterface $dateTime): array
	{
		return ['year' => (int) $dateTime->format('Y'), 'day' => $dateTime->format('m-d')];
	}

}
