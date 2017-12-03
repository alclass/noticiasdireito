<?php
namespace App\Models\AcadModels;
// use App\Models\AcadModels\KnowledgeAreaDescriptions;

class KnowledgeAreaDescriptions {

  /*

    This 'static const' class represents a quick solution to the knowledge areas' description. The root and 2nd-level knowledge areas had their descriptions hard-coded here.

    (For the time being, this is okay, due to the fact that a DB solution would
    take longer under the contexts we're having now. However, this fix may already be considered a TO-DO for the future.)

  */

  const descriptions = [

    'Direito' => 'A ciência do Direito é um ramo das ciências sociais que estuda as normas obrigatórias que controlam as relações dos indivíduos em uma sociedade. É uma disciplina que transmite aos estudantes de direito um conjunto de conhecimentos relacionados com as normas jurídicas determinadas por cada país. Para alguns autores, é um sinal de organização de uma determinada sociedade, porque indica a recepção de valores e aponta para a dignidade do ser humano. (Fonte: [https://www.significados.com.br/direito/].)',

    'Direito Administrativo' => 'É o ramo do direito público que trata de princípios e regras que disciplinam a função administrativa e que abrange órgãos, agentes e atividades desempenhadas pela Administração Pública na consecução do interesse público.',

    'Direito Ambiental e Biodireito' => 'Direito Ambiental e Biodireito. Direito Ambiental é um ramo do direito, constituindo um conjunto de princípios jurídicos e de normas jurídicas voltado à proteção jurídica da qualidade do meio ambiente.  Biodireito é o ramo do Direito Público que se associa à bioética, estudando as relações jurídicas entre o direito e os avanços tecnológicos conectados à medicina e à biotecnologia, com peculiaridades relacionadas ao corpo e à dignidade da pessoa humana.',

    'Direito Civil' => 'É um ramo do Direito que trata do conjunto de normas reguladoras dos direitos e obrigações de ordem privada concernente às pessoas, aos seus direitos e obrigações, aos bens e às suas relações, enquanto membros da sociedade. (Fonte: [www.significados.com.br].)',

    'Direito Constitucional' => 'É o ramo do direito responsável por analisar e controlar as leis fundamentais que regem o Estado dá-se-lhe o nome de direito constitucional. O seu objecto de estudo é a forma de governo e a regulação dos poderes públicos, tanto na sua relação com os cidadãos como entre os seus vários órgãos. É o ramo do Direito Público apto a expor, interpretar e sistematizar os princípios e normas fundamentais do Estado. É a ciência positiva das constituições. (Fonte: https://conceito.de/direito-constitucional com modificações.)',

    'Demais Temas em Direito Público e Privado' => 'Demais Temas em Direito Público e Privado visa abarcar assuntos que não estão diretamente ligado aos ramos clássicos do Direito, a exemplo do Direito Administrativo, Direito Constitucional, Direito Civil, entre outros. Direito Público envolve o fenômeno jurídico de ordem público, enquanto o Direito Privado rege as relações jurídicas entre particulares, relações essas que não devem remeter à ordem pública, caso que é tratado pelo primeiro.',

    'Direito Econômico e Financeiro' => 'Direito Econômico e Direito Financeiro. Direito Econômico é o ramo do direito que se compõe das normas jurídicas que regulam a produção e a circulação de produtos e serviços, com vista ao desenvolvimento econômico do país jurisdicionado. Direito financeiro é o ramo do direito público que disciplina a atividade financeira do estado, a receita tributária (sub-ramo denominado direito tributário), a receita pública e a despesa pública (direito fiscal e orçamentário).',

    'Direito Eleitoral' => 'É o ramo do Direito Público que visa regular o exercício da soberania popular; representa o ramo jurídico que regula o exercício da Democracia; estabelece as regras para a escolha dos representantes do povo, buscando que a vontade de todos seja convertida em governantes legítimos, eleitos de forma transparente e de acordos com as pretensões da coletividade.',

    'Direito Empresarial' => 'É o ramo do direito privado que pode ser entendido como o conjunto de normas disciplinadoras da atividade negocial do empresário e de qualquer pessoa física ou jurídica, destinada a fins de natureza econômica, desde que habitual e dirigida à produção de bens ou serviços conducentes a resultados patrimoniais ou lucrativos, e que a exerça com a racionalidade própria de "empresa", sendo um ramo especial de direito privado. (Fonte: Wikipédia).',

    'Filosofia e Outras Disciplinas Propedêuticas do Direito' => 'Este ramo faz menção a um conjunto de disciplinas que, em geral, são estudadas no início de um curso de graduação em Direito. Entre estas, podem-se citar: Introdução ao Direito, Filosofia do Direito, Sociologia do Direito, entre outras, inclusive incluindo disciplinas de línguas, como, p. ex., Latim ou Português Jurídicos.',

    'Direito Internacional' => 'É o ramo do Direito que abarca duas principais ramificações. A primeira é o  Direito Internacional Público, que é constituído pelas normas jurídicas internacionais que promovem a existência de uma dita Sociedade Internacional das Nações.  Os acordos, tratados, convenções internacionais, as emendas e os protocolos fazem parte, como exemplos, deste ramo do direito. O Direito Internacional Privado, por sua vez, abarcará os contextos em que os indíviduos e as empresas estejam em situações jurídico-internacionais que não possuem um cunho público-diplomático direto, caso que, da primeira definição acima, ficaria a cargo do Direito Internacional Público.',

    'Judiciário, MP e Correlatos' => 'Este ramo SD2 abarca subáreas do direito que não estão diretamente ligadas às subáreas tradicionais, a exemplo do Direito Administrativo, Direito Civil, Direito Constitucional, entre outras, e, por outro lado, conectam-se mais diretamente a assuntos do Poder Judiciário, do Ministério Público (MP) e correlatos.',

    'Direito Penal' => 'Também conhecido como Direito Criminal, é o ramo do Direito que pode ser entendido por pelo menos dois aspectos, quais sejam, o Formal (ou Estático) e o Material (ou Dinâmico). O aspecto material do Direito Penal refere-se a comportamentos considerados altamente reprováveis ou danosos ao organismo social, afetando bens jurídicos indispensáveis à própria conservação e progresso da sociedade. Já o aspecto formal do Direito Penal é o ramo do direito público dedicado às normas emanadas pelo Poder Legislativo para reprimir os delitos, lhes imputando penas com a finalidade de preservar a sociedade e proporcionar o seu desenvolvimento. (Fonte: Wikipédia com modificações.)',

    'Direito Processual' => 'É o ramo do direito público que contém o repositório de princípios e normas legais que regulamentam os procedimentos jurisdicionais, tendo como objetivo administrar o direito, no sentido de Sistema Judiciário, podendo, em alguns casos, ser um Sistema Extrajudicial. (Fonte: Revista Jurídica Iunib com modificações).',

    'Direito Previdenciário' => 'É o ramo do Direito público que objetiva o estudo e disciplina da seguridade social, em geral, regula e normatiza o que conhecemos como Previdência, seja a Social ou Privada. (Fonte: [direitoepraxis.blogspot.com].)',

    'Direito Trabalhista' => 'É o ramo da ciência do direito que tem por objeto as normas, as instituições jurídicas e os princípios que disciplinam as relações de trabalho subordinado, determinam os seus sujeitos e as organizações destinadas à proteção desse trabalho em sua estrutura e atividade. (Fonte: [www.jurisite.com.br].)',

    'Direito Tributário' => 'É o ramo do Direito que se relaciona com o conjunto de leis que regulamentam o arrecadamento dos tributos, assim como sua fiscalização. É uma parte jurídica que estabelece suas inclusões entre o Estado e os contribuintes com relação à arrecadação dos tributos. (Fonte: www.portaleducacao.com.br com modificações.)',

    // '' => '.',

  ];

  public static function get_hardcoded_breve_descricao($knowledgearea_name) {
    if (!array_key_exists($knowledgearea_name, self::descriptions)) {
      return self::descriptions['Direito'];
    }
    return self::descriptions[$knowledgearea_name];
  }

} // ends class KnowledgeAreaFetcher
