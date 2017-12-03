<?php
namespace App\Models\AcadModels;
// use App\Models\AcadModels\KAreaClosureTable;

use App\Models\AcadModels\KnowledgeArea;
use Illuminate\Database\Eloquent\Model;

class KAreaClosureTable extends Model {

  /*
    Closure Table is the technique chosen in this system to help deal
      with the hierarchical nature of Knowledge Area as it's
      kept in a Relational Database.

    Class KAreaClosureTableFetcher has static methods to find at least
      the following data sets:
        1) the path from root to node
        2) the subtree from node
        3) the siblings of node
      (there may be others more; these 3 already exist at the time of writing)
  */

  protected $table = 'karea_closuretable';

  /* The line below was commented out because it generates a weird kind of HasAttribute error
       which makes it impossible to extract an attribute from the model
     Avoind this " protected $primaryKey = ..." corrected the problem
  */
  // protected $primaryKey = ['parent_id','child_id'];

  protected $fillable = [
    'parent_id',
    'child_id',
    'depth',
	];

} // ends class KnowledgeArea extends Model
