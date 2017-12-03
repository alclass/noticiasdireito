<?php
namespace App\Models\Util;
// use App\Models\Util\UtilParams;

use Carbon\Carbon;

class UtilParams {

  const N_PAGINATE = 10;

  const YT_DF_DEFAULT_2LETTER = 'DF'; // 120px × 90px
  const YT_HQ_HIGHQUA_2LETTER = 'HQ';
  const YT_MQ_MEDQUAL_2LETTER = 'MQ'; // 320px × 180px
  const YT_SD_STANDEF_2LETTER = 'SD'; // 640px × 480px
  const YT_MX_MAXQUAL_2LETTER = 'MX';

  const URL_YT_DF_THUMBNAIL_INTERPLT = "http://img.youtube.com/vi/%s/default.jpg";
  const URL_YT_HQ_THUMBNAIL_INTERPLT = "http://img.youtube.com/vi/%s/hqdefault.jpg";
  const URL_YT_MQ_THUMBNAIL_INTERPLT = "http://img.youtube.com/vi/%s/mqdefault.jpg";
  const URL_YT_SD_THUMBNAIL_INTERPLT = "http://img.youtube.com/vi/%s/sddefault.jpg";
  const URL_YT_MX_THUMBNAIL_INTERPLT = "http://img.youtube.com/vi/%s/maxresdefault.jpg";

  const URL_YT_PLAYLIST_WITHOUT_PLID = 'https://www.youtube.com/playlist?list=';

  const TEMPORADA_1_STARTDATE = '2008-08-11';
  const TEMPORADA_1_ENDDATE   = '2010-08-23';
  const TEMPORADA_2_STARTDATE = '2010-10-04';
  const TEMPORADA_2_ENDDATE   = '2013-03-04';
  const TEMPORADA_3_STARTDATE = '2013-02-04';

  const TEMPORADAS_DATES = [
    1 => ['2008-08-11', '2010-08-23'] ,
    2 => ['2010-10-04', '2013-03-04'] ,
    3 => ['2013-02-04', null] ,
  ];

  const SERVERS_FOLDERNAME_FOR_PASTACURSOS  = 'pastacursos';
  const SITEMAPTXT_RELATIVE_TO_STORAGEPATH  = 'entrancefiles/Sitemap.txt';
  const TOTALCURSOS_RELATIVE_TO_STORAGEPATH = 'entrancefiles/total_de_cursos.txt';

  public static function get_n_paginate() {
    return env('N_PAGINATE', self::N_PAGINATE);
  }

  public static function get_servers_foldername_for_pastacursos() {
    return self::SERVERS_FOLDERNAME_FOR_PASTACURSOS;
  }

  public static function get_sitemaptxt_filepath_on_storage() {
    return storage_path(self::SITEMAPTXT_RELATIVE_TO_STORAGEPATH);
  }

  public static function get_total_de_cursos_via_file_on_entrancefiles() {
    $filepath    = storage_path(self::TOTALCURSOS_RELATIVE_TO_STORAGEPATH);
    if (!file_exists($filepath)) {
      self::set_total_de_cursos_via_file_on_entrancefiles(0);
    }
    $content = file_get_contents($filepath);
    return intval($content);
  }

  public static function set_total_de_cursos_via_file_on_entrancefiles($total_de_cursos) {
    $filepath    = storage_path(self::TOTALCURSOS_RELATIVE_TO_STORAGEPATH);
    $filepointer = fopen($filepath, 'w');
    $content     = '' . $total_de_cursos;
    fwrite($filepointer, $content);
    fclose($filepointer);
  }

  public static function get_ini_n_fim_de_temporada_as_datearray($n_temporada) {
    if (!array_key_exists($n_temporada, self::TEMPORADAS_DATES)) {
      return [];
    }
    $datestr_array = self::TEMPORADAS_DATES[$n_temporada];
    $datestr_ini = $datestr_array[0];
    $datestr_fim = $datestr_array[1];
    $date_fim = null;
    if ($datestr_fim != null) {
      $date_fim = new Carbon($datestr_fim);;
    }
    $date_ini = new Carbon($datestr_ini);
    // $date_fim may be null
    $date_ini_n_fim_array = [$date_ini, $date_fim];
    return $date_ini_n_fim_array;
  }

  public static function get_img_url_interpolate($size='DF') {
    /*
    Default Thumbnail
      http://img.youtube.com/vi/{$ytvideoid}/default.jpg
    High Quality Thumbnail
      http://img.youtube.com/vi/{$ytvideoid}/hqdefault.jpg
    Medium Quality
      http://img.youtube.com/vi/{$ytvideoid}/mqdefault.jpg
    Standard Definition
      http://img.youtube.com/vi/{$ytvideoid}/sddefault.jpg
    Maximum Resolution
      http://img.youtube.com/vi/{$ytvideoid}/maxresdefault.jpg
    */
    $img_url_interpolate = null;
    switch ($size) {
      case 'HQ':
        $img_url_interpolate = self::URL_YT_HQ_THUMBNAIL_INTERPLT;
        break;
      case 'MQ':
        $img_url_interpolate = self::URL_YT_MQ_THUMBNAIL_INTERPLT;
        break;
      case 'SD':
        $img_url_interpolate = self::URL_YT_SD_THUMBNAIL_INTERPLT;
        break;
      case 'MX':
        $img_url_interpolate = self::URL_YT_MX_THUMBNAIL_INTERPLT;
        break;
      default: // = DF ie DEFAULT
        $img_url_interpolate = self::URL_YT_DF_THUMBNAIL_INTERPLT;
        break;
    }
    return $img_url_interpolate;
  } // ends static get_img_url_interpolate()

  public static function get_ytvideothumbnailurl_by_11char_n_size($ytvideo_11char, $size='DF') {
    if ($ytvideo_11char == null) {
      return null;
    }
    $img_url_interpolate = self::get_img_url_interpolate($size);
    $img_url = sprintf($img_url_interpolate, $ytvideo_11char);
    return $img_url;
  }

  public static function get_ytplaylist_url_with_plid_or_searchurl($ytplaylistcharid, $coursetitle=null, $professorname=null) {
    $ytplaylisturl = self::get_ytplaylist_url_with_plid($ytplaylistcharid);
    if ($ytplaylisturl!=null) {
      return $ytplaylisturl;
    }
    // https://www.youtube.com/results?search_query=saber+direito%3B+Lei+Maria+da+Penha+na+Jurisprud%C3%AAncia
    if ($coursetitle==null) {
      return null;
    }
    // It's safe to 'mutate' $coursetitle, it's a string
    $coursetitle = StringUtil::convert_phrase_to_nonaccented_url_piecepath($coursetitle);
    $coursetitle = preg_replace('/\_/', '+', $coursetitle);
    $coursetitle = preg_replace('/[0-9]/', '', $coursetitle);
    $coursetitle = preg_replace('/\./', '', $coursetitle);
    $coursetitle = preg_replace('/\//', '', $coursetitle);
    $coursetitle = preg_replace('/\+de/', '', $coursetitle);
    $coursetitle = preg_replace('/\+da/', '', $coursetitle);
    $coursetitle = preg_replace('/\+do/', '', $coursetitle);
    $coursetitle = preg_replace('/\+das/', '', $coursetitle);
    $coursetitle = preg_replace('/\+dos/', '', $coursetitle);
    $coursetitle = str_replace('++', '+', $coursetitle);
    $searchtitle = $coursetitle;
    if ($professorname!=null) {
      $professorname = StringUtil::convert_phrase_to_nonaccented_url_piecepath($professorname);
      $professorname = preg_replace('/\_/', '+', $professorname);
      $searchtitle .= '%3B+' . $professorname;
    }
    $ytplaylist_searchurl = "https://www.youtube.com/results?search_query=saber+direito%3B+$searchtitle+&sp=EgIQAw%253D%253D";
    return $ytplaylist_searchurl;
  }

  public static function get_ytplaylist_url_with_plid($ytplaylistcharid) {
    if ($ytplaylistcharid == null) {
      return null;
    }
    return self::URL_YT_PLAYLIST_WITHOUT_PLID . $ytplaylistcharid;
  }

// get_ytplaylist_url_with_plid($this->ytplaylistcharid)

} // ends class UtilParams
