<?php
//include auth_session.php file on all user panel pages
include("auth_session.php");
include 'dbconnect.php';
$email = $_SESSION['email'];
$nodeID = $_SESSION['NodeID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $age = test_input($_POST["age"]);
        $job = test_input($_POST["job"]);
        $food = test_input($_POST["fFood"]);
        $hobbies = [];
        if (isset($_POST['hobbies'])) 
            {
                $hobbies = $_POST['hobbies'];
            }
        
        // Process age,job
        $stmt = $conn->prepare("UPDATE nodes SET Age = ?, Occupation = ? WHERE NodeID = ?");
        // Bind parameters
        $stmt->bind_param("ssi", $age, $job, $nodeID);
        $stmt->execute();
        $stmt->close();

        $nodeTypePref = "Pref";
        $catType = "Food";
        $edgeType = "Has_Pref";
        // Process food
        $stmt = $conn->prepare("INSERT INTO nodes (NodeType, Label, Category) VALUES (?, ?, ?)");
        // Bind parameters
        $stmt->bind_param("sss", $nodeTypePref, $food, $catType);
        $stmt->execute();
        $prefNodeID = $conn->insert_id;
        $stmt->close();
        $stmt2 = $conn->prepare("INSERT INTO edges (SourceID, TargetID, EdgeType) VALUES (?, ?, ?)");
        // Bind parameters
        $stmt2->bind_param("iis", $nodeID, $prefNodeID, $edgeType);
        $stmt2->execute();
        $stmt2->close();
        
        // Process hobbies
        $stmtNode = $conn->prepare("INSERT INTO nodes (NodeType, Label, Category) VALUES (?, ?, ?)");
        $stmtEdge = $conn->prepare("INSERT INTO edges (SourceID, TargetID, EdgeType) VALUES (?, ?, ?)");
        $catType = "Hobby";
        $nodeTypeInt = "Interest";
        $edgeType = "Has_Interest";
        foreach ($hobbies as $label)
            {
                $stmtNode->bind_param("sss", $nodeTypeInt, $label, $catType);
                $stmtNode->execute();
                $intNodeID = $conn->insert_id;

                $stmtEdge->bind_param("iis", $nodeID, $intNodeID, $edgeType);
                $stmtEdge->execute();
            }
        $stmtNode->close();
        $stmtEdge->close();
        $conn->close();
        header("Location: account.php");
        } 

        function test_input($data) 
        {
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
            <h1>Tell Us About Yourself</h1>
        </div>
        <div class="itemContainers">
            <label for="age">Date of Birth</label>
            <input type="date" id="age" name="age" required/>
        </div>
        <div class="itemContainers" >
            <label for="job">Occupation</label>
            <input class="textBoxes" type="text" name="job" required/>
        </div>
        <div class="itemContainers" >
            <label for="fFood">Favorite Food</label>
            <input class="textBoxes" type="text" name="fFood" required pattern="[A-Za-z ]+" oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')" />
        </div>
        <label for="radio">Preferred Partner</label>
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
        <div class="itemContainers">
            <label for="hobbies[]">Hobbies (CTRL+Left Click for multiple)</label>
            <select class="textBoxes" id="hobbies" name="hobbies[]" multiple size="10">
                <option value="Acting">Acting</option>
                <option value="Acrobatics">Acrobatics</option>
                <option value="Aerobics">Aerobics</option>
                <option value="Airbrushing">Airbrushing</option>
                <option value="Airsoft">Airsoft</option>
                <option value="Amateur Astronomy">Amateur Astronomy</option>
                <option value="Amateur Radio">Amateur Radio</option>
                <option value="Animation">Animation</option>
                <option value="Antiquing">Antiquing</option>
                <option value="Archery">Archery</option>
                <option value="Arm Wrestling">Arm Wrestling</option>
                <option value="Art Collecting">Art Collecting</option>
                <option value="Art Journaling">Art Journaling</option>
                <option value="Astrology">Astrology</option>
                <option value="Astronomy">Astronomy</option>
                <option value="Athletics">Athletics</option>
                <option value="Audio Engineering">Audio Engineering</option>
                <option value="Auto Detailing">Auto Detailing</option>
                <option value="Auto Restoration">Auto Restoration</option>
                <option value="Backpacking">Backpacking</option>
                <option value="Badminton">Badminton</option>
                <option value="Baking">Baking</option>
                <option value="Ballroom Dancing">Ballroom Dancing</option>
                <option value="Banjo">Banjo</option>
                <option value="Barbecuing">Barbecuing</option>
                <option value="Barre">Barre</option>
                <option value="Baseball">Baseball</option>
                <option value="Basket Weaving">Basket Weaving</option>
                <option value="Basketball">Basketball</option>
                <option value="Bass Guitar">Bass Guitar</option>
                <option value="Beadwork">Beadwork</option>
                <option value="Beatboxing">Beatboxing</option>
                <option value="Beekeeping">Beekeeping</option>
                <option value="Belly Dancing">Belly Dancing</option>
                <option value="Billiards">Billiards</option>
                <option value="Binge-Watching Shows">Binge-Watching Shows</option>
                <option value="Bird Watching">Bird Watching</option>
                <option value="Blacksmithing">Blacksmithing</option>
                <option value="Blogging">Blogging</option>
                <option value="Board Games">Board Games</option>
                <option value="Boating">Boating</option>
                <option value="Bodybuilding">Bodybuilding</option>
                <option value="Book Collecting">Book Collecting</option>
                <option value="Bookbinding">Bookbinding</option>
                <option value="Bowling">Bowling</option>
                <option value="Boxing">Boxing</option>
                <option value="Breadmaking">Breadmaking</option>
                <option value="Brewing Beer">Brewing Beer</option>
                <option value="Bridge">Bridge</option>
                <option value="Broom Making">Broom Making</option>
                <option value="Building Computers">Building Computers</option>
                <option value="Bullet Journaling">Bullet Journaling</option>
                <option value="Bungee Jumping">Bungee Jumping</option>
                <option value="Busking">Busking</option>
                <option value="Butterfly Watching">Butterfly Watching</option>
                <option value="Calligraphy">Calligraphy</option>
                <option value="Camping">Camping</option>
                <option value="Candle Making">Candle Making</option>
                <option value="Canoeing">Canoeing</option>
                <option value="Car Collecting">Car Collecting</option>
                <option value="Card Games">Card Games</option>
                <option value="Carpentry">Carpentry</option>
                <option value="Cartography">Cartography</option>
                <option value="Ceramics">Ceramics</option>
                <option value="Chess">Chess</option>
                <option value="Clay Sculpting">Clay Sculpting</option>
                <option value="Climbing">Climbing</option>
                <option value="Clock Repair">Clock Repair</option>
                <option value="Clowning">Clowning</option>
                <option value="Coaching Sports">Coaching Sports</option>
                <option value="Coding">Coding</option>
                <option value="Collecting Coins">Collecting Coins</option>
                <option value="Collecting Comics">Collecting Comics</option>
                <option value="Collecting Stamps">Collecting Stamps</option>
                <option value="Color Guard">Color Guard</option>
                <option value="Coloring">Coloring</option>
                <option value="Composting">Composting</option>
                <option value="Concert Going">Concert Going</option>
                <option value="Content Creation">Content Creation</option>
                <option value="Cooking">Cooking</option>
                <option value="Cosplay">Cosplay</option>
                <option value="Couponing">Couponing</option>
                <option value="Crafting">Crafting</option>
                <option value="Crochet">Crochet</option>
                <option value="Cross-Stitch">Cross-Stitch</option>
                <option value="CrossFit">CrossFit</option>
                <option value="Cryptography">Cryptography</option>
                <option value="Cycling">Cycling</option>
                <option value="Dancing">Dancing</option>
                <option value="Darts">Darts</option>
                <option value="Debate">Debate</option>
                <option value="Decorating">Decorating</option>
                <option value="Digital Art">Digital Art</option>
                <option value="Digital Illustration">Digital Illustration</option>
                <option value="Digital Photography">Digital Photography</option>
                <option value="Diorama Building">Diorama Building</option>
                <option value="DJing">DJing</option>
                <option value="Dodgeball">Dodgeball</option>
                <option value="Dog Training">Dog Training</option>
                <option value="Dominoes">Dominoes</option>
                <option value="Drawing">Drawing</option>
                <option value="Drone Flying">Drone Flying</option>
                <option value="Drumming">Drumming</option>
                <option value="Embroidery">Embroidery</option>
                <option value="Equestrian Sports">Equestrian Sports</option>
                <option value="Esports">Esports</option>
                <option value="Exercising">Exercising</option>
                <option value="Falconry">Falconry</option>
                <option value="Fantasy Sports">Fantasy Sports</option>
                <option value="Fashion Design">Fashion Design</option>
                <option value="Fencing">Fencing</option>
                <option value="Figure Skating">Figure Skating</option>
                <option value="Filmmaking">Filmmaking</option>
                <option value="Finger Painting">Finger Painting</option>
                <option value="Fishing">Fishing</option>
                <option value="Flag Football">Flag Football</option>
                <option value="Flamenco">Flamenco</option>
                <option value="Flower Arranging">Flower Arranging</option>
                <option value="Fly Fishing">Fly Fishing</option>
                <option value="Flying Kites">Flying Kites</option>
                <option value="Foam Smithing">Foam Smithing</option>
                <option value="Football">Football</option>
                <option value="Foraging">Foraging</option>
                <option value="Foreign Languages">Foreign Languages</option>
                <option value="Formula Racing">Formula Racing</option>
                <option value="Frisbee">Frisbee</option>
                <option value="Furniture Making">Furniture Making</option>
                <option value="Gardening">Gardening</option>
                <option value="Genealogy">Genealogy</option>
                <option value="Geocaching">Geocaching</option>
                <option value="Ghost Hunting">Ghost Hunting</option>
                <option value="Glass Blowing">Glass Blowing</option>
                <option value="Glass Etching">Glass Etching</option>
                <option value="Go (Board Game)">Go (Board Game)</option>
                <option value="Golf">Golf</option>
                <option value="Graphic Design">Graphic Design</option>
                <option value="Grilling">Grilling</option>
                <option value="Guitar">Guitar</option>
                <option value="Gymnastics">Gymnastics</option>
                <option value="Hackathons">Hackathons</option>
                <option value="Hand Lettering">Hand Lettering</option>
                <option value="Handball">Handball</option>
                <option value="Harmonica">Harmonica</option>
                <option value="Herbalism">Herbalism</option>
                <option value="Hiking">Hiking</option>
                <option value="Hip Hop Dance">Hip Hop Dance</option>
                <option value="Historical Reenactment">Historical Reenactment</option>
                <option value="Home Brewing">Home Brewing</option>
                <option value="Home Improvement">Home Improvement</option>
                <option value="Home Theater">Home Theater</option>
                <option value="Horseback Riding">Horseback Riding</option>
                <option value="Hot Air Ballooning">Hot Air Ballooning</option>
                <option value="Hula Hooping">Hula Hooping</option>
                <option value="Hunting">Hunting</option>
                <option value="Ice Hockey">Ice Hockey</option>
                <option value="Ice Skating">Ice Skating</option>
                <option value="Illustration">Illustration</option>
                <option value="Improv Comedy">Improv Comedy</option>
                <option value="Indoor Climbing">Indoor Climbing</option>
                <option value="Interior Design">Interior Design</option>
                <option value="Investing">Investing</option>
                <option value="Javelin Throw">Javelin Throw</option>
                <option value="Jazz Dance">Jazz Dance</option>
                <option value="Jewelry Making">Jewelry Making</option>
                <option value="Jigsaw Puzzles">Jigsaw Puzzles</option>
                <option value="Jiu-Jitsu">Jiu-Jitsu</option>
                <option value="Juggling">Juggling</option>
                <option value="Jump Rope">Jump Rope</option>
                <option value="Karaoke">Karaoke</option>
                <option value="Karate">Karate</option>
                <option value="Kayaking">Kayaking</option>
                <option value="Kendama">Kendama</option>
                <option value="Kendo">Kendo</option>
                <option value="Kickboxing">Kickboxing</option>
                <option value="Kite Surfing">Kite Surfing</option>
                <option value="Knitting">Knitting</option>
                <option value="Knife Throwing">Knife Throwing</option>
                <option value="Lacrosse">Lacrosse</option>
                <option value="LARPing">LARPing</option>
                <option value="Laser Tag">Laser Tag</option>
                <option value="Latin Dance">Latin Dance</option>
                <option value="Lego Building">Lego Building</option>
                <option value="Letterboxing">Letterboxing</option>
                <option value="Lifting Weights">Lifting Weights</option>
                <option value="Line Dancing">Line Dancing</option>
                <option value="Listening to Music">Listening to Music</option>
                <option value="Lockpicking (Legal)">Lockpicking (Legal)</option>
                <option value="Magic">Magic</option>
                <option value="Makeup Artistry">Makeup Artistry</option>
                <option value="Mandolin">Mandolin</option>
                <option value="Map Making">Map Making</option>
                <option value="Marathon Running">Marathon Running</option>
                <option value="Martial Arts">Martial Arts</option>
                <option value="Meditation">Meditation</option>
                <option value="Metal Detecting">Metal Detecting</option>
                <option value="Metalworking">Metalworking</option>
                <option value="Miniature Painting">Miniature Painting</option>
                <option value="Model Building">Model Building</option>
                <option value="Model Railroading">Model Railroading</option>
                <option value="Modern Dance">Modern Dance</option>
                <option value="Monopoly">Monopoly</option>
                <option value="Motorcycling">Motorcycling</option>
                <option value="Mountain Biking">Mountain Biking</option>
                <option value="Mountain Climbing">Mountain Climbing</option>
                <option value="Movie Collecting">Movie Collecting</option>
                <option value="Movie Watching">Movie Watching</option>
                <option value="Mushroom Foraging">Mushroom Foraging</option>
                <option value="Music Composition">Music Composition</option>
                <option value="Music Production">Music Production</option>
                <option value="Nail Art">Nail Art</option>
                <option value="Needle Felting">Needle Felting</option>
                <option value="Needlepoint">Needlepoint</option>
                <option value="Origami">Origami</option>
                <option value="Painting">Painting</option>
                <option value="Papercraft">Papercraft</option>
                <option value="Parkour">Parkour</option>
                <option value="Piano">Piano</option>
                <option value="Pickleball">Pickleball</option>
                <option value="Photography">Photography</option>
                <option value="Piano Tuning">Piano Tuning</option>
                <option value="Ping Pong">Ping Pong</option>
                <option value="Pipe Smoking">Pipe Smoking</option>
                <option value="Podcasting">Podcasting</option>
                <option value="Poetry">Poetry</option>
                <option value="Poker">Poker</option>
                <option value="Pole Dancing">Pole Dancing</option>
                <option value="Pottery">Pottery</option>
                <option value="Powerlifting">Powerlifting</option>
                <option value="Programming">Programming</option>
                <option value="Puzzle Solving">Puzzle Solving</option>
                <option value="Quilting">Quilting</option>
                <option value="Racing Drones">Racing Drones</option>
                <option value="Racquetball">Racquetball</option>
                <option value="Radio-Controlled Cars">Radio-Controlled Cars</option>
                <option value="Rapping">Rapping</option>
                <option value="Reading">Reading</option>
                <option value="Record Collecting">Record Collecting</option>
                <option value="Reef Keeping">Reef Keeping</option>
                <option value="Renovation Projects">Renovation Projects</option>
                <option value="Resin Art">Resin Art</option>
                <option value="Restoring Furniture">Restoring Furniture</option>
                <option value="Rock Balancing">Rock Balancing</option>
                <option value="Rock Climbing">Rock Climbing</option>
                <option value="Rock Collecting">Rock Collecting</option>
                <option value="Roleplaying Games">Roleplaying Games</option>
                <option value="Roller Skating">Roller Skating</option>
                <option value="Rowing">Rowing</option>
                <option value="Rug Making">Rug Making</option>
                <option value="Running">Running</option>
                <option value="Sailing">Sailing</option>
                <option value="Sand Art">Sand Art</option>
                <option value="Sand Sculpting">Sand Sculpting</option>
                <option value="Scrapbooking">Scrapbooking</option>
                <option value="Scuba Diving">Scuba Diving</option>
                <option value="Sculpting">Sculpting</option>
                <option value="Sewing">Sewing</option>
                <option value="Shogi">Shogi</option>
                <option value="Singing">Singing</option>
                <option value="Skateboarding">Skateboarding</option>
                <option value="Skiing">Skiing</option>
                <option value="Skydiving">Skydiving</option>
                <option value="Slacklining">Slacklining</option>
                <option value="Sleeping">Sleeping</option>
                <option value="Snorkeling">Snorkeling</option>
                <option value="Snowboarding">Snowboarding</option>
                <option value="Snowmobiling">Snowmobiling</option>
                <option value="Soap Making">Soap Making</option>
                <option value="Soccer">Soccer</option>
                <option value="Softball">Softball</option>
                <option value="Songwriting">Songwriting</option>
                <option value="Speed Cubing">Speed Cubing</option>
                <option value="Spelunking">Spelunking</option>
                <option value="Spinning Yarn">Spinning Yarn</option>
                <option value="Squash">Squash</option>
                <option value="Stamp Collecting">Stamp Collecting</option>
                <option value="Stand-Up Comedy">Stand-Up Comedy</option>
                <option value="Star Gazing">Star Gazing</option>
                <option value="Steampunk Crafting">Steampunk Crafting</option>
                <option value="Stone Carving">Stone Carving</option>
                <option value="Storytelling">Storytelling</option>
                <option value="Sudoku">Sudoku</option>
                <option value="Surfing">Surfing</option>
                <option value="Swimming">Swimming</option>
                <option value="Table Tennis">Table Tennis</option>
                <option value="Taekwondo">Taekwondo</option>
                <option value="Tai Chi">Tai Chi</option>
                <option value="Tattoo Art">Tattoo Art</option>
                <option value="Taxidermy">Taxidermy</option>
                <option value="Tea Tasting">Tea Tasting</option>
                <option value="Tennis">Tennis</option>
                <option value="Thrifting">Thrifting</option>
                <option value="Trombone">Trombone</option>
                <option value="Trumpet">Trumpet</option>
                <option value="Ukulele">Ukulele</option>
                <option value="Ultimate Frisbee">Ultimate Frisbee</option>
                <option value="Urban Exploration">Urban Exploration</option>
                <option value="Vegan Cooking">Vegan Cooking</option>
                <option value="Video Editing">Video Editing</option>
                <option value="Video Gaming">Video Gaming</option>
                <option value="Vinyl Collecting">Vinyl Collecting</option>
                <option value="Virtual Reality Gaming">Virtual Reality Gaming</option>
                <option value="Volleyball">Volleyball</option>
                <option value="Walking">Walking</option>
                <option value="Warhammer">Warhammer</option>
                <option value="Watch Collecting">Watch Collecting</option>
                <option value="Watercolor Painting">Watercolor Painting</option>
                <option value="Water Polo">Water Polo</option>
                <option value="Weightlifting">Weightlifting</option>
                <option value="Whittling">Whittling</option>
                <option value="Windsurfing">Windsurfing</option>
                <option value="Wine Tasting">Wine Tasting</option>
                <option value="Wood Burning">Wood Burning</option>
                <option value="Wood Carving">Wood Carving</option>
                <option value="Woodworking">Woodworking</option>
                <option value="Word Games">Word Games</option>
                <option value="Worldbuilding">Worldbuilding</option>
                <option value="Wrestling">Wrestling</option>
                <option value="Writing">Writing</option>
                <option value="Yo-Yoing">Yo-Yoing</option>
                <option value="Yoga">Yoga</option>
                <option value="Zumba">Zumba</option>
                </select>
        </div>
        <div class="formBtn">
                <input type="reset" id="reset" value="Reset">
                <input type="submit" id="submit" value="Submit" >
        </div>
    </form>
</body>
<script>
    const currentPath = window.location.pathname.split("/").pop();
    if(currentPath[0] === 's' && currentPath[1] === 'o' && currentPath[2] === 'i' && currentPath[3] === 'g' && currentPath[4] === 'n' && currentPath[5] === 'u' && currentPath[6] === 'p' && currentPath[7] === '.' && currentPath[8] === 'h' && currentPath[9] === 't' && currentPath[10] === 'm' && currentPath[11] === 'l'){
        document.getElementById("nav4").style.background = "#F8BBD0"
        document.getElementById("navA4").style.color = "#C2185B"
    }
</script>
</html>