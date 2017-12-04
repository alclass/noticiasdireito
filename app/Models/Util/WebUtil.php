<?php
namespace App\Models\Util;
// use App\Models\Util\WebUtil;

use App\Models\Util\UtilParams;
// use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator; // Collection
use Illuminate\Pagination\Paginator;

class WebUtil {

  public static function get_n_paginate() {
    return UtilParams::get_n_paginate();
  }

  public static function paginate_collection(
      $items, $perPage = null, $page = null, $options = []
    ) {
    if ($perPage == null) {
      $perPage = self::get_n_paginate();
    }
  	$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
  	$items = $items instanceof Collection ? $items : Collection::make($items);
  	return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
  }

} // ends class WebUtil
