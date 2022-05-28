
/*
 * Function that adds an operation to the recipe
 *
 * @param {HTMLElement} operation - Li tag of operation
*/
function to_recipe(operation) {
  const operation_id = operation.getAttribute("id");
  const div_text = operation.innerHTML;
  let ul = document.getElementById("recipe_ul");
  let li = document.createElement("li");
  
  let div = document.createElement("div");
  div.className = "operation_label";
  let name = document.createElement("span");
  name.className = "operation_name";
  name.innerHTML = div_text;
  let icons = document.createElement("span");

  let pause = document.createElement("span");
  pause.className = "icon material-icons";
  pause.innerHTML = "pause";
  pause.setAttribute("onclick", "change_state(this)");
  
  let remove = document.createElement("span");
  remove.className = "icon material-icons";
  remove.innerHTML = "close";
  remove.setAttribute("onclick", "from_recipe(this)");
  
  icons.appendChild(pause);
  icons.appendChild(remove);

  div.appendChild(name);
  div.appendChild(icons);

  let input = document.createElement("input");
  input.setAttribute("name", operation_id);
  input.setAttribute("oninput", "save_recipe(this)");
  input.setAttribute("placeholder", "Key");

  // set attributes for a caesar encode or decode operation
  // key can be only from 1-25 since there are 26 letters in the English alphabet
  if(operation_id == "caesar_encode" ||
      operation_id == "caesar_decode") {
    input.setAttribute("type", "number");
    input.setAttribute("min", "1");
    input.setAttribute("max", "25");
    input.setAttribute("value", "3");
    input.setAttribute("title", "1-25");
  }
  // set attributes for a caesar encode or decode operation
  // key consists of small and capital latin letters
  if(operation_id == "vigenere_encode" ||
    operation_id == "vigenere_decode") {
    input.setAttribute("type", "text");
    input.setAttribute("pattern", "[a-zA-Z]+");
    input.setAttribute("title", "Only english letters");
  }
  li.appendChild(div);
  if(operation_id == "vigenere_crack") {
    let select = document.createElement("select");
    select.setAttribute("name", operation_id);
    let option_english = document.createElement("option");
    option_english.setAttribute("value", "english");
    option_english.innerText = "English";
    let option_german = document.createElement("option");
    option_german.setAttribute("value", "german");
    option_german.innerText = "German";
    select.appendChild(option_english);
    select.appendChild(option_german);
    li.append(select);
  }
  else {
    li.appendChild(input);
  }

  // set attributes for a substitution cipher
  // the plaintext and ciphertext inputs without duplicates
  if(operation_id == "substitution_cipher") {
    input.setAttribute("placeholder", "From");
    input.setAttribute("pattern", "^(?!.*(.).*\\1).+$");
    input.setAttribute("title", "No duplicate characters");
    let second_input = document.createElement("input");
    second_input.setAttribute("type", "text");
    second_input.setAttribute("placeholder", "To");
    second_input.setAttribute("oninput", "save_recipe(this)");
    second_input.setAttribute("pattern", "^(?!.*(.).*\\1).+$");
    second_input.setAttribute("title", "No duplicate characters");
    li.appendChild(second_input);
  }
  ul.appendChild(li);
  // save the recipe in sessionStorage so that it doesn't disappear when page is reloaded
  sessionStorage.setItem("recipe", ul.innerHTML);
  // cipher the input text
}

/*
 * Function that changes state of the operation in the recipe 
 * 
 * @param {HTMLSpanElement} change_state_icon - Span tag of the change_state icon 
*/

function change_state(change_state_icon) {
  let li = change_state_icon.parentNode.parentNode.parentNode;
  
  // if operation is resumed than change the icon to play arrow symbol 
  // and add data-paused attribute from operations li
  if(change_state_icon.innerHTML == "pause") {
    change_state_icon.innerHTML = "play_arrow";
    li.setAttribute("data-paused", "true");
  }

  // else change the icon to pause symbol
  // and remove data-paused attribute to operations li
  else {
    change_state_icon.innerHTML = "pause";
    li.removeAttribute("data-paused");
  }

}

/*
 * Function that removes an operation from recipe
 * 
 * @param {HTMLElement} remove_icon - Span tag of the remove icon
*/
function from_recipe(remove_icon) {
  // remove operation and cipher the input text
  let li = remove_icon.parentNode.parentNode.parentNode;
  li.remove();
  // save the recipe in sessionStorage 
  const ul = document.getElementById("recipe_ul");
  sessionStorage.setItem("recipe", ul.innerHTML);
}

/*
 * Function that saves the recipe in sessionStorage oninput  
 * @param {HTMLElement} remove_icon - Input tag
*/
function save_recipe(input) {
  input.setAttribute("value", input.value);
  const ul = document.getElementById("recipe_ul");
  sessionStorage.setItem("recipe", ul.innerHTML);
}

// Restore the recipe from sessionStorage when page is reloaded
window.onload = function() {
  if("recipe" in sessionStorage) {
    let ul = document.getElementById("recipe_ul");
    ul.insertAdjacentHTML("afterbegin", sessionStorage.getItem("recipe"));
  }
}

/*
 * Function that ciphers the input text based on the recipe
*/
function cipher() {
  const ul = document.getElementById("recipe_ul");
  const plaintext = document.getElementById("input_textarea").value;
  let operations = [];
  let inputs_valid = true;
  // loop through each operaation, check validity of input and add this operation to the operations array
  Array.from(ul.children).forEach(
    (li) => {
      if(li.getAttribute("data-paused") == "true")
        return;

      let input = li.children[1];
      
      if(input.checkValidity() === false) 
        inputs_valid = false;
     
      let op_name = input.getAttribute("name");
      let op_value = input.value;
      
      if(op_name == "substitution_cipher") {
        let second_input = li.children[2];
        if(second_input.checkValidity() === false) 
          inputs_valid = false;
        op_value = [
          input.value,
          second_input.value
        ];
      }
      let operation = {};
      operation[op_name] = op_value;
      operations.push(operation);
    }
  );

  if(inputs_valid == false) {
    let output_textarea = document.getElementById("output_textarea");
    output_textarea.innerHTML = "Not all inputs are valid"; 
    return;
  }

  let json = {
    "operations": operations,
    "plaintext": plaintext
  };
  // send request to cipher.php code that accepts json consisting list of operations and the input text
  // and response with encrypted text
  fetch("cipher.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(json)
  })
  .then(response => response.text())
  .then(ciphertext => {
    // show the encrypted text in output textarea
    let output_textarea = document.getElementById("output_textarea");
    output_textarea.innerHTML = ciphertext; 
  });
}
