<?php
namespace App\Models\SabDirModels;
// use App\Models\SabDirModels\SabDirAula;

use App\Models\AcadModels\KnowledgeArea;
use App\Models\SabDirModels\SabDirAulaAux;
use App\Models\Util\MathUtil;
use App\Models\Util\StringUtil;
use App\Models\Util\UtilParams;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SabDirAula extends Model {

  public static function fetch_aula_by_cursodate_n_nord($firstemissiondate, $n_ord_aula) {
    // look up controller
  }

  public static function fetch_random_lecture() {
    /*
      fetch_random_lecture()
    */
    $total_aulas = self::count();
    $n_drawn = rand(0, $total_aulas-1);
    return self::skip($n_drawn)->first();
  }

  protected $table = 'sabdiraulas';
  protected $appends = ['lecturedate', 'routeurl_as_array']; // lecturedate is dependant on course's firstemissiondate
  // protected $dates = ['firstemissiondate'];

  protected $fillable = [
    'title', 'n_ord_aula', 'duration_in_min',
    'assunto', 'textossinopse',
    'artigo_relpath', 'resenha_relpath', 'resumo_relpath', 'transcricao_relpath',
    'nota_voto_editor', 'nota_voto_popular',
    'logoimagepath', 'ytvideochar11id', 'ytvideoalternativechar11id',
    'is_multipartvideo',
	];

  public function getLecturedateAttribute() {

    if ($this->curso->firstemissiondate != null) {
      $forward_n_days = $this->n_ord_aula - 1;
      return $this->curso->firstemissiondate->copy()->addDays($forward_n_days);
    }
    return null;
  } // ends getLecturedateAttribute()

  public function getRouteurlAsArrayAttribute() {
    $routeurl_as_array = [];
    if ($this->curso!=null && $this->curso->firstemissiondate!=null) {
      $routeurl_as_array[] = $this->curso->firstemissiondate->format('Y-m-d');
      $routeurl_as_array[] = $this->generate_urlphrasepiece();
      return $routeurl_as_array;
    }
    return null;
  } // ends getLecturedateAttribute()

  public function get_lecture_professor() {
    /*
    A course may have more than 1 professor,
    but the lecture has ONLY 1 professor.
    */

    if ($this->professor == null) {
      return $this->curso->get_1st_professor();
    }
    return $this->professor;
  } // ends get_lecture_professor()

  public function get_professor_fullname() {
    /*
      Depends on get_lecture_professor() above
    */
    $professor = $this->get_lecture_professor();
    if ($professor == null) {
      return 'n/a';
    }
    return $professor->fullname;
  } // ends get_professor_name()

  public function get_professor_first_n_lastnames_str() {
    /*
      Depends on get_lecture_professor() above
    */
    $professor = $this->get_lecture_professor();
    if ($professor == null) {
      return 'n/a';
    }
    return $professor->get_first_n_lastnames_str();
  } // ends get_professor_first_n_lastnames_str()


  public function get_knowledgeareas_or_root_in_array() {
    if ($this->curso->knowledgeareas->count() > 0) {
      return $this->curso->knowledgeareas;
    }
    $knowledgearea = KnowledgeArea::get_root_knowledgearea();
    $knowledgeareas = collect();
    $knowledgeareas->push($knowledgearea);
    return $knowledgeareas;
  }

  public function cursodate_as_10char_ymd() {
    return $this->curso->firstdate_as_10char_ymd();
  }

  public function generate_urlphrasepiece() {
    /*
    The $aula_phraseid
    */
    $aula_phraseid = '' . $this->n_ord_aula;
    $aula_phraseid .= '-' . StringUtil::convert_phrase_to_nonaccented_url_piecepath($this->title);
    // $aula_phraseid .= '-' . $this->curso->firstemissiondate->format('Ymd');
    return $aula_phraseid;
  }

  public function generate_localservers_thumbnail_aarray($thumbnailsize='DF') {
    /*
      This method depends on:
        SabDirAulaAux::get_localservers_imgurl_withparams_date_nord_n_size()
      That above mentioned method has the interpolating base string
        that leads to the thumbnail images in the local server html-laravel-public folder
    */
    $curso_title   = $this->curso->title;
    $aula_title    = $this->title;
    $n             = $this->n_ord_aula;
    $aula_routeurl = route('aularoute', $this->routeurl_as_array);
    $aula_datestr       = 'n/a';
    if ($this->lecturedate==null) {
      $aarray = [];
      $aarray['aula_routeurl'] = $aula_routeurl;
      $aarray['aula_title']    = $this->title;
      $aarray['curso_title']   = $curso_title;
      $aarray['aula_img_url']  = $aula_img_url;
      $aarray['aula_img_alt']  = $aula_img_alt;
      return $aarray;
    }
    $aula_datestr  = $this->lecturedate->format('d/m/Y');
    $curso_sqldate = '';
    if ($this->curso->firstemissiondate!=null) {
      $curso_sqldate = $this->curso->firstemissiondate->format('Y-m-d');
    }
    $aula_img_url  = SabDirAulaAux::get_localservers_imgurl_withparams_date_nord_n_size($sqldate, $n, $thumbnailsize);
    $aula_img_alt  = "Aula $n do curso $curso_title intitulada $aula_title, de $datestr";
    $aarray = [];
    $aarray['aula_routeurl'] = $aula_routeurl;
    $aarray['aula_title']    = $this->title;
    $aarray['curso_title']   = $curso_title;
    $aarray['aula_img_url']  = $aula_img_url;
    $aarray['aula_img_alt']  = $aula_img_alt;
    return $aarray;
  }

  public function get_relpath_to_lectures_synopsis() {
    /*
    Eg.:
      /material-complementar/2017/1030/sinopse-aula-1.html
      /material-complementar/2014/0205/sinopse-aula-4.html
      (...)
    */
    if ($this->curso==null) {
      return null;
    }
    if ($this->curso->firstemissiondate==null) {
      return null;
    }
    $relpath = 'material-complementar/';
    //$relpath .= $this->curso->firstemissiondate->year . '/';
    $relpath .= $this->curso->firstemissiondate->format('Y/md');
    // $relpath .= $this->curso->firstemissiondate->day;
    $n_aula = $this->n_ord_aula;
    $relpath .= '/' . "sinopse-aula-$n_aula.html";
    return $relpath;
  }

  public function get_anterior_aula_if_any() {
    if ($this->n_ord_aula == 1) {
      return null;
    }
    $previous_aula = $this->curso->get_aula_by_n_ord($this->n_ord_aula - 1);
    return $previous_aula;
  }

  public function get_proxima_aula_if_any() {
    $next_aula = $this->curso->get_aula_by_n_ord($this->n_ord_aula + 1);
    return $next_aula;
  }

  public function find_ytvideo11char_by_nordpart($n_ord_part=null) {

    if ($this->is_multipartvideo == false) {
      return $this->ytvideochar11id;
    }
    // /From here, $this->is_multipartvideo is true
    if ($this->videoparts->count() > 0) {
      if ($n_ord_part == null) {
        $n_ord_part = 1;
      }
      $multipartvideo = $this->get_videopart_by_n_ord($n_ord_part);
      if ($multipartvideo == null) {
        return null;
      }
      return $multipartvideo->ytvideochar11id;
    }
    // This is sort of last try, in general, if is_multipartvideo is true, ytvideochar11id in aula is null
    return null; // it may return null from here
  } // ends find_ytvideo11char_by_nordpart()

  public function get_ytvideothumbnailurl_by_size($n_ord_part=null, $size='DF') {
    $ytvideoid = $this->find_ytvideo11char_by_nordpart($n_ord_part);
    if ($ytvideoid == null) {
      return null;
    }
    $img_url = UtilParams::get_ytvideothumbnailurl_by_11char_n_size($ytvideoid, $size);
    return $img_url;
  } // ends get_ytvideothumbnailurl_by_size()

  public function get_videopart_by_n_ord($n_ord_part) {
    if ($n_ord_part == null) {
      return null;
    }
    if ($n_ord_part < 1) {
      return null;
    }
    if ($n_ord_part > $this->videoparts->count()) {
      return null;
    }
    $collection = $this->videoparts->where('n_ord_part', $n_ord_part);
    if (count($collection) > 0) {
      return $collection->first(); // this returns the model object itself
    }
    return null;
  }

  public function is_there_ytvideoid_available() {
    if ($this->find_ytvideo11char_by_nordpart(1) == null) {
      return false;
    }
    return true;
  }

  public function get_duration_in_min() {

    if ($this->is_multipartvideo == false) {
      if ($this->duration_in_min == null) {
        return 50;
      }
      else {
        return $this->duration_in_min;
      }
    }
    $duration_in_min = 0;
    foreach ($this->videoparts as $videopart) {
      $duration_in_min += $videopart->duration_in_min;
    } // ends foreach
    return $duration_in_min;
  }

  public function get_total_videoparts() {
    if (count($collection) > 0) {
      return $collection->first(); // this returns the model object itself
    }
    return null;
  }

  public function videoparts() {
    return $this->hasMany('App\Models\SabDirModels\MultipartVideoaula', 'sabdiraula_id');
  }

  public function curso() {
    return $this->belongsTo('App\Models\SabDirModels\SabDirCurso', 'sabdircurso_id');
  }

  public function knowledgearea() {
    return $this->belongsTo('App\Models\AcadModels\KnowledgeArea');
  }

  public function professor() {
    return $this->belongsTo('App\Models\SabDirModels\SabDirProfessor', 'sabdirprofessor_id');
  }

} // ends class SabDirAula extends Model
