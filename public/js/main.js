
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
    checkZipCode();
})
// Create a function to handle the account creation
function checkZipCode() {
    var form_zipcode = document.getElementById("form_zipcode").value;
      //check if postal code is valid with regex
    if (/(^[0-9]{4}[A-Z]{2}$)/gi.test(form_zipcode ) || form_zipcode == "") {
        document.getElementById("registration_form").requestSubmit();
    
        return;
    }
    alert("Please enter a valid postal code");
}
  