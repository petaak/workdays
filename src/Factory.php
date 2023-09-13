<?php declare(strict_types=1);

namespace h4kuna\Workdays;

use h4kuna\Workdays\HolidaysProvider\Cze;
use h4kuna\Workdays\HolidaysProvider\Svk;

final class Factory
{

	public static function create(): Builder
	{
		return new Builder([
			'cs_CZ' => static fn () => new WorkdaysUtil(new Cze()),
			'sk_SK' => static fn () => new WorkdaysUtil(new Svk()),
		]);
	}

}
