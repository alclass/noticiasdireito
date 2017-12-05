@extends('basetemplates.masterlayout')
@section('title')
  Notícias - Portal Direito.Science
@endsection

@section('css_section')
<?php
// v4-alpha.getbootstrap.com/examples/blog/
// colorlib.com/wp/css-layouts
// https://codepen.io/renaudtertrais/full/xJFny/
?>
  <link rel="stylesheet" href="{{ asset('css/flatblogcss3.css') }}">
  <script src="{{ asset('js/flatblogcss3.js') }}"></script>
@endsection

@section('bodycontent_section')

<h2 style="text-align:center" class="text-primary">Direito.Science</h2>
<h1 id="header" class="text-primary">Notícias</h1>

<div class="container list-article">
  <div class="btn-group pull-right" id="switch-view">
    <button class="btn btn-primary">
      <span class="icon-th-large"></span>
    </button>
    <button class="btn btn-primary active">
      <span class="icon-th-list"></span>
    </button>
  </div>
  <div class="clearfix"></div>

  <div class="row">
    @foreach ($newsobjects as $news_obj)
    <div class="col-xs-12 article-wrapper">
      <article>
        <a href="{{ route('newsobjroute', $news_obj->routeurl_as_array) }}" class="more">Acessar</a>
        <div class="img-wrapper"><img src="http://lorempixel.com/150/150/fashion" alt="" /></div>
        <h1>{{ $news_obj->newstitle }}</h1>
        <p>{{ $news_obj->newsdate->format('d/m/Y') }}</p>
        <p>
          {{ $news_obj->description }}
          <br>
          <a href="{{ route('newsobjroute', $news_obj->routeurl_as_array) }}">
            Acessar
          </a>
        </p>
      </article>
    </div>
    @endforeach
  </div>

<hr>

<h3>Últimos Videocursos do Saber Direito</h3>
<?php
  $sabdircursos = \App\Models\SabDirModels\SabDirCurso::getLastN(4);
?>

<div class="clearfix"></div>
<div class="row">


@foreach ($sabdircursos as $curso)
<div class="col-xs-12 article-wrapper">
  <article>
    <a href="{{ $curso->gen_outer_url() }}" class="more">Acessar</a>
    <div class="img-wrapper"><img src="http://lorempixel.com/150/150/fashion" alt="" /></div>
    {{ $loop->iteration }} )
    <h1>{{ $curso->title }}</h1>
    <p>{{ $curso->firstemissiondate->format('d/m/Y') }}</p>
    <p>
      Ministrado: {{ $curso->get_professores_with_first_n_last_names_str() }}
      <br>
      <em><b>Temas e Aulas:</b></em> {{ $curso->get_lecture_titles_as_one_text() }}
      <br>
      Originalmente televisionado entre
      <i class="fa fa-calendar fa-1g" aria-hidden="true"></i>
      {{ $curso->firstemissiondate->format('d/m/Y') }}
      e {{ $curso->finishdate->format('d/m/Y') }}
      <br>
      <a href="{{ $curso->gen_outer_url() }}">
        Acessar
      </a>
    </p>
  </article>
</div>
@endforeach
<br>
Estes e todos os {{ $curso->instance_count_cursos() }} cursos do acervo completo estão disponíveis a partir do Portal
<a class="button" href="{{ $news_obj->get_sabdirdois_root_url() }}">
  Saber Direito Dois
</a>.
</div>

</div>
@endsection
