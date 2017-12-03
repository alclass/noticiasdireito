<?php
namespace App\Models\AcadModels;
// use App\Models\AcadModels\KAreaClosureTableFetcher;

use App\Models\AcadModels\KAreaClosureTable;
use App\Models\AcadModels\KnowledgeArea;

class KAreaClosureTableFetcher {
  /*
    This class contains a repertoire of STATIC methods
    ie, this class is not to be used from an instantiation
  */

  public static function fetch_level_knowledgeareas_as_ids($level_n=1) {
    if ($level_n < 2) {
      return KnowledgeArea::get_root_knowledgearea();
    }
    $n_levels_from_root = $level_n - 1;
    $rows = KAreaClosureTable
      ::where('parent_id', KnowledgeArea::ROOT_CONVENTIONED_ID)
      ->where('depth', $n_levels_from_root)->get();
    $level_ka_ids = [];
    foreach ($rows as $row) {
      $level_ka_ids[] = $row->child_id;
    }
    return $level_ka_ids;
  } // ends fetch_level_knowledgeareas_as_ids()

  public static function fetch_level_knowledgeareas_as_objs($level_n=1) {
    $level_knowledge_areas_as_ids = self::fetch_level_knowledgeareas_as_ids($level_n);
    $level_knowledge_areas_as_objs = KnowledgeArea::find($level_knowledge_areas_as_ids);
    // $level_knowledge_areas_as_objs->sortBy('name');
    return $level_knowledge_areas_as_objs;
  } // ends fetch_level_knowledgeareas_as_objs()

  public static function fetch_subtree_knowledgeareas_as_ids($node_id) {
    /*
      This method fetches all ids that are children of $node_id
      The main application it has here is to make efficient
        an immediate retrieval of a subtree.

      Eg. One wants to query all courses in the 'Direito Civil' branch
        To do so: get_subtree_ka_ids(<dir_civ_karea_id>)
        will retrieve a list (array) of all id's that belong to this subtree for Direito Civil
    */
    $subtree_ka_ids = [];
    $rows = KAreaClosureTable
      ::where('parent_id', $node_id)
      ->orderBy('depth', 'asc')->get();
    foreach ($rows as $row) {
      $subtree_ka_ids[] = $row->child_id;
    }
    return $subtree_ka_ids;
  } // ends fetch_subtree_knowledgeareas_as_ids()

  public static function fetch_subtree_knowledgeareas_as_objs($node_id) {
    /*
      This method wraps around the former one [fetch_subtree_knowledgeareas_as_ids()].
      Its purpose is to retrieve all knowledge areas belonging to a certain subtree (or branch).
      The former fetch_subtree_knowledgeareas_as_ids() fetches the id's,
        here, these id's go into the WHERE-clause so that all of them are fetched
        with a single SELECT.
      In total, a subtree fetch will only need 2 SELECTs:
        1) one for the Closure Table
        2) a second one for the Knowledge Area table itself
    */
    $subtree_knowledge_areas_as_ids = self::fetch_subtree_knowledgeareas_as_ids($node_id);
    $path_from_root_to_karea_as_objs = KnowledgeArea::find($subtree_knowledge_areas_as_ids);
    return $path_from_root_to_karea_as_objs;
  } // ends fetch_subtree_knowledgeareas_as_objs()

  public static function fetch_path_from_root_to_karea_as_ids($node_id) {
    /*
      This method fetches all ids that form the uptree path from $node_id to the root-node
      The main application it has here is to make efficient
        an immediate retrieval of a Knowledge Area path, ie, its hierarchical sequence.

      Eg. One wants to query the hierarchical sequence of 'Direito Imobiliário'
        To do so: get_uptree_trail_karea_ids(<dir_civ_karea_id>)
        will retrieve a list (array) of all id's that form up this path down to root-node
    */
    $karea_as_ids = [];
    $rows = KAreaClosureTable
      ::where('child_id', $node_id)
      ->where('parent_id', '!=', KnowledgeArea::ROOTS_PARENT_CONVENTIONED_ID)
      ->orderBy('depth', 'desc')->get();
    foreach ($rows as $row) {
      $karea_as_ids[] = $row->parent_id;
    }
    return $karea_as_ids;
  } // ends fetch_path_from_root_to_karea_as_ids()

  public static function fetch_path_from_root_to_karea_as_objs($node_id) {
    /*
      This method wraps around the former one [fetch_path_from_root_to_karea_as_ids()].
      Its purpose is to retrieve all knowledge areas forming up a path from root-node to node
      The former fetch_path_from_root_to_karea_as_ids() fetches the id's,
        here, these id's go into the WHERE-clause so that all of them are fetched
        with a single SELECT.
      In total, a subtree fetch will only need 2 SELECTs:
        1) one for the Closure Table
        2) a second one for the Knowledge Area table itself
    */
    $path_from_root_to_karea_as_ids  = self::fetch_path_from_root_to_karea_as_ids($node_id);
    $path_from_root_to_karea_as_objs = KnowledgeArea::find($path_from_root_to_karea_as_ids);
    return $path_from_root_to_karea_as_objs;
  } // ends fetch_path_from_root_to_karea_as_objs()

  public static function fetch_path_from_root_excluded_to_karea_as_objs($node_id) {
    /*
      Same as above method, but it excludes the node object.
      E.g.: if path is 'p1/p2/p3/p4'
        => method fetch_path_from_root_to_karea_as_objs() will return all 4 objects,
        ie: [p1, p2, p3, p4]
        => method fetch_path_from_root_to_kareaparent_as_objs() will return all 3 objects,
        ie: [p1, p2, p3], ie, p4 is not include in the result
    */
    $path_from_root_to_karea_as_ids  = self::fetch_path_from_root_to_karea_as_ids($node_id);
    $last_id_throw_away = array_shift($path_from_root_to_karea_as_ids);
    $path_from_root_to_karea_as_objs = KnowledgeArea::find($path_from_root_to_karea_as_ids);
    return $path_from_root_to_karea_as_objs;
  } // ends fetch_path_from_root_to_kareaparent_as_objs()

  public static function fetch_path_from_root_to_kareaparent_as_objs($node_id) {
    /*
      Same as above method, but it excludes the node object.
      E.g.: if path is 'p1/p2/p3/p4'
        => method fetch_path_from_root_to_karea_as_objs() will return all 4 objects,
        ie: [p1, p2, p3, p4]
        => method fetch_path_from_root_to_kareaparent_as_objs() will return all 3 objects,
        ie: [p1, p2, p3], ie, p4 is not include in the result
    */
    $path_from_root_to_karea_as_ids  = self::fetch_path_from_root_to_karea_as_ids($node_id);
    $last_id_throw_away = array_pop($path_from_root_to_karea_as_ids);
    $path_from_root_to_karea_as_objs = KnowledgeArea::find($path_from_root_to_karea_as_ids);
    return $path_from_root_to_karea_as_objs;
  } // ends fetch_path_from_root_to_kareaparent_as_objs()

} // ends class KAreaClosureTableFetcher
