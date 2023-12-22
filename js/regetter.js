function register_validation() {

    //...
  
    if(!validateName(firstName)) {
      errorName.style.display = "block";
      return false;
    }
  
    if(!validateUsername(username)) {
      errorUsername.style.display = "block";
      return false;  
    }
  
    if(!validatePassword(password)) {
      errorPassword.style.display = "block";
      return false;
    }
  
    return true;
  }
  
  function validateName(name) {
    //regex check name
    return true; 
  }
  
  function validateUsername(username) {
    //regex check username
    return true;
  }
  
  function validatePassword(password) {
    //regex check password 
    return true;
  }
  
    function updateValue(val) {
      document.getElementById('value').innerText = val;
  }