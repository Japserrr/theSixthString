// Create a function to handle the account creation
function createAccount() {
    // Get the values of the input fields
    var name = document.getElementById("form_name");
    var password = document.getElementById("form_password");
    var confirmPassword = document.getElementById("form_control_password");
    var regex = /'/;
    // Check the username and password for single quotes
    if (regex.test(name) || regex.test(password) || regex.test(confirmPassword)) {
      alert("Invalid input: single quotes are not allowed");
      return;
    }
    alert("ok")
    var parsedPassword = password.replace(/[!@#$%^]/g, "\\$&");
    var confirmedParsedPassword = confirmPassword.replace(/[!@#$%^]/g, "\\$&");
    // Validate the input values
    if (name == "") {
      alert("Please enter a username");
      return;
    }
    if (parsedPassword == "") {
      alert("Please enter a password");
      return;
    }
    if (parsedPassword != confirmedParsedPassword) {
      alert("The passwords do not match");
      return;
    }
  
    // Create the account
    alert("Account created successfully!");
  }