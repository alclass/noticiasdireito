<?php
namespace App\Models\NewsModels;
// use App\Models\NewsModels\MonthObj;

// use App\Models\NewsModels\NewsObject;
use Carbon\Carbon;

class MonthObject {

  /*
    Dynamic attributes are:
      1) monthstr => returns months in Portuguese
      2) routeurl_as_array  => returns [<year>, <month>] for route url's
  */

  const MONTH_PT_NAMES = [
    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro',
  ];

  public static function make_monthobjs_as_collectof($n_previous_months=1, $p_refdate=null) {
    if ($p_refdate==null) {
      $refdate = Carbon::today();
    } else {
      $refdate = $p_refdate->copy();
    }
    $months_as_collect = collect();
    for ($i=0; $i < $n_previous_months; $i++) {
      // echo "i=$i date=$refdate->month";
      $monthcarbon = $refdate->copy();
      $monthobj = new self($monthcarbon);
      $months_as_collect->push($monthobj);
      $refdate->addMonths(-1);
    }
    return $months_as_collect;
  }

  public function __construct($carbondate) {
    if ($carbondate==null) {
      $carbondate = Carbon::today();
    }
    $this->carbondate = $carbondate;
    $this->total_newspieces = NewsObject::count_total_newspieces_in_month($carbondate);
  }

  public function getMonthstrAttribute() {
    // monthstr
    $month = $this->carbondate->month;
    $monthindex = $month - 1;
    return self::MONTH_PT_NAMES[$monthindex];
  }

  public function getRouteurl_as_arrayAttribute() {
    // routeurl_as_array
    $routeurl_as_array = [];
    $routeurl_as_array[] = $this->carbondate->year;;
    $routeurl_as_array[] = $this->carbondate->month;
    return $routeurl_as_array;
  }

  public function __get($propertyName) {
    $method = 'get' . ucfirst($propertyName) . 'Attribute';
    if (method_exists($this, $method)) {
      return $this->{$method}();
    }
  }

} // ends class MonthObject
