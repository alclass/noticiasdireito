<?php
  if(!isset($news_obj) || empty($news_obj)) {
    $news_obj = \App\Models\NewsModels\NewsObject::get_last_or_create_mock();
  }
  if(empty($news_obj)) {
    return redirect()->route('entranceroute');
  }
?>
@extends('basetemplates.masterlayout')
@section('title')
  Página [Sobre]: Notícias Portal Direito.Science:  assuntos sociojurídicos contemporâneos
@endsection

@section('css_section')
<!-- Custom styles for this template -->
<link href="{{ asset('blogfrombootstrap.css') }}" rel="stylesheet">
@endsection

@section('bodycontent_section')

@include('basetemplates/pagesheader')

<div class="container">

  <div class="row">
    <div class="col-sm-8 blog-main">
<p>
  <br>
  <br>
</p>

<h1 style="text-align:center">Sobre</h1>

<p>
  <br>
  <br>
</p>

<p>
  O Notícias
  <a href="{{ route('entranceroute') }}">
    Direito.Science
  </a> é um portal eletrônico de artigos temáticos na área do
  <b>Direito</b> e, mais amplamente, das ciências <b>sociojurídicas</b>.
  Ele também se encontra integrado com o
  <a href="//saberdireitodois.direito.win">
    Saber Direito Dois
  </a>, que é uma <em>aplicação web</em> que perfaz uma videoescola
  que recupera e redifunde os videocursos do Saber Direito da
  TV Justiça.
</p>

<p>
  O portal possui hoje em seu acervo:
  <a href="{{ route('entranceroute') }}">
    {{ $news_obj->total_de_noticias }} artigos
  </a>, com uma produção média de aproxidamente dez inéditos artigos por mês,
  um pouco para mais, um pouco para menos.
</p>
<p>
  Para acompanhar os artigos conforme saiam, de uma forma
  mais automático-notificatória,
  é possível fazê-lo com a ajuda de um aplicativo de leitor RSS.
</p>
<p>
  Dependendo de sua plataforma, celular ou computador
  de mesa, procure na <em>Play-Store</em> ou equivalente por um
  <b>aplicativo (ou <em>app</em>) leitor de notícias RSS feeds</b>.
  Depois de instalá-lo, clique ou digite nele o endereço-URL abaixo
  das entradas RSS:
</p>
<hr>
<p style="text-align:center">
<?php
  $rssfeeds_url = URL::to('/') . '/feed';
?>
  <i class="fa fa-rss fa-fw"></i>
  <a href="{{ $rssfeeds_url }}">
    {{ $rssfeeds_url }}
  </a>
</p>
<hr>
<p>
  Instalando o leitor e usando o endereço URL RSS acima, o aplicativo
  lhe fará, dependendo da configuração e algumas outras variáveis,
  notificação automática de novas publicações conforme saiam.
</p>

<br>
<br>

</div><!-- /.blog-main -->
<div class="col-sm-3 offset-sm-1 blog-sidebar">
  <div class="sidebar-module sidebar-module-inset">
  </div>
  @include('frag_templates/sidebar_arquivo')
  @include('frag_templates/sidebar_portais_n_rss')

</div><!-- /.blog-sidebar -->
</div><!-- /.row -->

<br>
<footer class="blog-footer" align="center">
  <br>
  <br>
  <p>
    <a href="#">Voltar ao Topo da Página</a>
  </p>
  <p style="font-size:10px">
    <?php
      $today = $news_obj->today;
    ?>
    &copy; 2017-{{ $today->year }}
    <a href="#">
      Direito.Science
    </a>
  </p>
</footer>
</div><!-- /.container -->

@endsection
