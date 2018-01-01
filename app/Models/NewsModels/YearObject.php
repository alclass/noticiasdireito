<?php
namespace App\Models\NewsModels;
// use App\Models\NewsModels\YearObject;

// use App\Models\NewsModels\NewsObject;
use Carbon\Carbon;

class YearObject {

  /*
    Dynamic attributes are:
      1) routeurl  => returns <year> for route url's
  */


  public static function make_objs_as_collect(
      $n_previous_years=1,
      $p_refdate=null
    ) {
    if ($p_refdate==null) {
      $refdate = Carbon::today();
    } else {
      $refdate = $p_refdate->copy();
    }
    $years_as_collect = collect();
    for ($i=0; $i <= $n_previous_years; $i++) {
      // echo "i=$i date=$refdate->month";
      $carbondate = $refdate->copy();
      $yearobj = new self($carbondate);
      $years_as_collect->push($yearobj);
      $refdate->addYears(-1);
    }
    return $years_as_collect;
  }

  public function __construct($carbondate) {
    if ($carbondate==null) {
      $carbondate = Carbon::today();
    }
    $this->carbondate = $carbondate;
    $this->total_newspieces = NewsObject::count_total_newspieces_in_year($carbondate);
  }

  public function getRouteurlAttribute() {
    // routeurl
    return $this->carbondate->year;
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

} // ends class YearObject
