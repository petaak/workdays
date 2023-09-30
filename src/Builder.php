<?php declare(strict_types=1);

namespace h4kuna\Workdays;

use h4kuna\DataType\Collection\LazyBuilder;
use h4kuna\Workdays\HolidaysProvider\BaseProvider;

/**
 * @extends LazyBuilder<Workdays>
 */
final class Builder extends LazyBuilder
{

    public function addProvider(string $name, BaseProvider $provider): void
    {
        $this->add($name, new Workdays($provider));
    }

}
