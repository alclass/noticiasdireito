<?php
// require 'autoload'
include 'NewsFileToDBLoader.php';
use App\Models\NewsModels\NewsFileToDBLoader;
// use Carbon\Carbon;
$loader = new NewsFileToDBLoader();
echo 'Executing method load_news_since_n_months_ago(1)' . "\n";
$loader->load_news_since_n_months_ago(1);
