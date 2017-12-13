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
    $n_lastones = intval($n_lastones);
    if ($n_lastones<1) {
      return null;
    }
    if (\App::environment('production')) {
      // production case limits news piece up to today's date
      $today = Carbon::today();
      return self
        ::where('newsdate', '<=', $today)
        ->orderBy('newsdate', 'desc')
        ->take($n_lastones)->get();
    }
    // non-production cases fall here
    return self::orderBy('newsdate', 'desc')->take($n_lastones)->get();
  }

  public static function fetch_most_recent() {
    return NewsObject
      ::orderBy('newsdate', 'desc')
      ->first();
  }

  public static function get_last_or_create_mock() {
    $most_recent_newsobject = self::fetch_most_recent();
    if ($most_recent_newsobject!=null) {
      return $most_recent_newsobject;
    }
    $news_object = new self;
    $news_object->newsdate = Carbon::now();
    $news_object->newstitle = 'O principal atributo do Direito: ser honesto!';
    $news_object->subtitle = 'O operador do Direito tem sua honra atrelada à honestidade.';
    $news_object->description = 'O Direito é a linha de base para o que chamamos de Sociedade Democrática de Direito. O Direito preconiza: ser honesto sempre e acima de tudo!';
    return $news_object;
  }

  public static function count_total_newspieces_in_month($p_carbondate) {
    $today = Carbon::today();
    if ($p_carbondate==null) {
      $carbondate = Carbon::today();
    } else {
      $carbondate = $p_carbondate->copy();
    }
    $carbondate->day = 1;
    $carbondate_monthbefore = $carbondate->copy()->addDays(-1);
    $carbondate_monthafter  = $carbondate->copy()->addMonth(1);
    $total_newspieces_in_month = self
      ::where('newsdate', '>', $carbondate_monthbefore)
      ->where('newsdate', '<', $carbondate_monthafter)
      ->count();
    if (\App::environment('production')) {
      if ($today->year == $carbondate->year && $today->month == $carbondate->month) {
        $total_newspieces_in_month = self
          ::where('newsdate', '>', $carbondate_monthbefore)
          ->where('newsdate', '<=', $today)
          ->count();
      }
    }
    return $total_newspieces_in_month;
  }

  protected $table   = 'newsobjects';
  protected $dates   = ['newsdate'];
  protected $appends = [
    'htmlnewspiece', 'routeurl_as_array',
    'today', 'total_de_noticias',
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

  public function getTodayAttribute() {
    return Carbon::today();
  }

  public function getTotalDeNoticiasAttribute() {
    /*
      Obs.:
      1) in production, counting is up to today's date,
        if there are 'future' news pieces,
        these are not counted.
      2) in non-production, all news pieces are counted.
        This is so that we may prepare articles in advance
        without showing them in production.
    */
    if (\App::environment('production')) {
      // in production
      $today = Carbon::today();
      $total_de_noticias = self
        ::where('newsdate', '<=', $today)
        ->count();
    }
    else {
      // in non-production
      $total_de_noticias = self::count();
    }
    return $total_de_noticias;
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

  public function gen_uniqueid_for_disqus_et_al() {
    /*
        routeurl_as_array is [$year, $month2charstr, $day2charstr, $underlined_newstitle]
          E.g. [2017, '12', '08', 'uma_nova_ideia_juridica']
        The result will be:
          '2017-12-08-uma_nova_ideia_juridica'

    */
    return implode('-', $this->routeurl_as_array);
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

  public function get_previous_newsobject_or_last() {
    $previous_newsobject = NewsObject
      ::where('newsdate', '<', $this->newsdate)
      ->orderBy('newsdate', 'desc')
      ->first();
    if ($previous_newsobject!=null) {
      return $previous_newsobject;
    }
    /*
      If code flow gets here, 'this' is already the oldest post and
      the most recent, in a kind of rotation wheel scheme, is one it searches for
        obs.1) the most recent in production is bound by $today
        obs.2) the most recent in non-production is the last one by date,
                even if it's in the future (posts that are written but not released)
    */
    if (\App::environment('production')) {
      $today = Carbon::today();
      $most_recent_newsobject = NewsObject
        ::where('newsdate', '<=', $today)
        ->orderBy('newsdate', 'desc')
        ->first();
    } else {
      $most_recent_newsobject = NewsObject
        ::orderBy('newsdate', 'desc')
        ->first();
    }
    return $most_recent_newsobject;
  }

  public function get_previous_or_last_routeurl_as_array() {
    $previous_or_last = $this->get_previous_newsobject_or_last();
    if ($previous_or_last==null) {
      /*
        Well, weird as this if is, at least $this exists,
        use itself in this 'weird' logical case
        (this if will probably never occur because if DB is empty,
        $this also does not exist)
      */
      return $this->routeurl_as_array;
    }
    return $previous_or_last->routeurl_as_array;
  }

  public function get_next_newsobject_or_first() {
    /*
      If next news piece depends on whether or not
      code runs on production.
        1) if in production, next is also bound by $today
        2) if not in production, next may be in the future
          ie it may be a future-dated post that has already been written but not released
    */
    $today = Carbon::today();
    if (\App::environment('production')) {
      $next_newsobject = NewsObject
        ::where('newsdate', '>', $this->newsdate)
        ->where('newsdate', '<=', $today)
        ->orderBy('newsdate', 'asc')
        ->first();
    } else {
      // non-production env's case
      $next_newsobject = NewsObject
        ::where('newsdate', '>', $this->newsdate)
        ->orderBy('newsdate', 'asc')
        ->first();
    }
    if ($next_newsobject!=null) {
      return $next_newsobject;
    }
    $oldest_newsobject = NewsObject
      ::orderBy('newsdate', 'asc')
      ->first();
    // protect 'production' from returning a possible future-dated post
    if (\App::environment('production')) {
      if ($oldest_newsobject!=null && $oldest_newsobject->newsdate > $today) {
        return null;
      }
    }
    /*
      Notice a null may be returned from here, which means
        perhaps DB is empty.
    */
    return $oldest_newsobject;
  }


  public function get_next_or_first_routeurl_as_array() {
    $next_or_first = $this->get_next_newsobject_or_first();
    if ($next_or_first==null) {
      /*
        Well, weird as this if is, at least $this exists,
        use itself in this 'weird' logical case
        (this if will probably never occur because if DB is empty,
        $this also does not exist)
      */
      return $this->routeurl_as_array;
    }
    return $next_or_first->routeurl_as_array;
  }

  public function get_lastest_n_courses($lastest_n=3) {
    $lastest_n = intval($lastest_n);
    if ($lastest_n < 1) {
      return null;
    }
    return SabDirCurso
      ::orderBy('firstemissiondate', 'desc')
      ->take($lastest_n)->get();
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
