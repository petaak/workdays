<?php declare(strict_types=1);

namespace h4kuna\Workdays\HolidaysProvider;

use DateTimeInterface;

class Holiday
{

	public function __construct(public /* readonly */ DateTimeInterface $date, public /* readonly */ string $name)
	{
	}

}
