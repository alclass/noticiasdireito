<?php
/*
Important: do not forget to run the command:

php artisan vendor:publish --provider="Spatie\Feed\FeedServiceProvider" --tag="config"

on production!

*/
  return [
    'feeds' => [
      'news' => [
          /*
           * Here you can specify which class and method will return
           * the items that should appear in the feed. For example:
           * 'App\Model@getAllFeedItems'
           *
           * You can also pass a parameter to that method:
           * ['App\Model@getAllFeedItems', 'parameter']
           */
          'items' => 'App\Models\NewsModels\NewsObject@getFeedItems',
          /*
           * The feed will be available on this url.
           */
          'url' => '/feed',
          'title' => 'Notícias Direito.Science - Entradas RSS',
      ],
  ],
];
/*
return [
  'feed_title' => "Notícias, Artigos e Resenhas do Direito.Science",
  'feed_description' => 'Assuntos sociojurídicos contemporâneos',
  'feed_logo' => 'http://example.com/images/brand/logo.png',
  'use_cache' => FALSE,
  'cache_key' => 'laravel-feed-cache-key',
  'cache_duration' => 3600,
  'max_size' => 10,
];
*/
