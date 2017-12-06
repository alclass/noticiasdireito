<?php
namespace App\Models\NewsModels;
// use App\Models\NewsModels\NewsObject;
use App\Models\NewsModels\MonthObject;
use App\Models\Util\FileSystemUtil;
use App\Models\SabDirModels\SabDirCurso;
use App\Models\Util\UtilParamsForNewsApp;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NewsObject extends Model {

  public static function getLastN($n_lastones=3) {
    return self::orderBy('newsdate', 'desc')->take($n_lastones)->get();
  }

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
    return FileSystemUtil::retrieve_htmlnewspiece_from_filesystem(
      $year,
      $month,
      $day,
      $underlined_newstitle
    );
  } // ends getHtmlnewspieceAttrib()

  public function cleanAllRelatedCourses() {
    if (!empty($this->sabdircursos)) {
      $this->sabdircursos()->detach();
      //$this->save();
    }
  } // ends cleanAllRelatedCourses()

  public function addSabDirCursoByDate($cursodate) {
    $carbondate = new Carbon($cursodate);
    $carbondate->setTime(0,0,0);
    $curso = SabDirCurso::where('firstemissiondate', $carbondate)->first();
    if ($curso!=null) {
      $this->sabdircursos()->attach($curso);
      // $this->save();
      return true;
    }
    return false;
  }

  public function gen_outer_url_for_course($curso, $protocol='http') {
    return UtilParamsForNewsApp::gen_outer_url_for_course($curso, $protocol);
  } // ends gen_outer_url_for_course()

  public function gen_outer_url($protocol='http') {
    /*
       Notice that all elements in $this->routeurl_as_array will form,
       in the routing function, a url as a slash-separated string,

       ie $year/$month/$day/$underlined_newstitle

       That's why:
         $url_complement = implode('/', $this->routeurl_as_array);
       below is a safe presumption.

       To check this, one can look up the route definition
         in routes/web.php (or elsewhere if it is somewhere else)
    */
    $noticiasdireito_http_url = UtilParamsForNewsApp::get_noticiasdireito_root_url($protocol);
    $url_complement = implode('/', $this->routeurl_as_array);
    $outer_url = $noticiasdireito_http_url . '/' . $url_complement;
    return $outer_url;
  }

  public function get_noticiasdireito_root_url($protocol='http') {
    return UtilParamsForNewsApp::get_noticiasdireito_root_url($protocol);
  }

  public function get_sabdirdois_root_url($protocol='http') {
    return UtilParamsForNewsApp::get_sabdirdois_root_url($protocol);
  }

  public function instance_getLastN($n_lastones=3) {
    return self::getLastN($n_lastones);
  }

  public function get_previous_months_as_objs($n_previous_max=5) {
    $first_newspiece = self::orderBy('newsdate', 'asc')->first();
    if ($first_newspiece==null) {
      return MonthObject::make_monthobjs_as_collectof(); // use default
    }
    $firstdate = $first_newspiece->newsdate->copy();
    $today = Carbon::today();
    $n_months = $today->diffInMonths($firstdate) + 1;
    if ($n_months > $n_previous_max) {
      $n_months = $n_previous_max;
    }
    $monthobjs = MonthObject::make_monthobjs_as_collectof($n_months, $today);
    return $monthobjs;
  }

  // attribute sabdircursos means that some courses are related to news object
  public function sabdircursos() {
    return $this->belongsToMany(
      'App\Models\SabDirModels\SabDirCurso',
      'newsobject_sabdircurso',
      'newsobject_id',
      'sabdircurso_id'
    );
  }

}
