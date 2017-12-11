<?php
namespace App\Http\Controllers\Feed;
// use App\Http\Controllers\Feed\FeedsController;
use App\Http\Controllers\Controller;
use App\Services\Feed\FeedBuilder;
use App\Models\NewsModels\NewsObject;
// use App\Models\Util\FileSystemUtil;
use Carbon\Carbon;
// use Parsedown;

class FeedsController extends Controller {

  private $builder;

  public function __construct(FeedBuilder $builder) {
      $this->builder = $builder;
  }

  //We're making atom default type
  public function getFeed($type = "atom") {
    if ($type === "rss" || $type === "atom") {
      return $this->builder->render($type);
    }

    // If invalid feed requested, redirect home
    return redirect()->route('entrance');
  }

}
