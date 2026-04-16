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
                <div class="navBtn" id="nav4"><a id="navA4" href="account.php">Account</a></div>
                <div class="navBtn" id="nav5"><a id="navA5" href="logout.php">Logout</a></div>
            </nav>
        </div>
    </div>
    <div class="successBody">
        <div class="successTop">
            <div class="successCard">
                <img src="/images/icon.png">
                <div class="successCardDesc">
                    <h1>Name</h1>
                    <p>City, State</p>
                    <div class="infoBoxes">
                        <p>Hobby</p>
                        <p>Food</p>
                        <p>Job</p>
                    </div>
                </div>
            </div>
            <div class="successCard">
                <img src="/images/icon.png">
                <div class="successCardDesc">
                    <h1>Name</h1>
                    <p>City, State</p>
                    <div class="infoBoxes">
                        <p>Hobby</p>
                        <p>Food</p>
                        <p>Job</p>
                    </div>
                </div>
            </div>
            <div class="successCard">
                <img src="/images/icon.png">
                <div class="successCardDesc">
                    <h1>Name</h1>
                    <p>City, State</p>
                    <div class="infoBoxes">
                        <p>Hobby</p>
                        <p>Food</p>
                        <p>Job</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="successBot">
            <div class="successCard">
                <img src="/images/icon.png">
                <div class="successCardDesc">
                    <h1>Name</h1>
                    <p>City, State</p>
                    <div class="infoBoxes">
                        <p>Hobby</p>
                        <p>Food</p>
                        <p>Job</p>
                    </div>
                </div>
            </div>
            <div class="successCard">
                <img src="/images/icon.png">
                <div class="successCardDesc">
                    <h1>Name</h1>
                    <p>City, State</p>
                    <div class="infoBoxes">
                        <p>Hobby</p>
                        <p>Food</p>
                        <p>Job</p>
                    </div>
                </div>
            </div>
            <div class="successCard">
                <img src="/images/icon.png">
                <div class="successCardDesc">
                    <h1>Name</h1>
                    <p>City, State</p>
                    <div class="infoBoxes">
                        <p>Hobby</p>
                        <p>Food</p>
                        <p>Job</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="nextBtn">
            Load More
        </div>
    </div>
 
</body>
<script>
    const currentPath = window.location.pathname.split("/").pop();
    if(currentPath[0] === 'c' && currentPath[1] === 'o' && currentPath[2] === 'n' && currentPath[3] === 't' && currentPath[4] === 'e' && currentPath[5] === 'n' && currentPath[6] === 't'){
        document.getElementById("nav2").style.background = "#F8BBD0"
        document.getElementById("navA2").style.color = "#C2185B"
    }
    if(currentPath[0] === 'c' && currentPath[1] === 'o' && currentPath[2] === 'n' && currentPath[3] === 't' && currentPath[4] === 'e' && currentPath[5] === 'n' && currentPath[6] === 't' && currentPath[7] === 's' && currentPath[8] === 'u' && currentPath[9] === 'c' && currentPath[10] === 'c' && currentPath[11] === 'e' && currentPath[12] === 's' && currentPath[13] === 's'){
        document.getElementById("matchNav2").style.background = "#F8BBD0"
        document.getElementById("matchNavA2").style.color = "#C2185B"
    }
</script>
</html>