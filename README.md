Usage
-------------

```php
$workdaysUtil = new Petaak\Workdays\WorkdaysUtil('CZE');

$datetime = new DateTime('2016-01-04 12:46:28');

echo ($workdaysUtil->isHoliday($datetime) ? 'true' : 'false') . PHP_EOL;
// false

echo ($workdaysUtil->isWorkday($datetime) ? 'true' : 'false') . PHP_EOL;
// true

$nextCzeHoliday = $workdaysUtil->getNextHoliday($datetime);
echo $nextCzeHoliday->getName() . PHP_EOL;
// Velký pátek
echo $nextCzeHoliday->getDate()->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-03-25 00:00:00

echo $workdaysUtil->getNextWorkday($datetime)->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-05 12:46:28

$workdaysUtil->addWorkdays($datetime, 7);
echo $datetime->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-13 12:46:28

$workdaysUtil->subWorkdays($datetime, 4);
echo $datetime->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-07 12:46:28




$datetime = new DateTime('2016-01-04 12:43:28');

echo ($workdaysUtil->isHoliday($datetime, 'SVK') ? 'true' : 'false') . PHP_EOL;
// false

echo ($workdaysUtil->isWorkday($datetime, 'SVK') ? 'true' : 'false') . PHP_EOL;
// true

$nextSvkHoliday = $workdaysUtil->getNextHoliday($datetime, 'SVK');
echo $nextSvkHoliday->getName() . PHP_EOL;
// Zjavenie Pána
echo $nextSvkHoliday->getDate()->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-06 00:00:00

echo $workdaysUtil->getNextWorkday($datetime, 'SVK')->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-05 12:43:28

$workdaysUtil->addWorkdays($datetime, 7, 'SVK');
echo $datetime->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-14 12:43:28

$workdaysUtil->subWorkdays($datetime, 4, 'SVK');
echo $datetime->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-08 12:43:28

$dateFrom = new DateTime('2019-12-20');
$dateTo = new DateTime('2020-01-10');
$workdaysUtil->findWorkdaysByDateInterval($dateFrom, $dateTo, Petaak\Workdays\HolidaysProvider\Cze::PROVIDER_COUNTRY_CODE);
// Array of DateTime
// [2019-12-20, 2019-12-23, 2019-12-27, 2019-12-30, 2019-12-31, 2020-01-02, 2020-01-03, 2020-01-06, 2020-01-07, 2020-01-08, 2020-01-09, 2020-01-10] 

```

### Custom Holiday Providers
```php
class CustomHolidaysProvider implements Petaak\Workdays\HolidaysProvider\IHolidaysProvider
{
    // ...
}

// initialize workdays util without country code; the correct holidays provider is not yet available
$workdaysUtil = new Petaak\Workdays\WorkdaysUtil();
$workdaysUtil->registerHolidaysProvider(new CustomHolidaysProvider(), 'ZZ');
// set the default country once the holidays provider is registered
$workdaysUtil->setCountry('ZZ');

```