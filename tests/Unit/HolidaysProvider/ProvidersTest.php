<?php declare(strict_types=1);

namespace h4kuna\Workdays\Tests\Unit\HolidaysProvider;

use h4kuna\DataType\Iterators\PeriodDayFactory;
use h4kuna\Workdays\HolidaysProvider\BaseProvider;
use h4kuna\Workdays\HolidaysProvider\Cze;
use h4kuna\Workdays\HolidaysProvider\Svk;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

class ProvidersTest extends TestCase
{

	/**
	 * @return array<array<mixed>>
	 */
	protected function provideSvk(): array
	{
		return [
			[2015, 15, new Svk()],
			[2016, 15, new Svk()],
			[2015, 12, new Cze()],
			[2016, 13, new Cze()],
		];
	}


	/**
	 * @dataProvider provideSvk
	 */
	public function testSvk(int $year, int $count, BaseProvider $provider): void
	{
		$period = PeriodDayFactory::createInFromInTo(new \DateTime("$year-01-01"), new \DateTime("$year-12-31"));
		$sum = [];
		foreach ($period as $date) {
			$holiday = $provider->get($date);
			if ($holiday !== null) {
				$sum[] = $holiday;
			}
		}
		Assert::count($count, $sum);
	}
}

(new ProvidersTest())->run();
