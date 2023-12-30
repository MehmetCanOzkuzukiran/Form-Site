function validateSignupForm() {
  var email = document.getElementById("email").value;
  var password = document.getElementById("newPassword").value;
  var confirmPassword = document.getElementById("confirmPassword").value;

  if (!email || !password || !confirmPassword) {
    alert("Please fill in all fields");
    return false;
  }

  if (password !== confirmPassword) {
    alert("Passwords do not match");
    return false;
  }

  return true;
}
  
function validateLoginForm() {
  var email = document.getElementById("email").value;
  var password = document.getElementById("passwordField").value;

  if (!email || !password) {
    alert("Please fill in all fields");
    return false;
  }

  return true;
}
  
  function sendPostData() {
    var title = document.getElementById("title").value;
    var content = document.getElementById("content").value;
  
    if (!title || !content) {
      alert("Please fill in all fields");
      return;
    }
  
    var data = {
      title: title,
      content: content
    };
  
    fetch('path/to/postCreation.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
      console.log('Success:', data);
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  }
  
  function previewImage(input) {
    var fileInput = input;
    var image = document.getElementById('previewImage');

    // Check if a file is selected
    if (!fileInput.files || !fileInput.files[0]) {
        alert("Please select a file");
        return;
    }

    var reader = new FileReader();

    reader.onload = function (e) {
        image.src = e.target.result;
    };

    reader.readAsDataURL(fileInput.files[0]);
}

  
  function togglePasswordVisibility(inputId, confirmId) {
    var passwordInput = document.getElementById(inputId);
    var confirmInput = document.getElementById(confirmId);

    // Toggle the password input type between "password" and "text"
    passwordInput.type = (passwordInput.type === "password") ? "text" : "password";
    confirmInput.type = (confirmInput.type === "password") ? "text" : "password";
}
function checkPasswordMatch() {
  var newPassword = document.getElementById('newPassword').value;
  var confirmPassword = document.getElementById('confirmPassword').value;
  var mismatchMessage = document.getElementById('passwordMismatch');

  if (newPassword !== confirmPassword) {
      mismatchMessage.style.display = 'block';
  } else {
      mismatchMessage.style.display = 'none';
  }
}
// Function to check login status and update UI
function updateUIBasedOnLoginStatus() {
  fetch('../php/check_login_status.php')
  .then(response => response.json())
  .then(data => {
      if (data.isLoggedIn) {
          document.getElementById('loginButton').style.display = 'none';
          document.getElementById('signupButton').style.display = 'none';
          document.getElementById('logoutButton').style.display = 'block';
      } else {
          document.getElementById('loginButton').style.display = 'block';
          document.getElementById('signupButton').style.display = 'block';
          document.getElementById('logoutButton').style.display = 'none';
      }
  })
  .catch(error => console.error('Error:', error));
}

// Call this function on page load or when needed
updateUIBasedOnLoginStatus();

document.addEventListener('DOMContentLoaded', (event) => {
  updateUIBasedOnLoginStatus();
});
