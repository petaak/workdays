<?php

namespace Petaak\Workdays\Tests;

use DateTime;
use Petaak\Workdays\Holiday;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

/**
 * Description of HolidayTest
 *
 * @author Petr Vácha <petr.vacha@ulozenka.cz>
 *
 * @testCase
 */
class HolidayTest extends TestCase
{

    public function __construct()
    {
        date_default_timezone_set('Europe/Prague');
    }

    /**
     *
     */
    public function testGetDate()
    {
        $date = new DateTime('2015-05-04');
        $name = 'Test holiday name';
        $holiday = new Holiday($date, $name);
        Assert::equal($date, $holiday->getDate());
    }

    /**
     *
     */
    public function testGetName()
    {
        $date = new DateTime('2015-05-04');
        $name = 'Test holiday name';
        $holiday = new Holiday($date, $name);
        Assert::equal($name, $holiday->getName());
    }
}

$test = new HolidayTest();
$test->run();
