<?php
//include auth_session.php file on all user panel pages
include("auth_session.php");
include 'dbconnect.php';
$email = $_SESSION['email'];
$nodeID = $_SESSION['NodeID'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/webFavi.png">
    <title>Matching</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chewy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="headerLogo">
            <a href="account.php">
                <img src="images/dbLogo.png" alt="dev" width="150" height="150">
            </a>
        </div>
        <div class="headerNav">
            <nav class="navBar">
                <div class="contentDrop">
                    <div class="navBtn" id="nav2"><a id="navA2" href="content.php">Find A Match</a></div>
                    <div class="dropElems">
                        <a id="matchNavA1" href="content.php">Make a New Match</a>
                        <a id="matchNavA2" href="contentsuccess.php">Successful Matches</a>
                        <a id="matchNavA3" href="contentall.php">View all Matches</a>
                    </div>
                </div>
                <div class="navBtn" id="nav3"><a id="navA3" href="about.php">Why Us?</a></div>
                <div class="navBtn" id="nav4"><a id="navA4" href="account.php">Account</a></div>
                <div class="navBtn" id="nav5"><a id="navA5" href="logout.php">Logout</a></div>
            </nav>
        </div>
    </div>
    <div class="matchingBody">
        <h1 class="matchingBodyTitle">Make Your Match</h1>
        <div class="cardContainer">
            <div class="uCard">
                <img src="/images/eagleImg.png">
                <h2>Eagle, 25</h2>
                <p>Idaho</p>
                <h3>SEO Expert</h3>
                <div class="cardAbout">
                    <div>
                        <h3>Favorite Food</h3>
                        <p>Fish</p>
                    </div>
                    <div>
                        <h3>Hobbies</h3>
                        <p>Skydiving, Aerobics</p>
                    </div>
                </div>
            </div>
            <div class="uCard">
                <img src="/images/giraffeImg.png">
                <h2>Giraffe, 30</h2>
                <p>California</p>
                <h3>SEO Expert</h3>
                <div class="cardAbout">
                    <div>
                        <h3>Favorite Food</h3>
                        <p>Salad</p>
                    </div>
                    <div>
                        <h3>Hobbies</h3>
                        <p>Fishing, Candle Making</p>
                    </div>
                </div>
            </div>
            <div class="uCard">
                <img src="/images/racconImg.png">
                <h2>Raccoon, 21</h2>
                <p>New York</p>
                <h3>SEO Expert</h3>
                <div class="cardAbout">
                    <div>
                        <h3>Favorite Food</h3>
                        <p>Almost Anything</p>
                    </div>
                    <div>
                        <h3>Hobbies</h3>
                        <p>Animation, Fencing</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="nextBtn">
            Skip
        </div>
    </div>
</body>
<script>
    const currentPath = window.location.pathname.split("/").pop();
    if(currentPath[0] === 'c' && currentPath[1] === 'o' && currentPath[2] === 'n' && currentPath[3] === 't' && currentPath[4] === 'e' && currentPath[5] === 'n' && currentPath[6] === 't' && currentPath[7] === '.' && currentPath[8] === 'h' && currentPath[9] === 't' && currentPath[10] === 'm' && currentPath[11] === 'l'){
        document.getElementById("nav2").style.background = "#F8BBD0"
        document.getElementById("navA2").style.color = "#C2185B"
    }
</script>
</html>