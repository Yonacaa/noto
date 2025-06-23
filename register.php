<?php 

include 'connect.php';

if(isset($_POST['signUp'])){
    session_start();
    $firstName=$_POST['fName'];
    $lastName=$_POST['lName'];
    $email=$_POST['email'];
    $password=$_POST['password'];

     $checkEmail="SELECT * From users where email='$email'";
     $result=$conn->query($checkEmail);
     if($result->num_rows>0){
        $_SESSION['flash_error'] = "Email Address Already Exists!";
        header("Location: index.php");
        exit();
     }
     else{
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insertQuery="INSERT INTO users(firstName,lastName,email,password)
                       VALUES ('$firstName','$lastName','$email','$hashed_password')";
            if($conn->query($insertQuery)==TRUE){
                $_SESSION['flash_success'] = "Account created successfully! Please login.";
                header("Location: index.php");
                exit();
            }
            else{
                $_SESSION['flash_error'] = "Error: " . $conn->error;
                header("Location: index.php");
                exit();
            }
     }
}

if(isset($_POST['signIn'])){
    session_start();
   $email=$_POST['email'];
   $password=$_POST['password'];
   
   $sql="SELECT * FROM users WHERE email='$email'";
   $result=$conn->query($sql);
   if($result->num_rows>0){
    $row=$result->fetch_assoc();
    // Check if password is hashed with password_hash
    if (password_verify($password, $row['password'])) {
        $_SESSION['email']=$row['email'];
        header("Location: homepage.php");
        exit();
    } else {
        // Fallback: check if password matches md5 hash (old)
        if ($row['password'] === md5($password)) {
            // Update password to new hash
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->bind_param("si", $newHash, $row['id']);
            $updateStmt->execute();

            $_SESSION['email']=$row['email'];
            header("Location: homepage.php");
            exit();
        } else {
            $_SESSION['flash_error'] = "Incorrect Email or Password";
            header("Location: index.php");
            exit();
        }
    }
   } else {
    $_SESSION['flash_error'] = "Incorrect Email or Password";
    header("Location: index.php");
    exit();
   }
}
?>
