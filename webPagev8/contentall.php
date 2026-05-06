<?php
//include auth_session.php file on all user panel pages
include("auth_session.php");
include 'dbconnect.php';
$email = $_SESSION['email'];
$nodeID = $_SESSION['NodeID'];
$currentMatchIDs = [];
$matchesAll = [];
$matchesInfo = [];
//list of NodeIDs of matches related to logged in user
$checkstmt = $conn->prepare("SELECT SourceID FROM edges WHERE TargetID = ? AND EdgeType = 'Matched'");
$checkstmt->bind_param("i", $nodeID);
$checkstmt->execute();
$result = $checkstmt->get_result();
while ($row = $result->fetch_assoc()) {
    $currentMatchIDs[] = $row['SourceID'];
}
$checkstmt->close();
//grabs userIDs of people matches with user based on matchID shared between users
foreach ($currentMatchIDs as $matchIDs) {
    $check2stmt = $conn->prepare("SELECT TargetID FROM edges WHERE SourceID = ? AND EdgeType = 'Matched' AND TargetID != ?");
    $check2stmt->bind_param("ii", $matchIDs, $nodeID);
    $check2stmt->execute();
    $result = $check2stmt->get_result();
    while ($row6 = $result->fetch_assoc()) {
        $matchesAll[] = $row6['TargetID'];
    }
    $check2stmt->close();
}
//matchinfo
foreach ($matchesAll as $users) {
    $check3stmt = $conn->prepare("SELECT * FROM nodes WHERE NodeID = ? AND NodeType = 'User'");
    $check3stmt->bind_param("i", $users);
    $check3stmt->execute();
    $result = $check3stmt->get_result();
    while ($row2 = $result->fetch_assoc()) {
        $matchesInfo[] = $row2;
    }
    $check3stmt->close();
}
foreach ($currentMatchIDs as $index => $matchID) {
    $check4stmt = $conn->prepare("SELECT MatchDate FROM nodes WHERE NodeID = ? AND NodeType = 'Match'");
    $check4stmt->bind_param("i", $matchID);
    $check4stmt->execute();
    $result = $check4stmt->get_result();
    if ($row2 = $result->fetch_assoc()) {
        $matchesInfo[$index]['matchDate'] = $row2['MatchDate'];
    }
    $check4stmt->close();
}
?>

<?php
// Graph Data
$graphElements = [];
$addedNodes = [];
$addedEdges = [];

// Adds a Cytoscape node only once
function addNode(&$graphElements, &$addedNodes, $id, $label, $type, $extraData = []) {
    if (!isset($addedNodes[$id])) {
        $graphElements[] = [
            "data" => array_merge([
                "id" => $id,
                "label" => $label,
                "type" => $type
            ], $extraData)
        ];

        $addedNodes[$id] = true;
    }
}

// Adds a Cytoscape edge only once
function addEdge(&$graphElements, &$addedEdges, $id, $source, $target, $label = "", $extraData = []) {
    if (!isset($addedEdges[$id])) {
        $graphElements[] = [
            "data" => array_merge([
                "id" => $id,
                "source" => $source,
                "target" => $target,
                "label" => $label
            ], $extraData)
        ];

        $addedEdges[$id] = true;
    }
}

// Add logged-in user as center node
addNode(
    $graphElements,
    $addedNodes,
    "user_" . $nodeID,
    "You",
    "currentUser",
    [
        "level" => 0
    ]
);

// Add direct matches and hidden second-degree matches
foreach ($matchesInfo as $user) {
    $matchedUserNodeId = "user_" . $user["NodeID"];

    // Add first-degree match node
    addNode(
        $graphElements,
        $addedNodes,
        $matchedUserNodeId,
        $user["Name"],
        "matchedUser",
        [
            "city" => $user["City"],
            "state" => $user["State"],
            "occupation" => $user["Occupation"],
            "level" => 1
        ]
    );

    // Add edge from logged-in user to first-degree match
    addEdge(
        $graphElements,
        $addedEdges,
        "edge_" . $nodeID . "_" . $user["NodeID"],
        "user_" . $nodeID,
        $matchedUserNodeId,
        isset($user["matchDate"]) ? date("m/d/Y", strtotime($user["matchDate"])) : "",
        [
            "edgeType" => "firstDegree"
        ]
    );

    // Find Match nodes connected to this first-degree match
    $theirMatchIDs = [];

    $stmt = $conn->prepare("
        SELECT SourceID
        FROM edges
        WHERE TargetID = ?
        AND EdgeType = 'Matched'
    ");

    $stmt->bind_param("i", $user["NodeID"]);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $theirMatchIDs[] = $row["SourceID"];
    }

    $stmt->close();

    // For each Match node, find other users connected to that match
    foreach ($theirMatchIDs as $theirMatchID) {
        $stmt2 = $conn->prepare("
            SELECT n.*
            FROM edges e
            JOIN nodes n ON e.TargetID = n.NodeID
            WHERE e.SourceID = ?
            AND e.EdgeType = 'Matched'
            AND n.NodeType = 'User'
            AND e.TargetID != ?
            AND e.TargetID != ?
        ");

        $stmt2->bind_param("iii", $theirMatchID, $user["NodeID"], $nodeID);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        while ($secondUser = $result2->fetch_assoc()) {
            $secondUserNodeId = "user_" . $secondUser["NodeID"];

            // Add hidden second-degree match node
            addNode(
                $graphElements,
                $addedNodes,
                $secondUserNodeId,
                $secondUser["Name"],
                "secondDegreeUser",
                [
                    "city" => $secondUser["City"],
                    "state" => $secondUser["State"],
                    "occupation" => $secondUser["Occupation"],
                    "level" => 2,
                    "visible" => false,
                    "parentMatch" => $matchedUserNodeId
                ]
            );

            // Add hidden edge from first-degree match to second-degree match
            addEdge(
                $graphElements,
                $addedEdges,
                "edge_" . $user["NodeID"] . "_" . $secondUser["NodeID"],
                $matchedUserNodeId,
                $secondUserNodeId,
                "",
                [
                    "visible" => false,
                    "parentMatch" => $matchedUserNodeId,
                    "edgeType" => "secondDegree"
                ]
            );
        }

        $stmt2->close();
    }
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
    <script src="https://unpkg.com/cytoscape/dist/cytoscape.min.js"></script>
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
    <h1 style="display: flex; justify-content: center;">Your Match Network</h1>
    <div id="cy" style="width: 100%; height: 1000px; border: 1px solid #ccc; border-radius: 10px; margin: 20px auto; position: relative;">
    <button id="resetGraphBtn" type="button">
        Reset Graph
    </button>
    </div>

    <script>
    const graphElements = <?php echo json_encode($graphElements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

    const cy = cytoscape({
        container: document.getElementById('cy'),
        elements: graphElements,

        style: [
            {
                selector: 'node',
                style: {
                    'label': 'data(label)',
                    'color': '#fff',
                    'text-valign': 'center',
                    'text-halign': 'center',
                    'font-size': 12,
                    'text-wrap': 'wrap',
                    'text-max-width': 120
                }
            },
            {
                selector: 'node[type = "currentUser"]',
                style: {
                    'background-color': '#C2185B',
                    'shape': 'ellipse',
                    'width': 75,
                    'height': 75,
                    'font-size': 14
                }
            },
            {
                selector: 'node[type = "matchedUser"]',
                style: {
                    'background-color': '#F48FB1',
                    'shape': 'round-rectangle',
                    'width': 145,
                    'height': 55,
                    'padding': '10px'
                }
            },
            {
                selector: 'node[type = "secondDegreeUser"]',
                style: {
                    'background-color': '#CE93D8',
                    'shape': 'round-rectangle',
                    'width': 125,
                    'height': 45,
                    'padding': '8px',
                    'font-size': 11,
                    'opacity': 1
                }
            },
            {
                selector: 'edge',
                style: {
                    'label': 'data(label)',
                    'width': 2,
                    'line-color': '#999',
                    'target-arrow-color': '#999',
                    'target-arrow-shape': 'triangle',
                    'curve-style': 'bezier',
                    'font-size': 10,
                    'color': '#333',
                    'text-rotation': 'none',
                    'text-margin-y': -12,
                    'text-background-color': '#ffffff',
                    'text-background-opacity': 1,
                    'text-background-padding': '2px',
                    'text-border-opacity': 1,
                    'text-border-width': 1,
                    'text-border-color': '#cccccc'
                }
            },
            {
                selector: 'edge[edgeType = "secondDegree"]',
                style: {
                    'line-style': 'dashed',
                    'width': 1.5,
                    'line-color': '#B39DDB',
                    'target-arrow-color': '#B39DDB'
                }
            }
        ],

        layout: {
            name: 'preset'
        }
    });

    const expandedMatches = {};
    const originalPositions = {};
    let originalZoom = 1;
    let originalPan = { x: 0, y: 0 };

    function runInitialLayoutAndSavePositions() {
        cy.layout({
            name: 'concentric',
            animate: true,
            fit: true,
            padding: 80,
            minNodeSpacing: 45,
            avoidOverlap: true,
            concentric: function(node) {
                return 3 - node.data('level');
            },
            levelWidth: function() {
                return 1;
            },
            stop: function() {
                cy.nodes().forEach(function(node) {
                    originalPositions[node.id()] = {
                        x: node.position('x'),
                        y: node.position('y')
                    };
                });

                originalZoom = cy.zoom();
                originalPan = {
                    x: cy.pan('x'),
                    y: cy.pan('y')
                };
            }
        }).run();
    }

    // Hide second-degree matches when the graph first loads
    cy.nodes('[type = "secondDegreeUser"]').style('display', 'none');
    cy.edges('[edgeType = "secondDegree"]').style('display', 'none');

    // Run the initial radial layout once and save starting positions, zoom, and pan
    runInitialLayoutAndSavePositions();

    // Reset button
    document.getElementById("resetGraphBtn").addEventListener("click", function(event) {
        event.stopPropagation();

        // Hide all second-degree matches
        cy.nodes('[type = "secondDegreeUser"]').style('display', 'none');
        cy.edges('[edgeType = "secondDegree"]').style('display', 'none');

        // Mark all first-degree matches as collapsed
        for (const matchId in expandedMatches) {
            expandedMatches[matchId] = false;
        }

        // Restore every node to its original page-load position
        cy.nodes().forEach(function(node) {
            const original = originalPositions[node.id()];

            if (original) {
                node.animate({
                    position: {
                        x: original.x,
                        y: original.y
                    }
                }, {
                    duration: 300
                });
            }
        });

        // Restore exact original zoom and pan
        setTimeout(function() {
            cy.zoom(originalZoom);
            cy.pan(originalPan);
        }, 320);
    });

    // Create hover tooltip
    const tooltip = document.createElement('div');
    tooltip.id = 'cyTooltip';
    tooltip.style.position = 'absolute';
    tooltip.style.display = 'none';
    tooltip.style.background = '#ffffff';
    tooltip.style.color = '#333';
    tooltip.style.border = '1px solid #ccc';
    tooltip.style.borderRadius = '10px';
    tooltip.style.padding = '10px 14px';
    tooltip.style.boxShadow = '0 4px 12px rgba(0,0,0,0.2)';
    tooltip.style.fontSize = '14px';
    tooltip.style.zIndex = '999';
    tooltip.style.pointerEvents = 'none';
    tooltip.style.minWidth = '180px';

    document.getElementById('cy').appendChild(tooltip);

    function tooltipHTML(data) {
        let html = "<strong>" + data.label + "</strong>";

        if (data.occupation) {
            html += "<br>Occupation: " + data.occupation;
        }

        if (data.city && data.state) {
            html += "<br>Location: " + data.city + ", " + data.state;
        }

        if (data.type === "matchedUser") {
            html += "<br><em>Click to show/hide their matches</em>";
        }

        return html;
    }

    function moveTooltip(node) {
        const pos = node.renderedPosition();

        tooltip.style.left = (pos.x + 20) + "px";
        tooltip.style.top = (pos.y + 20) + "px";
    }

    cy.on('mouseover', 'node', function(evt) {
        const node = evt.target;
        const data = node.data();

        tooltip.innerHTML = tooltipHTML(data);
        moveTooltip(node);
        tooltip.style.display = 'block';
    });

    cy.on('mousemove', 'node', function(evt) {
        moveTooltip(evt.target);
    });

    cy.on('mouseout', 'node', function() {
        tooltip.style.display = 'none';
    });

    function getRelatedElements(matchId) {
        const relatedNodes = cy.nodes().filter(function(ele) {
            return ele.data('parentMatch') === matchId;
        });

        const relatedEdges = cy.edges().filter(function(ele) {
            return ele.data('parentMatch') === matchId;
        });

        return {
            nodes: relatedNodes,
            edges: relatedEdges,
            all: relatedNodes.union(relatedEdges)
        };
    }

    function placeSecondDegreeAroundParent(parentNode, secondDegreeNodes) {
        const parentPos = parentNode.position();
        const count = secondDegreeNodes.length;

        if (count === 0) {
            return;
        }

        const mainUser = cy.nodes('[type = "currentUser"]')[0];
        const mainPos = mainUser.position();

        const dx = parentPos.x - mainPos.x;
        const dy = parentPos.y - mainPos.y;

        const baseAngle = Math.atan2(dy, dx);

        let radius = 170;

        if (count > 4) {
            radius = 210;
        }

        if (count > 8) {
            radius = 260;
        }

        if (count > 12) {
            radius = 320;
        }

        let spread = Math.PI;

        if (count <= 2) {
            spread = Math.PI / 2;
        }

        if (count >= 8) {
            spread = Math.PI * 1.25;
        }

        if (count >= 12) {
            spread = Math.PI * 1.5;
        }

        secondDegreeNodes.forEach(function(childNode, index) {
            let angle;

            if (count === 1) {
                angle = baseAngle;
            } else {
                angle = baseAngle - spread / 2 + (spread * index) / (count - 1);
            }

            const x = parentPos.x + radius * Math.cos(angle);
            const y = parentPos.y + radius * Math.sin(angle);

            childNode.position({
                x: x,
                y: y
            });
        });
    }

    cy.on('tap', 'node', function(evt) {
        const node = evt.target;
        const data = node.data();

        if (data.type === 'matchedUser') {
            const matchId = data.id;
            const related = getRelatedElements(matchId);

            if (expandedMatches[matchId]) {
                related.all.style('display', 'none');
                expandedMatches[matchId] = false;
            } else {
                placeSecondDegreeAroundParent(node, related.nodes);

                related.all.style('display', 'element');
                expandedMatches[matchId] = true;

                related.nodes.style('opacity', 0);
                related.nodes.animate({
                    style: {
                        opacity: 1
                    }
                }, {
                    duration: 250
                });
            }
        }
    });

    // Click background to collapse all second-degree matches
    cy.on('tap', function(evt) {
        if (evt.target === cy) {
            cy.nodes('[type = "secondDegreeUser"]').style('display', 'none');
            cy.edges('[edgeType = "secondDegree"]').style('display', 'none');

            for (const matchId in expandedMatches) {
                expandedMatches[matchId] = false;
            }
        }
    });
</script>
    const currentPath = window.location.pathname.split("/").pop();
    if (currentPath[0] === 'c' && currentPath[1] === 'o' && currentPath[2] === 'n' && currentPath[3] === 't' && currentPath[4] === 'e' && currentPath[5] === 'n' && currentPath[6] === 't') {
        document.getElementById("nav2").style.background = "#FAD4DF"
        document.getElementById("navA2").style.color = "#C2185B"
    }
</script>

</html>