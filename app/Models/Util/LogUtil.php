<?php
namespace App\Models\Util;
// use App\Models\Util\LogUtil;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LogUtil {

  public static function log_msg($log_msg) {

    $entranceLog = new Logger('entrance');
    $entranceLog->pushHandler(
      new StreamHandler(storage_path('logs/entrance.log')), Logger::INFO
    );
    $entranceLog->info('EntranceLog', [$log_msg]);

  } // ends log_msg()

} // ends class LogUtil
