<!doctype html>
<html lang="pt">
  <head>

    <meta charset="UTF-8">
    <meta http-equiv="Content-Language" content="pt">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--meta name="viewport" content="width=device-width, initial-scale=1"-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>

  <meta name="description" content="Portal de Notícias Jurídicas do Direito.Science">
  <meta name="keywords" content="Direito, Direito Administrativo, Direito Ambiental, Biodireito, Direito Civil, Contratos, Contratual, Sucessório, Consumidor, Obrigações, Reais, Responsabilidade Civil, Direito Constitucional, Direito Econômico, Direito Eleitoral, Direito Empresarial, Filosofia e Propedêuticas, Direito Financeiro, Direito Internacional, Direito Privado, Direito Público, Direito Previdenciário, Direito Trabalhista, Temas Transdisciplinares, Moderno, Interdisciplinar, Parte Geral, Parte Especial, Teorias, Prática, Judiciário, Judicial, Juizados, Tribunal, STJ, STF, Extrajudicial, Ministério Público, Advocacia, Direito Penal, Criminologia, Direito Processual, Direito Tributário, Ciências Sociais, Sociologia, Democracia, Estado Democrático, Exame de Ordem OAB, Editora, Livraria, Portal de Notícias">
  <meta name="author" content="Direito Dot Win Publisher Co">

<!-- Bootstrap -->
<!--link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
<link rel="stylesheet" href="//v4-alpha.getbootstrap.com/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<script type="text/javascript" scr="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<!-- Global site tag (gtag.js) - Google Analytics -->
@if (App::environment('production'))
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-110822744-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-110822744-1');
</script>
@endif

@include('feed::links')

@yield('css_section')
</head>
<body>

@yield('bodycontent_section')

</body>
@yield('scripts_section')
</html>
