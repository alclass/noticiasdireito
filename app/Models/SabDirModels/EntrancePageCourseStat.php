<?php
namespace App\Models\SabDirModels;
// use App\Models\SabDirModels\EntrancePageCourseStat;

use App\Models\SabDirModels\SabDirCurso;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EntrancePageCourseStat extends Model {
  /*
  *** THIS DOC_STRING is also ported in the Migration Class ***

  Obs.: for (IPv4), how to transform data to and from PHP & MySQL

    ip2long() - Converts a string containing an (IPv4) Internet Protocol dotted address into a long integer
    long2ip() - Converts an long integer address into a string in (IPv4) Internet standard dotted format

    >>> ip2long("127.0.0.1")
    => 2130706433
    >>> long2ip(2130706433)
    => "127.0.0.1"

  Obs.: for (IPv6), how to transform data to and from PHP & MySQL

    inet_pton() — Converts a human readable IP address to its packed in_addr representation
    inet_ntop() — Converts a packed internet address to a human readable representation

    >>> $ipv6 = '::1'
    => "::1"
    >>> $binary_ipv6_addr = inet_pton($ipv6)
    => "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\x01"
    >>> $ipv6_back = inet_ntop($binary_ipv6_addr);
    => "::1"
  */

  public static function register_course_id($curso_id) {
    $pagecoursestat = new self; // EntrancePageCourseStat
    $pagecoursestat->curso_id = $curso_id;
    $pagecoursestat->accessed_at = Carbon::now();
    $ipv4_readable = \Request::ip(); // $_SERVER['REMOTE_ADDR'];
    $pagecoursestat->set_ipv4_from_readable($ipv4_readable);
    /*
      TO-DO: Protect the save() method with a TRY/CATCH block to avoid key type sql-exception
             The scenario, though unlikely, for that would be an atempt for save the record twice
    */
    $pagecoursestat->save();
    return;
  }

  protected $table = 'entrancepagecoursestats';
  protected $dates = ['accessed_at'];

  protected $fillable = [
    'course_id', 'accessed_at',
    'ipv4', 'ipv6',
	];

  public function ipv4_as_readable() {
    if ($this->ipv4 == null) {
      return '';
    }
    return long2ip($this->ipv4);
  }

  public function ipv6_as_readable() {
    if ($this->ipv6 == null) {
      return '';
    }
    return inet_ntop($this->ipv6);
  }

  public function set_ipv4_from_readable($ipv4_readable) {
    if ($ipv4_readable == null) {
      return;
    }
    $this->ipv4 = ip2long($ipv4_readable);
  }

  public function set_ipv6_from_readable($ipv6_readable) {
    if ($ipv6_readable == null) {
      return '';
    }
    $this->ipv6 = inet_pton($ipv6_readable);
  }

  public function get_curso() {
    return SabDirCurso::find($this->course_id);
  }

} // ends class EntrancePageCourseStat extends Model
