<!doctype html>
<html lang="pt_BR">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Página de Notícia</title>
</head>

<body>
  <h3>Notícias do Direito.Science</h3>

@if(!empty($news_obj))

  <h1>{{ $news_obj->newstitle }}</h1>
  <p>
    Postagem: {{ $news_obj->newsdate->format('d/m/Y') }}
  </p>

  <p>
    {{ $news_obj->subtitle }}
    <br>
    Descrição: {{ $news_obj->description }}
  </p>

  <hr>
    {!! $news_obj->htmlnewspiece !!}

  <p>
    Cursos do
    <a href="{{ $news_obj->get_sabdirdois_http_url() }}">
      Saber Direito
    </a> relacionados:
  </p>
  @foreach($news_obj->sabdircursos as $curso)
  <p>
    {{ $loop->iteration }} - 
    <a href="{{ $news_obj->gen_outer_url_for_course($curso) }}">
      {{ $curso->title }}
    </a>
  </p>
  @endforeach

@endif

</body>
</html>
