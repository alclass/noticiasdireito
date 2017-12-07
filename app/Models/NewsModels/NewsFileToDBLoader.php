<?php
namespace App\Models\NewsModels;
// use App\Models\NewsModels\NewsFileToDBLoader;

use App\Models\NewsModels\NewsObject;
use App\Models\Util\FileSystemUtil;
use Carbon\Carbon;
use Parsedown;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class NewsFileToDBLoader {

  public function __construct() {

  }

  public function get_current_abspath($year_n_month_path) {
    $baserelfolder = FileSystemUtil::NOTICIAS_MD_1STFOLDERAFTERSTORAGE;
    $baserelfolder .= '/' . $year_n_month_path;
    return storage_path($baserelfolder);
  }

  private function insertOrUpdateNewsObject(
      $datestr, $newstitle, $subtitle, $description,
      $underlined_newstitle, $related_sabdircursos
  ) {
    echo "datestr $datestr";
    echo "newstitle $newstitle";
    echo "subtitle $subtitle";
    echo "underlined_newstitle $underlined_newstitle";
    foreach ($related_sabdircursos as $cursodate) {
      echo "cursodate $cursodate \n";
    }
    $o = NewsObject
      ::where('newsdate', $datestr)
      ->where('underlined_newstitle', $underlined_newstitle)
      ->first();
    if ($o == null) {
      $o = new NewsObject();
    }
    $o->newsdate    = $datestr;
    $o->newstitle   = $newstitle;
    $o->subtitle    = $subtitle;
    $o->description = $description;
    $o->underlined_newstitle = $underlined_newstitle;
    $o->save(); // this will get news_obj an id, needed for attaching courses below
    // clean up the attached courses to avoid duplicates
    $o->cleanAllRelatedCourses();
    foreach ($related_sabdircursos as $cursodate) {
      $bool_insert = $o->addSabDirCursoByDate($cursodate);
      echo "bool_insert for $cursodate is $bool_insert \n";
    }
    $o->save();
  }

  public function transform_markdown_into_html($jsonfilepath) {
    $markdownfilepath = substr($jsonfilepath, 0, -4) . 'md';
    $htmlfilepath = $markdownfilepath . '.html';
    if (file_exists($htmlfilepath)) {
      return false;
    }
    if (file_exists($markdownfilepath)) {
      $parsedown = new Parsedown();
      $markdown_src = file_get_contents($markdownfilepath);
      $news_htmlfrag = $parsedown->text($markdown_src);
      file_put_contents($htmlfilepath, $news_htmlfrag);
      return true;
    }
    return false;
  }

  public function look_into_filepath($filepath) {
    echo "filepath = $filepath\n";
    $ext = pathinfo($filepath, PATHINFO_EXTENSION);
    echo "ext = $ext\n";
    if ($ext=='json') {
      $contents = file_get_contents($filepath);
      $json_aarray = json_decode($contents);
      $pp = explode('/', $filepath);
      $arraysize = count($pp);
      $filename = $pp[$arraysize-1];
      $datestr = substr($filename, 0, 10);
      $newstitle = $json_aarray->newstitle;
      /*
      if (!NewsObject
            ::where('newsdate', $datestr)
            ->where('newstitle', $newstitle)
            ->exists()) {
            */
        $subtitle             = $json_aarray->subtitle;
        $description          = $json_aarray->description;
        $underlined_newstitle   = $json_aarray->underlined_newstitle;
        $related_sabdircursos = $json_aarray->related_sabdircursos;
        $this->insertOrUpdateNewsObject(
          $datestr, $newstitle, $subtitle, $description,
          $underlined_newstitle, $related_sabdircursos
        );
      //};
      $bool_didit = $this->transform_markdown_into_html($filepath);
    }
  }

  public function complete_dirtree_sweep() {
    $baserelfolder = FileSystemUtil::NOTICIAS_MD_1STFOLDERAFTERSTORAGE;
    $path = storage_path($baserelfolder);

    $filesystementries = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($path),
      RecursiveIteratorIterator::SELF_FIRST
    );
    foreach($filesystementries as $filename => $object){
      $this->look_into_folder($filename);
    }
  } // ends complete_dirtree_sweep()

  private function survey_path_with_year_n_month($year_n_month_path) {

    $passingpath = $this->get_current_abspath($year_n_month_path);
    echo "passingpath $passingpath";
    if (!is_dir($passingpath)) {
      return;
    }
    $files = scandir($passingpath);
    foreach ($files as $filename) {
      $filepath = $passingpath . '/' . $filename;
      $this->look_into_filepath($filepath);
    }
  } // ends survey_path_with_year_n_month()

  private function form_year_n_month_path($refdate) {
    $refyear  = $refdate->year;
    $refmonth = $refdate->month;
    $monthstr = strval($refmonth);
    if (strlen($monthstr) < 2) {
      $monthstr = "0$monthstr";
    }
    $year_n_month_path = "$refyear/$refmonth";
    return $year_n_month_path;
  } // ends form_year_n_month_path()

  public function load_news_since_n_months_ago($starting_from_n_months_ago=1) {
    $currentdate  = Carbon::now();
    $currentyear  = $currentdate->year;
    $currentmonth = $currentdate->month;
    $refdate = $currentdate->copy()->addMonths(-$starting_from_n_months_ago);
    // $refyear  = $refdate->year;
    // $refmonth = $refdate->month;
    while($refdate <= $currentdate) {
      $year_n_month_path = $this->form_year_n_month_path($refdate);
      $this->survey_path_with_year_n_month($year_n_month_path);
      $refdate->addMonths(1);
    }
  } // ends load_news_since_n_months_ago()

  public function load_news_for_month($refmonth=null, $refyear=null) {
    $today = Carbon::today();
    if ($refyear==null) {
      $refyear = $today->year;
    }
    if ($refmonth==null) {
      $refmonth = $today->month;
    }
    $refmonthstr = strval($refmonth);
    if (strlen($refmonthstr) < 2) {
      $refmonthstr = "0$refmonthstr";
    }
    $year_n_month_path = "$refyear/$refmonthstr";
    $this->survey_path_with_year_n_month($year_n_month_path);
  } // ends load_news_for_month()

  public function load_news_having_date($p_datestr=null) {
    if ($p_datestr==null) {
      $carbondate = Carbon::today();
    } else {
      $carbondate = new Carbon($p_datestr);
    }
    $datestr = $carbondate->format('Y-m-d');
    $refyear  = $carbondate->year;
    $refmonth = $carbondate->month;
    $year_n_month_path = "$refyear/$refmonth";
    $passingpath = $this->get_current_abspath($year_n_month_path);
    echo "passingpath $passingpath";
    $files = scandir($passingpath);
    // $filearticles = [];
    foreach ($files as $filename) {
      $datefromfileprefix = substr($filename, 0, 10);
      if ($datestr == $datefromfileprefix) {
        $filepath = $passingpath . '/' . $filename;
        $this->look_into_filepath($filepath);
      }
    }
  } // ends load_news_having_date()

} // ends class NewsFileToDBLoader

/*
if (php_sapi_name()=='cli') {
  $loader = new NewsFileToDBLoader();
  echo 'Executing method load_news_since_n_months_ago(1)' . "\n";
  $loader->load_news_since_n_months_ago(1);
}
*/
