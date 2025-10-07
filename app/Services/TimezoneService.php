<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class TimezoneService
{
    /**
     * Get all available timezones.
     *
     * @return array
     */
    public function getAllTimezones()
    {
        return Cache::remember('all_timezones', 60 * 24, function () {
            return timezone_identifiers_list();
        });
    }

    /**
     * Get common timezones with their offsets.
     *
     * @return array
     */
    public function getCommonTimezones()
    {
        return Cache::remember('common_timezones', 60 * 24, function () {
            $commonTimezones = [
                'UTC', 'America/New_York', 'America/Chicago', 'America/Denver', 'America/Los_Angeles',
                'Europe/London', 'Europe/Paris', 'Asia/Tokyo', 'Asia/Dubai', 'Australia/Sydney'
            ];

            $formattedTimezones = [];
            foreach ($commonTimezones as $timezone) {
                $formattedTimezones[$timezone] = $this->formatTimezone($timezone);
            }

            return $formattedTimezones;
        });
    }

    /**
     * Format a timezone for display.
     *
     * @param string $timezone
     * @return string
     */
    public function formatTimezone($timezone)
    {
        $dateTime = Carbon::now($timezone);
        $offset = $dateTime->format('P');
        return "(GMT{$offset}) {$timezone}";
    }

    /**
     * Convert a datetime from one timezone to another.
     *
     * @param string $datetime
     * @param string $fromTimezone
     * @param string $toTimezone
     * @return \Carbon\Carbon
     */
    public function convertTimezone($datetime, $fromTimezone, $toTimezone)
    {
        return Carbon::parse($datetime, $fromTimezone)->setTimezone($toTimezone);
    }

    /**
     * Get the current time in a specific timezone.
     *
     * @param string $timezone
     * @return \Carbon\Carbon
     */
    public function getCurrentTimeInTimezone($timezone)
    {
        return Carbon::now($timezone);
    }

    /**
     * Validate if a given string is a valid timezone.
     *
     * @param string $timezone
     * @return bool
     */
    public function isValidTimezone($timezone)
    {
        return in_array($timezone, $this->getAllTimezones());
    }

    /**
     * Get the user's timezone based on their IP address.
     *
     * @param string $ipAddress
     * @return string
     */
    public function guessTimezoneFromIP($ipAddress)
    {
        // This is a placeholder. You would typically use a geolocation service here.
        // For demonstration, we're returning a default timezone.
        return 'UTC';
    }
}