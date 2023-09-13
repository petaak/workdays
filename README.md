Usage
-------------

```php
$workdaysUtil = new \h4kuna\Workdays\WorkdaysUtil('CZE');

$datetime = new DateTime('2016-01-04 12:46:28');

echo ($workdaysUtil->isHoliday($datetime) ? 'true' : 'false') . PHP_EOL;
// false

echo ($workdaysUtil->isWorkday($datetime) ? 'true' : 'false') . PHP_EOL;
// true

$nextCzeHoliday = $workdaysUtil->nextHoliday($datetime);
echo $nextCzeHoliday->getName() . PHP_EOL;
// Velký pátek
echo $nextCzeHoliday->getDate()->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-03-25 00:00:00

echo $workdaysUtil->nextWorkday($datetime)->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-05 12:46:28

$workdaysUtil->moveWorkdays($datetime, 7);
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

$nextSvkHoliday = $workdaysUtil->nextHoliday($datetime);
echo $nextSvkHoliday->getName() . PHP_EOL;
// Zjavenie Pána
echo $nextSvkHoliday->getDate()->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-06 00:00:00

echo $workdaysUtil->nextWorkday($datetime, 'SVK')->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-05 12:43:28

$workdaysUtil->moveWorkdays($datetime, 7, 'SVK');
echo $datetime->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-14 12:43:28

$workdaysUtil->subWorkdays($datetime, 4, 'SVK');
echo $datetime->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-08 12:43:28

```

### Custom Holiday Providers

```php
class CustomHolidaysProvider implements \h4kuna\Workdays\HolidaysProvider
{
    // ...
}

// initialize workdays util without country code; the correct holidays provider is not yet available
$workdaysUtil = new \h4kuna\Workdays\WorkdaysUtil();
$workdaysUtil->registerHolidaysProvider(new CustomHolidaysProvider(), 'ZZ');
// set the default country once the holidays provider is registered
$workdaysUtil->setCountry('ZZ');

```
