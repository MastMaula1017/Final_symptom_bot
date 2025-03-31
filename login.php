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
    <link rel="stylesheet" href="style.css">
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <title>Login</title>
  </head>
  <body>
    <?php require 'partials/_nav.php' ?>
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
    
    <script>
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