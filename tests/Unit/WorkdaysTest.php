<?php declare(strict_types=1);

namespace h4kuna\Workdays\Tests\Unit;

use DateTime;
use DateTimeImmutable;
use h4kuna;
use h4kuna\Workdays\Factory;
use h4kuna\Workdays\Workdays;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class WorkdaysTest extends TestCase
{

	/**
	 * @throws h4kuna\DataType\Exceptions\InvalidStateException
	 */
	public function testUnknownSourceThrowsException(): void
	{
		Factory::create()->get('Foo');
	}


	/**
	 * @dataProvider provideIsHolidayArgs
	 */
	public function testIsHoliday(string $countryCode, string $date, bool $isHoliday): void
	{
		$util = Factory::create()->get($countryCode);
		Assert::same($isHoliday, $util->isHoliday(new DateTime($date)));
	}


	/**
	 * @dataProvider provideIsWorkdayArgs
	 */
	public function testIsWorkDay(string $countryCode, string $dateString, bool $isWorkday): void
	{
		$util = Factory::create()->get($countryCode);
		Assert::same($isWorkday, $util->isWorkday(new DateTime($dateString)));
	}


	/**
	 * @dataProvider provideGetNextWorkdayArgs
	 */
	public function testGetNextWorkday(string $countryCode, string $dateString, string $nextWorkdayString): void
	{
		$util = Factory::create()->get($countryCode);
		$nextWorkday = $util->nextWorkday(new DateTime($dateString));
		Assert::equal(new DateTime($nextWorkdayString), $nextWorkday);
	}


	/**
	 * @dataProvider provideGetNextHolidayArgs
	 */
	public function testGetNextHoliday(string $countryCode, string $dateString, string $nextHolidayDateString): void
	{
		$builder = Factory::create();
		$builder->add('PoorCountryWithFewHolidays', new Workdays(new h4kuna\Workdays\Tests\Fixtures\PoorCountryWithFewHolidays()));
		$util = $builder->get($countryCode);

		$nextHoliday = $util->nextHoliday(new DateTime($dateString));
		Assert::equal(new DateTimeImmutable($nextHolidayDateString), $nextHoliday->date);
	}


	/**
	 * @return array<mixed>
	 */
	protected function provideVacation(): array
	{
		return [
			['2023-12-22', false, false, new DateTimeImmutable('2023-12-24')],
			['2023-12-23', false, true, new DateTimeImmutable('2023-12-24')],
			['2023-12-24', true, false, new DateTimeImmutable('2024-12-24')],
		];
	}


	/**
	 * @dataProvider provideVacation
	 */
	public function testVacation(
		string $dateString,
		bool $isHoliday,
		bool $isVacation,
		DateTimeImmutable $nextHoliday
	): void
	{
		$builder = Factory::create();
		$builder->add('test', new Workdays(new h4kuna\Workdays\Tests\Fixtures\PoorCountryWithFewHolidays()));
		$util = $builder->get('test');

		$date = new DateTimeImmutable($dateString);
		Assert::same($isHoliday, $util->isHoliday($date));
		Assert::same($isVacation, $util->isVacation($date));
		Assert::equal($nextHoliday, $util->nextHoliday($date)->date);
	}


	public function testAddWorkdays(): void
	{
		$util = new Workdays(new h4kuna\Workdays\HolidaysProvider\Cze());
		$date = new DateTime('2015-12-23');
		$date = $util->moveWorkdays($date, 1);
		Assert::equal(new DateTime('2015-12-28'), $date);
		$date = $util->moveWorkdays($date, 2);
		Assert::equal(new DateTime('2015-12-30'), $date);
		$date = $util->moveWorkdays($date, 3);
		Assert::equal(new DateTime('2016-01-05'), $date);
		$date = $util->moveWorkdays($date, 4);
		Assert::equal(new DateTime('2016-01-11'), $date);
	}


	public function testChooseCorrectProvider(): void
	{
		$util = new Workdays(new h4kuna\Workdays\HolidaysProvider\Svk());
		$date = new DateTime('2016-09-28');
		Assert::false($util->isHoliday($date));
		Assert::true($util->isWorkday($date));
	}


	public function testEmptySourceThrowsException(): void
	{
		Assert::exception(function () {
			$util = new Workdays(new h4kuna\Workdays\Tests\Fixtures\PoorCountryWithNoHolidays());
			$util->nextHoliday(new DateTime());
		}, h4kuna\Workdays\Exceptions\InvalidStateException::class, 'For year "2023" there are no holidays.');
	}


	public function testDoesNotUseYearVariableThrowsException(): void
	{
		Assert::exception(function () {
			$util = new Workdays(new class extends h4kuna\Workdays\HolidaysProvider\BaseProvider {
				protected function holidaysInYear(int $year): array
				{
					return [
						new h4kuna\Workdays\HolidaysProvider\Holiday(new DateTimeImmutable('2013-12-12'), 'Bad date'),
					];
				}

			});
			$util->nextHoliday(new DateTime());
		}, h4kuna\Workdays\Exceptions\InvalidStateException::class, sprintf('You define bad year "2013-12-12" for require year "%s".', date('Y')));
	}


	/**
	 * @return array<mixed>
	 */
	protected function provideGetNextHolidayArgs(): array
	{
		$nextHolidays = $data = [];
		$nextHolidays['cs_CZ'] = [
			['2015-12-25 12:45', '2015-12-26'],
			['2015-12-28', '2016-01-01'],
			['2013-01-27', '2013-04-01'],
			['2016-01-01 12:35:05', '2016-03-25'],
		];
		$nextHolidays['sk_SK'] = [
			['2013-01-27', '2013-03-29'],
		];
		$nextHolidays['PoorCountryWithFewHolidays'] = [
			['2013-12-24', '2014-12-24'],
			['2021-01-27', '2021-12-24'],
		];
		foreach ($nextHolidays as $countryCode => $dates) {
			foreach ($dates as $pair) {
				$data[] = [$countryCode, $pair[0], $pair[1]];
			}
		}
		return $data;
	}


	/**
	 * @return array<mixed>
	 */
	protected function provideGetNextWorkdayArgs(): array
	{
		$nextWorkdays = $data = [];
		$nextWorkdays['cs_CZ'] = [
			['2015-12-23', '2015-12-28'],
			['2015-12-24', '2015-12-28'],
			['2015-12-27', '2015-12-28'],
			['2015-12-28', '2015-12-29'],
		];
		foreach ($nextWorkdays as $countryCode => $dates) {
			foreach ($dates as $pair) {
				$data[] = [$countryCode, $pair[0], $pair[1]];
			}
		}
		return $data;
	}


	/**
	 * @return array<mixed>
	 */
	protected function provideIsHolidayArgs(): array
	{
		$notHolidays = $holidays = $data = [];
		$holidays['cs_CZ'] = [
			'2013-05-01 12:45:12',
			'2013-04-01 05:45',
			'2015-04-06',
			'2015-12-24',
			'2015-12-25',
			'2015-12-26',
			'2016-03-25',
			'2016-03-28',
		];
		$notHolidays['cs_CZ'] = [
			'2013-04-05 23:45:01',
			'2013-04-29 17:25',
			'2015-12-27',
			'2016-11-25',
			'2015-01-26',
		];
		foreach ($holidays as $countryCode => $days) {
			foreach ($days as $day) {
				$data[] = [$countryCode, $day, true];
			}
		}

		foreach ($notHolidays as $countryCode => $days) {
			foreach ($days as $day) {
				$data[] = [$countryCode, $day, false];
			}
		}
		return $data;
	}


	/**
	 * @return array<mixed>
	 */
	protected function provideIsWorkdayArgs(): array
	{
		$workdays = $notWorkdays = $data = [];
		$workdays['cs_CZ'] = [
			'2013-05-06 12:45:12',
			'2013-04-05 05:45',
			'2015-04-07',
			'2015-12-29',
			'2015-12-22',
			'2015-12-17',
			'2016-03-04',
			'2016-03-07',
		];
		$notWorkdays['cs_CZ'] = [
			'2013-04-01 05:45',
			'2013-04-06 23:45:01',
			'2013-04-28 17:25',
			'2013-05-01 12:45:12',
			'2015-01-24',
			'2015-04-06',
			'2015-12-24',
			'2015-12-25',
			'2015-12-26',
			'2015-12-27',
			'2016-01-02',
			'2016-03-25',
			'2016-03-28',
			'2016-04-02',
			'2016-04-03',
			'2016-11-17',
			'2016-11-26',
			'2016-12-24',
		];
		foreach ($workdays as $countryCode => $days) {
			foreach ($days as $day) {
				$data[] = [$countryCode, $day, true];
			}
		}
		foreach ($notWorkdays as $countryCode => $days) {
			foreach ($days as $day) {
				$data[] = [$countryCode, $day, false];
			}
		}
		return $data;
	}

}

(new WorkdaysTest())->run();
