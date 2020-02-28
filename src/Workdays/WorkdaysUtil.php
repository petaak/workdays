<?php

namespace Petaak\Workdays;

use DateInterval;
use DateTime;
use Exception;
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

    /**
     * @var IHolidaysProvider[]
     */
    private $holidayProviderRegistry = array();

    /** @const int Limit for the getNextHoliday function */
    const MAX_YEARS_WITH_NO_HOLIDAY = 100;

    public function __construct($countryCode = 'CZE')
    {
        $this->setCountry($countryCode);
    }

    /**
     *
     * @param string $countryCode
     */
    public function setCountry($countryCode = null)
    {
        $this->holidaysProvider = $this->getHolidaysProviderByCountryCode($countryCode);
    }

    /**
     * @param IHolidaysProvider $holidayProvider
     * @param string $countryCode
     */
    public function registerHolidaysProvider(IHolidaysProvider $holidayProvider, $countryCode)
    {
        $this->holidayProviderRegistry[$countryCode] = $holidayProvider;
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
        $isWeekend = $this->isWeekend($date);
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
        $holidaysBetween = $this->holidaysBetween($date, $date, $countryCode);
        return !empty($holidaysBetween);
    }

    /**
     *
     * @param DateTime $date
     * @param string|null $countryCode
     * @return DateTime
     */
    public function getNextWorkday(DateTime $date = null, $countryCode = null)
    {
        if ($date === null) {
            $date = new DateTime();
        }
        return $this->addWorkdays(clone $date, 1, $countryCode);
    }

    /**
     * @param DateTime|null $date
     * @param string|null $countryCode
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
        for ($i = 0; $i < self::MAX_YEARS_WITH_NO_HOLIDAY; $i++) {
            $dateTo->modify('+ 1 YEAR');
            $followingHolidays = $this->holidaysBetween($dateFrom, $dateTo, $countryCode);
            if (count($followingHolidays) > 0) {
                $sorted = $this->sortHolidays($followingHolidays);
                return reset($sorted);
            }
        }
        throw new Exception('No holiday in the following ' . self::MAX_YEARS_WITH_NO_HOLIDAY . ' years.');
    }

    /**
     * @param DateTime $date
     * @param int $numberOfWorkdays
     * @param string|null $countryCode
     * @return DateTime
     */
    public function addWorkdays(DateTime $date, $numberOfWorkdays, $countryCode = null)
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
     * @param DateTime $date
     * @param int $numberOfWorkdays
     * @param string|null $countryCode
     * @return DateTime
     */
    public function subWorkdays(DateTime $date, $numberOfWorkdays, $countryCode = null)
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
        $holidaysBetween = array_filter($holidays, function (Holiday $holiday) use ($cloneFrom, $cloneTo) {
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
        usort($holidays, function (Holiday $first,Holiday $second) {
            if ($first->getDate() < $second->getDate()) {
                return -1;
            } elseif ($first->getDate() > $second->getDate()) {
                return 1;
            } else {
                return 0;
            }
        });
        return $holidays;
    }

    /**
     *
     * @param string $countryCode
     * @return IHolidaysProvider
     * @throws InvalidArgumentException
     */
    private function getHolidaysProviderByCountryCode($countryCode)
    {
        if (array_key_exists($countryCode, $this->holidayProviderRegistry)) {
            return $this->holidayProviderRegistry[$countryCode];
        }
        $providerNamespace = '\\Petaak\\Workdays\\HolidaysProvider\\';
        $className = $providerNamespace . ucfirst(strtolower($countryCode));
        if (class_exists($className)) {
            return new $className;
        } else {
            throw new InvalidArgumentException('HolidayProvider for country ' . $countryCode . ' not implemented.');
        }
    }

    /**
     * @param DateTime $dateFrom
     * @param DateTime $dateTo
     * @param string|null $countryCode
     * @return array
     */
    public function findWorkdaysByDateInterval(
        DateTime $dateFrom,
        DateTime $dateTo,
        $countryCode = null
    ) {
        $workDays = [];
        while ($dateFrom <= $dateTo) {
            if ($this->isWorkday($dateFrom, $countryCode)) {
                $workDays[] = clone $dateFrom;
            }
            $dateFrom = $this->getNextWorkday($dateFrom, $countryCode);
        }
        return $workDays;
    }
}
