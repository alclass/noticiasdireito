<?php
namespace App\Models\Util;
use Parsedown;

class FileSystemUtil {

  const NOTICIAS_MD_1STFOLDERAFTERSTORAGE = 'noticias_markdown' ;

  public static function mount_newspiece_markdown_relpath($year, $p_month, $p_day, $underlined_newstitle) {
    $day = strval($p_day);
    if (strlen($day)== 1) {
      $day = "0$day";
    }
    $month = strval($p_month);
    if (strlen($month)== 1) {
      $month = "0$month";
    }
    $foldername = self::NOTICIAS_MD_1STFOLDERAFTERSTORAGE;
    $relpath = "$foldername/$year/$month/$year-$month-$day" . '_' . "$underlined_newstitle.md";
    return $relpath;
  } // ends static mount_newspiece_markdown_relpath()

  public static function mount_newspiece_html_relpath($year, $month, $day, $underlined_newstitle) {
    $relpath = self::mount_newspiece_markdown_relpath($year, $month, $day, $underlined_newstitle);
    if ($relpath == null || $relpath == '') {
      return null;
    }
    return $relpath . '.html';
  }

  public static function mount_newspiece_markdown_serverpath($year, $month, $day, $underlined_newstitle) {
    $relpath = self::mount_newspiece_markdown_relpath($year, $month, $day, $underlined_newstitle);
    $serverpath = storage_path($relpath);
    return $serverpath;
  } // ends static mount_newspiece_markdown_serverpath()

  public static function create_n_get_htmlnewspiece_on_filesystem($year, $month, $day, $underlined_newstitle) {
    $serverpath = self::mount_newspiece_markdown_serverpath($year, $month, $day, $underlined_newstitle);
    if (!file_exists($serverpath)) {
      return null;
    }
    $parsedown = new Parsedown();
    $markdown_src = file_get_contents($serverpath);
    $news_htmlfrag = $parsedown->text($markdown_src);
    $htmlserverpath = $serverpath . '.html';
    file_put_contents($htmlserverpath, $news_htmlfrag);
    return $news_htmlfrag;
  } // ends static create_n_get_htmlnewspiece_on_filesystem()

  public static function retrieve_htmlnewspiece_from_filesystem($year, $month, $day, $underlined_newstitle) {
    $relpath = self::mount_newspiece_html_relpath($year, $month, $day, $underlined_newstitle);
    $serverpath = storage_path($relpath);
    if (!file_exists($serverpath)) {
      $news_htmlfrag = self::create_n_get_htmlnewspiece_on_filesystem($year, $month, $day, $underlined_newstitle);
      return $news_htmlfrag;
    }
    return file_get_contents($serverpath);
  } // ends static retrieve_htmlnewspiece_from_filesystem()

} // ends class UtilParams
