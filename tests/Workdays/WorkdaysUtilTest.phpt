<?php

namespace Petaak\Workdays\Tests\HolidayProvider;

use DateTime;
use Petaak\Workdays\WorkdaysUtil;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

/**
 * Description of WorkdaysUtilTest
 *
 * @author Petr Vácha <petr.vacha@ulozenka.cz>
 *
 * @testCase
 */
class WorkdaysUtilTest extends TestCase
{

    public function __construct()
    {
        date_default_timezone_set('Europe/Prague');
    }

    /**
     *
     * @param string $countryCode
     * @param DateTime $date
     * @param bool $isHoliday
     *
     * @dataProvider getTestIsHolidayArgs
     */
    public function testIsHoliday($countryCode, $date, $isHoliday)
    {
        $util = new WorkdaysUtil($countryCode);
        Assert::same($isHoliday, $util->isHoliday(new DateTime($date)));
    }

    /**
     *
     * @param string $countryCode
     * @param string $dateString
     * @param bool $isWorkday
     *
     * @dataProvider getTestIsWorkdayArgs
     */
    public function testIsWorkDay($countryCode, $dateString, $isWorkday)
    {
        $util = new WorkdaysUtil($countryCode);
        Assert::same($isWorkday, $util->isWorkday(new DateTime($dateString)));
    }

    /**
     *
     * @param string $countryCode
     * @param string $dateString
     * @param string $nextWorkdayString
     *
     * @dataProvider getTestGetNextWorkdayArgs
     */
    public function testGetNextWorkday($countryCode, $dateString, $nextWorkdayString)
    {
        $util = new WorkdaysUtil($countryCode);
        $nextWorkday = $util->getNextWorkday(new DateTime($dateString));
        Assert::equal(new DateTime($nextWorkdayString), $nextWorkday);
    }

    /**
     *
     * @param string $countryCode
     * @param string $dateString
     * @param string $nextHolidayDateString
     *
     * @dataProvider getTestGetNextHolidayArgs
     */
    public function testGetNextHoliday($countryCode, $dateString, $nextHolidayDateString)
    {
        $util = new WorkdaysUtil($countryCode);
        $nextHoliday = $util->getNextHoliday(new DateTime($dateString));
        Assert::equal(new DateTime($nextHolidayDateString), $nextHoliday->getDate());
    }

    /**
     *
     */
    public function testAddWorkdays()
    {
        $util = new WorkdaysUtil('CZE');
        $date = new DateTime('2015-12-23');
        $util->addWorkdays($date, 1);
        Assert::equal(new DateTime('2015-12-28'), $date);
        $util->addWorkdays($date, 2);
        Assert::equal(new DateTime('2015-12-30'), $date);
        $util->addWorkdays($date, 3);
        Assert::equal(new DateTime('2016-01-05'), $date);
        $util->addWorkdays($date, 4);
        Assert::equal(new DateTime('2016-01-11'), $date);
    }

    /**
     *
     */
    public function testSubWorkdays()
    {
        $util = new WorkdaysUtil('CZE');
        $date = new DateTime('2015-12-25');
        $util->subWorkdays($date, 1);
        Assert::equal(new DateTime('2015-12-23'), $date);
        $util->subWorkdays($date, 2);
        Assert::equal(new DateTime('2015-12-21'), $date);
        $util->subWorkdays($date, 3);
        Assert::equal(new DateTime('2015-12-16'), $date);
        $util->subWorkdays($date, 4);
        Assert::equal(new DateTime('2015-12-10'), $date);
    }

    /**
     *
     * @return array
     */
    public function getTestGetNextHolidayArgs()
    {
        $data = [];
        $nextHolidays['CZE'] = [
            ['2015-12-25 12:45', '2015-12-26'],
            ['2015-12-28', '2016-01-01'],
            ['2013-01-27', '2013-04-01'],
            ['2016-01-01 12:35:05', '2016-03-25'],
        ];
        $nextHolidays['SVK'] = [
            ['2013-01-27', '2013-03-29'],
        ];
        foreach ($nextHolidays as $countryCode => $dates) {
            foreach ($dates as $pair) {
                $data[] = [$countryCode, $pair[0], $pair[1]];
            }
        }
        return $data;
    }

    /**
     *
     * @return array
     */
    public function getTestGetNextWorkdayArgs()
    {
        $data = [];
        $nextWorkdays['CZE'] = [
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
     *
     * @return array
     */
    public function getTestIsHolidayArgs()
    {
        $data = [];
        $holidays['CZE'] = [
            '2013-05-01 12:45:12',
            '2013-04-01 05:45',
            '2015-04-06',
            '2015-12-24',
            '2015-12-25',
            '2015-12-26',
            '2016-03-25',
            '2016-03-28',
        ];
        $notHolidays['CZE'] = [
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
     *
     * @return array
     */
    public function getTestIsWorkdayArgs()
    {
        $data = [];
        $workdays['CZE'] = [
            '2013-05-06 12:45:12',
            '2013-04-05 05:45',
            '2015-04-07',
            '2015-12-29',
            '2015-12-22',
            '2015-12-17',
            '2016-03-04',
            '2016-03-07',
        ];
        $notWorkdays['CZE'] = [
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

$test = new WorkdaysUtilTest();
$test->run();
