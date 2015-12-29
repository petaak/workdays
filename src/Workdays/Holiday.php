<?php

namespace Petaak\Workdays;

use DateTime;

/**
 * Description of Holiday
 *
 * @author Petr Vácha <petr.vacha@ulozenka.cz>
 */
class Holiday
{

    private $date;
    private $name;

    /**
     *
     * @param DateTime $date
     * @param string $name
     */
    public function __construct(DateTime $date, $name)
    {
        $this->date = $date;
        $this->name = $name;
    }

    /**
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
