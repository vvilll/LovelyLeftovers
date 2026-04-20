<?php
//include auth_session.php file on all user panel pages
include("auth_session.php");
include 'dbconnect.php';
$email = $_SESSION['email'];
$nodeID = $_SESSION['NodeID'];
$currentMatchIDs =[];
$matchesAll = [];
$matchInfo = [];
//list of NodeIDs of matches related to logged in user
$checkstmt = $conn->prepare("SELECT SourceID FROM edges WHERE TargetID = ? AND EdgeType = 'Matched'");
$checkstmt->bind_param("i", $nodeID);
$checkstmt->execute();
$result = $checkstmt->get_result();
while($row = $result->fetch_assoc())
    {
        $currentMatchIDs[] = $row['SourceID'];  
    }
$checkstmt->close();
//grabs userIDs of people matches with user based on matchID shared between users
foreach($currentMatchIDs as $matchIDs)
    {
        $check2stmt = $conn->prepare("SELECT TargetID FROM edges WHERE SourceID = ? AND EdgeType = 'Matched' AND TargetID != ?");
        $check2stmt->bind_param("ii", $matchIDs, $nodeID);
        $check2stmt->execute();
        $result = $check2stmt->get_result();
        while($row6 = $result->fetch_assoc())
            {$matchesAll[] = $row6['TargetID'];}
        $check2stmt->close();
    }
//matchinfo
foreach($matchesAll as $users)
    {
        $check3stmt = $conn->prepare("SELECT * FROM nodes WHERE NodeID = ? AND NodeType = 'User'");
        $check3stmt->bind_param("i", $users);
        $check3stmt->execute();
        $result = $check3stmt->get_result();
        while($row2 = $result->fetch_assoc())
            {$matchesInfo[] = $row2;}
        $check3stmt->close();
    }
foreach($currentMatchIDs as $index => $matchID)
    {
        $check4stmt = $conn->prepare("SELECT MatchDate FROM nodes WHERE NodeID = ? AND NodeType = 'Match'");
        $check4stmt->bind_param("i", $matchID);
        $check4stmt->execute();
        $result = $check4stmt->get_result();
        if($row2 = $result->fetch_assoc())
            {
                $matchesInfo[$index]['matchDate'] = $row2['MatchDate'];
                $matchesInfo[$index]['matchID'] = $matchID;
            }

        $check4stmt->close();
    }
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
    <div class="successHeader">
        <h1>User Matches and Statistics</h1>
        <div class="successHeaderBody">
            <a onclick="displayYourMatch()" id="yourMatchSelect">Your matches</a>
            <a onclick="display5Match()" id="user5MatchSelect">Users with 5 or more Matches</a>
            <a onclick="displayTopMatch()" id="topUsersSelect">Top Users</a>
        </div>

    </div>
    <div class="successBody" id="yourMatch">
        <div class="successTop">
            <?php foreach($matchesInfo as $user):?>
            <div class="successCard">
                <img src="images/icon.png">
                <div class="successCardDesc">
                    <h1><?php echo htmlspecialchars($user['Name']) ;?></h1>
                    <p><?php echo $user['City'];?>, <?php echo htmlspecialchars($user['State']);?></p>
                    <div class="infoBoxes">
                        <p><?php echo htmlspecialchars($user['Occupation']);?></p>
                    </div>
                    <a href="delete.php?id=<?php echo $user['matchID']; ?>" onclick="return confirm('Are you sure to delete?')">Delete</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="successBody" id="user5Match" style="display: none;">
       <div class="topUCard">
            <div class="topUCardBody">
                <img src="images/icon.png">
                <div class="TopUCardDesc">
                    <h1>Name</h1>
                    <p>Match Count</p>
                </div>
            </div>
        </div>
    </div>
    <div class="successBody" id="topUsers" style="display: none;">
        <div class="matchTable">
            <table cellspacing="0">
                <tr>
                    <th>Name</th>
                    <th>Matches</th>
                    <th>Match Count</th>
                </tr>
                <tr class="r0">
                    <td id="matchName0">Jane Doe</td>
                    <td></td>
                    <td id="matchStat0">50</td>
                </tr>
                <!-- For each Match-->
                <tr>
                    <td>-</td>
                    <td>Name here</td>
                </tr>
                <!-- End for each -->

                <tr class="r1">
                    <td id="matchName1">John Doe</td>
                    <td></td>
                    <td id="matchStat1">48</td>
                </tr>
                <!-- For each Match-->
                <tr>
                    <td>-</td>
                    <td>Name here</td>
                </tr>
                <!-- End for each -->

                <tr class="r2">
                    <td id="matchName2">Jane Doe</td>
                    <td></td>
                    <td id="matchStat2">30</td>
                </tr>
                <!-- For each Match-->
                <tr>
                    <td>-</td>
                    <td>Name here</td>
                </tr>
                <!-- End for each -->

                <tr class="r3">
                    <td id="matchName3">John Doe</td>
                    <td></td>
                    <td id="matchStat3">25</td>
                </tr>
                <!-- For each Match-->
                <tr>
                    <td>-</td>
                    <td>Name here</td>
                </tr>
                <!-- End for each -->

                <tr class="r4">
                    <td id="matchName4">Jane Doe</td>
                    <td></td>
                    <td id="matchStat4">20</td>
                </tr>
                <!-- For each Match-->
                <tr>
                    <td>-</td>
                    <td>Name here</td>
                </tr>
                <!-- End for each -->
            </table>
        </div>
    </div>
 
</body>
<script>
    const currentPath = window.location.pathname.split("/").pop();
    if(currentPath[0] === 'c' && currentPath[1] === 'o' && currentPath[2] === 'n' && currentPath[3] === 't' && currentPath[4] === 'e' && currentPath[5] === 'n' && currentPath[6] === 't'){
        document.getElementById("nav2").style.background = "#FAD4DF"
        document.getElementById("navA2").style.color = "#C2185B"
    }

    function displayYourMatch() {
        document.getElementById("yourMatch").style.display = "flex";
        document.getElementById("user5Match").style.display = "none";
        document.getElementById("topUsers").style.display = "none";
        document.getElementById("yourMatchSelect").style.textDecoration = "underline";
        document.getElementById("user5MatchSelect").style.textDecoration = "none";
        document.getElementById("topUsersSelect").style.textDecoration = "none";
    }

    function display5Match() {
        document.getElementById("yourMatch").style.display = "none";
        document.getElementById("user5Match").style.display = "flex";
        document.getElementById("topUsers").style.display = "none";
        document.getElementById("yourMatchSelect").style.textDecoration = "none";
        document.getElementById("user5MatchSelect").style.textDecoration = "underline";
        document.getElementById("topUsersSelect").style.textDecoration = "none";
    }

    function displayTopMatch() {
        document.getElementById("yourMatch").style.display = "none";
        document.getElementById("user5Match").style.display = "none";
        document.getElementById("topUsers").style.display = "flex";
        document.getElementById("yourMatchSelect").style.textDecoration = "none";
        document.getElementById("user5MatchSelect").style.textDecoration = "none";
        document.getElementById("topUsersSelect").style.textDecoration = "underline";
    }
</script>
</html>