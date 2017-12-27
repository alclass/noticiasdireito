<?php
  if (!isset($news_obj) || $news_obj==null) {
    $news_obj = \App\Models\NewsModels\NewsObject
      ::get_last_or_create_mock();
  }
?>
<div class="sidebar-module">
  <hr>
  <h4>Arquivo</h4>
  <ol class="list-unstyled">
    @foreach ($news_obj->get_previous_months_as_objs() as $monthobj)
      <li>
        <a href="{{ route('newspermonthroute', $monthobj->routeurl_as_array) }}">
          {{ $monthobj->monthstr }}
        </a>
        <span style="font-size:small">
          ({{ $monthobj->total_newspieces }})
        </span>
      </li>
    @endforeach
    <hr>
    <li>
      <a href="{{ route('entranceroute') }}">
        Todas
      </a>
      <span style="font-size:small">
        ({{ $news_obj->total_de_noticias }})
      </span>
    </li>
  </ol>
</div>
