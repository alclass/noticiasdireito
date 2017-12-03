<?php
namespace App\Models\AcadModels;
// use App\Models\AcadModels\KnowledgeArea;

use App\Http\Controllers\SabDirControllers\SabDirCursoController;
use App\Models\SabDirModels\SabDirCurso;
use App\Models\SabDirModels\SabDirCursoWithKnowledgeArea;
use App\Models\Util\StringUtil;
/*
  Attention for developers who will create/develop methods here:
  ==============================================================
  * Class KnowledgeAreaFetcher was created to avoid cross importation
    between this class (KnowledgeArea) and class KAreaClosureTable.
  * So if it's needed to get depth, paths or subtrees, write the
    intentioned methods in class KnowledgeAreaFetcher and then
    create the method here returning the KnowledgeAreaFetcher
    method's return value.
*/
use App\Models\AcadModels\KnowledgeAreaFetcher;
use App\Models\AcadModels\KnowledgeAreaDescriptions;
use Illuminate\Database\Eloquent\Model;

class KnowledgeArea extends Model {

  const ROOTS_PARENT_CONVENTIONED_ID = null;
  const ROOT_CONVENTIONED_ID = 1;
  const ROOT_CONVENTIONED_NAME = 'Direito';

  public static function get_root_knowledgearea() {
    $root_knowledgearea = self::find(self::ROOT_CONVENTIONED_ID);
    if ($root_knowledgearea == null) {
      $root_knowledgearea       = new KnowledgeArea;
      $root_knowledgearea->name = self::ROOT_CONVENTIONED_NAME;
      $root_knowledgearea->id   = self::ROOT_CONVENTIONED_ID;
      $root_knowledgearea->parent_ka_id = self::ROOTS_PARENT_CONVENTIONED_ID;
    }
    return $root_knowledgearea;
  }

  public static function fetch_second_level_knowledgeareas() {
    $root_knowledgearea = self::get_root_knowledgearea();
    $n_level = 2;
    $second_level_knowledgeareas = $root_knowledgearea
      ->fetch_level_knowledgeareas_as_objs($n_level);
    // $second_level_knowledgeareas->sortBy('name');
    return $second_level_knowledgeareas;
  }

  public static function get_first_second_level_knowledgearea() {
    $karea = self::where('parent_ka_id', self::ROOT_CONVENTIONED_ID)->first();
    return $karea;
  }

  public static function get_2ndlevel_knowledgearea_from_kareaid($knowledgearea_id) {
    if ($knowledgearea_id < 1) {
      return self::get_first_second_level_knowledgearea();
    }
    if ($knowledgearea_id == self::ROOT_CONVENTIONED_ID) {
      return self::get_first_second_level_knowledgearea();
    }
    $karea = self::find($knowledgearea_id);
    if ($karea==null) {
      return self::get_first_second_level_knowledgearea();
    }
    return $karea->get_parent_second_level_karea_or_itself_or_root();
  }

  private $ka_fetcher = null;  // instance of class KnowledgeAreaFetcher
  protected $table    = 'knowledgeareas';
  protected $fillable = [
    'parent_ka_id', 'name', 'short_description', 'wiki_url',
	];

  protected $attributes = [
    'leveln', 'parentname', 'routeurl',
	];

  /*
  public function get_id() {
    return $this->id;
  }
  */

  public function am_i_root() {
    if ($this->id == self::ROOT_CONVENTIONED_ID) {
      return true;
    }
    return false;
  }

  public function getLevelnAttribute() {
    return count($this->fetch_path_from_root_to_karea_as_ids());
  }

  public function getRouteurlAttribute() {
    return $this->generate_url_id_dash_phrase();
  }

  public function getParentnameAttribute() {
    //return 'kaname';
    if ($this->am_i_root()==true) {
      return '[O ramo raiz não possui sobreárea.]';
    }
    /*
    $parent = self::find($this->parent_ka_id);
    */
    if ($this->parent!=null) {
      return $this->parent->name;
    }
    return 'não encontrado';
  }

  public function am_i_not_root() {
    return !($this->am_i_root());
  }

  public function get_parent() {
    if ($this->parent_ka_id == self::ROOTS_PARENT_CONVENTIONED_ID) {
      // in this case, $this here is root (self::get_root_knowledgearea()_
      // root's parent is null by convention
      return null;
    }
    $parent_ka = self::find($this->parent_ka_id);
    return $parent_ka;
  }

  public function get_parent_second_level_kareaid_or_itsownid() {
    if ($this->leveln < 3) {
      return $this->id;
    }
    $ids = $this->fetch_path_from_root_to_karea_as_ids();
    if (count($ids) < 3) {
      return self::ROOT_CONVENTIONED_ID;
    }
    return $ids[1];
  }

  public function get_parent_second_level_karea_or_itself_or_root() {
    /*
      It should return 'itself' when either it's root itself
        or it's already a second level karea itself
      In case DB has flawed data, this method may return root
        (root may be dynamically generated if without DB)
    */
    if ($this->leveln < 3) {
      return $this;
    }
    $ids = $this->fetch_path_from_root_to_karea_as_ids();
    if (count($ids) < 3) {
      return $this;
    }
    $second_level_karea_or_root = self::find($ids[1]);
    if ($second_level_karea_or_root == null) {
      return self::get_root_knowledgearea();
    }
    return $second_level_karea_or_root;
  }

  public function get_parent_second_level_kareaname_or_itself() {
    $second_level_karea = $this->get_parent_second_level_karea_or_itself();
    if ($second_level_karea == null) {
      return '';
    }
    return $second_level_karea->name;
  }

  public function get_instance_root_knowledgearea() {
    return self::get_root_knowledgearea();
  }

  public function get_total_of_knowledgeareas() {
    return self::count();
  }

  public function set_ka_fetcher_once() {
    if ($this->ka_fetcher != null) {
      return;
    }
    $this->ka_fetcher = new KnowledgeAreaFetcher($this, self::ROOT_CONVENTIONED_ID);
  }

  public function generate_url_id_dash_phrase() {
    /*
    The $curso_phraseid
    */
    $karea_phraseid = '' . $this->id;
    $karea_phraseid .= '-' . StringUtil::convert_phrase_to_nonaccented_url_piecepath($this->name);
    return $karea_phraseid;
  }

  public function get_breve_descricao() {
    $breve_descricao_texto = '';
    if ($this->leveln > 2) {
      $breve_descricao_texto = 'É o ramo SD2 de temas de '
        . $this->parentname . ' (*) que tratam de assuntos em '
        . $this->name . '.';
      return $breve_descricao_texto;
    }
    return KnowledgeAreaDescriptions::get_hardcoded_breve_descricao($this->name);
    // return $this->short_description;
  }

  public function get_siblings() {
    // siblings (brothers and sisters) are knowledge areas at the same depth
    $this->set_ka_fetcher_once();
    return $this->ka_fetcher->get_siblings();
  }

  public function get_children_knowledgeareas() {
    return self::where('parent_ka_id', $this->id)
      ->orderBy('name')
      ->get();
  }

  public function find_n_direct_courses() {
    /*
      The count... method elsewhere here counts
        all courses under the subtree.
      This method, otherwise, returns only courses directly
        attached to the underlying knowledge area (ie, $this).
    */
    return SabDirCursoWithKnowledgeArea::find_n_direct_courses($this->id);
  }

  public function get_first_course() {
    $ids = SabDirCursoWithKnowledgeArea
      ::fetch_sabdircurso_ids_from_knowledgearea_id($this->id);
    if (!empty($ids)) {
      return SabDirCurso::findOrFail($ids[0]);
    }
    return null;
  }

  public function fetch_level_knowledgeareas_as_ids($n_level=1) {
    $this->set_ka_fetcher_once();
    return $this->ka_fetcher->fetch_level_knowledgeareas_as_ids($n_level);
  }

  public function fetch_level_knowledgeareas_as_objs($n_level=1) {
    $this->set_ka_fetcher_once();
    return $this->ka_fetcher->fetch_level_knowledgeareas_as_objs($n_level);
  }

  public function fetch_path_from_root_to_karea_as_ids() {
    $this->set_ka_fetcher_once();
    return $this->ka_fetcher->fetch_path_from_root_to_karea_as_ids($this->id);
  }

  public function fetch_path_from_root_to_karea_as_objs() {
    $this->set_ka_fetcher_once();
    return $this->ka_fetcher->fetch_path_from_root_to_karea_as_objs($this->id);
  }

  public function fetch_path_from_root_excluded_to_karea_as_objs() {
    $this->set_ka_fetcher_once();
    return $this->ka_fetcher->fetch_path_from_root_excluded_to_karea_as_objs($this->id);
  }

  public function fetch_reversed_path_from_root_excluded_to_karea_as_objs() {
    $collection = $this->fetch_path_from_root_excluded_to_karea_as_objs();
    if (count($collection)>0) {
      $collection->reverse();
    }
    return $collection;
  }

  public function fetch_path_from_root_to_kareaparent_as_objs() {
    $this->set_ka_fetcher_once();
    return $this->ka_fetcher->fetch_path_from_root_to_kareaparent_as_objs($this->id);
  }

  public function get_1st_knowledgearea_downroot_or_root() {
    if ($this->id == self::ROOT_CONVENTIONED_ID) {
      return $this; // instance is root
    }
    if ($this->parent_ka_id == self::ROOT_CONVENTIONED_ID) {
      return $this; // instance is a 1st karea downroot
    }
    $karea_levels = $this->fetch_path_from_root_to_kareaparent_as_objs();
    if (count($karea_levels) < 2) {
      // oh oh, if this ever happens, weird-o (the if's above guarantee array size is >= 3)
      // anyway, return root_knowledgearea
      return self::get_root_knowledgearea();
    }
    return $karea_levels[1];
  } // ends get_1st_knowledgearea_downroot_or_root()

  public function total_subtree_knowledgeareas() {
    return count($this->fetch_subtree_knowledgeareas_as_ids()) - 1;
  } // ends total_subtree_knowledgeareas()

  public function fetch_subtree_knowledgeareas_as_ids() {
    $this->set_ka_fetcher_once();
    return $this->ka_fetcher->fetch_subtree_knowledgeareas_as_ids($this->id);
  }

  public function fetch_subtree_knowledgeareas_as_objs() {
    $this->set_ka_fetcher_once();
    return $this->ka_fetcher->fetch_subtree_knowledgeareas_as_objs($this->id);
  }

  public function get_piece_sep_knowledgearea_path($separator=' / ') {
    $path_from_root_to_karea_as_objs = $this->fetch_path_from_root_to_karea_as_objs();
    if ($path_from_root_to_karea_as_objs == null or count($path_from_root_to_karea_as_objs)==0){
      return 'n/a';
    }
    $karea_names = [];
    foreach ($path_from_root_to_karea_as_objs as $knowledgearea) {
      $karea_names[] = $knowledgearea->name;
    }
    $knowledgearea_piece_sep_path = implode($separator, $karea_names);
    $knowledgearea_piece_sep_path = $separator . $knowledgearea_piece_sep_path;
    return $knowledgearea_piece_sep_path;
  } // ends get_piece_sep_knowledgearea_path()

  public function count_n_courses_under_subtree_knowledgeareas() {
    /*
      The logic in here is not fully correct, because a course may have
      more than one related knowledge area.  If this is so, a curso will
      count more than once and reflect in the number of course upper
      knowledge areas have in total.

      On the other hand, we only want to adjust the total courses
      in the root knowledge area. That seems fair.

      Notice to a developer in the future who may want to correct this,
        we have just here corrected the summing issue for the root knowledge area.
      (If a double-checking count is done, the different must presumably be those courses
       in more than one knowledge area.)

    */
    if ($this->id == self::ROOT_CONVENTIONED_ID) {
      return SabDirCurso::count();
    }
    $ids = $this->fetch_subtree_knowledgeareas_as_ids();
    return SabDirCursoWithKnowledgeArea
      ::whereIn('knowledgearea_id', $ids)
      ->count();
  }

  public function fetch_knowledgearea_courses($n_pagination=null) {
    /*
      sabdircursos returned is 'paginateable'
    */
    return SabDirCursoWithKnowledgeArea
      ::fetch_sabdircursos_from_knowledgearea_id($this->id);
  }

  public function fetch_courses_under_subtree_knowledgeareas($n_pagination=null) {
    if ($n_pagination==null) {
      $n_pagination = SabDirCursoController::get_n_pagination();
    }
    $ids = $this->fetch_subtree_knowledgeareas_as_ids();
    $course_ids = [];
    $sabdircurso_n_knowledgearea_rows = SabDirCursoWithKnowledgeArea
      ::whereIn('knowledgearea_id', $ids)->get();
    foreach ($sabdircurso_n_knowledgearea_rows as $sabdircurso_n_knowledgearea_row) {
      $course_ids[] = $sabdircurso_n_knowledgearea_row->sabdircurso_id;
    }
    return SabDirCurso::whereIn('id', $course_ids)
      ->orderBy('firstemissiondate', 'desc')->paginate($n_pagination);
    // return SabDirCurso::find($course_ids);
  } // ends fetch_courses_under_subtree_knowledgeareas()

  public function parent() {
    return $this->belongsTo('App\Models\AcadModels\KnowledgeArea', 'parent_ka_id');
  }

} // ends class KnowledgeArea extends Model

/*
  These 2 commented-out methods belong to the old strategy to
  maintain paths and subtree, when they were the following two fields:
      // 'slash_sep_path_from_root_to_node',
      // 'slash_sep_list_encompassing_subtree',

  These were retired after introducing the Closure Table technique.


  public function get_uptree_knowledge_areas() {
    $slash_sep_ids = $this->knowledgearea_slash_sep_pathids;
    if ($slash_sep_ids == null) {
      return [];
    }
    $ids = explode('/', $slash_sep_ids);
    // CHECK if it's necessary to convert string to int here !!! (ids each will string above)
    $uptree_knowledge_areas = KnowledgeArea::find($ids);
    return $uptree_knowledge_areas;
  }

  public function get_knowledge_area_namepath($sep = '/') {
    $uptree_knowledge_areas = $this->get_uptree_knowledge_areas();
    $names = [];
    foreach ($uptree_knowledge_areas as $uptree_knowledge_area) {
      $names[] = $uptree_knowledge_area->name;
    }
    $namepath = join($sep, $names);
    return $namepath;
  }
*/
