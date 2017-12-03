<?php
namespace App\Models\Util;

class UtilParamsForNewsApp {

  const SABDIRDOIS_HTTP_URL = 'http://saberdireitodois.direito.win' ;

  public static function gen_outer_url_for_course($curso) {
    $url = self::SABDIRDOIS_HTTP_URL . '/curso/' ;
    $routeurl_as_array = $curso->routeurl_as_array;
    if (!empty($routeurl_as_array)) {
      $url .= implode('/', $routeurl_as_array);
    }
    return $url;
  } // ends static gen_outer_url_for_course()

} // ends class UtilParams
