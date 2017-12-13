<?php
  if(!isset($newsobjects) || empty($newsobjects)) {
    $news_obj = \App\Models\NewsModels\NewsObject::get_last_or_create_mock();
    $newsobjects = collect();
    $newsobjects->push($news_obj);
  }
?>
@extends('basetemplates.masterlayout')
@section('title')
  Notícias Portal Direito.Science: assuntos sociojurídicos contemporâneos
@endsection

@section('css_section')
<!-- Custom styles for this template -->
<link rel="stylesheet" href="{{ asset('css/bootstrap_pagination_fragment.css') }}">
@endsection

@section('bodycontent_section')

  @include('basetemplates/pagesheader')

    <p>
      <br>
    </p>


    @if(!empty($newsobjects))
    <div class="container">

      <div class="row">


        <div class="col-sm-8 blog-main">


<?php
  if (!isset($listing_subtitle) || empty($listing_subtitle)) {
    $listing_subtitle = 'Todos os Artigos';
  }
?>

          <div class="container" align="center">
            <h4>{{ $listing_subtitle }}</h4>
            <br>
            @if ($newsobjects->lastPage() > 1)
            <h6 style="color:darkblue">
              Página <b>{{ $newsobjects->currentPage() }}</b> de {{ $newsobjects->lastPage() }} ::
               Exibindo artigos de <b>{{ $newsobjects->firstItem() }}</b> a {{ $newsobjects->lastItem() }} ::
               Total: <b>{{ $newsobjects->total() }}</b> artigos
            </h6>
            {{ $newsobjects->links() }}
            @endif
            <br>
          </div>

          <?php
            $items_per_page = $newsobjects->perPage();
            $current_page = $newsobjects->currentPage();
          ?>
          @foreach($newsobjects as $news_obj)
          <?php
            $n_artigo = $items_per_page * ($current_page - 1) + $loop->iteration;
          ?>
          <div class="blog-post" style="text-align:center">
            <hr>
            <p class="blog-post-meta">
              <em>
                {{ $news_obj->newsdate->format('d/m/Y') }}
              </em>
              <br>
              <span style="font-size:10px">
                Artigo <b>{{ $n_artigo }}</b> de <b>{{ $newsobjects->total() }}</b>
              </span>
            </p>
            <h6 class="blog-post-title">
              <a href="{{ route('newsobjectroute', $news_obj->routeurl_as_array)}}">
                {{ $news_obj->newstitle }}
              </a>
            </h6>

          </div><!-- /.blog-post -->
          @endforeach


<br>


@if ($newsobjects->lastPage() > 1)
<div class="container" align="center">
  {{ $newsobjects->links() }}
  <h6 style="color:darkblue">
    Página <b>{{ $newsobjects->currentPage() }}</b> de {{ $newsobjects->lastPage() }} ::
     Exibindo artigos de <b>{{ $newsobjects->firstItem() }}</b> a {{ $newsobjects->lastItem() }} ::
     Total: <b>{{ $newsobjects->total() }}</b> artigos
  </h6>
</div>
@endif

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
<hr>
      <p style="font-size:small">
        Google Ads
      </p>
      <p>
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <ins class="adsbygoogle"
             style="display:block; text-align:center;"
             data-ad-layout="in-article"
             data-ad-format="fluid"
             data-ad-client="ca-pub-8025632868883287"
             data-ad-slot="6238982907"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
      </p>
<hr>
<h5>
  Videocursos Recentes do Saber Direito
</h5>
<table>
  <col width="4%">
  <col width="2%">
  <col width="12%">
  <col width="2%">
  <col width="60%">
  <col width="20%">
    @foreach($news_obj->get_lastest_n_courses(5) as $curso)
    <tr style="vertical-align:top">
      <td></td>
      <td style="font-size:small">
      </td>
      <td>
        <img src="{{ $curso->get_ytvideothumbnailurl_via_1stprof_by_size() }}"
        height="70"
        width="100"
        alt="Foto-estúdio do curso">
      </td>
      <td></td>
      <td>
        <a href="{{ $news_obj->gen_outer_url_for_course($curso) }}">
          {{ $curso->title }}
        </a>
        <br>
        <span style="font-size:11px">
          Exibição televisiva original de <b>{{ $curso->firstemissiondate->format('d/m/Y') }}</b>
          a
          <b>{{ $curso->finishdate->format('d/m/Y') }}</b>
        <br>
        </span>
        <span style="font-size:9px">
        Armazenado no <em>acervo</em> do
        <a href="http://saberdireitodois.direito.win">
          Saber Direito Dois
        </a>
        <hr>
        </span>
      </td>
      <td></td>
    </tr>
    @endforeach
</table>

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


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="http://v4-alpha.getbootstrap.com/assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="http://v4-alpha.getbootstrap.com/dist/js/bootstrap.min.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="http://v4-alpha.getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>


@endif

<script id="dsq-count-scr" src="//direito-win.disqus.com/count.js" async>
</script>

@endsection
