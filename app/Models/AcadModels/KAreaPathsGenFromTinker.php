<?php
namespace App\Models\AcadModels;

// use App\Models\AcadModels\KAreaPathsGenFromTinker
use App\Models\AcadModels\KnowledgeAreaPathGenerator;

class KAreaPathsGenFromTinker {
  /*
  This class is a "Tinker" wrapper to KnowledgeAreaPathGenerator
    ie, it's just a quick-help to run the generator methods of above class
  */

  public static function do() {
    $generator = new KnowledgeAreaPathGenerator;
    $generator->generate_downtreeslashsepsubtreeids();
    $generator->generate_uptreetrailslashseppathids();
  }

  public $generator = null;

  public function __construct() {
    $this->generator = new KnowledgeAreaPathGenerator;

  } // ends __construct()

  public function process() {
    $this->generator->generate_downtreeslashsepsubtreeids();
    $this->generator->generate_uptreetrailslashseppathids();
  } // ends process()

} // ends class KAreaPathsGenFromTinker
