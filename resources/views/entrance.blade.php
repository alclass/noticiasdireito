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

</body>
</html>
