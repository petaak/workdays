<?php declare(strict_types=1);

namespace h4kuna\Workdays\HolidaysProvider;

use DateTimeInterface;
use h4kuna\Workdays\Exceptions\InvalidStateException;

abstract class BaseProvider
{

	/**
	 * @var array<int, array<string, Holiday>>
	 */
	private array $holidaysCache = [];


	public function get(DateTimeInterface $date): ?Holiday
	{
		['year' => $year, 'day' => $day] = self::keys($date);
		$this->addToCache($year);

		return $this->holidaysCache[$year][$day] ?? null;
	}


	/**
	 * @return array<Holiday>
	 */
	abstract protected function holidaysInYear(int $year): array;


	private function addToCache(int $year): void
	{
		if (isset($this->holidaysCache[$year])) {
			return;
		}
		$holidays = $this->holidaysInYear($year);
		if ($holidays === []) {
			throw new InvalidStateException(sprintf('For year "%s" there are no holidays.', $year));
		}
		self::sortHolidays($holidays);

		foreach ($holidays as $holiday) {
			['year' => $yearHoliday, 'day' => $day] = self::keys($holiday->date);
			if ($yearHoliday !== $year) {
				throw new InvalidStateException(sprintf('You define bad year "%s-%s" for require year "%s".', $yearHoliday, $day, $year));
			}

			if (isset($this->holidaysCache[$year][$day]) === false || $this->holidaysCache[$year][$day]->vacation === true) {
				$this->holidaysCache[$year][$day] = $holiday;
			}
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
