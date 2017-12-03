<?php
namespace App\Models\Util;
// use App\Models\Util\MathUtil;

// use Carbon\Carbon;

class MathUtil {

  public static function draw_n_random_elements_from_1darray(
      $array,
      $n_to_draw=1
    ) {
    $total_available = count($array);
    if ($total_available <= $n_to_draw)  {
      return $array;
    }
    $drawn_elems = [];
    $n_drawn_uptonow = 0;
    while($n_drawn_uptonow < $n_to_draw) {
      $random_index = rand(0, $total_available-1);
      $id_1elem_array = array_splice($array, $random_index, 1, []);
      $drawn_elems[] = $id_1elem_array[0];
      $total_available = count($array); // this diminished one
      $n_drawn_uptonow = count($drawn_elems); // this increased one
    } // ends while
    return $drawn_elems;
  } // ends static draw_n_random_elements_from_1darray()

} // ends class MathUtil
