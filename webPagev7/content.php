<?php
//include auth_session.php file on all user panel pages
include("auth_session.php");
include 'dbconnect.php';
$email = $_SESSION['email'];
$nodeID = $_SESSION['NodeID'];
$potentMatches = [];
$ages = [];
$foods = [];
$currentMatchIDs =[];
$matchesAll = [];
if (isset($_POST['matchUser1'])) 
    {
        $matchUID = $_POST["matchUser1"];
        $nodeType = "Match";
        $edgeType1 = "Matched";
        $curDate = date('Y-m-d');
        $stmt = $conn->prepare("INSERT INTO nodes (NodeType, MatchDate) VALUES (?, ?)");
        $stmt->bind_param("ss", $nodeType,$curDate);
        $stmt->execute();
        $matchNodeID = $conn->insert_id;
        $stmt->close();
        $stmt2 = $conn->prepare("INSERT INTO edges (SourceID, TargetID, EdgeType) VALUES (?, ?, ?)");
        $stmt2->bind_param("iis", $matchNodeID,$nodeID,$edgeType1);
        $stmt2->execute();
        $stmt2->close();
        $stmt3 = $conn->prepare("INSERT INTO edges (SourceID, TargetID, EdgeType) VALUES (?, ?, ?)");
        $stmt3->bind_param("iis", $matchNodeID,$matchUID,$edgeType1);
        $stmt3->execute();
        $stmt3->close();
    }
if (isset($_POST['matchUser2'])) 
    {
        $matchUID = $_POST["matchUser2"];
        $nodeType = "Match";
        $edgeType1 = "Matched";
        $curDate = date('Y-m-d');
        $stmt = $conn->prepare("INSERT INTO nodes (NodeType, MatchDate) VALUES (?, ?)");
        $stmt->bind_param("ss", $nodeType,$curDate);
        $stmt->execute();
        $matchNodeID = $conn->insert_id;
        $stmt->close();
        $stmt2 = $conn->prepare("INSERT INTO edges (SourceID, TargetID, EdgeType) VALUES (?, ?, ?)");
        $stmt2->bind_param("iis", $matchNodeID,$nodeID,$edgeType1);
        $stmt2->execute();
        $stmt2->close();
        $stmt3 = $conn->prepare("INSERT INTO edges (SourceID, TargetID, EdgeType) VALUES (?, ?, ?)");
        $stmt3->bind_param("iis", $matchNodeID,$matchUID,$edgeType1);
        $stmt3->execute();
        $stmt3->close();
    }
if (isset($_POST['matchUser3'])) 
    {
        $matchUID = $_POST["matchUser3"];
        $nodeType = "Match";
        $edgeType1 = "Matched";
        $curDate = date('Y-m-d');
        $stmt = $conn->prepare("INSERT INTO nodes (NodeType, MatchDate) VALUES (?, ?)");
        $stmt->bind_param("ss", $nodeType,$curDate);
        $stmt->execute();
        $matchNodeID = $conn->insert_id;
        $stmt->close();
        $stmt2 = $conn->prepare("INSERT INTO edges (SourceID, TargetID, EdgeType) VALUES (?, ?, ?)");
        $stmt2->bind_param("iis", $matchNodeID,$nodeID,$edgeType1);
        $stmt2->execute();
        $stmt2->close();
        $stmt3 = $conn->prepare("INSERT INTO edges (SourceID, TargetID, EdgeType) VALUES (?, ?, ?)");
        $stmt3->bind_param("iis", $matchNodeID,$matchUID,$edgeType1);
        $stmt3->execute();
        $stmt3->close();
    }
if ($_SERVER["REQUEST_METHOD"] == "GET" || isset($_POST['refreshMatches'])) 
    {
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
        //grabs 3 random users
        $excludeIDs = $matchesAll;
        $excludeIDs[] = $nodeID;
        $placeholders = implode(',', array_fill(0, count($excludeIDs), '?'));
        $sql = "SELECT * FROM nodes
                WHERE NodeType = 'User'
                AND NodeID NOT IN ($placeholders)
                ORDER BY RAND()
                LIMIT 3";
        $stmt = $conn->prepare($sql);
        $types = str_repeat('i', count($excludeIDs));
        $stmt->bind_param($types, ...$excludeIDs);
        $stmt->execute();
        $result = $stmt->get_result();
        $current = date("Y");
        while($row = $result->fetch_assoc())
            {
                $birthdate = $row['Age'];
                $age = $current - $birthdate;
                $ages[] = $age;
                $potentMatches[] = $row;  
            }
        $stmt->close();
        //grabs fav food for each match
        foreach($potentMatches as $fmatch)
            {
                $stmt2 = $conn->prepare("SELECT * FROM nodes WHERE NodeID IN (SELECT TargetID FROM edges WHERE SourceID = ? AND EdgeType = 'Has_Pref')");
                $stmt2->bind_param("i", $fmatch['NodeID']);
                $stmt2->execute();
                $result = $stmt2->get_result();
                while($row2 = $result->fetch_assoc())
                    {$foods[] = $row2;}
                $stmt2->close();
            }
        //grabs all interests for each match
        foreach($potentMatches as &$match)
            {
                $stmt3 = $conn->prepare("SELECT * FROM nodes WHERE NodeID IN (SELECT TargetID FROM edges WHERE SourceID = ? AND EdgeType = 'Has_Interest')");
                $stmt3->bind_param("i", $match['NodeID']);
                $stmt3->execute();
                $result = $stmt3->get_result();
                $match['relatedNodes'] = [];
                while($row3 = $result->fetch_assoc())
                    {$match['relatedNodes'][] = $row3;}
                $stmt3->close();
            }
        unset($match);
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
    <div class="matchingBody">
        <h1 class="matchingBodyTitle">Make Your Match</h1>
        <div class="cardContainer">
            <form method="post" id="matchedUser1">
            <input type="hidden" name="matchUser1" value="<?php echo $potentMatches[0]['NodeID'];?>">
            <input type="hidden" name="refreshMatches" value="1">
                <div class="uCard" id="uCardL">
                    <img src="images/icon.png">
                    <h2><?php echo $potentMatches[0]['Name'];?>,<?php echo $ages[0];?></h2>
                    <p><?php echo $potentMatches[0]['City'];?>,<?php echo $potentMatches[0]['State'];?></p>
                    <h3><?php echo $potentMatches[0]['Occupation'];?></h3>
                    <div class="cardAbout">
                        <div class="aboutFood">
                            <h3>Favorite Food</h3>
                            <p><?php echo $foods[0]['Label'];?></p>
                        </div>
                        <div class="aboutHobbies">
                            <h3>Hobbies</h3>
                        <p>
                            <?php 
                                $count = 0; 
                                foreach($potentMatches[0]['relatedNodes'] as $related){
                                    echo $related['Label']; 
                                    $count++; 
                                    if($count > 3){
                                        break;
                                    }
                                    echo " | ";
                                }
                            ?>
                            </p>
                        </div>
                    </div>
                </div>
            </form>
            <form method="post" id="matchedUser2">
            <input type="hidden" name="matchUser2" value="<?php echo $potentMatches[1]['NodeID'];?>">
            <input type="hidden" name="refreshMatches" value="1">
                <div class="uCard" id="uCardM">
                    <img src="images/icon.png">
                    <h2><?php echo $potentMatches[1]['Name'];?>,<?php echo $ages[1];?></h2>
                    <p><?php echo $potentMatches[1]['City'];?>,<?php echo $potentMatches[1]['State'];?></p>
                    <h3><?php echo $potentMatches[1]['Occupation'];?></h3>
                    <div class="cardAbout">
                        <div class="aboutFood">
                            <h3>Favorite Food</h3>
                            <p><?php echo $foods[1]['Label'];?></p>
                        </div>
                        <div class="aboutHobbies">
                            <h3>Hobbies</h3>
                            <p>
                            <?php 
                                $count = 0; 
                                foreach($potentMatches[1]['relatedNodes'] as $related){
                                    echo $related['Label']; 
                                    $count++; 
                                    if($count > 3){
                                        break;
                                    }
                                    echo " | ";
                                }
                            ?>
                            </p>
                        </div>
                    </div>
                </div>
            </form>
            <form method="post" id="matchedUser3">
            <input type="hidden" name="matchUser3" value="<?php echo $potentMatches[2]['NodeID'];?>">
            <input type="hidden" name="refreshMatches" value="1">
                <div class="uCard" id="uCardR">
                    <img src="images/icon.png">
                    <h2><?php echo $potentMatches[2]['Name'];?>,<?php echo $ages[2];?></h2>
                    <p><?php echo $potentMatches[2]['City'];?>,<?php echo $potentMatches[2]['State'];?></p>
                    <h3><?php echo $potentMatches[2]['Occupation'];?></h3>
                    <div class="cardAbout">
                        <div class="aboutFood">
                            <h3>Favorite Food</h3>
                            <p><?php echo $foods[2]['Label'];?></p>
                        </div>
                        <div class="aboutHobbies">
                            <h3>Hobbies</h3>
                            <p>
                            <?php 
                                $count = 0; 
                                foreach($potentMatches[2]['relatedNodes'] as $related){
                                    echo $related['Label']; 
                                    $count++; 
                                    if($count > 3){
                                        break;
                                    }
                                    echo " | ";
                                }
                            ?>
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <form method="post" id="refreshForm">
        <input type="hidden" name="refreshMatches" value="1">
        <div class="nextBtn" id="skipBtn">
            Skip
        </div>
        </form>
    </div>

</body>
<script>
    const currentPath = window.location.pathname.split("/").pop();
    const wait = 2000;
    if(currentPath[0] === 'c' && currentPath[1] === 'o' && currentPath[2] === 'n' && currentPath[3] === 't' && currentPath[4] === 'e' && currentPath[5] === 'n' && currentPath[6] === 't'){
        document.getElementById("nav2").style.background = "#FAD4DF"
        document.getElementById("navA2").style.color = "#C2185B"
    }
    const leftDiv = document.getElementById('uCardL');
    const midDiv = document.getElementById('uCardM');
    const rightDiv = document.getElementById('uCardR');
    
    leftDiv.addEventListener('click', function () {
        midDiv.classList.add('disappear');
        rightDiv.classList.add('disappear');
        resetCards(wait);
        document.getElementById("matchedUser1").submit();
    });
    midDiv.addEventListener('click', function () {
        leftDiv.classList.add('disappear');
        rightDiv.classList.add('disappear');
        resetCards(wait);
        document.getElementById("matchedUser2").submit();
    });
    rightDiv.addEventListener('click', function () {
        leftDiv.classList.add('disappear');
        midDiv.classList.add('disappear');
        resetCards(wait);
        document.getElementById("matchedUser3").submit();
    });
    document.getElementById('skipBtn').addEventListener('click', function () {
        leftDiv.classList.add('disappear');
        midDiv.classList.add('disappear');
        rightDiv.classList.add('disappear');
        resetCards(wait);
        document.getElementById("refreshForm").submit();
    });
    function resetCards(time) {
        setTimeout(() => {
        leftDiv.classList.remove('disappear');
        midDiv.classList.remove('disappear');
        rightDiv.classList.remove('disappear');
        }, time);
    }

    window.addEventListener("beforeunload", () => {
        localStorage.setItem("sPOS", window.scrollY);
    });

    window.addEventListener("load", () => {
        if (localStorage.getItem("sPOS")) window.scrollTo(0, localStorage.getItem("sPOS"));
    });
</script>
</html>