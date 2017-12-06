<?php
namespace App\Http\Controllers;
// use App\Http\Controllers\NoticiasController;
use App\Http\Controllers\Controller;
use App\Models\NewsModels\NewsObject;
use App\Models\Util\FileSystemUtil;
use Carbon\Carbon;
// use Parsedown;

class NoticiasController extends Controller {

  public function mount_newslisting_for_entrance() {
    /* look up directory
      $newspiece_aarray = $this->get_last_newspiece_aarray();
      $newspiece_entries = collect();
      $newspiece_entries->push($newspiece_aarray);
    */
    $newsobjects = NewsObject
      ::orderBy('newsdate', 'desc')
      ->paginate(10);
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

  private function get_last_newspiece_aarray() {
    $newspiece_aarray = [];
    $newspiece_aarray['year']  = 2017;
    $newspiece_aarray['month'] = 12;
    $newspiece_aarray['day']   = 01;
    $newspiece_aarray['underlined_newstitle'] = 'a_decisao_do_stj_sobre_indenizacao_por_extravio_em_voos';
    return $newspiece_aarray;
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
    if ($year==null || $month==null) {
      //$refdate = Carbon::today();
      $newsobjects = self::paginate(10);
      return redirect()->route('entranceroute')->with(['newsobjects'=>$newsobjects]);
    }
    $refdatestr = "$year-$month-01";
    $refdate = new Carbon($refdatestr);
    $nextmonthdate = $refdate->copy()->addMonths(1);
    $previousmonthlastdaydate = $refdate->copy()->addDays(-1);
    $newsobjects = NewsObject
      ::where('newsdate', '<', $nextmonthdate)
      ->where('newsdate', '>', $previousmonthlastdaydate)
      ->paginate(10);
    if (count($newsobjects)==0) {
      $newsobjects = NewsObject::paginate(10);
      return redirect()->route('entranceroute')->with(['newsobjects'=>$newsobjects]);
    }
    return view('entrance', [
      'newsobjects' => $newsobjects,
    ]);
  }


}
