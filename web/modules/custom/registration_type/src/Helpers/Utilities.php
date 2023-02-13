<?php
namespace Drupal\registration_type\Helpers;

use stdClass;

class Utilities {

  /**
   * function Stub, this should be replaced by the actual function.
   *
   * @param bool $get
   *
   * @return void
   */
  static function vul_gender_codes(bool $get = FALSE): array {
    $variable = ['A','B','C','D'];

    foreach ($variable as &$single) {
      $_single = new stdClass();
      $_single->gender_code = $single;
      $single = $_single;
    }
    // Ugly
    return $variable;
  }

  /**
   * @param array $code_array
   * @param string $join
   * @see https://gist.github.com/angry-dan/e01b8712d6538510dd9c
   * @return string
   */
  static public function natural_language_join(array $code_array, string $join = 'and'): string {
    $last = array_pop($code_array);
    if (!empty($code_array)) {
      return implode(', ', $code_array) . ' ' . $join . ' ' . $last;
    }
  }

}
