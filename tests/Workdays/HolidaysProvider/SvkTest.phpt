<?php

namespace Petaak\Workdays\Tests\HolidayProvider;

use Petaak\Workdays\HolidaysProvider\Svk;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

/**
 * Description of SvkTest
 *
 * @author Petr Vácha <petr.vacha@ulozenka.cz>
 */
class SvkTest extends TestCase
{

    public function testGetByYear()
    {
        $provider = new Svk();
        Assert::count(15, $provider->getHolidaysByYear(2014));
        Assert::count(15, $provider->getHolidaysByYear(2015));
        Assert::count(15, $provider->getHolidaysByYear(2016));
        Assert::count(15, $provider->getHolidaysByYear(2017));
    }
}

$test = new SvkTest();
$test->run();
