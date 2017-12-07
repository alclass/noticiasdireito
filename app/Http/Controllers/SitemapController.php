<?php
namespace App\Http\Controllers;
// use App\Http\Controllers\NoticiasController;
use App\Http\Controllers\Controller;
use App\Models\NewsModels\NewsObject;
use App\Models\Util\FileSystemUtil;
use Carbon\Carbon;
// use Parsedown;

class SitemapController extends Controller {

  public function gen_dyn_download_txt_sitemap() {
    /* look up directory
      $newspiece_aarray = $this->get_last_newspiece_aarray();
      $newspiece_entries = collect();
      $newspiece_entries->push($newspiece_aarray);
    */
    $outgoing_txt_sitemap = '';
    $url = route('entranceroute');
    $outgoing_txt_sitemap .= $url . "\n";
    $newsobjects = NewsObject
      ::orderBy('newsdate', 'desc')
      ->get();
    foreach ($newsobjects as $newsobject) {
      $url = route('newsobjectroute', $newsobject->routeurl_as_array);
      $outgoing_txt_sitemap .= $url . "\n";
    }
    return \Response
      ::make($outgoing_txt_sitemap, 200)
      ->header('Content-Type', 'plain/text');
  }

}
