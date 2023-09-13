<?php declare(strict_types=1);

namespace h4kuna\Workdays\Tests\Unit;

use DateTimeImmutable;
use h4kuna\Workdays\HolidaysProvider\Holiday;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class HolidayTest extends TestCase
{

	public function testGetDate(): void
	{
		$date = new DateTimeImmutable('2015-05-04');
		$name = 'Test holiday name';
		$holiday = new Holiday($date, $name);
		Assert::equal($date, $holiday->date);
		Assert::equal($name, $holiday->name);
	}

}

(new HolidayTest())->run();
