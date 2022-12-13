//include CryptoJS
const CryptoJS = require("crypto-js");

//Get password field and button
const togglePassword = document.getElementById('togglePassword');
const password = document.getElementById('form_password');
// Add event listener to the button to toggle the password to text and back
togglePassword.addEventListener('click', function (e) {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
})
document.getElementById("submit-btn").addEventListener("click", function (event) {
    event.preventDefault();
    register();
})
// Create a function to handle the account creation
async function register() {
    let name = document.getElementById("form_name").value;
    let email = document.getElementById("form_email").value.toLowerCase();
    let password = document.getElementById("form_password").value;
    let confirmPassword = document.getElementById("form_control_password").value;
    let registerDate = new Date().toISOString().slice(0, 19).replace('T', ' ');

    // Check the username and password for single quotes
    
    if(name == "" || email == "" || password == "" || confirmPassword == "")
    {
        alert("Please fill in all the fields");
        return;
    }
  
    if(checkRegex(name) || checkRegex(email))
    {
        alert("Please do not use single quotes in the input fields");
        return;
    }
    let parsedPassword = password.replace(/[!@#$%^]/g, "\\$&");
    
    if (parsedPassword != confirmPassword.replace(/[!@#$%^]/g, "\\$&")) {
        alert("The passwords do not match");
        return;
      }


    //encrypt password
    let encryptedPassword = CryptoJS.AES.encrypt(parsedPassword, "secret").toString();
    console.log(parsedPassword, encryptedPassword, name, email)
      //build user info object
    let userInfo = {
        name: name,
        email: email,
        password: encryptedPassword,
        createdAt: registerDate
    }
        

     try{
          //make call to backend
          await fetch('/api/users', {
              method: 'POST',
              headers: {

                  'Content-Type': 'application/json'
              },
              body: JSON.stringify(userInfo)
          })
          .then(response => response.json())
          .then(data => {

              if(data.success)
              {
                  alert("Account created successfully");
                  window.location.href = "/login";
              }
              else
              {
                  alert(data.message);
              }
          })
     }
     catch(e)
     {
            //catch if error
            alert("Error: " + e.message)

     }
}
  function checkRegex(value)
  {
    return !!(new RegExp(/'/g).test(value));
  }
  