<?php
namespace App\Http\Controllers;
// use App\Http\Controllers\NoticiasController;
use App\Http\Controllers\Controller;
use App\Models\NewsModels\NewsObject;
use App\Models\NewsModels\MonthObject;
use App\Models\NewsModels\YearObject;
use App\Models\Util\UtilParamsForNewsApp;
use App\Models\Util\FileSystemUtil;
use Carbon\Carbon;
// use Parsedown;

class NoticiasController extends Controller {

  public static function get_n_paginate() {
    return UtilParamsForNewsApp::get_n_paginate();
    //return self::N_PAGINATE;
  }


  public function mount_newslisting_for_entrance_nonprodenv() {
    $n_paginate = self::get_n_paginate();
    $newsobjects = NewsObject
      ::orderBy('newsdate', 'desc')
      ->paginate($n_paginate);
    return view('entrance', [
      'newsobjects' => $newsobjects,
    ]);
  }

  public function mount_newslisting_for_entrance() {
    /* look up directory
      $newspiece_aarray = $this->get_last_newspiece_aarray();
      $newspiece_entries = collect();
      $newspiece_entries->push($newspiece_aarray);
    */
    if (!\App::environment('production')) {
      return $this->mount_newslisting_for_entrance_nonprodenv();
    }
    $n_paginate = self::get_n_paginate();
    $today = Carbon::today();
    $newsobjects = NewsObject
      ::where('newsdate', '<=', $today)
      ->orderBy('newsdate', 'desc')
      ->paginate($n_paginate);
    return view('entrance', [
      'newsobjects' => $newsobjects,
    ]);
  }

  private function mount_newspiece_aarray($year, $month, $day, $underlined_newstitle) {
    $newspiece_aarray = [];
    $newspiece_aarray['year']  = $year;
    $newspiece_aarray['month'] = $month;
    $newspiece_aarray['day']   = $day;
    $newspiece_aarray['underlined_newstitle'] = $underlined_newstitle;
    return $newspiece_aarray;
  }

  private function show_no_newspage_exists() {
    return view('newspage', [
      'news_htmlfrag' => '<h2>Database has no news piece at the moment.</h2>',
      'newspiece_aarray' => $newspiece_aarray,
    ]);
  }

  private function does_newspiece_exist($year, $month, $day, $underlined_newstitle) {
    $year = intval($year);
    if ($year==0) {
      return false;
    }
    $month = intval($month);
    if ($month==0) {
      return false;
    }
    $day = intval($day);
    if ($day==0) {
      return false;
    }
    $datestr = "$year-$month-$day";
    $newsdate = new Carbon($datestr);
    return NewsObject
      ::where('newsdate', $newsdate)
      ->where('underlined_newstitle', $underlined_newstitle)
      ->exists();
  }

  private function show_newspage_inner($year, $month, $day, $underlined_newstitle) {
    $datestr = "$year-$month-$day";
    $news_obj = NewsObject
      ::where('newsdate', $datestr)
      ->where('underlined_newstitle', $underlined_newstitle)
      ->first();
    // $newsobj // redirect to last one if null
    // $htmlnewspiece = retrieve_htmlnewspiece_from_filesystem($year, $month, $day, $underlined_newstitle);
    // $news_obj->htmlnewspiece
    return view('newspage', [
      'news_obj'       => $news_obj,
      // 'news_htmlfrag' => $news_htmlfrag,
    ]);
  }

  public function show_newspage($year=null, $month=null, $day=null, $underlined_newstitle=null) {
    /*
      This method does the following:
      1) if a newspiece exists with params as they are, return calling show_newspage_inner()
      2) if not, then some things are in order.

      Check if there's a newsobject with its params: $year, $month, $day
      If there isn't one, redirect to 'entrance'
      If there is one, check if it returns true from does_newspiece_exist()
        before redirecting to it
      If does_newspiece_exist() falses, redirect to 'entrance'
        if not, redirect back here, it's good to go, ie, it will not loop infinitely
    */
    if ($this->does_newspiece_exist($year, $month, $day, $underlined_newstitle)) {
      return $this->show_newspage_inner($year, $month, $day, $underlined_newstitle);
    }
    // before redirecting to entranceroute, try to see if [$year, $month, $day] exists
    // return $this->show_no_newspage_exists();
    $today = Carbon::today();
    if ($year==null) {
      $year = $today->year;
    }
    if ($month==null) {
      $month = $today->month;
    }
    if ($day==null) {
      $day = $today->day;
    }
    $datestr = "$year-$month-$day";
    $news_obj =  NewsObject
      ::where('newsdate', $datestr)
      ->first();
    if ($news_obj==null) {
      return redirect()->route('entranceroute');
    }
    // test if it exists before redirects
    $year  = $news_obj->newsdate->year;
    $month = $news_obj->newsdate->month;
    $day   = $news_obj->newsdate->day;
    $underlined_newstitle = $news_obj->underlined_newstitle;
    if ($this->does_newspiece_exist($year, $month, $day, $underlined_newstitle)) {
      // see docstring above, we're protecting against an infinite redirect
      return redirect()->route('newsobjectroute', $news_obj->routeurl_as_array);
    }
    return redirect()->route('entranceroute');
  }

  private function list_news_for_current_month_inprodenv() {

    $today = Carbon::today();
    $refdate = $today->copy();
    $refdate->day = 1;
    $previousmonthlastdaydate = $refdate->copy()->addDays(-1);
    $n_paginate = self::get_n_paginate();
    $newsobjects = NewsObject
      ::where('newsdate', '<=', $today)
      ->where('newsdate', '>', $previousmonthlastdaydate)
      ->paginate($n_paginate);
    $monthobj = new MonthObject($refdate);
    $listing_subtitle = "Artigos no mês $monthobj->monthstr de $refdate->year";
    return view('entrance', [
      'newsobjects'      => $newsobjects,
      'listing_subtitle' => $listing_subtitle,
    ]);
  }

  private function list_news_for_year_inprod($refdate) {
    $today = Carbon::today();
    if ($refdate==null) {
      $refdate = $today;
    }
    // TO-DO: test $refdate's type
    $carbondate_yearbefore = $refdate->copy();
    $carbondate_yearbefore->year  = $refdate->year-1;
    $carbondate_yearbefore->month = 12;
    $carbondate_yearbefore->day   = 31;
    $carbondate_yearafter  = $refdate->copy();
    $carbondate_yearafter->year = $refdate->year+1;
    $carbondate_yearafter->month = 1;
    $carbondate_yearafter->day   = 1;
    // Enters $cuteoff_date
    $cuteoff_date = $carbondate_yearafter;
    if ($carbondate_yearafter > $today) {
      $cuteoff_date = $today->copy()->addDays(1);
    }
    $n_paginate = self::get_n_paginate();
    $newsobjects = NewsObject
      ::where('newsdate', '>', $carbondate_yearbefore)
      ->where('newsdate', '<', $cuteoff_date)
      ->orderBy('newsdate', 'asc')
      ->paginate($n_paginate);
    $listing_subtitle = "Artigos no ano $refdate->year";
    return view('entrance', [
      'newsobjects'      => $newsobjects,
      'listing_subtitle' => $listing_subtitle,
    ]);
  }

  private function list_news_for_year_nonprod($refdate) {
    $carbondate_yearbefore = $refdate->copy();
    $carbondate_yearbefore->year  = $refdate->year-1;
    $carbondate_yearbefore->month = 12;
    $carbondate_yearbefore->day   = 31;
    $carbondate_yearafter  = $refdate->copy();
    $carbondate_yearafter->year = $refdate->year+1;
    $carbondate_yearafter->month = 1;
    $carbondate_yearafter->day   = 1;
    $n_paginate = self::get_n_paginate();
    $newsobjects = NewsObject
      ::where('newsdate', '>', $carbondate_yearbefore)
      ->where('newsdate', '<', $carbondate_yearafter)
      ->orderBy('newsdate', 'asc')
      ->paginate($n_paginate);
    $listing_subtitle = "Artigos no ano $refdate->year";
    return view('entrance', [
      'newsobjects'      => $newsobjects,
      'listing_subtitle' => $listing_subtitle,
    ]);
  } // ends list_news_for_year_general_case()

  public function list_news_for_year($year=null) {
    $year = intval($year);
    // Remember that intval(null) or intval(<non-number>) is 0
    if ($year < 1) {
      return redirect()->route('entranceroute');
    }
    $refdatestr = "$year-01-01";
    $refdate = new Carbon($refdatestr);
    $today = Carbon::today();
    if (\App::environment('production')) {
      if ($today->year < $refdate->year) {
        /*
          This case is the 'news items are in the future',
          redirect to the 'entrance' route
          which lists all past news items
        */
        return redirect()->route('entranceroute');
      }
      /*
        From here on, $refdate->year is either
        equal or less than today's date
      */
      return $this->list_news_for_year_inprod($refdate);
    }
    return $this->list_news_for_year_nonprod($refdate);
  } // ends list_news_for_year()

  public function list_news_for_month($year=null, $month=null) {
    $year  = intval($year);
    $month = intval($month);
    // Remember that intval(null) or intval(<non-number>) is 0
    if ($year < 1 || $month < 1) {
      return redirect()->route('entranceroute');
    }
    if ($month > 12) {
      $month = 12;
    }
    $refdatestr = "$year-$month-01";
    $refdate = new Carbon($refdatestr);
    $today = Carbon::today();
    if (\App::environment('production')) {
      if ($refdate->year == $today->year && $refdate->month == $today->month) {
        return $this->list_news_for_current_month_inprodenv();
      }
    }
    $nextmonthdate            = $refdate->copy()->addMonths(1);
    $previousmonthlastdaydate = $refdate->copy()->addDays(-1);
    $n_paginate = self::get_n_paginate();
    $newsobjects = NewsObject
      ::where('newsdate', '<', $nextmonthdate)
      ->where('newsdate', '>', $previousmonthlastdaydate)
      ->orderBy('newsdate', 'asc')
      ->paginate($n_paginate);
    $monthobj = new MonthObject($refdate);
    $listing_subtitle = "Artigos no mês $monthobj->monthstr de $refdate->year";
    return view('entrance', [
      'newsobjects'      => $newsobjects,
      'listing_subtitle' => $listing_subtitle,
    ]);
  } // ends list_news_for_month()

} // ends class NoticiasController extends Controller
