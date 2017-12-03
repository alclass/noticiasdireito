<?php
namespace App\Models\NewsModels;
// use App\Models\NewsModels\NewsDBInsertor;

use App\Models\NewsModels\NewsObject;
use App\Models\Util\UtilParams;
use Carbon\Carbon;
use Parsedown;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class NewsDBInsertor {

  public function __construct() {

  }

  public function get_current_abspath($year_n_month_path) {
    $baserelfolder = UtilParams::NOTICIAS_MD_1STFOLDERAFTERSTORAGE;
    $baserelfolder .= '/' . $year_n_month_path;
    return storage_path($baserelfolder);
  }

  private function insertNewsObject($datestr, $newstitle, $subtitle, $underlined_newstitle) {
    echo "datestr $datestr";
    echo "newstitle $newstitle";
    echo "subtitle $subtitle";
    echo "underlined_newstitle $underlined_newstitle \n";
    $o = new NewsObject();
    $o->newsdate  = $datestr;
    $o->newstitle = $newstitle;
    $o->subtitle  = $subtitle;
    $o->underlined_newstitle = $underlined_newstitle;
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
      if (!NewsObject
            ::where('newsdate', $datestr)
            ->where('newstitle', $newstitle)
            ->exists()) {
        $subtitle             = $json_aarray->subtitle;
        $description          = $json_aarray->description;
        $underlined_newstitle = $json_aarray->underlined_newstitle;
        $this->insertNewsObject($datestr, $newstitle, $subtitle, $underlined_newstitle);
      };
      $bool_didit = $this->transform_markdown_into_html($filepath);
    }
  }

  public function complete_dirtree_sweep() {
    $baserelfolder = UtilParams::NOTICIAS_MD_1STFOLDERAFTERSTORAGE;
    $path = storage_path($baserelfolder);

    $filesystementries = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($path),
      RecursiveIteratorIterator::SELF_FIRST
    );
    foreach($filesystementries as $filename => $object){
      $this->look_into_folder($filename);
    }
  } // ends complete_dirtree_sweep()

  public function timely_dirtree_sweep($starting_from_n_months_ago=1) {
    $currentdate  = Carbon::now();
    $currentyear  = $currentdate->year;
    $currentmonth = $currentdate->month;
    $refdate = $currentdate->copy()->addMonths(-$starting_from_n_months_ago);
    $refyear  = $refdate->year;
    $refmonth = $refdate->month;
    while($refdate <= $currentdate) {
      $refyear  = $refdate->year;
      $refmonth = $refdate->month;
      $year_n_month_path = "$refyear/$refmonth";
      $passingpath = $this->get_current_abspath($year_n_month_path);
      echo "passingpath $passingpath";
      $files = scandir($passingpath);
      foreach ($files as $filename) {
        $filepath = $passingpath . '/' . $filename;
        $this->look_into_filepath($filepath);
      }
      $refdate->addMonths(1);
    }

  } // ends timely_dirtree_sweep()


} // ends class NewsDBInsertor
