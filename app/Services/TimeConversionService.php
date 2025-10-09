<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidTimeZoneException;

class TimeConversionService
{
  // public function convertToUserTimezone($utcDateTime, $userTimezone)
  // {
  //     return Carbon::parse($utcDateTime)->setTimezone($userTimezone);
  // }

  // public function formatForUser($utcDateTime, $userTimezone, $format = 'Y-m-d H:i:s')
  // {
  //     return $this->convertToUserTimezone($utcDateTime, $userTimezone)->format($format);
  // }

  public function convertToUserTimezone($utcDateTime, $userTimezone)
  {
    try {
      return Carbon::parse($utcDateTime)->setTimezone($userTimezone);
    } catch (InvalidTimeZoneException $e) {
      // Log the error
      return Carbon::parse($utcDateTime);
    }
  }

  public function formatForUser($utcDateTime, $userTimezone, $format = 'Y-m-d H:i:s')
  {
    return $this->convertToUserTimezone($utcDateTime, $userTimezone)->format($format);
  }

  public function getFormattedDate($date, $timezone = null)
  {
    $timezone = $timezone ?? config('app.timezone');
    return $this->formatForUser($date, $timezone, 'd F, Y');
  }
}
