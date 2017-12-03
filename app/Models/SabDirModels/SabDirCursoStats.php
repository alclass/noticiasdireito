<?php
namespace App\Models\SabDirModels;
// use App\Models\SabDirModels\SabDirCursoStats;

use App\Models\AcadModels\KnowledgeArea;
use App\Models\SabDirModels\SabDirCurso;
use App\Models\Util\DateUtil;
use App\Models\Util\UtilParams;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SabDirCursoStats {

  public $total_de_cursos;
  private $first_curso;
  private $last_curso;
  private $lectures_avg_duration_in_min;
  private $n_cursos_sem_5_aulas;
  private $total_sddebates;
  private $total_sdrespondem;
  private $n_semanas_total;
  private $total_de_professores;
  private $n_de_professores_f;
  private $n_de_professores_m;
  private $total_de_aulas;
  private $total_de_ramos;

  public function __construct() {
    $this->total_de_cursos = SabDirCurso::count();
    // The attributes below will be fetched or calculated on demand (lazy-charge)
    $this->last_curso      =  null;
    $this->first_curso     =  null;
    $this->lectures_avg_duration_in_min = null;
    $this->n_cursos_sem_5_aulas = null;
    $this->total_sddebates = null;
    $this->total_sdrespondem = null;
    $this->n_semanas_sem_cursos = null;
    $this->n_semanas_total = null;
    $this->total_de_professores = null;
    $this->n_de_professores_f = null;
    $this->n_de_professores_m = null;
    $this->total_de_aulas     = null;
    $this->total_de_ramos     = null;
  }

  public function db_is_not_empty() {
    if ($this->first_curso == null) {
      return false;
    }
    return true;
  }

/*
$total_de_ramos  = $curso_stats->get_total_de_ramos();
$total_de_aulas  = $curso_stats->get_total_de_aulas();
$total_de_professores = $curso_stats->get_total_de_professores();
*/

public function get_total_de_ramos() {
  if ($this->total_de_ramos!=null) {
    return $this->total_de_ramos;
  }
  $this->total_de_ramos = KnowledgeArea::count();
  return $this->total_de_ramos;
}


  public function get_total_de_aulas() {
    if ($this->total_de_aulas!=null) {
      return $this->total_de_aulas;
    }
    $this->total_de_aulas = SabDirAula::count();
    return $this->total_de_aulas;
  }

  public function get_first_curso() {
    if ($this->first_curso!=null) {
      return $this->first_curso;
    }
    $this->first_curso = SabDirCurso::fetch_first_course();
    return $this->first_curso;
  }

  public function get_last_curso() {
    if ($this->last_curso!=null) {
      return $this->last_curso;
    }
    $this->last_curso = SabDirCurso::fetch_most_recent_course();
    return $this->last_curso;
  }

  public function get_firstcourse_emissiondate() {
    $first_curso = $this->get_first_curso();
    if ($first_curso == null || $first_curso->firstemissiondate==null) {
      $emissiondate = new Carbon('2008-08-11');
    }
    else {
      $emissiondate = $first_curso->firstemissiondate;
    }
    return $emissiondate;
  }

  public function get_lastcourse_emissiondate() {
    $last_curso = $this->get_last_curso();
    if ($last_curso == null || $last_curso->firstemissiondate==null) {
      $emissiondate = Carbon::now();
    }
    else {
      $emissiondate = $last_curso->firstemissiondate;
    }
    return $emissiondate;
  }

  public function calc_history_timespan_in_ys_ms_ds() {
    $firstcourse_emissiondate = $this->get_firstcourse_emissiondate();
    $lastcourse_emissiondate  = $this->get_lastcourse_emissiondate();

    return DateUtil::calc_history_timespan_in_ys_ms_ds(
      $firstcourse_emissiondate,
      $lastcourse_emissiondate
    );
  } // ends calc_history_timespan_in_ys_ms_ds()

  public function find_sd_history_timespan_in_weeks() {

    if ($this->last_curso != null && $this->last_curso->firstemissiondate != null) {
      $last_curso_date = $this->last_curso->firstemissiondate;
      if ($this->first_curso != null && $this->first_curso->firstemissiondate != null) {
        // Calculate time span between first and last courses
        $first_curso_date = $this->first_curso->firstemissiondate;
        $n_weeks = $last_curso_date->diffInWeeks($first_curso_date);
        return $n_weeks;
      }
    }
    return 0;
  } // ends ()

  public function find_n_semanas_sem_cursos() {
    if ($this->n_semanas_sem_cursos == null) {
      $this->n_semanas_total = $this->find_sd_history_timespan_in_weeks();
      $this->n_semanas_sem_cursos = $this->n_semanas_total - $this->total_de_cursos;
      // TO-DO: test if decrease 1 here is correct!
      $this->n_semanas_sem_cursos -= 1;
    }
    return $this->n_semanas_sem_cursos;
  } // ends find_n_semanas_sem_cursos()

  public function find_percent_semanas_sem_cursos() {

    if ($this->n_semanas_total == 0) {
      return 0;
    }
    $percent_semanas_sem_cursos = intval(round($this->n_semanas_sem_cursos * 100 / $this->n_semanas_total, 0));
    return $percent_semanas_sem_cursos;
  } // ends find_percent_semanas_sem_cursos()

  public function find_n_cursos_com_5_aulas() {
    /*
    This doesn't need to be 'cached' as an attribute,
    because the percent calculation is not asked after
    */
    return SabDirCurso::all()->where('n_aulas', 5)->count();
  } // ends find_n_cursos_com_5_aulas()


  public function find_n_cursos_sem_5_aulas() {
    /*
    This goes 'cached' as an attribute,
    because, differently from its com_5_aulas counterpart above,
    the percent calculation is asked after
    */
    if ($this->n_cursos_sem_5_aulas != null) {
      return $this->n_cursos_sem_5_aulas;
    }
    $this->n_cursos_sem_5_aulas = SabDirCurso
      ::all()->where('n_aulas', '!=', 5)->count();
    return $this->n_cursos_sem_5_aulas;

  } // ends ()

  public function find_percent_cursos_sem_5_aulas() {
     $n_cursos_sem_5_aulas = $this->find_n_cursos_sem_5_aulas();
     return intval(round($n_cursos_sem_5_aulas * 100 / $this->total_de_cursos,0));
  } // ends find_percent_cursos_sem_5_aulas()

  public function find_n_cursos_no_ano($ano) {
    $last_day_of_previous_year_as_date = DateUtil::get_last_day_of_previous_year_as_date($ano);
    $first_day_of_next_year_as_date    = DateUtil::get_first_day_of_next_year_as_date($ano);
    return SabDirCurso
     ::where('firstemissiondate', '>', $last_day_of_previous_year_as_date)
     ->where('firstemissiondate', '<', $first_day_of_next_year_as_date)
     ->count();
  } // ends find_n_cursos_no_ano()

  public function find_total_de_professores() {
    if ($this->total_de_professores == null) {
      $this->total_de_professores = SabDirProfessor::count();
    }
    return $this->total_de_professores;
  } // ends find_total_de_professores()

  public function calc_lectures_avg_duration_in_min() {
    if ($this->lectures_avg_duration_in_min == null) {
      $this->lectures_avg_duration_in_min =  intval(round(SabDirAula::avg('duration_in_min'),0));
    }
    return $this->lectures_avg_duration_in_min;
  } // ends find_lectures_avg_duration_in_min()

  public function find_total_sddebates() {
    if ($this->total_sddebates == null) {
      $this->total_sddebates    = SabDirCurso::where('ytvideoids_debate', '!=', null)->count();
    }
    return $this->total_sddebates;
  } // ends find_total_sddebates()

  public function find_percent_sddebates() {
    return intval(round(100 * $this->total_sddebates / $this->total_de_cursos));
  } // ends find_percent_sddebates()

  public function find_total_sdrespondem() {
    if ($this->total_sdrespondem == null) {
      $this->total_sdrespondem = SabDirCurso::where('ytvideoids_responde', '!=', null)->count();
    }
    return $this->total_sdrespondem;
  } // ends find_total_sddrespondem()

  public function find_percent_sdrespondem() {
    return intval(round(100 * $this->total_respondem / $this->total_de_cursos));
  } // ends find_percent_sdrespondem()

  public function find_n_com_debate_sem_responde() {
    $total_sddrespondem = SabDirCurso
      ::where('ytvideoids_debate',  '!=', null)
      ->where('ytvideoids_responde', '=', null)
      ->count();
    return $total_sddrespondem;
  } // ends find_n_com_debate_sem_responde()

  public function find_n_com_responde_sem_debate() {
    $total_sddrespondem = SabDirCurso
      ::where('ytvideoids_responde', '!=', null)
      ->where('ytvideoids_debate',   '=',  null)
      ->count();
    return $total_sddrespondem;
  } // ends find_n_com_responde_sem_debate()

  public function find_n_sem_debate_sem_responde() {
    $total_sem_debate_sem_responde = SabDirCurso
      ::where('ytvideoids_debate',   '=', null)
      ->where('ytvideoids_responde', '=', null)
      ->count();
    return $total_sem_debate_sem_responde;
  } // ends find_n_com_debate_sem_responde()

  public function find_n_profs_com_mais_de_1_curso() {
    // $n_profs_com_mais_de_1_curso = 0;
    $rows = DB
      ::table('sabdircurso_sabdirprofessor')
      ->select(DB::raw('count(sabdircurso_id) as sabdircurso_id_count, sabdirprofessor_id' ))
      ->groupBy('sabdirprofessor_id')
      ->having('sabdircurso_id_count', '>', 1)
      ->get();
    $n_profs_com_mais_de_1_curso = count($rows);
    return $n_profs_com_mais_de_1_curso;
  } // ends find_n_professores_com_mais_de_um_curso()

  public function get_n_de_professores_do_genero($p_gender) {
    $p_gender = strtolower($p_gender);
    $total_de_professores_do_genero = 0;
    switch ($p_gender) {
      case 'f':
        if ($this->n_de_professores_f != null) {
          return $this->n_de_professores_f;
        }
        $total_de_professores_do_genero = SabDirProfessor
          ::where('is_gender_female', true)
          ->count();
          $this->n_de_professores_f = $total_de_professores_do_genero;
        break;
      case 'm':
        if ($this->n_de_professores_m != null) {
          return $this->n_de_professores_m;
        }
        $total_de_professores_do_genero = SabDirProfessor
          ::where('is_gender_female', false)
          ->count();
          $this->n_de_professores_m = $total_de_professores_do_genero;
        break;
      default:
        $total_de_professores_do_genero = SabDirProfessor
          ::count();
        break;
    }
    return $total_de_professores_do_genero;
  } // ends get_n_de_professores_do_genero()

public function get_total_de_professores() {
  if ($this->total_de_professores != null) {
    return $this->total_de_professores;
  }
  $this->total_de_professores = SabDirProfessor::count();
  return $this->total_de_professores;
} // ends get_total_de_professores()

public function get_percent_de_professores_do_genero($p_gender) {
  $n_profs = $this->get_n_de_professores_do_genero($p_gender);
  $total_de_professores = $this->get_total_de_professores();
  if ($this->total_de_professores == 0) {
    return 0;
  }
  $percent = intval(round($n_profs * 100 / $total_de_professores, 0));
  return $percent;
} // ends get_percent_de_professores_do_genero()

  public function get_n_de_professores_no_ramoprincipal($knowledgearea) {
    if ($knowledgearea==null) {
      return 0;
    }
    return SabDirProfessor
      ::where('main_knowledgearea_id', $knowledgearea->id)
      ->count();
  } // ends get_n_de_professores_no_ramoprincipal()

  public function find_n_cursos_com_mais_de_1_prof() {
    // $n_profs_com_mais_de_1_curso = 0;
    $rows = DB
      ::table('sabdircurso_sabdirprofessor')
      ->select(DB::raw('count(sabdirprofessor_id) as sabdirprofessor_id_count, sabdircurso_id' )) // , sabdirprofessor_id
      ->groupBy('sabdircurso_id')
      ->having('sabdirprofessor_id_count', '>', 1)
      ->get();
    $n_profs_com_mais_de_1_curso = count($rows);
    return $n_profs_com_mais_de_1_curso;
  } // ends find_n_professores_com_mais_de_um_curso()

  public function get_course_at_season($curso_index_at_season, $season_index) {

    /*
      TO-DO:
        In the future, these dates below should be put in a table,
          instead of being hard-coded here.
        This new table might not have a corresponding model and
          a DB:raw() might fetch its data.
    */

    $frontier_dates = [
      '2008-08-11', // 1ª temporada: 1º curso
      '2010-08-23', // 1ª temporada: último curso
      '2010-10-04', // 2ª temporada: 1º curso
      '2013-03-04', // 2ª temporada: último curso
      '2013-02-04', // 3ª temporada: 1º curso
    ];
    $mapped_index = 0;
    /*
    // This one is already the default, no need to check this if
    if ($curso_index_at_season==0 && $season_index==0) {
      $mapped_index = 0;
    }
    */
    if ($curso_index_at_season==-1 && $season_index==0) {
      // 1ª temporada: último curso
      $mapped_index = 1;
    }
    else if ($curso_index_at_season==0 && $season_index==1) {
      // 2ª temporada: 1º curso
      $mapped_index = 2;
    }
    else if ($curso_index_at_season==-1 && $season_index==1) {
      // 2ª temporada: último curso
      $mapped_index = 3;
    }
    else if ($curso_index_at_season==0 && $season_index==2) {
      // 3ª temporada: 1º curso
      $mapped_index = 4;
    }
    else if ($curso_index_at_season==-1 && $season_index==2) {
      // 3ª temporada: último curso
      // return from here, for this curso is the last one in DB and
      // there's no $mapped_index with a previously known date (which week there may be a new one)
      return SabDirCurso::fetch_most_recent_course();
    }
    $frontier_date = $frontier_dates[$mapped_index];
    return SabDirCurso::where('firstemissiondate', $frontier_date)->first();
  } // get_course_at_season()

  public function get_duration_str_for_season($season_index) {
    /*
    $frontier_dates = [
      '2008-08-11', // 1ª temporada: 1º curso
      '2010-08-23', // 1ª temporada: último curso
      '2010-10-04', // 2ª temporada: 1º curso
      '2013-03-04', // 2ª temporada: último curso
      '2013-02-04', // 3ª temporada: 1º curso
    ];
    */
    $dt_season_1_ini = new Carbon('2008-08-11');
    $dt_season_1_fim = new Carbon('2010-08-23');
    $dt_season_2_ini = new Carbon('2010-10-04');
    $dt_season_2_fim = new Carbon('2013-03-04');
    $dt_season_3_ini = new Carbon('2013-02-04');
    $dt_season_3_fim = Carbon::now();
    $curso = SabDirCurso::fetch_most_recent_course();
    if ($curso!=null) {
      $dt_season_3_fim = $curso->firstemissiondate->copy();
    }
    $dt_ini = $dt_season_1_ini;
    $dt_fim = $dt_season_1_fim;
    switch ($season_index) {
      case 1: # index for 2nd season
        $dt_ini = $dt_season_2_ini;
        $dt_fim = $dt_season_2_fim;
        break;
      case 2: # index for 3rd season
        $dt_ini = $dt_season_3_ini;
        $dt_fim = $dt_season_3_fim;
        break;
      default:
        // the default was set above, before 'switch/cases'
        break;
    }

    $history_timespan_in_ys_ms_ds = DateUtil
      ::calc_history_timespan_in_ys_ms_ds($dt_ini, $dt_fim);

    $n_years_in_between  = $history_timespan_in_ys_ms_ds['n_years_in_between'];
    $n_months_in_between = $history_timespan_in_ys_ms_ds['n_months_in_between'];
    $n_days_in_between   = $history_timespan_in_ys_ms_ds['n_days_in_between'];


    $duration_str_for_season = '';
    if ($n_years_in_between > 0){
      $duration_str_for_season .= $n_years_in_between . ' anos';
    }
    if ($n_months_in_between > 0){
      if (strlen($duration_str_for_season) > 0){
        $duration_str_for_season .= ', ' . $n_months_in_between . ' meses';
      }
    }
    if ($n_days_in_between > 0){
      if (strlen($duration_str_for_season) > 0){
        $duration_str_for_season .= ', ' . $n_days_in_between . ' dias ';
      }
    }

    return $duration_str_for_season;
  } // get_duration_str_for_season()

  public function check_n_temporada_n_default_it_ifneeded($n_temporada) {
    $n_temporada = intval($n_temporada);
    if ($n_temporada < 1 || $n_temporada > 3) {
      $n_temporada = 3;
    }
    return $n_temporada;
  }

  public function find_n_cursos_na_temporada($n_temporada) {
    $n_temporada = $this->check_n_temporada_n_default_it_ifneeded($n_temporada);
    $ini_n_fim_dates = UtilParams::get_ini_n_fim_de_temporada_as_datearray($n_temporada);
    $ini_date = $ini_n_fim_dates[0];
    $fim_date = $ini_n_fim_dates[1];
    if ($fim_date==null) {
      $fim_date = Carbon::now();
    }
    return SabDirCurso
      ::where('firstemissiondate', '>=', $ini_date)
      ->where('firstemissiondate', '<=', $fim_date)
      ->count();
  } // find_n_cursos_na_temporada()

  public function get_dateini_of_temporada_as_str($n_temporada) {
    $n_temporada = $this->check_n_temporada_n_default_it_ifneeded($n_temporada);
    $ini_n_fim_dates = UtilParams::get_ini_n_fim_de_temporada_as_datearray($n_temporada);
    $date = $ini_n_fim_dates[0];
    if ($date==null) {
      $date = Carbon::now();
    }
    return $date->format('d/m/Y');
  } // get_dateini_of_temporada_as_str()

  public function get_last_courses_date() {
    if ($this->last_curso==null) {
      return Carbon::now();
    }
    if ($this->last_curso->firstemissiondate==null) {
      return Carbon::now();
    }
    return $this->last_curso->firstemissiondate->copy();
  } // get_last_courses_date()

  public function get_datefim_of_temporada_as_str($n_temporada) {
    $n_temporada = $this->check_n_temporada_n_default_it_ifneeded($n_temporada);
    $ini_n_fim_dates = UtilParams::get_ini_n_fim_de_temporada_as_datearray($n_temporada);
    $datefim = $ini_n_fim_dates[1];
    if ($datefim==null) {
      $datefim = $this->get_last_courses_date();
    }
    return $datefim->format('d/m/Y');
  } // get_dateini_of_temporada_as_str()

  public function does_db_have_the_seasons_courses() {

    $frontier_dates = [
      '2008-08-11', // 1ª temporada: 1º curso
      '2010-08-23', // 1ª temporada: último curso
      '2010-10-04', // 2ª temporada: 1º curso
      '2013-03-04', // 2ª temporada: último curso
      '2013-02-04', // 3ª temporada: 1º curso
    ];
    $boolean_result = true;
    foreach ($frontier_dates as $frontier_date) {
      $does_exist = SabDirCurso
        ::where('firstemissiondate', $frontier_date)->exists();
      $boolean_result = $boolean_result && $does_exist;
    }
    return $boolean_result;
  }

  public function get_duracao_historica_do_sd_str() {
    $duracao_historica_do_sd_str = '0 anos, 0 meses, 0 dias';
    $curso     = SabDirCurso::fetch_first_course();
    if ($curso == null || $curso->firstemissiondate == null) {
      return $history_timespan_in_ys_ms_ds = '0 anos, 0 meses, 0 dias';
    }
    $startdate = $curso->firstemissiondate;
    $curso     = SabDirCurso::fetch_most_recent_course();
    if ($curso == null || $curso->firstemissiondate == null) {
      $enddate = Carbon::now();
    }
    else {
      $enddate   = $curso->firstemissiondate;
    }

    $history_timespan_in_ys_ms_ds = DateUtil
      ::calc_history_timespan_in_ys_ms_ds($startdate, $enddate);

    $n_years_in_between  = $history_timespan_in_ys_ms_ds['n_years_in_between'];
    $n_months_in_between = $history_timespan_in_ys_ms_ds['n_months_in_between'];
    $n_days_in_between   = $history_timespan_in_ys_ms_ds['n_days_in_between'];

    $duracao_historica_do_sd_str = ''
      . $n_years_in_between  . ' anos, '
      . $n_months_in_between . ' meses, '
      . $n_days_in_between   . ' dias.';

    return $duracao_historica_do_sd_str;
  } // ends get_duracao_historica_do_sd_str()

} // ends class SabDirCursoStats
