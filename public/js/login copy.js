//Get password field and button
const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("form_password");
// Add event listener to the button to toggle the password to text and back
togglePassword.addEventListener("click", function (e) {
  const type =
    password.getAttribute("type") === "password" ? "text" : "password";
  password.setAttribute("type", type);
  this.classList.toggle("fa-eye-slash");
});

document
  .getElementById("submit-btn")
  ?.addEventListener("click", function (event) {
    event.preventDefault();
    checkZipCode();
    checkPhoneNumber();

    if (checkZipCode() && checkPhoneNumber() && checkHouseNumber()) {
      submitForm();
    }
  });

document
  .getElementById("submit-btn-login")
  ?.addEventListener("click", function (event) {
    event.preventDefault();
    document.getElementById("login_form").requestSubmit();
  });

function checkHouseNumber() {
  var form_housenumber = document.getElementById("form_house_number").value;
  if (
    /^[0-9]{1,4}[a-zA-Z]{0,2}$/.test(form_housenumber) ||
    form_housenumber == ""
  ) {
    document
      .getElementById("form_house_number")
      .classList.replace("is-invalid", "is-valid");
    document.getElementById("label_house_number").innerHTML = "Huisnummer";
    return true;
  }
  document.getElementById("form_house_number").classList.add("is-invalid");
  document.getElementById("label_house_number").innerHTML =
    "Ongeldig Huisnummer!";
  return false;
}
function checkPhoneNumber() {
  if (
    /^((\+|00)(32|31|352)\s?|0)((\s?\d{2}){3}|(\s?\d{3}){2,3})$/gi.test(
      document.getElementById("form_phone").value.replace(/\s/g, "")
    ) ||
    document.getElementById("form_phone").value == ""
  ) {
    document
      .getElementById("form_phone")
      .classList.replace("is-invalid", "is-valid");
    document.getElementById("label_phone_number").innerHTML = "Telefoonnummer";
    return true;
  }
  document.getElementById("form_phone").classList.add("is-invalid");
  document.getElementById("label_phone_number").innerHTML =
    "Ongeldig Telefoonnummer!";
  return false;
}
// Create a function to handle the account creation
function checkZipCode() {
  var form_zipcode = document.getElementById("form_zipcode").value;
  //check if postal code is valid with regex
  if (/(^[0-9]{4}[A-Z]{2}$)/gi.test(form_zipcode) || form_zipcode == "") {
    document
      .getElementById("form_zipcode")
      .classList.replace("is-invalid", "is-valid");
    document.getElementById("label_form_zipcode").innerHTML = "Postcode";

    return true;
  }

  document.getElementById("form_zipcode").classList.add("is-invalid");
  document.getElementById("label_form_zipcode").innerHTML =
    "Ongeldige postcode!";
  return false;
}
function submitForm() {
  document.getElementById("registration_form").requestSubmit();
}