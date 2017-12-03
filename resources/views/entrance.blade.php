<!doctype html>
<html lang="pt_BR">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Notícias do Saber Direito Dois</title>
</head>

<body>
  <h1>Notícias do Saber Direito Dois</h1>

@foreach ($newsobjects as $news_obj)

  {{ $news_obj->newstitle }}
  <br>
  {{ $news_obj->newsdate->format('d/m/Y') }}
  <br>
  <a href="{{ route('newsobjroute', $news_obj->routeurl_as_array) }}">
    Acessar
  </a>
@endforeach

</body>
</html>
