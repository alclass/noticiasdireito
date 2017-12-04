<?php
namespace App\Models\Util;
// use App\Models\Util\DateUtil;

use Carbon\Carbon;

class DateUtil {


  public static function get_last_day_of_previous_year_as_date($year) {
    $year     = intval($year);
    $date_str = '' . $year-1 . '-' . '12' . '-' . '31';
    return new Carbon($date_str);
  }

  public static function get_first_day_of_next_year_as_date($year) {
    $year     = intval($year);
    $date_str = '' . $year+1 . '-' . '1' . '-' . '1';
    return new Carbon($date_str);
  }

  public static function calc_history_timespan_in_ys_ms_ds($startdate, $enddate) {

    /*
      This method calculates the time span between TWO dates in years, months and days.
      The return value is an associate array has the following keys:
        'n_years_in_between'  => v1,
        'n_months_in_between' => v2,
        'n_days_in_between'   => v3,

      If one of the dates is null, return is null.
      If dates are same, return is a "triple" 0 (zero), each with its key.

    */

    if ($startdate == null || $enddate == null) {
      return null;
    }
    if ($startdate == $enddate) {
      $history_timespan_in_ys_ms_ds = [
        'n_years_in_between'  => 0,
        'n_months_in_between' => 0,
        'n_days_in_between'   => 0,
      ];
      return $time_span_ys_ms_ds_aarray;
    }
    $pointer_date = $startdate->copy();
    $n_years_in_between  = $enddate->diffInYears($pointer_date);
    if ($n_years_in_between > 0) {
      $pointer_date->addYears($n_years_in_between);
    }
    $n_months_in_between = $enddate->diffInMonths($pointer_date);
    if ($n_months_in_between > 0) {
      $pointer_date->addMonths($n_months_in_between);
    }
    $n_days_in_between = $enddate->diffInDays($pointer_date);
    $history_timespan_in_ys_ms_ds = [
      'n_years_in_between'  => $n_years_in_between,
      'n_months_in_between' => $n_months_in_between,
      'n_days_in_between'   => $n_days_in_between,
    ];
    return $history_timespan_in_ys_ms_ds;
  } // ends static calc_history_timespan_in_ys_ms_ds()

} // ends class DateUtil
