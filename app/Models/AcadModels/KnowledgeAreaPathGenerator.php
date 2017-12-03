<?php
namespace App\Models\AcadModels;

use App\Models\AcadModels\KnowledgeArea;
use Illuminate\Database\Eloquent\Model;

class KnowledgeAreaPathGenerator {
  /*
  The class aims to generate the following TWO opposite direction paths, they are:

    => 'uptreetrailslashseppathids',
    => 'downtreeslashsepsubtreeids'

  The first (uptreetrailslashseppathids) is the path from the root-node to the node itself
    E.g.  Suppose Dir. Imob. is id=8, its up tree path might be "/1/2/7/8"
      This path says 7 is the parent of 8, 2 the parent of 7 and 1 the parent of 2

  The second (downtreeslashsepsubtreeids) is not a path exactly, but the set of all
    nodes down tree from the node itself
    E.g.  Suppose Dir. Civ. is id=2, its up down path fill-up be "2/3/4/7/8/40/45/100"
      This path informs every node that is a descendant of Dir. Civ. id=2
      Notice that 7 and 8, seen above, are also descendants

    These lists are generated for economical purposes, due to the fact that it's
      time expensive to find them all on the fly, without this caching proposed here

    Domain uses:
    [1]
      The first, ie uptreetrailslashseppathids, is used to inform the knowledge area path
        of a course
        Eg. /1/2/7/8 is translated to Direito/Direito Civil/Direito Reais/Direito Imobiliário
    [2]
      The second, ie downtreeslashsepsubtreeids, is used to find a query problem.
      Eg. what are all the Dir. Civ. courses?  Courses that are Dir. Civ. are all those
        that have k_area_id's inside the downtreeslashsepsubtreeids.

  */

  public $uptree_trails_id_mapping_path       = null;
  public $downtree_id_mapping_its_subtree_ids = null;


  public function __construct() {
    /*
    $this->$uptree_trails_id_mapping_path will be filled-in like this:
    [
      1 => [1],         // this is the root-node
      2 => [1, 2],      // this means id 2 is child of id 1 (the root-node)
      3 => [1, 2, 5],   // this means id 5 is child of id 2 whose parent is the root-node
      4 => [1, 3],      // again, a first generation child
      <etc>             // <etc>
    ]
    */
    $this->uptree_trails_id_mapping_path       = array();
    $this->downtree_id_mapping_its_subtree_ids = array();
  }

  public function generate_downtreeslashsepsubtreeids() {

    $root = KnowledgeArea::first();
    if ($root == null) {
      return;
    }
    $node_id = $root->id;
    $this->recurse_gen_downtreeslashsepsubtreeids($node_id);
    foreach ($this->downtree_id_mapping_its_subtree_ids as $id => $downtree_ids) {
      $knowledgearea = KnowledgeArea::find($id);
      $slash_sep_ids = '/' . join('/', $downtree_ids);
      $knowledgearea->downtreeslashsepsubtreeids = $slash_sep_ids;
      print ('$downtreeslashsepsubtreeids = ' . $slash_sep_ids . '\n');
      print ('Saving to db \n');
      $knowledgearea->save();
    }
  } // ends generate_downtreeslashsepsubtreeids()

  public function recurse_gen_downtreeslashsepsubtreeids($node_id, $downtreeslashsepsubtreeids=[], $n_protect_recurses=0) {
    $children_nodes = KnowledgeArea::where('parent_ka_id', $node_id)->get();
    $ids = [];
    foreach ($children_nodes as $child_node) {
      $child_node_id = $child_node->id;
      if (in_array($child_node_id, $downtreeslashsepsubtreeids)) {
        continue;
      }
      $downtreeslashsepsubtreeids[] = $child_node_id;
    } // ends foreach
    // Loop recursing
    foreach ($children_nodes as $child_node) {
      $n_protect_recurses += 1;
      if ($n_protect_recurses > 10000) {
        // it will be buggy, maybe we should raise an exception here (TO-DO)
        return $downtreeslashsepsubtreeids;
      }
      $downtreeslashsepsubtreeids += $this->recurse_gen_downtreeslashsepsubtreeids($child_node, $downtreeslashsepsubtreeids, $n_protect_recurses);
      $this->downtree_id_mapping_its_subtree_ids += array($child_node => $downtreeslashsepsubtreeids);
    } // ends foreach
    // This addition is for the last one, ie, the root which receives the sum itself and everyone else
    //$this->downtree_id_mapping_its_subtree_ids += array($node_id => $downtreeslashsepsubtreeids);
    return $downtreeslashsepsubtreeids;
  } // ends recurse_gen_downtreeslashsepsubtreeids()

  public function generate_uptreetrailslashseppathids() {

    $this->init_root_node();
    $this->traverse_all();
    $this->dbgenerate_uptree_slash_sep_path();

  } // ends generate_uptreetrailslashseppathids()

  public function init_root_node() {
    /* Convention:  id 1 (or the least one in db) is the root-node (here the root-node is 'Direito')
        Please, verify that the root-node is 1 or at least the smaller int is really the root one
    */
    $root_knowlegde_area = KnowledgeArea::first();
    if ($root_knowlegde_area == null) {
      // db is either empty or something weird happened
      // returning from here means knowledge area tree is empty
      return;
    }
    $root_id = $root_knowlegde_area->id;
    // this should be the first push to the array (though there is the +=, the array was initialized empty in __construct())
    $this->uptree_trails_id_mapping_path += array($root_id => [$root_id]);
  } // ends init_root_node()


  public function traverse_all() {

    $knowledge_area_ids = KnowledgeArea::orderBy('id', 'asc')->get(['id']);
    foreach ($knowledge_area_ids as $knowledge_area_with_field_id) {
      $knowledge_area_id = $knowledge_area_with_field_id->id;
      if (array_key_exists($knowledge_area_id, $this->uptree_trails_id_mapping_path)) { // in_array(), array_key_exists(), array_intersect()
        continue;
      }
      $this->parcours_by_id($knowledge_area_id);
    } // ends foreach

  } // ends traverse_all()

  public function parcours_by_id($knowledge_area_id) {

    $parent_ka_id = KnowledgeArea::find($knowledge_area_id)->parent_ka_id;
    if ($parent_ka_id == null) {
      /* this is weird, the ascendent ordering of id's should guarantee
         this not to happen, because parents are processed before children
         routine can't go on, return here      */
      return;
    }
    $parent_path  = $this->uptree_trails_id_mapping_path[$parent_ka_id];
    // IMPORTANT: array are assigned by COPY not by reference, so line below is safe
    $child_path   = $parent_path; // arrays are hard-copied, not referenced, safe assigning
    // Append child's id
    $child_path[] = $knowledge_area_id;
    // IMPORTANT: the line below is very php-iish (that's a valid way to push to
    //   an associative array when indices are integer :: if $array[] is used, it won't work as needed here)
    $this->uptree_trails_id_mapping_path += array($knowledge_area_id => $child_path);
  } // ends parcours_by_id()

  public function get_slash_sep_path() {
    $ids = $this->parcours();
    $slash_sep_path = '/' . join($ids, '/');
    return $slash_sep_path;
  } // ends get_slash_sep_path()

  public function dbgenerate_uptree_slash_sep_path() {
    // $this->id_mapping_its_pathlist = array();
    if (empty($this->uptree_trails_id_mapping_path)) {
      print ('The Knowledge Area Tree seems to be empty in db. No paths generated. \n');
    }
    foreach ($this->uptree_trails_id_mapping_path as $id => $pathidlist) {
      $slash_sep_path = '/' . join('/', $pathidlist);
      $knowledge_area = KnowledgeArea::find($id);
      $knowledge_area->knowledgearea_slash_sep_pathids = $slash_sep_path;
      print ('$slash_sep_path = ' . $slash_sep_path . '\n');
      print ('Saving to db \n');
      $knowledge_area->save();
      // $this->id_mapping_its_pathlist += array($id, $slash_sep_path);
    }
  } // ends generate_slash_sep_path()

} // ends class KnowledgeAreaPathGenerator
