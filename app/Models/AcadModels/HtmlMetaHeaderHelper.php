<?php
namespace App\Models\AcadModels;
// use App\Models\AcadModels\HtmlMetaHeaderHelper;

class HtmlMetaHeaderHelper {

  /*
    Class HtmlMetaHeaderHelper
  */

  /*
  IMPORTANT: when img_url does not begins with http/https, wrap it thru function asset()
  */

  const IMG_TAG_ALT_ATTRIB_DEFAULT = 'Logo imagem do Saber Direito Dois: beautiful svg art.';
  const IMG_TAG_URL_ATTRIB_DEFAULT = 'images/svg_originated_images/modern_spirals_svg_art_direitodotwin.png';
  const IMG_WIDTH_DEFAULT  = 310;
  const IMG_HEIGHT_DEFAULT = 260;

  /*
      This const below has not yet been implemented, it's hard-coded directly in the root template
  */
  const HTML_META_KEYWORDS_DEFAULT = 'Direito, Vídeo, Aula, Videoaula, Curso, Videocurso, Escola, Videoescola, Videoteca, Vídeo-biblioteca, Ramos do Direito, Subáreas de Conhecimento, Grafos de Área de Conhecimento, Direito Administrativo, Direito Ambiental, Biodireito, Direito Civil, Contratos, Contratual, Sucessório, Consumidor, Obrigações, Reais, Responsabilidade Civil, Direito Constitucional, Direito Econômico, Direito Eleitoral, Direito Empresarial, Filosofia e Propedêuticas, Direito Financeiro, Direito Internacional, Direito Privado, Direito Público, Direito Previdenciário, Direito Trabalhista, Temas Transdisciplinares, Moderno, Interdisciplinar, Parte Geral, Parte Especial, Teorias, Prática, Judiciário, Judicial, Juizados, Tribunal, STJ, STF, Extrajudicial, Ministério Público, Advocacia, Direito Penal, Criminologia, Direito Processual, Direito Tributário, Ciências Sociais, Sociologia, Democracia, Estado Democrático, Sistema de Referência e Recuperação de Acervo, História, Histórico, Programa Saber Direito, TV Justiça, Blog, Exame de Ordem OAB, Editora, Livraria';

  const HTML_META_N_FIELDS_NDAARRAY = [
    'aula' => [
      'img_alt' => 'Saber Direito Dois; TV Justiça Saber Direito Aula Logo',
      'img_url' => 'http://www.stf.jus.br/repositorio/cms/portalTvJustica/portalTvJusticaProgramacao/imagem/SABERDIREITOAULA_00000grande.jpg',
      'hmeta_predescr' => 'Infopágina da Aula',
    ],
    'curso' => [
      'img_alt' => 'Saber Direito Dois; TV Justiça Saber Direito (Curso) Logo',
      'img_url' => 'https://lh4.googleusercontent.com/--78LLR5DGEw/UTyKBE6_1vI/AAAAAAAAh-8/g18sSyTjCwc/w407-h271-p-o/PDVD_055.JPG',
      'hmeta_predescr' => 'Infopágina do Curso',
    ],
    'cursos' => [
      'img_alt' => 'Saber Direito Dois; Logo TV Justiça na tela de monitor e uma câmara',
      'img_url' => 'http://www.stf.jus.br/repositorio/cms/portalTvJustica/portalTvJusticaAssista/imagem/TVJusticaaovivo2.jpg',
      'hmeta_predescr' => 'Listagem dos Cursos',
    ],
    'professor' => [
      'img_alt' => 'Saber Direito Dois; Professor no Programa Saber Direito da TV Justiça; microphone logo',
      'img_url' => 'http://www.freepngimg.com/download/microphone/2-microphone-png-image.png',
      'hmeta_predescr' => 'Infopágina sobre o Professor Participante',
    ],
    'professores' => [
      'img_alt' => 'Professores no Programa Saber Direito da TV Justiça; foto-montagem com LFG, J. Frederico, P. Canhadas, P. Lenza, N. Rosenvald e F. Tartuce',
      'img_url' => 'images/montage_images/professores_no_saber_direito_created_with_app_photojoiner.net_thumbnail.jpg',
      'img_width'  => 355,
      'img_height' => 225,
      'hmeta_predescr' => 'Listagem dos Professores Participantes',
    ],
    'ramodescricao' => [
      'img_alt' => 'Saber Direito Dois Ramo Descrição: A bela árvore de tons de verde',
      'img_url' => 'https://i.pinimg.com/736x/29/10/32/291032823cbbb63bee58fcc5a359cbac--free-family-tree-template-family-tree-printable.jpg',
      'hmeta_predescr' => 'Grafo das Subáreas de Direito aos cursos',
    ],
    'ramos' => [
      'img_alt' => 'Saber Direito Dois; Logo TV Justiça na tela de monitor e uma câmara',
      'img_url' => 'http://www.stf.jus.br/repositorio/cms/portalTvJustica/portalTvJusticaAssista/imagem/TVJusticaaovivo2.jpg',
      'hmeta_predescr' => 'Navegação aos cursos via Ramos do Direito',
    ],
    'ramos1' => [
      'img_alt' => 'Saber Direito Dois Beautiful TV Logo for the knowledge areas page',
      'img_url' => 'http://www.freepngimg.com/download/television/3-2-television-png-clipart.png',
      'hmeta_predescr' => 'Navegação aos cursos a partir do Ramo Raiz Direito',
    ],
    'sddebate' => [
      'img_alt' => 'Saber Direito Dois; TV Justiça Saber Direito Debate/Entrevista Logo',
      'img_url' => 'http://www.stf.jus.br/repositorio/cms/portalTvJustica/portalTvJusticaNoticia/imagem/saberdireito_debate_450px.jpg',
      'hmeta_predescr' => 'Infopágina do Saber Direito Debate/Entrevista',
    ],
    'sdresponde' => [
      'img_alt' => 'Saber Direito Dois; TV Justiça Saber Direito Responde Logo',
      'img_url' => 'http://www.stf.jus.br/repositorio/cms/portalTvJustica/portalTvJusticaNoticia/imagem/saberdireito_responde_450px.jpg',
      'hmeta_predescr' => 'Infopágina do Saber Direito Responde',
    ],
    // ====================================================
    'brevehistorico' => [
      'img_alt' => null,
      'img_url' => null,
      'hmeta_predescr' => 'Breve Histórico das origens do Saber Direito Dois',
    ],
    'contato' => [
      'img_alt' => null,
      'img_url' => null,
      'hmeta_predescr' => 'Página [Contato] com o Saber Direito Dois',
    ],
    'entrance' => [
      'img_alt' => null,
      'img_url' => null,
      'hmeta_predescr' => 'Bem-vindo ao Saber Direito Dois',
    ],
    'quemsomos' => [
      'img_alt' => null,
      'img_url' => null,
      'hmeta_predescr' => 'Página [Quem Somos] ao Saber Direito Dois',
    ],
    'sobre' => [
      'img_alt' => null,
      'img_url' => null,
      'hmeta_predescr' => 'Página [Sobre] o Saber Direito Dois',
    ],
    // Same as tvjustica et al.
    'sdstatistics' => [
      'img_alt' => 'Logo imagem da TV Justiça com arte da deusa com a venda nos olhos',
      'img_url' => 'https://1.bp.blogspot.com/_gJApV2RyekQ/TKvB2AKrX2I/AAAAAAAAGJ8/wILHCoQzk5o/S1600-R/PDVD_102.JPG',
      'hmeta_predescr' => 'Dezenas de estatísticas e curiosidades sobre o Programa Saber Direito',
    ],
    'temporadas' => [
      'img_alt' => 'Logo imagem da TV Justiça com arte da deusa com a venda nos olhos',
      'img_url' => 'https://1.bp.blogspot.com/_gJApV2RyekQ/TKvB2AKrX2I/AAAAAAAAGJ8/wILHCoQzk5o/S1600-R/PDVD_102.JPG',
      'hmeta_predescr' => 'Breve Histórico do Programa Saber Direito via suas Temporadas',
    ],
    // Same as sdstatistics et al
    'tvjustica' => [
      'img_alt' => 'Logo imagem da TV Justiça com arte da deusa com a venda nos olhos',
      'img_url' => 'https://1.bp.blogspot.com/_gJApV2RyekQ/TKvB2AKrX2I/AAAAAAAAGJ8/wILHCoQzk5o/S1600-R/PDVD_102.JPG',
      'hmeta_predescr' => 'Breve Descrição e Referências sobre a TV Justiça',
    ],
  ];

  const GOOGLE_ADS_SNIPPETS_DEFAULT = 'in-article';
  const GOOGLE_ADS_SNIPPETS = [
    'in-article' => '
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <ins class="adsbygoogle"
       style="display:block; text-align:center;"
       data-ad-layout="in-article"
       data-ad-format="fluid"
       data-ad-client="ca-pub-8025632868883287"
       data-ad-slot="6238982907"></ins>
  <script>
       (adsbygoogle = window.adsbygoogle || []).push({});
  </script>',
  ];

  const HMETA_DESCRIPTION_BASE = 'O Saber Direito Dois é uma vídeo-escola jurídica e um sistema de referência ao Programa Saber Direito da TV Justiça com centenas de videocursos em acervo.';

  const TMPHTML_META_N_FIELDS_NDAARRAY = [
    'ramodescricao' => 'Grafo de Ramos do Direito aos cursos',
    'ramos' => 'Navegador via Ramos do Direito aos cursos',
    'professor' => 'Infopágina do Professor Participante',
    'professores' => 'Listagem dos Professores Participantes',
    // ------------------------------------------
  ];

  /*
  public static function create_obj($pagetype=null) {
    $obj = new self;
    switch ($pagetype) {
      case 'cursos':
        $obj = new self($pagetype);
        $obj->
        break;

      default:
        # code...
        break;
    }
  }
  */

  public static function pass_n_return_img_url_thru_f_asset_ifneeded($img_url) {
    if (empty($img_url)) {
      return null;
    }
    /*
      OBS: substr() was replaced by mb_strcut()
      when it envolved Portuguese text
      For URLs, substr() was kept on.
    */
    if (substr($img_url, 0, 4) != 'http') {
      $img_url = asset($img_url);
    }
    return $img_url;
  }

  public static function return_default_img_url_passing_thru_f_asset_ifneeded() {
    $img_url = self::IMG_TAG_URL_ATTRIB_DEFAULT;
    return self::pass_n_return_img_url_thru_f_asset_ifneeded($img_url);
  }

  public static function concatenate_standard_hmeta_description($starttext=null) {
    if ($starttext == null) {
      return self::HMETA_DESCRIPTION_BASE;
    }
    $hmeta_description_text = $starttext . '. ' . self::HTML_META_N_FIELDS_NDAARRAY[$key];
    $hmeta_description_text .= ': ' . self::HMETA_DESCRIPTION_BASE;
    return $hmeta_description_text;
  }

  public static function gen_HTML_META_N_FIELDS_NDAARRAY_by_key_n_compl($key, $complement=null) {
    if ($key == null || !array_key_exists($key)) {
      return self::HMETA_DESCRIPTION_BASE;
    }
    $hmeta_description_text = self::HTML_META_N_FIELDS_NDAARRAY[$key];
    if ($complement!=null) {
      $hmeta_description_text .= ': ' . $complement;
    }
    $hmeta_description_text .= ': ' . self::HMETA_DESCRIPTION_BASE;
    return $hmeta_description_text;
  }

  public $img_tag_url_attrib;
  public $img_tag_alt_attrib;
  public $hmeta_description_text;
  public $hmeta_description_complement;

  /* Just to document the 3 class attributes used outside (se get-attribute 'magic' method at the end)
   protected $appends = [
     'img_alt', 'img_url',
     'img_width', 'img_height',
     'html_meta_page_description'
   ];

  */

  public function __construct($pagetype=null) {
    $this->img_tag_url_attrib     = null;
    $this->img_tag_alt_attrib     = null;
    $this->hmeta_description_text = null;
    $this->hmeta_description_complement = null;
    // ---------------------------------
    if ($pagetype == null) {
      // default to
      $pagetype = 'brevehistorico';
    }
    $this->pagetype = $pagetype;
  }

  public function return_hmeta_description_text_prepended_with_complement_ifany() {
    $hmeta_description_text = self::HMETA_DESCRIPTION_BASE;
    if (!empty($this->hmeta_description_complement)) {
      $hmeta_description_text = $this->hmeta_description_complement . ': ' . $hmeta_description_text;
    }
    return $hmeta_description_text;
  }

  // attrib html_meta_page_description
  public function getHtml_meta_page_descriptionAttribute() {
    return $this->get_hmeta_description_text();
  }

  public function get_hmeta_description_text() {
    if ($this->hmeta_description_text != null) {
      return $this->hmeta_description_text;
    }
    if (!array_key_exists($this->pagetype, self::HTML_META_N_FIELDS_NDAARRAY)) {
      return $this->return_hmeta_description_text_prepended_with_complement_ifany();
    }
    $inner_aarray = self::HTML_META_N_FIELDS_NDAARRAY[$this->pagetype];
    $hmeta_description_text = $inner_aarray['hmeta_predescr'];
    $hmeta_description_text = $hmeta_description_text . ': ' . $this->return_hmeta_description_text_prepended_with_complement_ifany();
    return $hmeta_description_text;
  }

  public function getImg_altAttribute() {
    return $this->get_img_tag_alt_attrib();
  }

  private function get_img_alt_from_ndaarray_or_default() {
    $inner_aarray = self::HTML_META_N_FIELDS_NDAARRAY[$this->pagetype];
    $img_alt = $inner_aarray['img_alt'];
    if ($img_alt == null) {
      return self::IMG_TAG_ALT_ATTRIB_DEFAULT;
    }
    return $img_alt;
  }

  public function get_img_tag_alt_attrib() {
    if ($this->img_tag_alt_attrib != null) {
      return $this->img_tag_alt_attrib;
    };
    if (!array_key_exists($this->pagetype, self::HTML_META_N_FIELDS_NDAARRAY)) {
      return self::IMG_TAG_ALT_ATTRIB_DEFAULT;
    }
    return $this->get_img_alt_from_ndaarray_or_default();
  }

  public function getImg_urlAttribute() {
    return $this->get_img_tag_url_attrib();
  }

  private function get_img_url_from_ndaarray_or_default() {
    $inner_aarray = self::HTML_META_N_FIELDS_NDAARRAY[$this->pagetype];
    $img_url = $inner_aarray['img_url'];
    if ($img_url == null) {
      return self::return_default_img_url_passing_thru_f_asset_ifneeded();
    }
    return self::pass_n_return_img_url_thru_f_asset_ifneeded($img_url);
  }

  public function get_img_tag_url_attrib() {
    if ($this->img_tag_url_attrib != null) {
      return $this->img_tag_url_attrib;
    };
    if (!array_key_exists($this->pagetype, self::HTML_META_N_FIELDS_NDAARRAY)) {
      return self::return_default_img_url_passing_thru_f_asset_ifneeded();
    }
    return $this->get_img_url_from_ndaarray_or_default();
  }

  public function getImg_widthAttribute() {
    return $this->get_img_width_attrib();
  }

  public function get_img_width_attrib() {
    if (!array_key_exists($this->pagetype, self::HTML_META_N_FIELDS_NDAARRAY)) {
      return self::IMG_WIDTH_DEFAULT;
    }
    $inner_aarray = self::HTML_META_N_FIELDS_NDAARRAY[$this->pagetype];
    if (!array_key_exists('img_width', $inner_aarray)) {
      return self::IMG_WIDTH_DEFAULT;
    }
    return $inner_aarray['img_width'];
  }

  public function getImg_heightAttribute() {
    return $this->get_img_height_attrib();
  }

  public function get_img_height_attrib() {
    if (!array_key_exists($this->pagetype, self::HTML_META_N_FIELDS_NDAARRAY)) {
      return self::IMG_HEIGHT_DEFAULT;
    }
    $inner_aarray = self::HTML_META_N_FIELDS_NDAARRAY[$this->pagetype];
    if (!array_key_exists('img_height', $inner_aarray)) {
      return self::IMG_HEIGHT_DEFAULT;
    }
    return $inner_aarray['img_height'];
  }

  public function insert_googleads($layout) {
    if (!array_key_exists($layout, self::GOOGLE_ADS_SNIPPETS)) {
      return self::GOOGLE_ADS_SNIPPETS[self::GOOGLE_ADS_SNIPPETS_DEFAULT];
    }
    return self::GOOGLE_ADS_SNIPPETS[$layout];
  }

  public function __get($propertyName) {
    $method = 'get' . ucfirst($propertyName) . 'Attribute';
    if (method_exists($this, $method)) {
      return $this->{$method}();
    }
  }

} // ends class HtmlMetaHeaderHelper
