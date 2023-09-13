<?php declare(strict_types=1);

namespace h4kuna\Workdays;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use h4kuna\DataType\Date\Convert;
use h4kuna\Memoize\MemoryStorage;
use h4kuna\Workdays\Exceptions\InvalidStateException;
use h4kuna\Workdays\HolidaysProvider\BaseProvider;
use h4kuna\Workdays\HolidaysProvider\Holiday;

class WorkdaysUtil
{
	use MemoryStorage;

	public function __construct(private /* readonly */ BaseProvider $holidayProvider)
	{
	}


	public function isWorkday(DateTimeInterface $date): bool
	{
		return self::isWeekend($date) === false && $this->isHoliday($date) === false;
	}


	public function isHoliday(DateTimeInterface $date): bool
	{
		return $this->holidayProvider->get($date) !== null;
	}


	public static function isWeekend(DateTimeInterface $date): bool
	{
		return $date->format('N') >= 6;
	}


	/**
	 * @return ($date is DateTime ? DateTime : DateTimeImmutable)
	 */
	public function nextWorkday(DateTime|DateTimeImmutable $date): DateTime|DateTimeImmutable
	{
		return $this->moveWorkdays($date, 1);
	}


	public function nextHoliday(DateTime|DateTimeImmutable $date): Holiday
	{
		$copyDate = Convert::toMutable($date);
		$limit = 366;
		do {
			$copyDate->modify('+1 day');
			if (--$limit === 0) {
				throw new InvalidStateException('No holiday in the following 366 days.');
			}
			$holiday = $this->holidayProvider->get($copyDate);
		} while ($holiday === null);

		return $holiday;
	}


	/**
	 * @return ($date is DateTime ? DateTime : DateTimeImmutable)
	 */
	public function moveWorkdays(
		DateTime|DateTimeImmutable $date,
		int $numberOfWorkdays,
	): DateTime|DateTimeImmutable
	{
		$interval = new DateInterval('P1D');
		$copyDate = Convert::toMutable($date);

		$action = $numberOfWorkdays >= 0
			? static fn (DateTime $date): DateTime => $copyDate->add($interval)
			: static fn (DateTime $date): DateTime => $copyDate->sub($interval);

		$countDay = abs($numberOfWorkdays);
		$days = 0;
		while ($days < $countDay) {
			$action($copyDate);

			if ($this->isWorkday($copyDate)) {
				$days++;
			}
		}

		return Convert::bySource($date, $copyDate);
	}

}
