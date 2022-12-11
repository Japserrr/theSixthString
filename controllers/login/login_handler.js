
// Create a function to handle the account creation
function createAccount() {


  var accountDetails = [];
  // Get the values from the form
  for (const element of document.forms[0].elements) {
  
    if(element.nodeName != "INPUT")
    {
      continue;
    }
    if(element.value == "") {
      
      alert("Please fill out all fields");
      return;
    }
    if(checkRegex(element.value))
    {
      alert("Invalid input: single quotes are not allowed");

      return;
    }

    accountDetails.push({name: element.id, value: element.value});
  }
 


  let parsedPassword = password.replace(/[!@#$%^]/g, "\\$&");
  let confirmedParsedPassword = confirmPassword.replace(/[!@#$%^]/g, "\\$&");

  alert("Account created successfully!");

 

  
  
  
  

  // if (parsedPassword != confirmedParsedPassword) {
  //   alert("The passwords do not match");
  //   return;
  // }
 
  // Create the account
}
function checkRegex(value)
{
  var regex = new RegExp(/'/g) ;

  if(regex.test(value))
  {
    return true;
  }
 return false;
}
