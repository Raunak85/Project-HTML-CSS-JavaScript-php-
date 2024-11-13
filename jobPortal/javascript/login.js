
document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");
    const errorMessage = document.getElementById("error-message");

    loginForm.addEventListener("submit", (event) => {
      
        errorMessage.textContent = "";

     
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();
        const role = document.getElementById("role").value;

        
        if (!validateEmail(email)) {
            errorMessage.textContent = "Please enter a valid email address.";
            event.preventDefault(); 
            return;
        }
        
        if (password.length < 6) {
            errorMessage.textContent = "Password must be at least 6 characters long.";
            event.preventDefault(); 
            return;
        }

        if (!role) {
            errorMessage.textContent = "Please select a role.";
            event.preventDefault(); 
        }
    });

    // Email validation function
    function validateEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }
});
