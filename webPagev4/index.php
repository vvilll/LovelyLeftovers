<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/webFavi.png">
    <title>Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chewy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body {
        background-image: url('images/homeBG.png');
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
<body>
    <?php
    require('dbconnect.php');
    session_start();
    if (isset($_POST['email'])) {
        $email = stripslashes($_REQUEST['email']);   
        $email = mysqli_real_escape_string($conn, $email);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($conn, $password);
        $query    = "SELECT * FROM `nodes` WHERE Email='$email'
                     AND Password='$password'";
        $result = mysqli_query($conn, $query) or die(mysql_error());
        $rows = mysqli_num_rows($result);
        if ($rows == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['email'] = $row['Email'];
            $_SESSION['NodeID'] = $row['NodeID'];
            header("Location: account.php");
        } else {
            echo "<div class='form'>
                  <h3>Incorrect Email/password.</h3><br/>
                  <p class='link'>Click here to <a href='index.php'>Login</a> again.</p>
                  </div>";
        }
    } else {
?>
    <div class="header">
        <div class="headerLogo">
            <a href="index.php">
                <img src="images/dbLogo.png" alt="dev">
            </a>
        </div>
        <div class="headerNav">
            <nav class="navBar">
                <div class="navBtn" id="nav1"><a id="navA1" href="index.php">Home</a></div>
                <div class="navBtn" id="nav3"><a id="navA3" href="about.php">Why Us?</a></div>
                <div class="navBtn" id="nav4"><a id="navA4" href="signup.php">Signup</a></div>
            </nav>
        </div>
    </div>
    <div class="body">
        <div class="bodyText">
            <h1>Find Your Perfect Pear</h1>
            <p>Unsuccessful on mainstream dating apps? Join today, what you’ll find here is nacho average romance.</p>
        </div>
        <form class="bodyForm" method="post">
            <h1>Login</h1>
            <div>
                <label for="tIn">Email</label><br>
                <input class="textBoxes" type="text" id="tIn" name="email" placeholder="email@email.com"><br>
            </div>
            <div>
                <label for="tIn2">Password</label><br>
                <input class="textBoxes" type="text" id="tIn2" name="password"><br>
            </div>
            <div class="formBtn"><input type="submit"></div>
        </form>
    </div>
<?php
    }
?>
</body>
<script>
    const currentPath = window.location.pathname.split("/").pop();
    if(currentPath[0] === 'i' && currentPath[1] === 'n' && currentPath[2] === 'd' && currentPath[3] === 'e' && currentPath[4] === 'x' && currentPath[5] === '.' && currentPath[6] === 'h' && currentPath[7] === 't' && currentPath[8] === 'm' && currentPath[9] === 'l'){
        document.getElementById("nav1").style.background = "#F8BBD0"
        document.getElementById("navA1").style.color = "#C2185B"
    }
</script>
</html>