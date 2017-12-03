<?php
namespace App\Models\SabDirModels;
// use App\Models\SabDirModels\SabDirCurso;

use App\Models\AcadModels\KAreaClosureTableFetcher;
use App\Models\AcadModels\KnowledgeArea;
use App\Models\Util\DateUtil;
use App\Models\Util\StringUtil;
use App\Models\Util\UtilParams;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SabDirCurso extends Model {

  public static function fetch_random_course() {
    $total_de_cursos = self::count();
    if ($total_de_cursos==0) {
      return null;
    }
    // the skip() function to be applied to self expects a zero-based index
    $random_i = rand(0, $total_de_cursos-1);
    return self::skip($random_i)->first();
  }

  public static function fetch_most_recent_course() {
    return self::orderBy('firstemissiondate', 'desc')->first();
  }

  public static function fetch_first_course() {
    return self::orderBy('firstemissiondate', 'asc')->first();
  }

  public static function count_courses_in_year($ano) {
    if ($ano==null) {
      return 0;
    }

    $last_day_of_previous_year = DateUtil::get_last_day_of_previous_year_as_date($ano);
    $first_day_of_next_year    = DateUtil::get_first_day_of_next_year_as_date($ano);

    return self
      ::where('firstemissiondate', '>',  $last_day_of_previous_year)
      ->where('firstemissiondate', '<',  $first_day_of_next_year)
      ->count();
  }

  protected $table = 'sabdircursos';
  protected $dates = ['firstemissiondate'];
  protected $appends = [
    'finishdate', 'n_aulas', 'sddebatedate', 'sdrespondedate',
    'routeurl_as_array', 'titleforurl', 'ytplaylisturl',
  ];

  protected $fillable = [
    'title', 'firstemissiondate',  'roteirodoc_url',
    'assunto', 'breve_resumo', 'resenha_relpath',
    'nota_voto_editor', 'nota_voto_popular',
    'ytvideoids_debate', 'ytvideoids_responde',
    'sddebate_duration_in_min', 'sddresponde_duration_in_min',
    'ytplaylistcharid', 'ytplaylistalternativecharid',
    'ytplaylist_searched_n_not_found',
    'ytuser', 'ytchannel',
    'torrenturl',
	];
  // ytplaylist_searched_n_not_found

  public function get_date_formatted($format_str='d/m/Y') {
    if ($this->firstemissiondate != null) {
      return $this->firstemissiondate->format($format_str);
    }
    return 'n/a';
  }

  public function addDays($n_days=1) {
    if ($this->firstemissiondate == null) {
      return null;
    }
    return $this->firstemissiondate->copy()->addDays($n_days);
  }

  public function getNAulasAttribute() {
    return $this->aulas->count();
  }

  public function getFinishdateAttribute() {
    if ($this->firstemissiondate == null) {
      return null;
    }
    // a course generally has a 7-day span, so finish date is first date plus 6
    return $this->firstemissiondate->copy()->addDays(6);
  }

  public function getSddebatedateAttribute() {
    /*
      Translatable attribute $sddebatedate
      It's 6 days after $firstemissiondate
      (No need yet to have it in the params.ini datafile, it's okay hardcoded for now)
    */
    return $this->addDays(5);
  }

  public function getSdrespondedateAttribute() {
    /*
      Translatable attribute $sdrespondedate
      It's 5 days after $firstemissiondate
      (No need yet to have it in the params.ini datafile, it's okay hardcoded for now)
    */
    return $this->addDays(6);
  }

  public function getRouteurlAsArrayAttribute() {
    $routeurl_as_array = [];
    if ($this->firstemissiondate != null) {
      $routeurl_as_array[] = $this->firstemissiondate->format('Y-m-d');
    }
    else {
      $routeurl_as_array[] = $this->id;
    }
    $routeurl_as_array[] = $this->generate_urltitlepath();;
    return $routeurl_as_array;
  }

  public function getTitleforurlAttribute() {
    return $this->generate_urltitlepath();
  }

  public function getYtplaylisturlAttribute() {
    /*
    IMPORTANT:
      This method returns either the ytplaylisturl using the ytplaylist11charid
      or, if the latter is null, it returns the search url for a playlist with
      course's title and first professor name
        $ytplaylisturl = UtilParams::get_ytplaylist_url_with_plid($this->ytplaylistcharid);
    */
    $coursetitle = $this->title;
    $professorname = null; // until otherwise
    if (count($this->professores)>0) {
      $professorname = $this->professores[0]->get_tarjaname();
    }
    $ytplaylisturl = UtilParams::get_ytplaylist_url_with_plid_or_searchurl(
      $this->ytplaylistcharid,
      $coursetitle,
      $professorname
    );
    return $ytplaylisturl;
  }

  public function has_ytplaylist() {
    if ($this->ytplaylisturl!=null) {
      return true;
    }
    return false;
  }

  public function get_root_knowledgearea() {
    return KnowledgeArea::get_root_knowledgearea();
  } // ends get_root_knowledgearea()

  public function get_knowledgeareas_or_root_in_array() {
    if ($this->knowledgeareas->count() > 0) {
      return $this->knowledgeareas;
    }
    $knowledgearea = $this->get_root_knowledgearea();
    $knowledgeareas = collect();
    $knowledgeareas->push($knowledgearea);
    return $knowledgeareas;
  }

  public function fetch_path_from_root_to_1stkarea_as_ids() {
    if ($this->knowledgeareas()->count() == 0) {
      return [];
    }
    $knowledgearea = $this->knowledgeareas()->first();
    if ($knowledgearea != null) {
      return KAreaClosureTableFetcher
        ::fetch_path_from_root_to_karea_as_ids($knowledgearea->id);
    }
    return [];
  } // ends fetch_path_from_root_to_1stkarea_as_ids()

  public function fetch_path_from_root_to_1stkarea_as_objs() {
    if ($this->knowledgeareas()->count() == 0) {
      return [];
    }
    $knowledgearea = $this->knowledgeareas()->first();
    if ($knowledgearea != null) {
      return KAreaClosureTableFetcher
        ::fetch_path_from_root_to_karea_as_objs($knowledgearea->id);
    }
    return [];
  } // ends fetch_path_from_root_to_1stkarea_as_objs()

  public function get_piece_sep_knowledgearea_path_for_first_ka_if_any($separator=' / ') {
    $knowledgearea = $this->knowledgeareas()->first();
    if ($knowledgearea != null) {
      return 'n/a';
    }
    $karea_charsep_path = $knowledgearea->get_piece_sep_knowledgearea_path($separator);
    return $karea_charsep_path;
  } // ends get_piece_sep_knowledgearea_path_for_first_ka_if_any()


  public function get_piece_sep_knowledgearea_path_list($separator=' / ') {
    $karea_charsep_paths = [];
    foreach ($this->knowledgeareas as $knowledgearea) {
      $karea_charsep_path = $knowledgearea->get_piece_sep_knowledgearea_path($separator);
      $karea_charsep_paths[] = $karea_charsep_path;
    }
    return $karea_charsep_paths;
  } // ends get_piece_sep_knowledgearea_path_list()

  public function firstdate_as_10char_ymd() {
    return $this->firstemissiondate->format('Y-m-d');
  }

  public function generate_urltitlepath() {
    /*
    The $curso_phraseid
    */
    return StringUtil::convert_phrase_to_nonaccented_url_piecepath($this->title);
  }

  public function get_routeurl_as_array() {
    /*
    The $curso_phraseid
    */
    $array = [];
    $array[] = $this->firstemissiondate->format('Y-m-d');
    $array[] = $this->generate_urltitlepath();
    return $array;
  }

  public function get_1st_professor() {
    return $this->professores()->first();
  }

  public function get_1st_knowledgearea_or_root() {
    $knowledgearea = $this->knowledgeareas()->first();
    if ($knowledgearea == null) {
      return KnowledgeArea::get_root_knowledgearea();
    }
    return $knowledgearea;
  }

  private function get_professores_plural_with_first_n_last_names_str() {
    $professores_str = 'Professores: ';
    $prof_names = [];
    foreach ($this->professores as $professor) {
      $prof_names[] = $professor->get_first_n_lastnames_str();
    }
    $prof_names_str = implode(', ', $prof_names);
    $professores_str .= $prof_names_str;
    return $professores_str;
  }

  public function get_professores_with_first_n_last_names_str() {
    if (count($this->professores) == 0) {
      return 'Não há professores ou Banco de Dados offline';
    }
    if (count($this->professores) > 1) {
      return $this->get_professores_plural_with_first_n_last_names_str();
    }
    $professor = $this->professores->first();
    $professores_str = 'Professor: ';
    if ($professor->is_gender_female) {
      $professores_str = 'Professora: ';
    }
    return $professores_str . $professor->get_tarjaname();
  } // ends get_professores_with_first_n_last_names_str()

  public function has_sddebate() {
    if ($this->ytvideoids_debate == null) {
      return false;
    }
    if (strlen($this->ytvideoids_debate) < 10) {
      return false;
    }
    return true;
  }

  public function has_sdresponde() {
    if ($this->ytvideoids_responde == null) {
      return false;
    }
    if (strlen($this->ytvideoids_responde) < 11) { // a ytvideoid has 11 chars
      return false;
    }
    return true;
  }

  public function has_debate_responde($debate_ou_responde) {
    if ($debate_ou_responde == 'debate') {
      return $this->has_sddebate();
    }
    else if ($debate_ou_responde == 'responde') {
      return $this->has_sdresponde();
    }
    return false;
  }

  public function is_debate_responde_multipart($debate_ou_responde) {
    if ($this->get_total_videoparts_of_debate_or_responde($debate_ou_responde)>1) {
      return true;
    }
    return false;
  }

  public function get_sddebate_duration_in_min() {
    /*
    */
    if ($this->has_debate_responde('debate') == false) {
      return null;
    }
    if ($this->is_debate_responde_multipart('debate') == false) {
      $duration_in_min = intval($this->sddebate_duration_in_min);
      if ($duration_in_min == 0) {
        return 28;
      }
      return $duration_in_min;
    }
    $duration_in_min = 0;
    $pp = explode(' ', $this->sddebate_duration_in_min);
    foreach ($pp as $minutes_str) {
      $minutes = intval($minutes_str);
      $duration_in_min += $minutes;
    }
    if ($duration_in_min == 0) {
      return 28;
    }
    return $duration_in_min;
  }

  public function get_sdresponde_duration_in_min() {
    /*
    */
    if ($this->has_debate_responde('responde') == false) {
      return null;
    }
    if ($this->is_debate_responde_multipart('responde') == false) {
      $duration_in_min = intval($this->sdresponde_duration_in_min);
      if ($duration_in_min == 0) {
        return 27;
      }
      return $duration_in_min;
    }
    $duration_in_min = 0;
    $pp = explode(' ', $this->sdresponde_duration_in_min);
    foreach ($pp as $minutes_str) {
      $minutes = intval($minutes_str);
      $duration_in_min += $minutes;
    }
    if ($duration_in_min == 0) {
      return 27;
    }
    return $duration_in_min;
  }

  public function get_debate_responde_duration_in_min($which_weekend_program='debate') {
    /*
    */
    if ($which_weekend_program == 'debate') {
      return $this->get_sddebate_duration_in_min();
    }
    return $this->get_sdresponde_duration_in_min();
  }

  public function get_ytvideo_11char_weekend($which_weekend_program='debate', $if_videopart_n = null) {
    $if_videopart_n = intval($if_videopart_n);
    if ($if_videopart_n == null or $if_videopart_n < 1) {
      $if_videopart_n = 1;
    }
    if ($which_weekend_program=='debate') {
      if ($this->has_sddebate() == false) {
        return null;
        //  'ytvideoids_responde';
      } else {
        $pp = explode(' ', $this->ytvideoids_debate);
        if (count($pp) < $if_videopart_n) {
          return null;
        }
        $debate_or_videopart_11char = $pp[$if_videopart_n-1];
        return $debate_or_videopart_11char;
      } // ends inner if-else
    } // ends outer if ($which_weekend_program=='debate')

    /*
      if it's not 'debate', it will be considered 'responde'
      (even if incoming parameter has a different third value)
    */
    if ($this->has_sdresponde() == false) {
      return null;
    }
    $pp = explode(' ', $this->ytvideoids_responde);
    if (count($pp) < $if_videopart_n) {
      return null;
    }
    $responde_or_videopart_11char = $pp[$if_videopart_n-1];
    return $responde_or_videopart_11char;
  } // ends get_ytvideo_11char_weekend()


  public function get_total_videoparts_of_debate_or_responde($which_weekend_program='debate') {
    if ($which_weekend_program == 'debate') {
      if ($this->has_sddebate() == false) {
        return 0;
      }
    $pp = explode(' ', $this->ytvideoids_debate);
    return count($pp);
    }
    if ($which_weekend_program == 'responde') {
      if ($this->has_sdresponde() == false) {
        return 0;
      }
    $pp = explode(' ', $this->ytvideoids_responde);
    return count($pp);
    }
    return null;
  }

  public function get_ytvideothumbnail_weekend_by_size(
    $which_weekend_program='debate',
    $if_videopart_n = 1,
    $size='DF'
  ) {
    // 'ytvideoids_debate', 'ytvideoids_responde'
    $ytvideo_11char_weekend = $this->get_ytvideo_11char_weekend($which_weekend_program, $if_videopart_n);
    return UtilParams::get_ytvideothumbnailurl_by_11char_n_size($ytvideo_11char_weekend, $size);
  } // ends get_ytvideothumbnail_weekend_by_size()

  public function get_ytvideothumbnailurl_via_1stprof_by_size($size='DF') {
    foreach ($this->professores as $professor) {
      $thumbnail_photo_url = $professor->get_profs_yt_thumbnail_photo_url($size);
      if ($thumbnail_photo_url!=null) {
        return $thumbnail_photo_url;
      }
    }
    // If nothing was found above, fallback to the method below
    return $this->get_ytvideothumbnailurl_by_size($size);
  } // ends get_ytvideothumbnailurl_via_1stprof_by_size()

  public function get_ytvideothumbnailurl_by_size($size='DF') {
    /*
    Sizes are:
      DF (Default),
      HQ (High Quality), MQ (Medium Quality),
      SD (Standard Quality), MX (Max Quality)
    */
    $primeira_aula = $this->aulas->first();
    if ($primeira_aula == null) {
      return '';
    }
    $n_ord_part_just_in_case = 1;
    $img_url = $primeira_aula->get_ytvideothumbnailurl_by_size($n_ord_part_just_in_case, $size);
    return $img_url;
  }

  public function get_lecture_titles_as_one_text() {
    $text = '';
    foreach ($this->aulas as $aula) {
      $text .= '[' . $aula->n_ord_aula . '] ' . $aula->title . '; ';
    }
    return $text;
  }

  public function get_aula_n($n_ord_aula) {
    return $this->aulas->where('n_ord_aula', $n_ord_aula)->first();
  }

  public function get_aula_by_n_ord($n_ord_aula) {
    /*
      This method was created before the one above.
      Please, use the one above, this one may get deprecated in the future.
    */
    if ($n_ord_aula == null || $n_ord_aula < 0) {
      return null;
    }
    $n_total_aulas = $this->aulas->count();
    if ($n_ord_aula > $n_total_aulas) {
      return null;
    }
    $index = $n_ord_aula - 1;
    $aula = $this->aulas[$index];
    return $aula;
  }



  public function aulas() {
    return $this->hasMany('App\Models\SabDirModels\SabDirAula', 'sabdircurso_id');
  }

  public function knowledgeareas() {
    return $this->belongsToMany(
      'App\Models\AcadModels\KnowledgeArea',
      'sabdircurso_knowledgearea',
      'sabdircurso_id',
      'knowledgearea_id'
    );
  }

  public function professores() {
    return $this->belongsToMany(
      'App\Models\SabDirModels\SabDirProfessor',
      'sabdircurso_sabdirprofessor',
      'sabdircurso_id',
      'sabdirprofessor_id'
    );
  }

} // ends class SabDirCurso extends Model
