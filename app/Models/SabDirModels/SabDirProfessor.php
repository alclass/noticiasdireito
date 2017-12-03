<?php
namespace App\Models\SabDirModels;
// use App\Models\SabDirModels\SabDirProfessor;

use App\Models\SabDirModels\SabDirProfessorAux;
use App\Models\Util\StringUtil;
use App\Models\Util\UtilParams;
use Illuminate\Database\Eloquent\Model;

class SabDirProfessor extends Model {

  public static function get_1stcourse_by_professors_first_n_last_names($firstname, $lastname) {
    $professor = SabDirProfessor
      ::where('fullname', 'like', "$firstname%$lastname")
      ->first();
    if (count($professor->cursos)>0) {
      return $professor->cursos->first();
    }
    return null;
  } // ends get_1stcourse_by_professors_first_n_last_names()

  protected $table = 'sabdirprofessores';
  protected $dates = ['birthdate'];

  protected $appends = ['firstname', 'lastname', 'routeurl'];

  protected $fillable = [
    // 'fullname',
    'fullname',
    'tarjaname',
    'photopath', 'yt11cid_fallback_photo',
    'birthdate', 'birthcity',
    'instituicao_entao', 'instituicao_atual',
    'titulacao1', 'titulacao2',
    'cargo1', 'cargo2', 'curriculolattescharid',
    'profs_description', 'nota_voto_editor', 'nota_voto_popular',
    'socnetw_url1', 'socnetw_url2',
	];
  // is_gender_female
  public function total_professores_do_genero($f_ou_m) {
    /*
      The db-table-field also accepts null, so
      if a professor has not been 'seen',
      he or she will come up via the queries below
    */
    if ($f_ou_m == 'f') {
      return SabDirProfessor
        ::where('is_gender_female', true)
        ->count();
    }
    return SabDirProfessor
      ::where('is_gender_female', false)
      ->count();
  }

  public function getFirstnameAttribute() {
    if (count($this->fullname) > 0) {
      $pp = explode(' ', $this->fullname);
      if (count($pp) > 0) {
        $firstname = $pp[0];
        return $firstname;
      }
    }
    return null;
  }

  public function getLastnameAttribute() {
    if (count($this->fullname) > 0) {
      $pp = explode(' ', $this->fullname);
      $n_elements = count($pp);
      if ($n_elements > 1) {
        $lastname = $pp[$n_elements-1];
        return $lastname;
      }
    }
    return null;
  }

  public function getRouteurlAttribute() {
    return $this->generate_phraseid();
  }

  public function get_first_n_lastnames_str() {
    if ($this->fullname == null or $this->fullname == '') {
      return '';
    }
    $pp = explode(' ', $this->fullname);
    foreach ($pp as $key=>$word) {
      if ($word=='') {
        unset($pp[$key]);
      }
    }
    $n_elements = count($pp);
    if ($n_elements==0) {
      return '';
    }
    if ($n_elements==1) {
      return $this->fullname;
    }
    $firstname = $pp[0];
    $lastname  = $pp[$n_elements-1];
    $first_n_lastnames_str = $firstname . ' ' . $lastname;
    return $first_n_lastnames_str;
  } // ends get_first_n_lastnames_str()

  public function get_tarjaname() {
    if ($this->tarjaname != null and $this->tarjaname != '') {
      return $this->tarjaname;
    }
    return $this->get_first_n_lastnames_str();
  } // ends get_tarjaname()

  public function get_firstname() {
    $first_n_lastnames_str = $this->get_first_n_lastnames_str();
    if ($first_n_lastnames_str==null or $first_n_lastnames_str=='') {
      return '';
    }
    $pp = explode(' ', $first_n_lastnames_str);
    $n_elements = count($pp);
    if ($n_elements>0) {  // ie, there must be at least 1 name there
      $firstname = $pp[0];
      return $firstname;
    }
    /*
      Otherwise, return the empty string ''
      Notice also that the algorithm in $this->get_first_n_lastnames_str()
        will already return '' above if the string is null or '' or made of blanks
    */
    return '';
  } // ends get_firstname()

  public function get_lastname() {
    $first_n_lastnames_str = $this->get_first_n_lastnames_str();
    if ($first_n_lastnames_str==null or $first_n_lastnames_str=='') {
      return '';
    }
    $pp = explode(' ', $first_n_lastnames_str);
    $n_elements = count($pp);
    if ($n_elements>=2) { // ie, there must be at least 2 names there
      $lastname = $pp[$n_elements-1];
      return $lastname;
    }
    // Otherwise, return the empty string ''
    return '';
  } // ends get_firstname()

  public function generate_phraseid() {
    /*
    The $aula_phraseid
    */
    $professor_phraseid = StringUtil::convert_phrase_to_nonaccented_url_piecepath($this->get_first_n_lastnames_str());
    $professor_phraseid .= '-' . $this->id;
    return $professor_phraseid;
  }

  public function get_lecture_yt11cid_or_photo_fallback($image_2letter_size=null) {
    /*
      The aim of this method is to solve the 2-or-more-teach issue,
        ie, as an example, if a course is taught by 2 teachers,
        the portrait photo for one individual teacher may
        be wrongly displaying the protrait photo of the other teacher.

      For these cases, $this->yt11cid_fallback_photo must be filled in
        so that it may be used here.

      Today, in almost 500 courses, there's only one course
        in this situation, only today only prof. Fernando Castellani
        needs this field filled-in, because he's the second teacher
        in a course with prof. Marcelo Cometti.  Before this
        initiative, Fernando's protrait was, wrongly, that of Marcelo's.
    */

    if ($this->yt11cid_fallback_photo != null) {
      return $this->yt11cid_fallback_photo;
    }
    foreach ($this->cursos as $curso) {
      foreach ($curso->aulas as $aula) {
        $ytvideo11char = $aula->find_ytvideo11char_by_nordpart();
        if ($ytvideo11char != null) {
          return $ytvideo11char;
        }
      }
    }
    return null;
  }

  public function get_profs_yt_thumbnail_photo_url($image_2letter_size=null) {
    if ($image_2letter_size == null) {
      //$image_2letter_size = UtilParams::YT_MQ_MEDQUAL_2LETTER;
      $image_2letter_size = UtilParams::YT_DF_DEFAULT_2LETTER;
    }
    $ytvideo11char = $this->get_lecture_yt11cid_or_photo_fallback($image_2letter_size);
    // wrap the $ytvideo11char to the URL interpolate
    return UtilParams::get_ytvideothumbnailurl_by_11char_n_size($ytvideo11char, $image_2letter_size);
  }

  public function get_main_knowledgearea_or_null() {
    $prof_aux = new SabDirProfessorAux($this);
    return $prof_aux->get_main_knowledgearea_or_null();
  }

  public function get_main_knowledgearea_or_root() {
    $prof_aux = new SabDirProfessorAux($this);
    return $prof_aux->get_main_knowledgearea_or_root();
  }

  public function knowledgeareas() {
    return $this->hasMany(
      'App\Models\AcadModels\KnowledgeArea',
      'sabdirprofessor_knowledgearea',
      'sabdirprofessor_id',
      'knowledgearea_id'
    );

  }

  public function cursos() {
    return $this->belongsToMany(
      'App\Models\SabDirModels\SabDirCurso',
      'sabdircurso_sabdirprofessor',
      'sabdirprofessor_id',
      'sabdircurso_id'
    );
  }

} // ends class SabDirProfessor extends Model
