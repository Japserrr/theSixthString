
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
    let email = document.getElementById("form_email").value.toLowerCase();
    let password = document.getElementById("form_password").value;
    let first_name = document.getElementById("form_firstname").value;
    let last_name = document.getElementById("form_lastname").value;
    try{
        let infix = document.getElementById("form_infix").value;
        let phone = document.getElementById("form_phone").value;
        let address = document.getElementById("form_address").value;
        let postal_code = document.getElementById("form_postalcode").value;
        let city = document.getElementById("form_city").value;
        let country = document.getElementById("form_country").value;

        if(checkRegex(infix) || checkRegex(phone) || checkRegex(address) || checkRegex(postal_code) || checkRegex(city) || checkRegex(country))
        {
            alert("Please do not use single quotes in the input fields");
            return;
        }
    }
    catch(e)
    {
        console.log(e.message);
    }
   

    let registerDate = new Date().toISOString().slice(0, 19).replace('T', ' ');

    // Check the username and password for single quotes
    
    if(first_name == "" || email == "" || password == "" || last_name == "")
    {
        alert("Vul A.U.B. alle benodigde velden in.");
        return;
    }
  
    if(checkRegex(email) ||  checkRegex(first_name) || checkRegex(last_name))
    {
        alert("Gebruik A.U.B. geen enkele aanhalingstekens in de invoervelden");
        return;
    }
    
    //encrypt password
    let encryptedPassword = CryptoJS.AES.encrypt(password, "secret").toString();
  
      //build user info object
    let userInfo = {
        first_name: first_name,
        email: email,
        password: encryptedPassword,
        last_name: last_name,
        createdAt: registerDate
    }
        

     try{
          //make call to backend
          await fetch('/controllers/PageController.php', {
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
  