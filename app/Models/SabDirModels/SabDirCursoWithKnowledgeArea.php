<?php
namespace App\Models\SabDirModels;

use App\Http\Controllers\SabDirControllers\SabDirCursoController;
use App\Models\SabDirModels\SabDirCurso;
use Illuminate\Database\Eloquent\Model;

class SabDirCursoWithKnowledgeArea extends Model {
  /*
  This class aimed initially to speed up the counting of courses in Knowledge Areas
  Technically, this class is not necessary, because SabDirCurso has a reciprocal
    Many-to-Many relationship with KnowledgeArea
  */

  protected $table = 'sabdircurso_knowledgearea';
  protected $fillable = [
    'sabdircurso_id', 'knowledgearea_id',
	];

  public static function find_n_direct_courses($knowledgearea_id) {
    return self
      ::where('knowledgearea_id', $knowledgearea_id)
      ->count();
  }

  public static function fetch_sabdircursos_from_knowledgearea_ids($knowledgearea_ids) {
    $rows_ids = self
      ::whereIn('knowledgearea_id', $knowledgearea_ids)
      ->get(['sabdircurso_id']);
    $sabdircursos = SabDirCurso
      ::whereIn('id', $rows_ids)
      ->orderBy('firstemissiondate', 'desc')
      ->get();
    return $sabdircursos;
  }

  public static function fetch_sabdircurso_ids_from_knowledgearea_id($knowledgearea_id) {
    $rows = self
      ::where('knowledgearea_id', $knowledgearea_id)
      ->get(['sabdircurso_id']);
    $sabdircurso_ids = [];
    foreach ($rows as $row) {
      $sabdircurso_ids[] = $row->sabdircurso_id;
    }
    return $sabdircurso_ids;
  }

  public static function fetch_sabdircursos_from_knowledgearea_id($knowledgearea_id) {
    $ids = self::fetch_sabdircurso_ids_from_knowledgearea_id($knowledgearea_id);
    $n_paginate   = SabDirCursoController::get_n_paginate();
    $sabdircursos = SabDirCurso::whereIn('id', $ids)
      ->orderBy('firstemissiondate', 'desc')
      ->paginate($n_paginate);
    return $sabdircursos;
  }

} // ends class SabDirCursoWithKnowledgeArea extends Model
