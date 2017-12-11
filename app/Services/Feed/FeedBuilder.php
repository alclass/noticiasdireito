<?php
namespace App\Services\Feed;

use Illuminate\Support\Facades\App;
use App\Models\NewsModels\NewsObject;

class FeedBuilder {
  private $config;

  public function __construct()  {
    $this->config = config()->get('feed');
  }

  public function render($type)  {
    $feed = App::make("feed");
    if ($this->config['use_cache']) {
      $feed->setCache(
        $this->config['cache_duration'],
        $this->config['cache_key']
      );
    }

    if (!$feed->isCached()) {
      $posts = $this->getFeedData();
      $feed->title = $this->config['feed_title'];
      $feed->description = $this->config['feed_description'];
      $feed->logo = $this->config['feed_logo'];
      $feed->link = url('feed');
      $feed->setDateFormat('datetime');
      $feed->lang = 'pt';
      $feed->setShortening(true);
      $feed->setTextLimit(250);

      if (!empty($newsobjects)) {
        $feed->pubdate = $newsobjects[0]->newsdate;
        foreach ($newsobjects as $news_obj) {
          $routeurl = route('$newsobjectroute', $news_obj->routeurl_as_array);
          $author = "Direito.Science";http://localhost:8000
          // set item's title, author, url, pubdate, description, content, enclosure (optional)*
          $feed->add($news_obj->newstitle, $author, $routeurl, $news_obj->newsdate, '', '');
        }
      }
    }

    return $feed->render($type);
  }

  /**
   * Creating rss feed with our most recent posts.
   * The size of the feed is defined in feed.php config.
   *
   * @return mixed
   */
  private function getFeedData() {
    $maxSize = $this->config['max_size'];
    $newsobjects = NewsObject::paginate($maxSize);
    return $newsobjects;
  }

}
