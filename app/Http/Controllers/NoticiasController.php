<?php
namespace App\Http\Controllers;
// use App\Http\Controllers\NoticiasController;
use App\Http\Controllers\Controller;
use App\Models\NewsModels\NewsObject;
use App\Models\NewsModels\MonthObject;
use App\Models\Util\FileSystemUtil;
use Carbon\Carbon;
// use Parsedown;

class NoticiasController extends Controller {

  const N_PAGINATE = 7;

  public static function get_n_paginate() {
    return self::N_PAGINATE;
  }

  public function mount_newslisting_for_entrance() {
    /* look up directory
      $newspiece_aarray = $this->get_last_newspiece_aarray();
      $newspiece_entries = collect();
      $newspiece_entries->push($newspiece_aarray);
    */
    $n_paginate = self::get_n_paginate();
    $newsobjects = NewsObject
      ::orderBy('newsdate', 'desc')
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

  public function does_newspiece_exist($year, $month, $day, $underlined_newstitle) {
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
    // $dateplusunderlinedtitle
    if (!$this->does_newspiece_exist($year, $month, $day, $underlined_newstitle)) {
      $newspiece_aarray = $this->get_last_newspiece_aarray();
      if ($newspiece_aarray==null) {
        return $this->show_no_newspage_exists();
      }
      $year  = $newspiece_aarray['year'];
      $month = $newspiece_aarray['month'];
      $day   = $newspiece_aarray['day'];
      $underlined_newstitle = $newspiece_aarray['underlined_newstitle'];
    }
    return $this->show_newspage_inner($year, $month, $day, $underlined_newstitle);
  }

  public function list_news_for_month($year=null, $month=null) {
    $n_paginate = self::get_n_paginate();
    if ($year==null || $month==null) {
      //$refdate = Carbon::today();
      $newsobjects = self::paginate($n_paginate);
      return redirect()->route('entranceroute')->with(['newsobjects'=>$newsobjects]);
    }
    $refdatestr = "$year-$month-01";
    $refdate = new Carbon($refdatestr);
    $nextmonthdate = $refdate->copy()->addMonths(1);
    $previousmonthlastdaydate = $refdate->copy()->addDays(-1);
    $newsobjects = NewsObject
      ::where('newsdate', '<', $nextmonthdate)
      ->where('newsdate', '>', $previousmonthlastdaydate)
      ->paginate($n_paginate);
    /*
    if (count($newsobjects)==0) {
      $newsobjects = NewsObject::paginate($n_paginate);
      return redirect()->route('entranceroute')->with(['newsobjects'=>$newsobjects]);
    }
    */
     $monthobj = new MonthObject($refdate);
     $listing_subtitle = "Artigos no mês $monthobj->monthstr de $refdate->year";
    return view('entrance', [
      'newsobjects' => $newsobjects,
      'listing_subtitle' => $listing_subtitle,
    ]);
  } // ends list_news_for_month()

} // ends class NoticiasController extends Controller
