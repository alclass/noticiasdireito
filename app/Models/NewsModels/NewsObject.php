<?php
namespace App\Models\NewsModels;
// use App\Models\NewsModels\NewsObject;
use App\Models\Util\UtilParams;
use Illuminate\Database\Eloquent\Model;

class NewsObject extends Model {

  protected $table   = 'newsobjects';
  protected $dates   = ['newsdate'];
  protected $appends = [
    'htmlnewspiece', 'routeurl_as_array'
  ];

  public function getRouteurlAsArrayAttribute() {
    $routeurl_as_array = [];
    // [1st elem] year
    $yearstr = strval($this->newsdate->year);
    $routeurl_as_array[] = $yearstr;
    // [2nd elem] month
    $monthstr = strval($this->newsdate->month);
    if (strlen($monthstr)==1) {
      $monthstr = "0$monthstr";
    }
    $routeurl_as_array[] = $monthstr;
    // [3rd elem] day
    $daystr = strval($this->newsdate->day);
    if (strlen($daystr)==1) {
      $daystr = "0$daystr";
    }
    $routeurl_as_array[] = $daystr;
    // [4th elem] underlined_newstitle
    $routeurl_as_array[] = $this->underlined_newstitle;
    return $routeurl_as_array;
  }

  public function getHtmlnewspieceAttribute() {
    // $htmlnewspiece = retrieve_htmlnewspiece_from_filesystem($year, $month, $day, $underlined_newstitle);
    $year  = $this->newsdate->year;
    $month = $this->newsdate->month;
    $day   = $this->newsdate->day;
    $underlined_newstitle = $this->underlined_newstitle;
    return UtilParams::retrieve_htmlnewspiece_from_filesystem(
      $year,
      $month,
      $day,
      $underlined_newstitle
    );
  } // ends getHtmlnewspieceAttrib()

}
