<?php

namespace Petaak\Workdays;

use DateInterval;
use DateTime;
use InvalidArgumentException;
use Petaak\Workdays\HolidaysProvider\IHolidaysProvider;

/**
 * Description of WorkdaysUtil
 *
 * @author Petr Vácha <petr.vacha@ulozenka.cz>
 */
class WorkdaysUtil
{

    /** @var IHolidaysProvider */
    private $holidaysProvider;

    public function __construct($countryCode = 'CZE')
    {
        $this->setCountry($countryCode);
    }

    /**
     *
     * @param DateTime $date
     * @param string $countryCode
     * @return bool
     */
    public function isWorkday(DateTime $date = null, $countryCode = null)
    {
        if ($date === null) {
            $date = new DateTime();
        }
        $isHoliday = $this->isHoliday($date, $countryCode);
        $isWeekend = $this->isWeekend($date, $countryCode);
        return !$isWeekend && !$isHoliday;
    }

    /**
     *
     * @param DateTime $date
     * @param string $countryCode
     * @return bool
     */
    public function isHoliday(DateTime $date = null, $countryCode = null)
    {
        if ($date === null) {
            $date = new DateTime();
        }
        return !empty($this->holidaysBetween($date, $date, $countryCode));
    }

    /**
     *
     * @param DateTime $date
     * @param type $countryCode
     * @return DateTime
     */
    public function getNextWorkday(DateTime $date = null, $countryCode = null)
    {
        return $this->addWorkdays(clone $date, 1, $countryCode);
    }

    /**
     *
     * @param DateTime $date
     * @return Holiday
     * @throws Exception
     */
    public function getNextHoliday(\DateTime $date = null, $countryCode = null)
    {
        if ($date === null) {
            $date = new DateTime();
        }
        $dateFrom = clone $date;
        $dateFrom->modify('+1 DAY');
        $dateTo = clone $date;
        $dateTo->modify('+ 1 YEAR');
        $followingHolidays = $this->holidaysBetween($dateFrom, $dateTo, $countryCode);
        if (count($followingHolidays) > 0) {
            $sorted = $this->sortHolidays($followingHolidays);
            return reset($sorted);
        } else {
            throw new Exception('No holiday in the following 1 year.');
        }
    }

    /**
     *
     * @param DateTime $date
     * @param int $numberOfWorkdays
     * @return DateTime
     */
    public function addWorkdays(\DateTime $date, $numberOfWorkdays, $countryCode = null)
    {
        $interval = new DateInterval('P1D');
        $days = 0;
        while ($days < abs($numberOfWorkdays)) {
            if ($numberOfWorkdays >= 0) {
                $date->add($interval);
            } else {
                $date->sub($interval);
            }
            if ($this->isWorkday($date, $countryCode)) {
                $days++;
            }
        }
        return $date;
    }

    /**
     *
     * @param DateTime $date
     * @param int $numberOfWorkdays
     * @return DateTime
     */
    public function subWorkdays(\DateTime $date, $numberOfWorkdays, $countryCode = null)
    {
        return $this->addWorkdays($date, -$numberOfWorkdays, $countryCode);
    }

    /**
     *
     * @param DateTime $date
     * @return bool
     */
    private function isWeekend(DateTime $date)
    {
        return $date->format('N') >= 6;
    }

    /**
     *
     * @param DateTime $dateFrom
     * @param DateTime $dateTo
     * @param string $countryCode
     * @return Holiday[]
     */
    private function holidaysBetween(DateTime $dateFrom, DateTime $dateTo, $countryCode = null)
    {
        $holidays = [];
        $dateFromYear = $dateFrom->format('Y');
        $cloneFrom = clone $dateFrom;
        $cloneFrom->setTime(0, 0, 0);
        $cloneTo = clone $dateTo;
        $cloneTo->setTime(0, 0, 0);
        $dateToYear = $dateTo->format('Y');
        for ($year = $dateFromYear; $year <= $dateToYear; $year++) {
            $holidaysInYear = $this->getHolidaysByYear($year, $countryCode);
            $holidays = array_merge($holidays, $holidaysInYear);
        }
        $holidaysBetween = array_filter($holidays, function ($holiday) use ($cloneFrom, $cloneTo) {
            $date = $holiday->getDate();
            return $date >= $cloneFrom && $date <= $cloneTo;
        });
        return $holidaysBetween;
    }

    /**
     *
     * @param int $year
     * @param string $countryCode
     * @return Holiday[]
     */
    private function getHolidaysByYear($year, $countryCode = null)
    {
        if ($countryCode === null) {
            return $this->holidaysProvider->getHolidaysByYear($year);
        } else {
            $holidaysProvider = $this->getHolidaysProviderByCountryCode($countryCode);
            return $holidaysProvider->getHolidaysByYear($year);
        }
    }

    /**
     *
     * @param Holiday[] $holidays
     * @return Holiday[]
     */
    private function sortHolidays($holidays)
    {
        usort($holidays, function ($first, $second) {
            if ($first->getDate() < $second->getDate()) {
                return -1;
            } elseif ($first->getDate() > $second->getDate()) {
                return 1;
            } else {
                return 0;
            };
        });
        return $holidays;
    }

    /**
     *
     * @param string $countryCode
     */
    private function setCountry($countryCode = null)
    {
        $this->holidaysProvider = $this->getHolidaysProviderByCountryCode($countryCode);
    }

    /**
     *
     * @param string $countryCode
     * @return IHolidaysProvider
     * @throws InvalidArgumentException
     */
    private function getHolidaysProviderByCountryCode($countryCode)
    {
        $providerNamespace = '\\Petaak\\Workdays\\HolidaysProvider\\';
        $className = $providerNamespace . ucfirst(strtolower($countryCode));
        if (class_exists($className)) {
            return new $className;
        } else {
            throw new InvalidArgumentException('HolidayProvider for coutry ' . $countryCode . ' not implemented.');
        }
    }
}
