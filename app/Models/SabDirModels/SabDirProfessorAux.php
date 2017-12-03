<?php
namespace App\Models\SabDirModels;
// use App\Models\SabDirModels\SabDirProfessorAux;

use App\Models\AcadModels\KnowledgeArea;
use App\Models\SabDirModels\SabDirCurso;
// use App\Models\Util\DateUtil;
// use Illuminate\Support\Facades\DB;
// use Carbon\Carbon;


class SabDirProfessorAux {

  public static function set_main_karea_for_all_professors() {
    $professores = SabDirProfessor::all();
    foreach ($professores as $sabdirprofessor) {
      $professor_aux = new self($sabdirprofessor);
      $professor_aux->fillin_main_knowledgearea();
      echo 'professor = ' . $sabdirprofessor->tarjaname . ' karea_id=' . $sabdirprofessor->main_knowledgearea_id .'\n';
    }
  } // ends set_main_karea_for_all_professors()

  public function __construct($professor) {
    $this->sabdirprofessor = $professor;
  } // ends __construct()

  public function get_main_knowledgearea_or_null() {
    $karea_id = $this->sabdirprofessor->main_knowledgearea_id;
    return KnowledgeArea::find($karea_id);
  }

  public function get_main_knowledgearea_or_root() {
    $karea = $this->get_main_knowledgearea_or_null();
    if ($karea==null) {
      return KnowledgeArea::get_root_knowledgearea();
    }
    return $karea;
  }

  public function fillin_main_knowledgearea() {
    $sabdircurso = $this->sabdirprofessor->cursos->first();
    if ($sabdircurso != null) {
      $knowledgearea = $sabdircurso->knowledgeareas->first();
      if ($knowledgearea != null) {
        $second_level_karea = $knowledgearea->get_1st_knowledgearea_downroot_or_root();
        $this->sabdirprofessor->main_knowledgearea_id = $second_level_karea->id;
        $this->sabdirprofessor->save();
      }
    }
  } // ends fillin_main_knowledgearea()


} // ends class SabDirCursoStats
