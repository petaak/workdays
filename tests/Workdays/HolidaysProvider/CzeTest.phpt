<?php

namespace Petaak\Workdays\Tests\HolidayProvider;

use Petaak\Workdays\HolidaysProvider\Cze;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

/**
 * Description of CzeTest
 *
 * @author Petr Vácha <petr.vacha@ulozenka.cz>
 */
class CzeTest extends TestCase
{

    public function __construct()
    {
        date_default_timezone_set('Europe/Prague');
    }

    public function testGetByYear()
    {
        $provider = new Cze();
        Assert::count(12, $provider->getHolidaysByYear(2014));
        Assert::count(12, $provider->getHolidaysByYear(2015));
        Assert::count(13, $provider->getHolidaysByYear(2016));
        Assert::count(13, $provider->getHolidaysByYear(2017));
    }
}

$test = new CzeTest();
$test->run();
