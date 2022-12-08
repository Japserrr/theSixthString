// Create a function to handle the account creation
function createAccount() {
    // Get the values of the input fields
    var name = document.getElementById("form_name").value;
    var username = document.getElementById("form_username").value;
    var password = document.getElementById("form_password").value;
    var confirmPassword = document.getElementById("form_control_password").value;
    var regex = /'/;

    // Check the username and password for single quotes
    if (regex.test(username) || regex.test(password)) {
      alert("Invalid input: single quotes are not allowed");
      return;
    }
    var parsedPassword = password.replace(/[!@#$%^]/g, "\\$&");
    var confirmedParsedPassword = confirmPassword.replace(/[!@#$%^]/g, "\\$&");
    // Validate the input values
    if (username == "") {
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