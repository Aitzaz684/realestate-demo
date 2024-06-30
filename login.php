<?php

include 'components/connect.php';

$warning_msg = array(); // Initialize an empty array for warning messages
$error_msg = array(); // Initialize an empty array for error messages

// Check if the form was submitted
if(isset($_POST['submit'])){
   
   // Sanitize and validate user inputs
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $pass = $_POST['pass']; // No need to sanitize as we'll hash it
   
   // Hash the password
   $hashed_password = sha1($pass); // Not recommended, but we'll stick with your method for now
   
   // Check if user exists with the provided email and password
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
   $select_user->execute([$email, $hashed_password]);

   if($select_user->rowCount() > 0){
      // User found, set user ID in a cookie and redirect to home page
      $user = $select_user->fetch(PDO::FETCH_ASSOC);
      setcookie('user_id', $user['id'], time() + 60*60*24*30, '/');
      header('Location: home.php');
      exit(); // Stop further execution of the script
   } else {
      $error_msg[] = 'Incorrect email or password!';
   }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- login section starts  -->

<section class="form-container">

   <form action="" method="post">
      <h3>welcome back!</h3>
      <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box">
      <input type="password" name="pass" required maxlength="20" placeholder="enter your password" class="box">
      <p>don't have an account? <a href="register.php">register new</a></p>
      <input type="submit" value="login now" name="submit" class="btn">
   </form>

</section>

<!-- login section ends -->










<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>