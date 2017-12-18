@if(\App::environment('production'))
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
@endif
