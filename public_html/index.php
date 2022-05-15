<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cipher</title>
  <link rel="stylesheet" href="static/css/base.css">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="static/js/index.js"></script>
</head>
<body>
  <div id="content">
    <div id="tools_block">
      <div class="label" id="tools"><p>Tools</p></div>
      <ul id="tools_ul">
        <li id="caesar_encode" onclick="to_recipe(this)">Caesar Encode</li>
        <li id="caesar_decode" onclick="to_recipe(this)">Caesar Decode</li>
        <li id="vigenere_encode" onclick="to_recipe(this)">Vigenere Encode</li>
        <li id="vigenere_decode" onclick="to_recipe(this)">Vigenere Decode</li>
        <li id="substitution_cipher" onclick="to_recipe(this)">Substitution cipher</li>
      </ul>
    </div>

    <div class="block_box" id="input_block">
      <div class="label" id="input"><p>Input</p></div>
      <textarea id="input_textarea" autofocus></textarea>
    </div>

    <div class="block_box" id="recipe_block">
      <div class="label" id="recipe"><p>Recipe</p></div>
      <ul id="recipe_ul">
      </ul>
    </div>

    <div class="block_box" id="output_block">
      <div class="label" id="output"><p>Output</p></div>
      <textarea id="output_textarea" autofocus></textarea>
    </div>
    
    <div id="go_button" onclick="cipher()">Go</div>
  </div>
</body>
</html>
