<?php

namespace Petaak\Workdays\Tests\HolidayProvider;

use Petaak\Workdays\HolidaysProvider\HolidayProvider;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../src/HolidayProvider.php';

class BaseHolidaysProviderTest extends TestCase
{

    public function __construct()
    {
        date_default_timezone_set('Europe/Prague');
    }

    /**
     * @dataProvider getTestEasterSundayArgs
     */
    public function testEasterSunday($year, $date)
    {
        $provider = new HolidayProvider();
        $result = $provider->getEasterSundayTester($year);
        Assert::equal($result->format('Y-m-d'), $date);
    }

    /**
     * @return array
     */
    public function getTestEasterSundayArgs()
    {
        return [
            [2014, '2014-04-20'],
            [2015, '2015-04-05'],
            [2016, '2016-03-27'],
            [2017, '2017-04-16'],
            [2018, '2018-04-01'],
            [2019, '2019-04-21'],
            [2020, '2020-04-12'],
            [2021, '2021-04-04'],
            [2022, '2022-04-17'],
            [2023, '2023-04-09'],
            [2024, '2024-03-31'],
            [2025, '2025-04-20'],
            [2026, '2026-04-05'],
            [2027, '2027-03-28'],
            [2028, '2028-04-16'],
        ];
    }

    public function testPhpEasterDate()
    {
        $provider = new HolidayProvider();
        for ($year = 1970; $year < 2037; ++$year) {
            $result = $provider->getEasterSundayTester($year);

            Assert::equal($result->format('Y-m-d'), date('Y-m-d', easter_date($year)));
        }
    }
}

$test = new BaseHolidaysProviderTest();
$test->run();
