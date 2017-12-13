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
  </a> é um portal eletrônico de artigos temáticos na área do Direito. Ele também encontra-se integrado com o
  <a href="//saberdireitodois.direito.win">
    Saber Direito Dois
  </a>, que é uma aplicação web que perfaz uma videoescola que recupera e redifunde os videocursos do Saber Direito da TV Justiça.
</p>

<p>
  O portal possui hoje em seu acervo:
  <a href="{{ route('entranceroute') }}">
    {{ $news_obj->total_de_noticias }} artigos
  </a>, com uma produção média de aproxidamente dez inéditos artigos por mês, um pouco para mais, um pouco para menos.
</p>
<!--
<p>
  Para acompanhar os artigos conforme saiam, de uma forma
  mais automático-notificatória, é possível fazê-lo usando um aplicativo de leitor RSS. Aplicativos tanto para celular quanto para computadores de mesa podem notificar as novas publicações. O endereço-URL para o leitor RSS é:
</p>
<p>
  <a href="{{ route('entranceroute') }}">
    {{ $news_obj->total_de_noticias }} artigos
  </a>
</p>
-->

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
