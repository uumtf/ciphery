<?php
if($_SERVER['REQUEST_METHOD'] != "POST"){ 
  echo "What are you doing here?";
  exit;
}

// include of cipher algorithms
include "../src/operations.php";

// decode post data as json
$data = json_decode(file_get_contents('php://input'), true);
$operations = $data["operations"];
$plaintext = $data["plaintext"];
$ciphertext = $plaintext;

// loop through all the operations and encrypt the input text one by one
foreach($operations as $index => $op) {
  foreach($op as $op_name => $op_key) {
    if(function_exists($op_name)) {
      $ciphertext = $op_name($ciphertext, $op_key);
    }
  }
}
// return the encrypted text
echo $ciphertext;

?>
