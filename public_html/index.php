<html lang="en">
<head>
  <meta charset="UTF-8">
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
      <div class="list">
        <ul id="tools_ul">
          <li id="caesar_encode" onclick="to_recipe(this)">Caesar Encode</li>
          <li id="caesar_decode" onclick="to_recipe(this)">Caesar Decode</li>
          <li id="vigenere_encode" onclick="to_recipe(this)">Vigenere Encode</li>
          <li id="vigenere_decode" onclick="to_recipe(this)">Vigenere Decode</li>
          <li id="substitution_cipher" onclick="to_recipe(this)">Substitution cipher</li>
        </ul>
      </div>
    </div>
    <div id="recipe_block">
      <div class="label" id="recipe"><p>Recipe</p></div>
      <div class="list" id="recipe_list">
        <ul id="recipe_ul">
        </ul>
      </div>
    </div>
    <div id="io_block">
      <div class="label" id="input"><p>Input</p></div>
        <div class="textarea" id="input_text">
        <textarea id="input_textarea" autofocus></textarea>
        </div>
      <div id="go_button" onclick="cipher()">Go</div>
      <div class="textarea" id="output_text">
        <textarea id="output_textarea" readonly></textarea>
      </div>
    </div>
  </div>
</body>
</html>
