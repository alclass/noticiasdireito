<!doctype html>
<html lang="pt_BR">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Notícias - Portal Direito.Science</title>
</head>

<body>
  <h1>Direito.Science</h1>
  <h1>Portal de Notícias</h1>

@foreach ($newsobjects as $news_obj)
<p>
  {{ $news_obj->newstitle }}
  <br>
  {{ $news_obj->newsdate->format('d/m/Y') }}
  <br>
  <a href="{{ route('newsobjroute', $news_obj->routeurl_as_array) }}">
    Acessar
  </a>
</p>
@endforeach

<hr>

<h3>Últimos Videocursos do Saber Direito</h3>
<?php
  $sabdircursos = \App\Models\SabDirModels\SabDirCurso::getLastN(4);
?>
@foreach ($sabdircursos as $curso)
<p>
  <em>{{ $curso->firstemissiondate->format('d/m/Y') }}</em>
  <br>
  {{ $loop->iteration }} )
  <a href="{{ $curso->gen_outer_url() }}">
    {{ $curso->title }}
  </a>
  <br>
  Ministrado pelo(a/s) {{ $curso->get_professores_with_first_n_last_names_str() }}
  <br>
  Originalmente televisionado entre {{ $curso->firstemissiondate->format('d/m/Y') }}
  e {{ $curso->finishdate->format('d/m/Y') }}
  <br>
  <em><b>Temas e Aulas:</b></em> {{ $curso->get_lecture_titles_as_one_text() }}

</p>
@endforeach

<br>
Estes e todos os {{ $curso->instance_count_cursos() }} cursos do acervo completo estão disponíveis a partir do Portal
<a class="button" href="{{ $news_obj->get_sabdirdois_root_url() }}">
  Saber Direito Dois
</a>.

</body>
</html>
