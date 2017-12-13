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
  Página [Contato]: Notícias Portal Direito.Science:  assuntos sociojurídicos contemporâneos
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

<h1 style="text-align:center">Contato</h1>

<p>
  <br>
  <br>
</p>

<p>
  Atualmente, a melhor opção  para nos contactar é por meio da página <b>Facebook</b> ligada tanto ao Portal
  <a href="{{ route('entranceroute') }}">
    Direito.Science
  </a>
  quanto ao Portal
  <a href="//direito.win">
    Direito.Win.
  </a>
</p>
<p>
  O enlace à retrocitada página <b>Facebook</b> é:
</p>
<p style="text-align:center">
  <a href="//facebook.com/saberdireitodois">
    facebook.com/saberdireitodois
  </a>
</p>

<p>
  Lembramos também que todas as entradas de artigos e notícias no Portal possui, ao final da página, uma interface de discussão e comentários do <b>Disqus</b>, que é o sistema terceiro que usamos para a interatividade por artigo e notícia.
</p>


<br>
<hr>
<br>

</div><!-- /.blog-main -->
<div class="col-sm-3 offset-sm-1 blog-sidebar">
  <div class="sidebar-module sidebar-module-inset">
  </div>
  @include('frag_templates/sidebar_arquivo')
  <div class="sidebar-module">
    <hr>
    <h4>Portais</h4>
    <ol class="list-unstyled">
      <li><a href="//saberdireitodois.direito.win">Saber Direito Dois</a></li>
      <li><a href="//direito.science">Direito dot Science</a></li>
      <li><a href="//direito.win">Direito dot Win</a></li>
    </ol>
  </div>
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
