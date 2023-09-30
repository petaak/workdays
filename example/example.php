<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use h4kuna\Workdays;

$builder = Workdays\Factory::create();

$workdaysUtil = $builder->get('cs_CZ');

$datetime = new DateTime('2016-01-04 12:46:28');

echo ($workdaysUtil->isHoliday($datetime) ? 'true' : 'false') . PHP_EOL;
// false

echo ($workdaysUtil->isWorkday($datetime) ? 'true' : 'false') . PHP_EOL;
// true

$nextCzeHoliday = $workdaysUtil->nextHoliday($datetime);
echo $nextCzeHoliday->name . PHP_EOL;
// Velký pátek
echo $nextCzeHoliday->date->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-03-25 00:00:00

echo $workdaysUtil->nextWorkday($datetime)->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-05 12:46:28

$workdaysUtil->moveWorkdays($datetime, 7);
echo $datetime->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-13 12:46:28


$workdaysUtil = $builder->get('sk_SK');
$datetime = new DateTime('2016-01-04 12:43:28');

echo ($workdaysUtil->isHoliday($datetime) ? 'true' : 'false') . PHP_EOL;
// false

echo ($workdaysUtil->isWorkday($datetime) ? 'true' : 'false') . PHP_EOL;
// true

$nextSvkHoliday = $workdaysUtil->nextHoliday($datetime);
echo $nextSvkHoliday->name . PHP_EOL;
// Zjavenie Pána
echo $nextSvkHoliday->date->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-06 00:00:00

echo $workdaysUtil->nextWorkday($datetime)->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-05 12:43:28

$workdaysUtil->moveWorkdays($datetime, 7);
echo $datetime->format('Y-m-d H:i:s') . PHP_EOL;
// 2016-01-14 12:43:28
