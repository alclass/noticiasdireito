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

  public static function make_objs_as_collect($n_previous_months=1, $p_refdate=null) {
    if ($p_refdate==null) {
      $refdate = Carbon::today();
    } else {
      $refdate = $p_refdate->copy();
    }
    $months_as_collect = collect();
    for ($i=0; $i <= $n_previous_months; $i++) {
      // echo "i=$i date=$refdate->month";
      $monthcarbon = $refdate->copy();
      $monthobj = new self($monthcarbon);
      $months_as_collect->push($monthobj);
      $refdate->addMonths(-1);
      /*
        The 'if' below is necessary when
          current month has more days than previous month:
        Examples:
        1) Dec/Nov, Oct/Set, Jul/Jun, Mai/Apr
          all have 31 days to 30 days
        2) Mar/Feb
          is a 31 days to 28 (or 29) days relation
        What happens with method addMonths(-1) at the end of month for these cases:
          For the cases in 1) above, when day=31,
          addMonths(-1) result in the first day of the same month!
          (eg., if date is '2017-12-31', addMonths(-1) results in '2017-12-01',
           ie, failing to get the previous month)
        For following 'if' corrects this.
      */
      if ($refdate->month == $monthcarbon->month) {
        $refdate->addMonths(-1);
      }
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
    /*
      See which words/fields are dynamic attributes
      on the Class' docstring above
    */
    $method = 'get' . ucfirst($propertyName) . 'Attribute';
    if (method_exists($this, $method)) {
      return $this->{$method}();
    }
  }

} // ends class MonthObject
