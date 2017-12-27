<div class="sidebar-module">
  <hr>
  <h4>Portais</h4>
  <ol class="list-unstyled">
    <li><a href="//saberdireitodois.direito.win">Saber Direito Dois</a></li>
    <li><a href="//direito.science">Direito dot Science</a></li>
    <li><a href="//direito.win">Direito dot Win</a></li>
    <hr>
    <li>
      <?php
        $rssfeeds_url = URL::to('/') . '/feed';
      ?>
      <a href="{{ $rssfeeds_url }}">
        <i class="fa fa-rss fa-fw"></i>
          Entradas RSS
      </a>
      <br>
      <span style="font-size:small">
        (para aplicativos leitores)
      </span>
    </li>
  </ol>
</div>
