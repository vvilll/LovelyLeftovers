<?php
//include auth_session.php file on all user panel pages
include("auth_session.php");
include 'dbconnect.php';
$email = $_SESSION['email'];
$nodeID = $_SESSION['NodeID'];

$sql = "SELECT * FROM nodes WHERE NodeID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $nodeID);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $name = test_input($_POST["name"]);
        $mobile = test_input($_POST["phon"]);
        $email = strtolower(trim($_POST["email"]));
        $pass = $_POST["nPasswd"];
        $city = test_input($_POST["City"]);
        $state = test_input($_POST["state"]);
        $zipcode = test_input($_POST["Zip"]);
        $job = test_input($_POST["Occupation"]);
        $food = test_input($_POST["fFood"]);
        $emailcheck = false;
        $checkSql = "SELECT NodeID FROM nodes WHERE Email = ? AND NodeID != ? LIMIT 1";
        if ($checkStmt = $conn->prepare($checkSql)) {
            $checkStmt->bind_param("si", $email, $nodeID);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                echo "That email is already registered.";
                $emailcheck = true;
            }

            $checkStmt->close();
        } else {
            echo "Error preparing email check: " . $conn->error;
        }
        if($emailcheck)
        {}
        else
            {
                // process updating info for user
                if($stmt = $conn->prepare("UPDATE nodes SET Name = ?, Email = ?, Mobile = ?, Zipcode = ?, City = ?, State = ?, Occupation = ?, Password = ? WHERE NodeID = ?")) 
                    {
                        // Bind parameters
                        $stmt->bind_param("ssssssssi", $name, $email, $mobile, $zipcode, $city, $state, $job, $pass, $nodeID);
                        $stmt->execute();
                        $stmt->close();
                    } 
                else 
                    {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                // process food update
                $stmt2 = $conn->prepare("SELECT * FROM nodes WHERE NodeID IN (SELECT TargetID FROM edges WHERE SourceID = ? AND EdgeType = 'Has_Pref') LIMIT 1");
                $stmt2->bind_param("i", $nodeID);
                $stmt2->execute();
                $result = $stmt2->get_result();
                $row = $result->fetch_assoc();
                $stmt = $conn->prepare("UPDATE nodes SET Label = ? WHERE NodeID = ?");
                $stmt->bind_param("si", $food, $row['NodeID']);
                $stmt->execute();
                $stmt2->close();
                $stmt->close();
            }
        $conn->close();
    }

function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/webFavi.png">
    <title>Account</title>
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
    <form class="loginForm" id="chngInfo" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <h1>Personal Information</h1>
        <div class="frmGroup">
            <div class="mailPhoneContainer">
                <div class="itemContainers">
                    <label for="name">Name</label>
                    <input class="textBoxes" type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['Name']); ?>" required/>
                </div>

                <div class="itemContainers">
                    <label for="phone">Phone</label>
                    <input class="textBoxes" type="text" id="phone" name="phon" value="<?php echo htmlspecialchars($user['Mobile']); ?>" required/>
                </div>
            </div>
            <div class="itemContainers">
                <label for="email">E-mail</label>
                <input class="textBoxes" type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required/>
            </div>
            <div class="itemContainers">
                <label for="oPasswd">Old Password</label>
                <input class="textBoxes" type="password" id="oPasswd" name="oPasswd" required/>
            </div>
            <div class="itemContainers">
                <label for="nPasswd">New Password</label>
                <input class="textBoxes" type="password" id="nPasswd" name="nPasswd" required/>
            </div>
        </div>

        <h1>Address</h1>
        <div class="frmGroup">
          <div class="itemContainers">
                <label for="City">City</label>
                <input class="textBoxes" type="text" id="City" name="City" value="<?php echo htmlspecialchars($user['City']); ?>" required/>
            </div>
            <div class="itemContainers">
                <label for="State">State</label>
                    <select class="textBoxes" id="state" name="state" required>
                        <option value="<?php echo htmlspecialchars($user['State']);?>" selected><?php echo htmlspecialchars($user['State']); ?></option>
                        <option value="Alabama">Alabama</option>
                        <option value="Alaska">Alaska</option>
                        <option value="Arizona">Arizona</option>
                        <option value="Arkansas">Arkansas</option>
                        <option value="California">California</option>
                        <option value="Colorado">Colorado</option>
                        <option value="Connecticut">Connecticut</option>
                        <option value="Delaware">Delaware</option>
                        <option value="Florida">Florida</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Hawaii">Hawaii</option>
                        <option value="Idaho">Idaho</option>
                        <option value="Illinois">Illinois</option>
                        <option value="Indiana">Indiana</option>
                        <option value="Iowa">Iowa</option>
                        <option value="Kansas">Kansas</option>
                        <option value="Kentucky">Kentucky</option>
                        <option value="Louisiana">Louisiana</option>
                        <option value="Maine">Maine</option>
                        <option value="Maryland">Maryland</option>
                        <option value="Massachusetts">Massachusetts</option>
                        <option value="Michigan">Michigan</option>
                        <option value="Minnesota">Minnesota</option>
                        <option value="Mississippi">Mississippi</option>
                        <option value="Missouri">Missouri</option>
                        <option value="Montana">Montana</option>
                        <option value="Nebraska">Nebraska</option>
                        <option value="Nevada">Nevada</option>
                        <option value="New Hampshire">New Hampshire</option>
                        <option value="New Jersey">New Jersey</option>
                        <option value="New Mexico">New Mexico</option>
                        <option value="New York">New York</option>
                        <option value="North Carolina">North Carolina</option>
                        <option value="North Dakota">North Dakota</option>
                        <option value="Ohio">Ohio</option>
                        <option value="Oklahoma">Oklahoma</option>
                        <option value="Oregon">Oregon</option>
                        <option value="Pennsylvania">Pennsylvania</option>
                        <option value="Rhode Island">Rhode Island</option>
                        <option value="South Carolina">South Carolina</option>
                        <option value="South Dakota">South Dakota</option>
                        <option value="Tennessee">Tennessee</option>
                        <option value="Texas">Texas</option>
                        <option value="Utah">Utah</option>
                        <option value="Vermont">Vermont</option>
                        <option value="Virginia">Virginia</option>
                        <option value="Washington">Washington</option>
                        <option value="West Virginia">West Virginia</option>
                        <option value="Wisconsin">Wisconsin</option>
                        <option value="Wyoming">Wyoming</option>
                    </select>
            </div>
            <div class="itemContainers">
                <label for="Zip">Zip</label>
                <input class="textBoxes" type="text" id="Zip" name="Zip" value="<?php echo htmlspecialchars($user['Zipcode']); ?>" required/>
            </div>
        </div>
  
        <h1>Preferences</h1>
        <div class="frmGroup">
            <div class="itemContainers">
                <label for="Occupation">Occupation</label>
                <input class="textBoxes" type="text" id="Occupation" name="Occupation" value="<?php echo htmlspecialchars($user['Occupation']); ?>" required/>
            </div>
            <div class="itemContainers" >
                <label for="fFood">Favorite Food</label>
                <input class="textBoxes" type="text" name="fFood" required pattern="[A-Za-z ]+" oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')" />
            </div>
        </div>

        <div class="formBtn">
            <input type="submit" value="Save Changes"></input>
        </div>
    </form>
</body>
<script>
    const currentPath = window.location.pathname.split("/").pop();
    if(currentPath[0] === 'a' && currentPath[1] === 'c' && currentPath[2] === 'c' && currentPath[3] === 'o' && currentPath[4] === 'u' && currentPath[5] === 'n' && currentPath[6] === 't'){
        document.getElementById("nav4").style.background = "#F8BBD0"
        document.getElementById("navA4").style.color = "#C2185B"
    }
</script>
</html>