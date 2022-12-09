window.addEventListener('load', function () {


// Create a function to handle the account creation
function createAccount() {
  // Get the values of the input fields
  let name = document.getElementById("form_name").value;
  let email = document.getElementById("email").value;
  let password = document.getElementById("form_password").value;
  let confirmPassword = document.getElementById("form_control_password").value;
  console.log(name);
  //check with regex for character ' 
  let regex = new RegExp(/'/g) ;
  
  // Check if any of the fields are empty

  if(name.value == "" || password.value == "" || confirmPassword.value == "") {
    alert("Please fill out all fields");
    return;
  }
  console.log( regex.test(name))

  // Check the username and password for single quotes
  if (regex.test(name) || regex.test(password) || regex.test(confirmPassword) || regex.test(email)) {
    alert("Invalid input: single quotes are not allowed");
    return;
  }
  
  // let parsedPassword = password.replace(/[!@#$%^]/g, "\\$&");
  // let confirmedParsedPassword = confirmPassword.replace(/[!@#$%^]/g, "\\$&");
  

  // if (parsedPassword != confirmedParsedPassword) {
  //   alert("The passwords do not match");
  //   return;
  // }
 
  // Create the account
  alert("Account created successfully!");
}

});

