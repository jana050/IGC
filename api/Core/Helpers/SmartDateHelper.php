<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Helpers;

use Exception;

// others

/**
 * Description of Validator
 *
 * @author kms
 */
class SmartDateHelper
{

    static public function getDatesBetween($start_date, $end_date)
    {
        $dates = [];
        // Create DateTime objects for the start and end dates
        $start = new \DateTime($start_date);
        $end = new \DateTime($end_date);
        // Add one day to end date to include it in the range
        $end->modify('+1 day');
        // Create a DatePeriod object
        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);
        // Iterate through the period and add dates to the array
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d'); // You can format the date as needed
        }
        return $dates;
    }


    static public function dateFormat($_date, $format="d-m-Y")
    {    
        try{
            $_start = new \DateTime($_date);     
            return $_start->format($format);
        }catch(\Exception $ex){
            return $_date;
        }        
    }

    static function getDateDifferenceInDays($date1, $date2) {
        // Try creating DateTime objects, handle invalid dates
        try {
            $datetime1 = new \DateTime($date1);
            $datetime2 = new \DateTime($date2);
        } catch (Exception $e) {
            return 0;
        }
        // Calculate the difference
        $interval = $datetime1->diff($datetime2);
        // Return the absolute difference in days
        return $interval->days;
    }


    public static function parsePostDate($value)
    {
        $formats = [
            'Y-m-d\TH:i:s.u\Z',      // ISO 8601 format with microseconds
            'Y-m-d H:i:s',           // Standard date and time
            'Y-m-d',                 // Date only
            'd-m-Y',                 // European date format
            'm/d/Y',                 // US date format
            'Y-m-d H:i:s P',         // Date with timezone
            'Y-m-d H:i:s',           // Date with time
            'd-m-Y H:i:s',           // Date and time (European style)
            'd/m/Y',                 // Date (European style with slashes)
            'm-d-Y',                 // Date (US style with dashes)
            'Y-m-d\TH:i:sP',         // ISO 8601 with timezone offset
            'Y-m-d\TH:i:s',          // ISO 8601 without timezone
            'Y-m-d\TH:i:s.u',        // ISO 8601 with microseconds
            'm-d-Y H:i:s',           // Date and time (US style)
        ];

        foreach ($formats as $format) {
            $dateTime = \DateTime::createFromFormat($format, $value, new \DateTimeZone('UTC'));
            if ($dateTime) {
                return $dateTime->format('Y-m-d'); // Return the date in 'Y-m-d' format
            }
        }

        // Handle the specific format with GMT and timezone description
        $pattern = '/^(.*?GMT[+-]\d{4})/';
        if (preg_match($pattern, $value, $matches)) {
            // Extract the part before "(India Standard Time)"
            $cleanedDate = $matches[1];
            $timestamp = strtotime($cleanedDate);

            if ($timestamp !== false) {
                $dateTime = new \DateTime();
                $dateTime->setTimestamp($timestamp);
                return $dateTime->format('Y-m-d');
            }
        }

        // If no format matches, try strtotime for generic parsing
        $timestamp = strtotime($value);
       // echo $value . "   time stamp " . $timestamp;
        if ($timestamp !== false) {
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($timestamp);
            return $dateTime->format('Y-m-d');
        }
        //exit();

        // Return null or handle invalid date
        return $value;
    }

   static public function monthShortNameToNumber(string $shortName): string {
        $date = \DateTime::createFromFormat('M', $shortName); // 'M' matches short month names
        if ($date) {
            return $date->format('m'); // 'm' returns the two-digit month number
        }
       return 0;
    }

}
