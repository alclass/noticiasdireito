<?php
namespace App\Models\SabDirModels;
// use App\Models\SabDirModels\SabDirAula;

use Illuminate\Database\Eloquent\Model;

class MultipartVideoaula extends Model {

  protected $table = 'multipartvideoaulas';

  protected $fillable = [
    'sabdiraula_id',   'n_ord_part',
    'duration_in_min', 'ytvideochar11id',
	];

  public function get_previous_videopart_if_any() {
    if ($this->n_ord_part == 1) {
      return null;
    }
    $previous_videopart = $this->aula->get_videopart_by_n_ord($this->n_ord_part - 1);
    return $previous_videopart;
  }

  public function get_next_videopart_if_any() {
    $next_videopart = $this->aula->get_videopart_by_n_ord($this->n_ord_aula + 1);
    // return null if this videopart ($this) is already the last one
    return $next_videopart;
  }

  public function aula() {
    return $this->belongsTo('App\Models\SabDirModels\SabDirAula', 'sabdiraula_id');
  }

} // ends class MultipartVideoaula extends Model
