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
        <h1>{{ $news_obj->newstitle }}</h1>
        <p>{{ $news_obj->newsdate->format('d/m/Y') }}</p>
        <p>
          {{ $news_obj->description }}
          <br>
          <div style="text-align:center">
          <a href="{{ route('newsobjectroute', $news_obj->routeurl_as_array) }}"
             class="more">
            Acessar
          </a>
        </div>
        </p>
        <div class="img-wrapper"><img src="https://i.pinimg.com/236x/32/01/6c/32016c4943083f05377f8cb3d2900f2f--lady-justice-tattoo-inspiration.jpg"
           alt="Direito Imagem"
           height="200" width="100" /></div>
      </article>
    </div>
    @endforeach
  </div>

{{ $newsobjects->links() }}

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
      <a href="{{ $curso->gen_outer_url() }}" class="more">
        Acessar
      </a>
    </p>
  </article>
</div>
@endforeach
<br>
Estes e todos os {{ $curso->instance_count_cursos() }} cursos do acervo completo estão disponíveis a partir do Portal
<?php
  if (!isset($news_obj) || empty($news_obj)) {
    $news_obj = \App\Models\NewsModels\NewsObject::get_last_or_create_mock();
  }
?>
<a class="button" href="{{ $news_obj->get_sabdirdois_root_url() }}">
  Saber Direito Dois
</a>.
</div>

</div>
@endsection
