[![Downloads this Month](https://img.shields.io/packagist/dm/h4kuna/workdays.svg)](https://packagist.org/packages/h4kuna/workdays)
[![Latest Stable Version](https://poser.pugx.org/h4kuna/workdays/v/stable?format=flat)](https://packagist.org/packages/h4kuna/workdays)
[![Coverage Status](https://coveralls.io/repos/github/h4kuna/workdays/badge.svg?branch=master)](https://coveralls.io/github/h4kuna/workdays?branch=master)
[![Total Downloads](https://poser.pugx.org/h4kuna/workdays/downloads?format=flat)](https://packagist.org/packages/h4kuna/workdays)
[![License](https://poser.pugx.org/h4kuna/workdays/license?format=flat)](https://packagist.org/packages/h4kuna/workdays)

Usage
-------------

```php
use h4kuna\Workdays;
$builder = Workdays\Factory::create();

$workdays = $builder->get('cs_CZ');

$datetime = new DateTime('2016-01-04 12:46:28');

echo ($workdays->isHoliday($datetime) ? 'true' : 'false') . PHP_EOL;
// false

echo ($workdays->isWorkday($datetime) ? 'true' : 'false') . PHP_EOL;
// true

$nextHoliday = $workdays->nextHoliday($datetime);
echo $nextHoliday->name . PHP_EOL;
// Velký pátek
echo $nextHoliday->date->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-03-25 00:00:00

echo $workdays->nextWorkday($datetime)->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-05 12:46:28

$workdays->moveWorkdays($datetime, 7);
echo $datetime->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-13 12:46:28


$workdays = $builder->get('sk_SK');
$datetime = new DateTime('2016-01-04 12:43:28');

echo ($workdays->isHoliday($datetime) ? 'true' : 'false') . PHP_EOL;
// false

echo ($workdays->isWorkday($datetime) ? 'true' : 'false') . PHP_EOL;
// true

$nextHoliday = $workdays->nextHoliday($datetime);
echo $nextHoliday->name . PHP_EOL;
// Zjavenie Pána
echo $nextHoliday->date->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-06 00:00:00

echo $workdays->nextWorkday($datetime)->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-05 12:43:28

$workdays->moveWorkdays($datetime, 7);
echo $datetime->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-14 12:43:28

```

### Custom Holiday Providers

```php
use h4kuna\Workdays;

$builder = Workdays\Factory::create();

class CustomHolidaysProvider implements Workdays\HolidaysProvider\BaseProvider
{
    protected function holidaysInYear(int $year): array {
        return [
            // fill dates        
        ];
    }
}

$builder->addProvider('myProvider', new CustomHolidaysProvider());

// initialize workdays util without country code; the correct holidays provider is not yet available
$workdays = $builder->get('myProvider');
```
