<?php
$login = false;
$showError = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'partials/_dbconnect.php';
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    
    // Check if any field is empty
    if(empty($username) || empty($password)) {
        $showError = "All fields are required";
    } else {
        // $sql = "Select * from users where username='$username' AND password='$password'";
        $sql = "Select * from users where username='$username'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if ($num == 1){
            while($row=mysqli_fetch_assoc($result)){
                if (password_verify($password, $row['password'])){ 
                    $login = true;
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    header("location: welcome.php");
                } 
                else{
                    $showError = "Invalid Credentials";
                }
            }
        } 
        else{
            $showError = "Invalid Credentials";
        }
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="login.css">
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <title>Login</title>
  </head>
  <script>
    // Check for saved theme preference or default to light
    const theme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', theme);
  </script>
  <body>
    <?php require 'partials/_nav.php' ?>
    <button class="theme-toggle" aria-label="Toggle dark mode">
      <svg class="moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/>
      </svg>
      <svg class="sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M12,7c-2.76,0-5,2.24-5,5s2.24,5,5,5s5-2.24,5-5S14.76,7,12,7L12,7z M2,13h2c0.55,0,1-0.45,1-1s-0.45-1-1-1H2 c-0.55,0-1,0.45-1,1S1.45,13,2,13z M20,13h2c0.55,0,1-0.45,1-1s-0.45-1-1-1h-2c-0.55,0-1,0.45-1,1S19.45,13,20,13z M11,2v2 c0,0.55,0.45,1,1,1s1-0.45,1-1V2c0-0.55-0.45-1-1-1S11,1.45,11,2z M11,20v2c0,0.55,0.45,1,1,1s1-0.45,1-1v-2c0-0.55-0.45-1-1-1 S11,19.45,11,20z M5.99,4.58c-0.39-0.39-1.03-0.39-1.41,0c-0.39,0.39-0.39,1.03,0,1.41l1.06,1.06c0.39,0.39,1.03,0.39,1.41,0 s0.39-1.03,0-1.41L5.99,4.58z M18.36,16.95c-0.39-0.39-1.03-0.39-1.41,0c-0.39,0.39-0.39,1.03,0,1.41l1.06,1.06 c0.39,0.39,1.03,0.39,1.41,0c0.39-0.39,0.39-1.03,0-1.41L18.36,16.95z M19.42,5.99c0.39-0.39,0.39-1.03,0-1.41 c-0.39-0.39-1.03-0.39-1.41,0l-1.06,1.06c-0.39,0.39-0.39,1.03,0,1.41s1.03,0.39,1.41,0L19.42,5.99z M7.05,18.36 c0.39-0.39,0.39-1.03,0-1.41c-0.39-0.39-1.03-0.39-1.41,0l-1.06,1.06c-0.39,0.39-0.39,1.03,0,1.41s1.03,0.39,1.41,0L7.05,18.36z"/>
      </svg>
    </button>
    <div class="animated-bg">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>
    <div class="container">
      <div class="card">
        <div class="card-header">
          <h3>Welcome Back</h3>
        </div>
        <div class="card-body">
              <?php
              if($login){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> You are logged in
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
              }
              if($showError){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> '.$showError.'
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
              }
              ?>

             

    <!-- Alerts -->
    <!-- <div class="alert alert-success">Login successful!</div> -->
    <!-- <div class="alert alert-error">Invalid credentials</div> -->

    <form action="login.php" method="post">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required />
      </div>
      <button type="submit" class="btn">
        <span>Login</span>
        <span class="loading" style="display: none;"></span>
      </button>
    </form>
    <div class="signup-link">
      Don't have an account? <a href="signup.php">Sign Up</a>
    </div>
  </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="theme.js"></script>
    <script>
      // Initialize theme toggle
      initTheme();
      
      // Form submission and loading animation
      document.querySelector('form').addEventListener('submit', function(e) {
        const btn = this.querySelector('.btn');
        const btnText = btn.querySelector('span:not(.loading)');
        const loading = btn.querySelector('.loading');
        
        btnText.style.opacity = '0';
        loading.style.display = 'inline-block';
        
        // Enable inputs and button after 100ms if form is invalid
        setTimeout(() => {
          if (!this.checkValidity()) {
            btnText.style.opacity = '1';
            loading.style.display = 'none';
          }
        }, 100);
      });

      // Input focus effects
      document.querySelectorAll('.form-control').forEach(input => {
        const field = input.parentElement;
        
        input.addEventListener('focus', () => {
          field.style.transform = 'translateY(-4px)';
          input.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
        });
        
        input.addEventListener('blur', () => {
          field.style.transform = 'none';
          input.style.boxShadow = 'none';
        });
      });

      // Auto-hide alerts after 5 seconds
      document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
          alert.style.opacity = '0';
          alert.style.transform = 'translateY(-10px)';
          setTimeout(() => {
            if (alert.parentElement) {
              alert.parentElement.removeChild(alert);
            }
          }, 300);
        }, 5000);
      });
    </script>
  </body>
</html>