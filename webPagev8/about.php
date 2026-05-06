<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/webFavi.png">
    <title>About</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chewy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="headerLogo">
            <a href="index.php">
                <img src="images/dbLogo.png" alt="dev" width="150" height="150">
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
    <div class="aboutDesc">
        <h1>Title</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi quis magni consequatur id. Similique ipsa laborum libero accusantium doloremque debitis magni assumenda, perspiciatis eos, blanditiis numquam non corporis iure consequuntur.</p>
    </div>
    <div class="aboutStats">
        <div class="stat">
            <p>Total Users</p>
            <h1>1,000</h1>
        </div>
        <div class="stat">
            <p>Founded In</p>
            <h1>2026</h1>
        </div>
        <div class="stat">
            <p>Successful Users</p>
            <h1>500</h1>
        </div>
    </div>
</body>
<script>
    const currentPath = window.location.pathname.split("/").pop();
    if(currentPath[0] === 'a' && currentPath[1] === 'b' && currentPath[2] === 'o' && currentPath[3] === 'u' && currentPath[4] === 't'){
        document.getElementById("nav3").style.background = "#FAD4DF"
        document.getElementById("navA3").style.color = "#C2185B"
    }
</script>
</html>