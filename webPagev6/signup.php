<?php
        session_start();
        require('dbconnect.php');
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fname = test_input($_POST["fname"]);
        $lname = test_input($_POST["lname"]);
        $email = strtolower(trim($_POST["email"]));
        $pass = $_POST["passwd"];
        $city = test_input($_POST["city"]);
        $state = test_input($_POST["state"]);
        $gender = test_input($_POST["gender"]);
        $zipcode = test_input($_POST["zip"]);
        $mobile = test_input($_POST["mobile"]);
        $name = $fname . " " . $lname;
        $nodeTypeUser = "User";
        $emailcheck = false;
        $checkSql = "SELECT NodeID FROM nodes WHERE Email = ? LIMIT 1";
        if ($checkStmt = $conn->prepare($checkSql)) {
            $checkStmt->bind_param("s", $email);
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
        else{
        // SQL query template
        $sql = "INSERT INTO nodes (NodeType, Name, Email, Gender, Password, State, City, Mobile, Zipcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // Prepare the SQL query template
        if($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("sssssssss", $nodeTypeUser, $name, $email, $gender, $pass, $state, $city, $mobile, $zipcode);
        $stmt->execute();
        $userNodeID = $conn->insert_id;
        $_SESSION['email'] = $email;
        $_SESSION['NodeID'] = $userNodeID;
        $stmt->close();
        $conn->close();
        header("Location: signup2.php");
        exit();
        } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }
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
    <title>Login</title>
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
    <form class="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="formHeader">
            <h1>Account Creation</h1>
        </div>
        <div class="itemContainers">
            <label for="email">E-mail</label>
            <input class="textBoxes" type="text" id="email" name="email" placeholder="name@example.com" required/>
        </div>
        <div class="itemContainers">
            <label for="passwd">Password</label>
            <input class="textBoxes" type="password" id="passwd" name="passwd" required/>
        </div>
        <label for="radio">Gender</label>
        <div class="genderRadio">
            <div class="genderRadio">
                <input type="radio" id="mr" name="gender" value="male"/>
                <label for="mr"> Male</label>
            </div>

            <div class="genderRadio">
                <input type="radio" id="mrs" name="gender" value="female" />
                <label for="female" > Female</label>
            </div>

            <div class="genderRadio">
                <input type="radio" id="oth" name="gender" value="other" />
                <label for="other" > Other</label>
            </div>
        </div>
        <div>
            <div class="itemContainers" >
                <label for="fname">First Name</label>
                <input class="textBoxes" type="text" id="fname" name="fname" required pattern="[A-Za-z ]+" oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')" placeholder="First Name"/>
            </div>
            <div class="itemContainers">
                <label for="lname">Last Name</label>
                <input class="textBoxes" type="text" id="lname" name="lname" required pattern="[A-Za-z ]+" oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')" placeholder="Last Name"/>
            </div>
        </div>
        <div>
            <div class="itemContainers" >
                <label for="city">City</label>
                <input class="textBoxes" type="text" id="city" name="city" placeholder="City" required/>
            </div>
            <div class="itemContainers">
                <label for="state">State</label>
                <select class="textBoxes" id="state" name="state" required>
                    <option selected disabled>Select State</option>
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
        </div>
        <div>
            <div class="itemContainers">
                <label for="zip">Zip</label>
                <input class="textBoxes" type="text" id="zip" name="zip" required pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Zip / Postal code"/>
            </div>
            <div class="itemContainers">
                <label for="photoUp">Upload Photo</label>
                <input type="file" accept=".jpg, .png, .WebP" id="photoUp" name="photoUp"/>
            </div>
        </div>
        <div class="itemContainers">
            <label for="mobile">Mobile</label>
            <input class="textBoxes" type="text" id="mobile" name="mobile" required pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="+91..."/>
        </div>
        <div class="formBtn">
            <input type="submit" id="submit" value="Submit">
        </div>
    </form>
</body>
<script>
    const currentPath = window.location.pathname.split("/").pop();
    if(currentPath[0] === 's' && currentPath[1] === 'i' && currentPath[2] === 'g' && currentPath[3] === 'n' && currentPath[4] === 'u' && currentPath[5] === 'p'){
        document.getElementById("nav4").style.background = "#F8BBD0"
        document.getElementById("navA4").style.color = "#C2185B"
    }
</script>
</html>