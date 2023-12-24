function fetchUserEmail() {
    fetch('../php/fetch_user_email.php')
    .then(response => response.json())
    .then(data => {
        if (data.email) {
            document.getElementById('userEmail').textContent = data.email;
        }
    })
    .catch(error => console.error('Error:', error));
  }
  
  // Call this function on page load
  window.onload = fetchUserEmail;

  document.addEventListener('DOMContentLoaded', (event) => {
    updateUIBasedOnLoginStatus();
});
