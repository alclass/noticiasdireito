<?php
namespace App\Http\Controllers;
// use App\Http\Controllers\NoticiasController;
use App\Http\Controllers\Controller;
use App\Models\NewsModels\NewsObject;
use App\Models\Util\FileSystemUtil;
use Carbon\Carbon;
// use Parsedown;

class SitemapController extends Controller {

  public function fill_n_return_newspiece_urls_text() {
    $outgoing_txt_sitemap = '';
    $newsobjects = NewsObject
      ::orderBy('newsdate', 'desc')
      ->get();
    foreach ($newsobjects as $newsobject) {
      $url = route('newsobjectroute', $newsobject->routeurl_as_array);
      $outgoing_txt_sitemap .= $url . "\n";
    }
    return $outgoing_txt_sitemap;
  } // ends fill_n_return_newspiece_urls_text()

  public function fill_n_return_base_urls_text() {
    $outgoing_txt_sitemap = '';
    $url = route('entranceroute');
    $outgoing_txt_sitemap .= $url . "\n";
    $url = route('contatoroute');
    $outgoing_txt_sitemap .= $url . "\n";
    $url = route('sobreroute');
    $outgoing_txt_sitemap .= $url . "\n";
    return $outgoing_txt_sitemap;
  } // ends fill_n_return_base_urls_text()

  public function gen_dyn_download_txt_sitemap() {
    /* look up directory
      $newspiece_aarray = $this->get_last_newspiece_aarray();
      $newspiece_entries = collect();
      $newspiece_entries->push($newspiece_aarray);
    */
    $outgoing_txt_sitemap = '';
    $outgoing_txt_sitemap .= $this->fill_n_return_base_urls_text();
    $outgoing_txt_sitemap .= $this->fill_n_return_newspiece_urls_text();
    return \Response
      ::make($outgoing_txt_sitemap, 200)
      ->header('Content-Type', 'plain/text');
  } // ends gen_dyn_download_txt_sitemap()

} // ends class SitemapController extends Controller
