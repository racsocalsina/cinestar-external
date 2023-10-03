<?php

namespace App\Traits;

use Carbon\Carbon;

/**
 * Trait ChangeDate
 *
 * @package App\Traits
 */
trait ChangeDate
{
    /**
     * @param        $date
     * @param string $timezone
     * @return string
     */
    protected function convertToDateTimeUtc($date, $timezone = 'America/Lima'): string
    {
        $response = '';
        if (!empty($date)) {
            $date = str_replace('/', '-', $date);
            $date = Carbon::parse($date)->format('Y/m/d H:i:s');

            $dateFormat = Carbon::createFromFormat('Y/m/d H:i:s', $date, $timezone);
            $dateFormat->setTimezone('UTC');
            $response = Carbon::parse($dateFormat)->format('Y-m-d H:i:s');
        }

        return $response;
    }

    /**
     * @param        $date
     * @param string $timezone
     * @return string
     */
    protected function convertToDateUtc($date, $timezone = 'America/Lima'): string
    {
        $response = '';
        if (!empty($date)) {
            $date = str_replace('/', '-', $date);
            $date = Carbon::parse($date)->format('Y/m/d');

            $dateFormat = Carbon::createFromFormat('Y/m/d', $date, $timezone);
            $dateFormat->setTimezone('UTC');
            $response = Carbon::parse($dateFormat)->format('Y-m-d');
        }

        return $response;
    }

    /**
     * @param        $date
* @param string $format
* @param string $timezone
* @return string
*/
    protected function convertUtcToDateTimeZone($date, $format = 'Y/m/d', $timezone = 'America/Lima'): string
    {
        $response = '';
        if (!empty($date)) {
            $date = str_replace('/', '-', $date);
            $date = Carbon::parse($date)->format('Y/m/d');

            $dateFormat = Carbon::createFromFormat('Y/m/d', $date, 'UTC');
            $dateFormat->setTimezone($timezone);
            $response = Carbon::parse($dateFormat)->format($format);
        }

        return $response;
    }

    /**
     * @param        $date
     * @param string $format
     * @param string $timezone
     * @return string
     */
    protected function convertUtcToDateTimeTimeZone($date, $format = 'Y/m/d H:i:s', $timezone = 'America/Lima'): string
    {
        $response = '';
        if (!empty($date)) {
            $date = str_replace('/', '-', $date);
            $date = Carbon::parse($date)->format('Y/m/d H:i:s');

            $dateFormat = Carbon::createFromFormat('Y/m/d H:i:s', $date, 'UTC');
            $dateFormat->setTimezone($timezone);
            $response = Carbon::parse($dateFormat)->format($format);
        }

        return $response;
    }
}