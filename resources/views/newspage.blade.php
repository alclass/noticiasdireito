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
          <a class="nav-link active" href="{{ URL::to('/') }}">Entrada</a>
          <a class="nav-link" href="{{ URL::to('/') }}">Atualidades</a>
          <a class="nav-link" href="//saberdireitodois.direito.win/cursos">Videocursos</a>
          <a class="nav-link" href="//saberdireitodois.direito.win/sobre">Sobre</a>
        </nav>
      </div>
    </div>

    <div class="blog-header">
      <div class="container">
        <h1 class="blog-title">Notícias
             <a href="{{ URL::to('/') }}">
              Direito.Science
            </a>
        </h1>
        <p class="lead blog-description">Assuntos sociojurídicos contemporâneos</p>
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
            <p class="blog-post-meta">Postagem: {{ $news_obj->newsdate->format('d/m/Y') }}</p>
            <p>
              {{ $news_obj->subtitle }}
            </p>
            <p>
              Descrição: {{ $news_obj->description }}
            </p>

            <hr>
              {!! $news_obj->htmlnewspiece !!}
          </div><!-- /.blog-post -->

          <p>
            <br>
          </p>

          <div align="center">
          <nav class="blog-pagination">
            <a class="btn btn-outline-primary" href="#">Anterior</a>
            <a class="btn btn-outline-primary {{ (0==1?'disabled':'') }}" href="#">Próximo</a>
          </nav>
          </div>

<p>
  <br>
</p>

          <div class="blog-post">
            <h3 class="blog-post-title">Videocursos relacionados do
                <a href="{{ $news_obj->get_sabdirdois_root_url() }}">
                  Saber Direito
                </a>
            </h3>
            <br>
            @foreach($news_obj->sabdircursos as $curso)
            <p>
              {{ $loop->iteration }} -
              <a href="{{ $news_obj->gen_outer_url_for_course($curso) }}">
                {{ $curso->title }}
              </a>
            </p>
            @endforeach
          </div><!-- /.blog-post -->


        </div><!-- /.blog-main -->


        <div class="col-sm-3 offset-sm-1 blog-sidebar">
          <div class="sidebar-module sidebar-module-inset">
            <h4>Proposta</h4>
            <p>O Notícias <em>Direito dot Science</em> traz assuntos pontuais e de repercussão no mundo sociojurídico.</p>
          </div>
          <div class="sidebar-module">
            <h4>Arquivo</h4>
            <ol class="list-unstyled">
              @foreach ($news_obj->get_previous_months_as_objs() as $monthobj)
                <li><a href="{{ route('newspermonthroute', $monthobj->routeurl_as_array) }}">{{ $monthobj->monthstr }}</a></li>
              @endforeach
            </ol>
          </div>
          <div class="sidebar-module">
            <h4>Portais</h4>
            <ol class="list-unstyled">
              <li><a href="//saberdireitodois.direito.win">Saber Direito Dois</a></li>
              <li><a href="//direito.science"></a>Direito dot Science</li>
              <li><a href="//direito.win"></a>Direito dot Win</li>
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
@endsection
