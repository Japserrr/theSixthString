
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
    let first_name = document.getElementById("form_firstname").value;
    let last_name = document.getElementById("form_lastname").value;
  
    let infix = document.getElementById("form_infix").value;
    let phone = document.getElementById("form_phone").value;
    let address = document.getElementById("form_address").value;
    let postal_code = document.getElementById("form_zipcode").value;
    let city = document.getElementById("form_city").value;
    let country = document.getElementById("form_country").value;   
   
    
    if(checkRegex(email) ||  checkRegex(first_name) || checkRegex(last_name) || checkRegex(infix) || checkRegex(phone) || checkRegex(address) || checkRegex(postal_code) || checkRegex(city) || checkRegex(country) )
    {
        alert("Gebruik A.U.B. geen enkele aanhalingstekens in de invoervelden");
        return;
    }
    document.getElementById("registration_form").requestSubmit();

       
}
  function checkRegex(value)
  {
    return !!(new RegExp(/'/g).test(value));
  }
  