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
  {{ $news_obj->newstitle }} Notícias - Portal Direito.Science
@endsection

@section('css_section')
<!-- Custom styles for this template -->
<link href="{{ asset('blogfrombootstrap.css') }}" rel="stylesheet">
@endsection

@section('bodycontent_section')

@include('basetemplates/pagesheader')

    <p>
      <br>
    </p>

    @if(!empty($news_obj))
    <div class="container">

      <div class="row">

        <div class="col-sm-8 blog-main">

          <div class="blog-post">
            <h2 class="blog-post-title">{{ $news_obj->newstitle }}</h2>
            <p class="blog-post-meta">
              Postagem:
              <em><b>
                {{ $news_obj->newsdate->format('d/m/Y') }}
              </em></b>
            </p>
            <hr>
            <p>
              {{ $news_obj->subtitle }}
            </p>
            <p>
              <b><em>Descrição:</em></b>
            </p>
            <p>
              {{ $news_obj->description }}
            </p>

            <hr>
              {!! $news_obj->htmlnewspiece !!}
          </div><!-- /.blog-post -->

          <p>
            <br>
            <a class="btn btn-outline-primary"
              href="#disqus_comments">
              Comentar | Discutir esta notícia
            </a>
            <!--a class="btn btn-outline-primary"
              href="#disqus_thread">
              Comentar
            </a-->
          </p>
          <p>
            <br>
          </p>

          <div align="center">
          <nav class="blog-pagination">
            <a class="btn btn-outline-primary"
              href="{{ route('newsobjectroute', $news_obj->get_previous_or_last_routeurl_as_array()) }}">
              Artigo
              <br>
              Anterior
            </a>
            <i class="fa fa-rotate-left"></i>
            <i class="fa fa-align-justify"></i>
            <i class="fa fa-rotate-right"></i>
            <a class="btn btn-outline-primary {{ (0==1?'disabled':'') }}"
              href="{{ route('newsobjectroute', $news_obj->get_next_or_first_routeurl_as_array()) }}">
              Próximo
              <br>
              Artigo
            </a>
          </nav>
          </div>

@include('frag_templates/googleads_include')


<br>
<hr>
<br>
          <div class="blog-post">
            <h3 class="blog-post-title">Videocursos relacionados do
                <a href="{{ $news_obj->get_sabdirdois_root_url() }}">
                  Saber Direito
                </a>
            </h3>
            <br>
<table>
  <col width="10%">
  <col width="4%">
  <col width="2%">
  <col width="12%">
  <col width="2%">
  <col width="70%">
    @foreach($news_obj->sabdircursos as $curso)
    <tr>
      <td></td>
      <td>
        {{ $loop->iteration }}º)
      </td>
      <td></td>
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
      </td>
    </tr>
    @endforeach
</table>
          </div><!-- /.blog-post -->


@include('frag_templates/disqus_include')

        </div><!-- /.blog-main -->
        <div class="col-sm-3 offset-sm-1 blog-sidebar">
          <div class="sidebar-module sidebar-module-inset">
          </div>
          @include('frag_templates/sidebar_gsearch')          
          @include('frag_templates/sidebar_arquivo')
          @include('frag_templates/sidebar_portais_n_rss')
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
        <a href="{{ URL::to('/') }}">
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
