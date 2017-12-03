<?php
namespace App\Models\Util;
// use App\Models\Util\StringUtil;

class StringUtil {

  /*
  // size 53 elements
  const accented_letter_conversion_map = [
    'À' => 'a', 'à' => 'a', 'Á' => 'a', 'á' => 'a', 'Â' => 'a', 'ã' => 'a', 'Â' => 'a', 'â' => 'a', 'Ä' => 'a', 'ä' => 'a',
    'È' => 'e', 'è' => 'e', 'É' => 'e', 'é' => 'e', 'Ẽ' => 'e', 'ẽ' => 'e', 'Ê' => 'e', 'ê' => 'e', 'Ë' => 'e', 'ë' => 'e',
    'Ì' => 'i', 'ì' => 'i', 'Í' => 'i', 'í' => 'i', 'Î' => 'i', 'î' => 'i', 'Ĩ' => 'i', 'ĩ' => 'i', 'Ï' => 'i', 'ï ' => 'i',
    'Ò' => 'o', 'ò' => 'o', 'Ó' => 'o', 'ó' => 'o', 'Õ' => 'o', 'õ' => 'o', 'Ô' => 'o', 'ô' => 'o', 'Ö' => 'o', 'ö' => 'o',
    'Ù' => 'u', 'ù' => 'u', 'Ú' => 'u', 'ú' => 'u', 'Û' => 'u', 'û' => 'u', 'Ũ' => 'u', 'ũ' => 'u', 'Ü' => 'u', 'ü' => 'u',
    'Ç' => 'c', 'ç' => 'c', 'Ñ' => 'n', 'ñ' => 'n'];
  */

  // size 21 elements
  const accented_letter_conversion_map = [
    'À' => 'a', 'à' => 'a', 'Á' => 'a', 'á' => 'a', 'Â' => 'a', 'ã' => 'a', 'Â' => 'a', 'â' => 'a',
    'É' => 'e', 'é' => 'e', 'Ê' => 'e', 'ê' => 'e',
    'í' => 'i',
    'Ó' => 'o', 'ó' => 'o', 'Õ' => 'o', 'õ' => 'o', 'Ô' => 'o', 'ô' => 'o',
    'ú' => 'u',
    'ç' => 'c',
    'ñ' => 'n' ];

  public static function convert_space_to_underline($phrase) {
    $phrase = str_replace(' ', '_', $phrase);
    return $phrase;
  } // ends convert_space_to_underline()

  public static function strip_questionmark_exclamationmark_etal($phrase) {
    /*
    The '&', if any, becomes an 'e'.
    The built-in PHP function preg_replace() is used here:
      preg_replace('/[\?\!\,\;\:]/', '', $phrase);

    The str_replace() might have been used, it would be such as:
      $phrase = str_replace('?', '', $phrase);
      $phrase = str_replace('!', '', $phrase);
      etc.
    */
    $phrase = str_replace('&', 'e', $phrase);
    $phrase = preg_replace('/[\?\!\,\;\:]/', '', $phrase);
    // TO-DO: strip the elongated dash "--"
    return $phrase;
  } // ends strip_questionmark_exclamationmark_etal()

  public static function transliterate_accented_vowels_n_cedilla_to_nonaccented($phrase) {
    /*
    The example below shows a target ascii convertion of all vowels (Aa Ee Ii Oo Uu) each with uppercase and lowercase and with:
      ` (crase), ' (agudo), ~ (til), ^ (circunflexo) e " (trema)

    $v = "À|à|Á|á|Â|ã|Â|â|Ä|ä È|è|É|é|Ẽ|ẽ|Ê|ê|Ë|ë Ì|ì|Í|í|Î|î|Ĩ|ĩ|Ï|ï Ò|ò|Ó|ó|Õ|õ|Ô|ô|Ö|ö Ù|ù|Ú|ú|Û|û|Ũ|ũ|Ü|ü Çç Ññ";
    iconv('UTF-8', 'ASCII//TRANSLIT', $v);
    => "A|a|A|a|A|a|A|a|A|a E|e|E|e|E|e|E|e|E|e I|i|I|i|I|i|I|i|I|i O|o|O|o|O|o|O|o|O|o U|u|U|u|U|u|U|u|U|u Cc Nn"

    IMPORTANT:
      the CONSONANTS that were TESTED above are:
        1) the Portuguese Çç and
        2) the Spanish Ññ

    Presumable iconv('UTF-8', 'ASCII//TRANSLIT', $phrase) works with all diacritics if set_locale()
      is UTF-8 correct and compatible (which, under this test, pt-BR seems to generate
      the result correctly).

     setlocale(LC_COLLATE, 'pt_BR'); // pt_BR.utf8
     return iconv('UTF-8', 'ASCII//TRANSLIT', $phrase);
    $phrase = preg_replace('/[ÀàÁáÂãÂâÄä]/', 'a', $phrase);
    $phrase = preg_replace('/[ÈèÉéẼẽÊêËë]/', 'e', $phrase);
    $phrase = preg_replace('/[ÌìÍíÎîĨĩÏï]/', 'i', $phrase);
    $phrase = preg_replace('/[ÒòÓóÕõÔôÖö]/', 'o', $phrase);
    $phrase = preg_replace('/[ÙùÚúÛûŨũÜü]/', 'u', $phrase);
    $phrase = preg_replace('/[Çç]/', 'c', $phrase);
    $phrase = preg_replace('/[Ññ]/', 'n', $phrase);
    */
    // The split function below [preg_split()] maintains the UTF-8 with the '//u' given param.
    $phrase_split = preg_split('//u', $phrase, -1, PREG_SPLIT_NO_EMPTY);
    foreach ($phrase_split as $letter) {
      $bool = array_key_exists($letter, self::accented_letter_conversion_map);
      if ($bool == true) {
        $mappedletter = self::accented_letter_conversion_map[$letter];
        $phrase = str_replace($letter, $mappedletter, $phrase);
      }
    }
    return $phrase;

  } // ends transliterate_accented_vowels_n_cedilla_to_nonaccented()

  public static function convert_phrase_to_nonaccented_url_piecepath($phrase) {
    $phrase = self::convert_space_to_underline($phrase);
    $phrase = self::strip_questionmark_exclamationmark_etal($phrase);
    return self::transliterate_accented_vowels_n_cedilla_to_nonaccented($phrase);
  } // ends convert_phrase_to_nonaccented_url_piecepath()


} // ends class LogUtil
