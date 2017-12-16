<?php
namespace App\Models\Util;

class UtilParamsForNewsApp {
  /*
    DO NOT UPDATE this class outside of noticiasdireito_laraapp!
    When updating, make sure the source code is one in noticiasdireito_laraapp,
      then update it to other consumer apps.
  */
  const SABDIRDOIS_BASE_URL      = 'saberdireitodois.direito.win';
  const NOTICIASDIREITO_BASE_URL = 'noticias.direito.science';

  const N_PAGINATE = 7;
  const N_PAGINATE_NONPROD = 50;

  public static function get_n_paginate() {
    if (\App::environment('production')) {
      return self::N_PAGINATE;
    }
    return self::N_PAGINATE_NONPROD;
  }

  public static function treat_protocol_prefix($p_protocol)  {
    if (empty($p_protocol)) {
      return 'http:';
    }
    $protocol = strtolower($p_protocol);
    switch ($protocol) {
      case 'http':
        // add colon to it
        return 'http:';
      case 'https':
        // add colon to it
        return 'https:';
    }
    /*
      From here, the practical result, to the function receiver,
      will be a url beginning with double slashes, such as '//domain.tld/etc'
    */
    return '';
  }

  public static function get_noticiasdireito_root_url($curso, $protocol='http') {
    $protocol = self::treat_protocol_prefix($protocol);
    return $protocol . '//' . self::NOTICIASDIREITO_BASE_URL;
  } // ends static get_noticiasdireito_root_url()

  public static function get_sabdirdois_root_url($curso, $protocol='http') {
    $protocol = self::treat_protocol_prefix($protocol);
    return $protocol . '//' . self::SABDIRDOIS_BASE_URL;
  } // ends static get_noticiasdireito_root_url()

  public static function gen_outer_url_for_course($curso, $protocol='http') {
    $url = self::get_sabdirdois_root_url($protocol) . '/curso/' ;
    $routeurl_as_array = $curso->routeurl_as_array;
    if (!empty($routeurl_as_array)) {
      $url .= implode('/', $routeurl_as_array);
    }
    return $url;
  } // ends static gen_outer_url_for_course()

} // ends class UtilParamsForNewsApp
