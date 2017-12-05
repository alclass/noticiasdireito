<?php
namespace App\Models\NewsModels;
// use App\Models\NewsModels\NewsObject;
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
  public function get_sabdirdois_http_url() {
    return UtilParamsForNewsApp::SABDIRDOIS_HTTP_URL;
  }

  public function gen_outer_url_for_course($curso) {
    return UtilParamsForNewsApp::gen_outer_url_for_course($curso);
  } // ends gen_outer_url_for_course()

  public function instance_getLastN($n_lastones=3) {
    return self::getLastN($n_lastones);
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
