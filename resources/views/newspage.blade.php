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
  Notícias - Portal Direito.Science
@endsection

@section('css_section')
<!-- Custom styles for this template -->
<link href="{{ asset('blogfrombootstrap.css') }}" rel="stylesheet">
@endsection

@section('bodycontent_section')


  <div class="blog-masthead">
      <div class="container">
        <nav class="nav blog-nav">
          <a class="nav-link active" href="{{ URL::to('/') }}">Notícias Recentes</a>
          <a class="nav-link" href="//saberdireitodois.direito.win/cursos">Videocursos</a>
          <a class="nav-link" href="//saberdireitodois.direito.win/sobre">Sobre</a>
        </nav>
      </div>
    </div>

    <div class="blog-header">
      <div class="container">

        <div class="row">

          <div class="col-sm-2">
            <img src="https://i.pinimg.com/236x/32/01/6c/32016c4943083f05377f8cb3d2900f2f--lady-justice-tattoo-inspiration.jpg"
              alt="Imagem-ícone que representa a Justiça e o Direito com a Deusa Vendada com uma espada"
              height="200" width="100" />
          </div>

            <div class="col-sm-7">
        <h1 class="blog-title">
          <br>
          Notícias
             <a href="{{ URL::to('/') }}">
              Direito.Science
            </a>
        </h1>
        <p class="lead blog-description">
          Assuntos sociojurídicos contemporâneos
        </p>

      </div>



      <div class="col-sm-2">

        <p style="font-size:small">O Portal
          <br>
          Notícias <em>Direito
          <br>
          dot Science</em>
          <br>
          traz assuntos atuais, pontuais e de repercussão no mundo sociojurídico.
        </p>
      </div>

    </div>
  </div>
</div>

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
              Notícia
              <br>
              Anterior
            </a>
            <i class="fa fa-rotate-left"></i>
            <i class="fa fa-align-justify"></i>
            <i class="fa fa-rotate-right"></i>
            <a class="btn btn-outline-primary {{ (0==1?'disabled':'') }}"
              href="{{ route('newsobjectroute', $news_obj->get_next_or_first_routeurl_as_array()) }}">
              Próxima
              <br>
              Notícia
            </a>
          </nav>
          </div>
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

<p>
  <br>
</p>

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

<hr>
  <p style="font-size:small">
    Sistema
    <a name="disqus_comments">
      Disqus
    </a>
    para Discussão e Comentários
  </p>
  <div id="disqus_thread"></div>
  <script>
    var disqus_config = function () {
      this.page.url = "{{ route('newsobjectroute', $news_obj->routeurl_as_array) }}";  // Replace PAGE_URL with your page's canonical URL variable
      this.page.identifier = "{{ $news_obj->gen_uniqueid_for_disqus_et_al() }}"; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
    };
  (function() { // DON'T EDIT BELOW THIS LINE
    var d = document, s = d.createElement('script');
    s.src = 'https://direito-win.disqus.com/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
  })();
  </script>
  <noscript>
    Please enable JavaScript to view the
    <a href="https://disqus.com/?ref_noscript">
      comments powered by Disqus.
    </a>
  </noscript>

        </div><!-- /.blog-main -->


        <div class="col-sm-3 offset-sm-1 blog-sidebar">

          <div class="sidebar-module sidebar-module-inset">



          </div>

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
