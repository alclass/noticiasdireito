<?php
namespace App\Models\SabDirModels;
// use App\Models\SabDirModels\SabDirAulaAux;

use App\Models\AcadModels\KnowledgeArea;
use App\Models\SabDirModels\SabDirCurso;
use App\Models\Util\StringUtil;
use App\Models\Util\UtilParams;
use Illuminate\Support\Facades\URL;
// use Illuminate\Support\Facades\DB;
// use Carbon\Carbon;


class SabDirAulaAux {

  public static function get_all_curso_ids_as_array() {
    $ids_collection = SabDirCurso::all(['id']);
    $ids_array = [];
    foreach ($ids_collection as $row) {
      $ids_array[] = $row->id;
    }
    return $ids_array;
  }

  public static function draw_n_random_curso_ids($n_to_draw) {
    $ids = self::get_all_curso_ids_as_array();
    return MathUtil::draw_n_random_elements_from_1darray($ids, $n_to_draw);
  }

  public static function draw_n_random_cursos($n_to_draw) {
    $ids = self::draw_n_random_curso_ids($n_to_draw);
    return self::whereIn('id', $ids)->get();
  }

  public static function draw_n_random_lectures($n_to_draw) {
    $cursos = self::draw_n_random_cursos();
    $aulas = collect();
    foreach ($cursos as $curso) {
      $n_aulas = $curso->aulas->count();
      $i = rand(0, $n_aulas-1);
      $aula = $curso->aulas[$i];
      $aulas->push($aula);
    }
    return $aulas;
  }

  public static function draw_n_random_lectures_n_return_aarraycollect($n_to_draw, $size='DF') {
    /*
      $aarray's keys:
        'aula_img_url'
        'aula_img_alt'
        'aula_routeurl'
        'aula_title'
        'curso_title'
    */
    $aulas = self::draw_n_random_lectures($n_to_draw);
    $aarraycollect = collect();
    foreach ($aulas as $aula) {
      $aarray = $aula->generate_localservers_thumbnail_aarray();
      $aarraycollect->push($aarray);
    }
    return $aarraycollect;
  }

  public static function mount_aularouteurl_withparams_date_nord_n_aulatitle(
      $sqldate,
      $n_ord_aula,
      $aulatitle
    ) {
    $aulatitle_as_urlpiece = StringUtil::convert_phrase_to_nonaccented_url_piecepath($aulatitle);
    $urlphrasepiece = "$n_ord_aula-$aulatitle_as_urlpiece";
    $routeurl_as_array = [$sqldate, $urlphrasepiece];
    $urlpart = route('aularoute', $routeurl_as_array);
    return $urlpart; // URL::to($urlpart);
  }

  public static function mount_aula_img_alt_withparams_aulatitle_cursotitle_n_date(
      $aulatitle,
      $n_ord_aula,
      $cursotitle,
      $profname,
      $sqldate
     ) {
    $datestr = 'n/a';
    $pp = explode('-', $sqldate);
    if (count($pp)>2) {
      $datestr = $pp[2] . '/' . $pp[1] . '/' . $pp[0];
    }
    $aula_img_alt = "Aula $n_ord_aula do curso: $cursotitle"
                  . " de $datestr intitulada $aulatitle ministrada pelo(a)"
                  . " Prof. $profname";
    return $aula_img_alt;
  }

  public static function mount_localserver_imgurl_withparams_date_nord_n_size(
      $firstemissiondate_str,
      $n_ord_aula,
      $thumbnailsize='DF'
    ) {
    $extlessfilename='default';
    switch ($thumbnailsize) {
      case 'DF':
        $extlessfilename='default';
        break;
      default:
        $extlessfilename='default';
        break;
    }
    $pp = explode('-', $firstemissiondate_str);
    if (count($pp) < 3) {
      return '';
    }
    $year_str = $pp[0];
    $pastacursos = UtilParams::get_servers_foldername_for_pastacursos();
    $aula_img_url = "$pastacursos/$year_str/$firstemissiondate_str/a$n_ord_aula/ytthumbnailimages/$extlessfilename.jpg";
    $aula_img_url = asset($aula_img_url); // storage_path($aula_img_url);
    return $aula_img_url;
  }

  public static function get_aarraycollect_for_9_aulas_hardcoded($thumbnailsize='DF') {
    /*
      This method was created to save RAM and CPU once
        it is executed in the main ENTRANCE page where
        resource consumption should be kept low.

      Because of that, it doesn't actually query the sql-database.
      Its resource consumption is basically array-filling
        and string interpolation, which are lean and more
        economical of server resources (RAM + CPU + db-queries).

      There are other methods in this class that are capable
        of truly randomly drawing lectures from the database
        and can be used if server becomes more resourceful.

      The second strategy of having the thumbnails in server
        is better explained below.

      The few jpg's copied from YouTube's images CDN are located in:
        public/cursos/{year}/{firstemissiondate}/a{n_ord_aula}/ytthumbnailimages/default.jpg

      The local jpg's were copied here because YouTube fails
        some thumbnails, ie, some are retrieved from their CDN,
        some are not, giving a bad impression in the ENTRANCE page
        due to these 'gap-holes' in the image matrix.

      Because of that, the ENTRANCE-PAGE uses
        copied thumbnails from the local server as said above.
    */
    $array_of_aarrays = [
      [
        'aula_known_title'  => 'Estrutura do Controle no Brasil',
        'curso_known_title' => 'Controle de Constitucionalidade Difuso',
        'profname'      => 'Paulo Nasser',
        'coursedatestr' => '2014-02-10', // 1
        'n_ord_aula'    => 1,
      ],
      [
        'aula_known_title'  => 'Taxas e Contribuição de Melhoria',
        'curso_known_title' => 'Direito Tributário: Noções Gerais, Tributos e Espécies Tributárias',
        'profname'      => 'Patricia Canhadas',
        'coursedatestr' => '2008-09-24', // 2
        'n_ord_aula'    => 5,
      ],
      [
        'aula_known_title'  => 'Garantia de Emprego',
        'curso_known_title' => 'Contrato de Trabalho: Princípios, Garantia de Emprego, Suspensão e Extinção',
        'profname'      => 'Rafael Tonassi',
        'coursedatestr' => '2010-08-23', // 3
        'n_ord_aula'    => 2,
      ],
      [
        'aula_known_title'  => 'Mediação, Conciliação e Negociação',
        'curso_known_title' => 'Conflito; Soluções Alternativas',
        'profname'      => 'Fabio Menna',
        'coursedatestr' => '2008-09-14', // 4
        'n_ord_aula'    => 2,
      ],
      [
        'aula_known_title'  => 'Lei Anticorrupção - Ações Cabíveis',
        'curso_known_title' => 'Direito Anticorrupção',
        'profname'      => 'Paula Freire',
        'coursedatestr' => '2016-08-29', // 5
        'n_ord_aula'    => 4,
      ],
      [
        'aula_known_title'  => 'Teoria da Desconsideração',
        'curso_known_title' => 'Inovações no Direito Empresarial e Recuperacional',
        'profname'      => 'Mônica Gusmão',
        'coursedatestr' => '2015-02-23', // 6
        'n_ord_aula'    => 5,
      ],
      [
        'aula_known_title'  => 'Evolução do Direito Penal II',
        'curso_known_title' => 'Evolução do Direito Penal',
        'profname'      => 'Rogério Greco',
        'coursedatestr' => '2010-06-14', // 7
        'n_ord_aula' => 2,
      ],
      [
        'aula_known_title'  => 'Unidade de Conservação',
        'curso_known_title' => 'Espaços Territoriais Protegidos em Direito Ambiental',
        'profname'      => 'Daniela Martins',
        'coursedatestr' => '2015-04-20', // 8
        'n_ord_aula'    => 3,
      ],
      [
        'aula_known_title'  => 'Demurrage de Navios e Contêineres',
        'curso_known_title' => 'Direito Marítimo',
        'profname'      => 'Fábio Gentil',
        'coursedatestr' => '2012-07-09', // 9
        'n_ord_aula'    => 5,
      ],
    ];
    $aarraycollect = collect();
    foreach ($array_of_aarrays as $aarray) {
      $sqldate      = $aarray['coursedatestr'];
      $n_ord_aula   = $aarray['n_ord_aula'];
      // 1st (of 3) attribute: $aula_img_url
      $aula_img_url = self::mount_localserver_imgurl_withparams_date_nord_n_size(
        $sqldate,
        $n_ord_aula,
        $thumbnailsize
      );
      $aulatitle  = $aarray['aula_known_title'];
      $cursotitle = $aarray['curso_known_title'];
      $profname   = $aarray['profname'];
      // 2nd (of 3) attribute: $aula_img_alt
      $aula_img_alt = self::mount_aula_img_alt_withparams_aulatitle_cursotitle_n_date(
        $aulatitle,
        $n_ord_aula,
        $cursotitle,
        $profname,
        $sqldate
      );
      // 3rd (of 3) attribute: $aula_routeurl
      $aula_routeurl = self::mount_aularouteurl_withparams_date_nord_n_aulatitle(
        $sqldate,
        $n_ord_aula,
        $aulatitle
      );
      $outgoing_aarray = [];
      $outgoing_aarray['aula_img_url']  = $aula_img_url;
      $outgoing_aarray['aula_img_alt']  = $aula_img_alt;
      $outgoing_aarray['aula_routeurl'] = $aula_routeurl;
      $aarraycollect->push($outgoing_aarray);
    }
    return $aarraycollect;
  }

/*
  public function __construct($aula) {
    $this->sabdiraula = $aula;
  } // ends __construct()
*/

} // ends class SabDirAulaAux
