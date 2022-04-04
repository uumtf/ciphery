<?php

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
