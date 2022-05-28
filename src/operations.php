<?php

// include frequency of letters
include "const.php";


/*
 * Function that periodically shifts a character within the interval 
 * of two other characters and returns the shifted character
 *
 * @param {char} char  - Original character
 * @param {char} begin - Begin of the interval
 * @param {char} end   - End of the interval
 * @param {int}  key   - Shift number
 *
 * @return {char} Shifted character
*/
function shift_char_mod($char, $begin, $end, $key) {
  $mod = ord($end) - ord($begin) + 1;
  $key = $key%$mod;
  return chr(ord($begin) + ($mod + ord($char) - ord($begin) + $key)%$mod);
}


/*
 * Function that implements slice of string 
 *
 * @param {string} str    - Original character
 * @param {int}    begin  - Begin of the interval
 * @param {int}    end    - End of the interval
 * @param {int}    step   - Step of slice
 *
 * @return {string} Sliced string
*/
function slice_by_step($str, $start, $end, $step) {
  $result = "";
  for($i = $start; $i < min($end, strlen($str)); $i += $step) {
    $result .= $str[$i];
  }
  return $result;
}

/*
 * Function that calculates the distribution of letters in text with default
 * distribution in given language
 *
 * @param {string} text     - given text
 * @param {string} language - Language to that it is being compared
 *
 * @return {int} Final distribution of letters in given text
 *
*/
function frequency_compare($text, $language) {
  $frequency = array_fill(0, count(constant($language)), 0);
  $chars = str_split($text);
  $length = count($chars);
  foreach($chars as $char) {
    $frequency[ord($char) - ord('a')]++;
  }
  //print_r($frequency);
  $final_distribution = 0;
  foreach(array_combine($frequency, constant($language)) as $have => $need) {
    $final_distribution += abs(($have/$length) - $need);
  }

  return $final_distribution;
}

/*
 * Function that implements caesar encryption
 * @param {string} plaintext - Plaintext
 * @param {int}    key       - Shift number
 *
 * @return {string} Encrypted text
*/
function caesar_encode($plaintext, $key) {
  $ciphertext = "";
  $chars = str_split($plaintext);
  foreach($chars as $char) {
    if($char >= "a" and $char <= "z")
      $ciphertext .= shift_char_mod($char, "a", "z", $key);
    else if($char >= "A" and $char <= "Z")
      $ciphertext .= shift_char_mod($char, "A", "Z", $key);
    else if($char >= "0" and $char <= "9")
      $ciphertext .= shift_char_mod($char, "0", "9", $key);
    else
      $ciphertext .= $char;
  }
  return $ciphertext;
}


/*
 * Function that implements caesar decryption 
 * @param {string} ciphertext - Ciphertext
 * @param {int}    key       - Shift number
 *
 * @return {string} Decrypted text
*/
function caesar_decode($ciphertext, $key) {
  $key = -$key; 
  return caesar_encode($ciphertext, $key);
}

/*
 * Function that implements cracking the caesar cipher 
 * @param {string} ciphertext    - Ciphertext 
 * @param {string} language      - Assumend language of plaintext
 * @param {int} [key_min_length=1] - Minimal length of key 
 * @param {int} [key_max_length=15] - Maximal kength of key
 *
 * @return {string} All possible variations of caesar shifts sorted by language match
*/
function caesar_crack($ciphertext, $language, $key_min_length=1, $key_max_length=15) {
  $language = strtoupper($language);
  if(!isset($language)) {
    return "This language is not supported yet";
  }
  $chars = strtolower(preg_replace("/[^a-zA-Z]+/", "", $ciphertext));

  $keys = [];
  for($key = 0; $key < 26; $key++) {
    $keys[$key] = frequency_compare(caesar_decode($chars, $key), $language); 
  }  
  asort($keys);
  $result = "";
  foreach($keys as $key => $distribution) {
    $result .= "Key: ".$key."\n\n".caesar_decode($ciphertext, $key).
                "\n---------------------------------------\n";
  }
  return $result;
}

/*
 * Function that implements vigenere encryption 
 * @param {string} plaintext - Plaintext 
 * @param {string} key       - Key
 *
 * @return {string} Encrypted text
*/
function vigenere_encode($plaintext, $key) {
  if(strlen($key) == 0) 
    return $plaintext;
  $ciphertext = "";
  $chars = str_split($plaintext);
  $key_index = 0;
  foreach($chars as $char) {
    if($char >= "a" and $char <= "z") {
      $ciphertext .= shift_char_mod($char, "a", "z", ord(strtolower($key[$key_index]))-ord('a'));
      $key_index = ($key_index+1)%strlen($key);
    }
    else if($char >= "A" and $char <= "Z") {
      $ciphertext .= shift_char_mod($char, "A", "Z", ord(strtoupper($key[$key_index]))-ord('A'));
      $key_index = ($key_index+1)%strlen($key);
    }
    else 
      $ciphertext .= $char;
  }
  return $ciphertext;
}

/*
 * Function that implements vigenere decryption 
 * @param {string} ciphertext - ciphertext
 * @param {string} key        - Key
 *
 * @return {string} Decrypted text
*/
function vigenere_decode($ciphertext, $key) {
  if(strlen($key) == 0) 
    return $ciphertext;
  $plaintext = "";
  $chars = str_split($ciphertext);
  $key_index = 0;
  foreach($chars as $char) {
    if($char >= "a" and $char <= "z") {
      $plaintext .= shift_char_mod($char, "a", "z", -ord(strtolower($key[$key_index]))+ord('a'));
      $key_index = ($key_index+1)%strlen($key);
    }
    else if($char >= "A" and $char <= "Z") {
      $plaintext .= shift_char_mod($char, "A", "Z", -ord(strtoupper($key[$key_index]))+ord('A'));
      $key_index = ($key_index+1)%strlen($key);
    }
    else 
      $plaintext .= $char;
  }
  return $plaintext;
}



/*
 * Function that implements cracking the vigenere cipher with frequency analysis 
 * @param {string} ciphertext    - Ciphertext 
 * @param {string} language      - Assumend language of plaintext
 * @param {int} [key_min_length=1] - Minimal length of key 
 * @param {int} [key_max_length=15] - Maximal kength of key
 *
 * @return {string} 2 possible keys and plaintexts
*/
function vigenere_crack($ciphertext, $language, $key_min_length=1, $key_max_length=15) {
  $language = strtoupper($language);
  if(!isset($language)) {
    return "This language is not supported yet";
  }

  $chars = strtolower(preg_replace("/[^a-zA-Z]+/", "", $ciphertext));
  $length = strlen($chars);
  $key_max_length = min($key_max_length, $length);
  $keys = [];
  for($key_length = $key_min_length; $key_length <= $key_max_length; $key_length++) {
    $key = "";
    for($key_index = 0; $key_index < $key_length; $key_index++) {
      $group = slice_by_step($chars, $key_index, $length, $key_length);
      $tries = [];
      for($i = ord('a'); $i<=ord('z'); $i++) {
        $distribution = frequency_compare(vigenere_decode($group, chr($i)), $language);
        $tries[chr($i)] = $distribution;
      }
      $key .= array_search(min($tries), $tries);
    }
    $keys[$key] = frequency_compare(vigenere_decode($chars, $key), $language);
  }

  asort($keys);
  $first_key = array_keys($keys)[0];
  $first_plaintext = vigenere_decode($ciphertext, $first_key);  
  $second_key = array_keys($keys)[1];
  $second_plaintext = vigenere_decode($ciphertext, $second_key);  
  
  return "Key: ".$first_key."\n\n".$first_plaintext.
         "\n---------------------------------------\n".
         "Key: ".$second_key."\n\n".$second_plaintext;
}

/*
 * Function that implements substitution cipher 
 * @param {string}                 plaintext - Plaintext 
 * @param {Array.{string, string}} key       - Array that conists of two strings
 *                                             that describe the substitution
 * @return {string} Encrypted text
*/
function substitution_cipher($plaintext, $key) {
  $ciphertext = "";
  $chars = str_split($plaintext);
  $from = $key[0];
  $to = $key[1];
  foreach($chars as $char) {
    $pos = strpos($from, $char);
    if($pos !== false) 
      $ciphertext .= $to[$pos];
    else 
      $ciphertext .= $char;
  }
  return $ciphertext;
}

?>
