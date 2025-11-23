<?php
//---------- CONNECT TO DATABASE ----------
$conn = new mysqli("localhost", "root", "", "log");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$errors = [];
$username = "";
$email = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Safely get POST data
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
    $email    = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    $confirm  = isset($_POST["confirm"]) ? $_POST["confirm"] : "";

    // ---------- VALIDATION ----------
    if ($username === "") {
        $errors[] = "Username is required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required";
    }

    if ($password === "") {
        $errors[] = "Password is required";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match";
    }

    // ---------- CHECK IF EMAIL EXISTS ----------
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM logs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email already exists";
        }
        $stmt->close();
    }

    // ---------- INSERT NEW USER ----------
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO logs (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hash);

        if ($stmt->execute()) {
            // Redirect to login page after successful signup
            header("Location: login3.php");
            exit;
        } else {
            $errors[] = "Database Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Instagram login</title>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background: #fafafa;
        display: flex;
    }

    /* ======================== SIDEBAR ========================== */
    .sidebar {
        width: 80px;
        background: #fff;
        height: 100vh;
        border-right: 1px solid #ddd;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 20px;
        position: fixed;
        left: 0;
        top: 0;
        gap: 25px;
    }

    .sidebar svg {
        width: 28px;
        height: 28px;
        fill: none;
        stroke: black;
        stroke-width: 1.8;
        cursor: pointer;
    }

    .sidebar svg:hover {
        stroke: #d62976; /* Instagram pink on hover */
    }

    /* ======================== MAIN FEED ======================== */
    .feed {
        margin-left: 100px; /* enough space for sidebar */
        padding: 20px;
        width: 600px;
    }

    /* ======================== STORIES =========================== */
    .stories {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }

    .story {
        text-align: center;
    }

    .story img {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        border: 3px solid #d62976;
        object-fit: cover;
    }

    .story-name {
        font-size: 12px;
    }

    /* ======================== POST CARD ======================== */
    .post {
        background:white;
        border: 1px solid #ddd;
        border-radius: 10px;
        margin-top: 20px;
        overflow: hidden;
    }

    .post-header {
        display: flex;
        align-items: center;
        padding: 12px;
        gap: 10px;
    }

    .post-header img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .post-header .username {
        font-weight: bold;
        font-size: 14px;
    }

    .post-img {
        width: 100%;
        display: block;
    }

    .post-actions {
        padding: 12px;
        font-size: 24px;
        display: flex;
        gap: 15px;
    }

    .post-caption {
        padding: 0 12px 12px 12px;
        font-size: 14px;
    }
</style>
</head>

<body>

<!-- ===================== SIDEBAR ====================== -->
<div class="sidebar">
    <!-- Home -->
    <svg viewBox="0 0 24 24">
        <path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-6v-7H10v7H4a1 1 0 0 1-1-1V9.5z"/>
    </svg>

    <!-- Search -->
    <svg viewBox="0 0 24 24">
        <path d="M10 18a8 8 0 1 1 6.32-3.16l4.42 4.42-1.41 1.41-4.42-4.42A8 8 0 0 1 10 18z"/>
    </svg>

    <!-- Create (Plus) -->
    <svg viewBox="0 0 24 24">
        <path d="M12 5v14m7-7H5" stroke-width="2" stroke-linecap="round"/>
    </svg>

    <!-- Activity (Heart) -->
    <svg viewBox="0 0 24 24">
        <path d="M12 21s-6.5-4.35-9-8.5C-1 7 2 3 6 3c2.11 0 4.22 1.11 5 3 0.78-1.89 2.89-3 5-3 4 0 7 4 3 9.5-2.5 4.15-9 8.5-9 8.5z"/>
    </svg>

    <!-- Profile -->
    <svg viewBox="0 0 24 24">
        <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4 0-8 2-8 5v2h16v-2c0-3-4-5-8-5z"/>
    </svg>
</div>


<!-- ======================= FEED ======================== -->
<div class="feed">

    <!-- Stories Row -->
    <div class="stories">
        <div class="story">
            <img src="https://via.placeholder.com/65">
              
            <div class="story-name">user1</div>
        </div>

        <div class="story">
            <img src="https://via.placeholder.com/65">
            <div class="story-name">user2</div>
        </div>

        <div class="story">
            <!-- <img src="https://via.placeholder.com/65"> -->
            
            <div class="story-name">user3</div>
        </div>
    </div>

    <!-- ===================== POST ====================== -->
    <div class="post">

        <div class="post-header">
            <img src="https://via.placeholder.com/40">
            <div>
                <div class="username">MINIICT</div>
                <div style="font-size: 12px; color: #777;">8h</div>
                <img src="ALX.png" width="400px" height="300px">

            </div>
        </div>
        <b>he Government of Rwanda, ALX, and Anthropic have launched “Chidi,” a groundbreaking AI learning tutor designed to revolutionize education across Africa. Unveiled in Kigali on November 17, 2025, Chidi utilizes Anthropic’s Claude model to foster critical thinking and problem-solving skills through inquiry-based guidance rather than providing rote answers.

Grounded in African pedagogy, the initiative is now expanding into Rwanda’s public education system, targeting 2,000 educators and students.

ALX CEO Fred Swaniker stated the partnership aims to transform African youth from AI consumers into creators, ensuring access to world-class technological tools.

        <img class="post-img" src="https://via.placeholder.com/600x400">
          <img src="ganza.png" width="400px" height="300px">
        <div class="post-actions">
            <div class="post-actions">
    <!-- Like (Heart) -->
    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="cursor:pointer;">
        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
    </svg>

    <!-- Comment (Speech Bubble) -->
    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="cursor:pointer;">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>

    <!-- Share (Paper Plane) -->
    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="cursor:pointer;">
        <line x1="22" y1="2" x2="11" y2="13"></line>
        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
    </svg>

    <!-- Bookmark -->
    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="cursor:pointer;">
        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
    </svg>
</div>
        <button class="btn">Logout</button><a href="signup.php">     
         
        </div>

        <div class="post-caption">
            <img src="ALX.png" width="400px" height="300px"<b><br><br><br><br><br><br>
<style>
                .ig-icons {
    display: flex;
    gap: 40px;   /* smaller spacing */
    align-items: center;

}

.icon, .heart-icon {
    width: 40px;   /* made smaller */
    height: 40px;  /* made smaller */
    cursor: pointer;
    fill: whitesmoke;
    stroke: #000;
    stroke-width: 1.8; /* thinner stroke for small size */
}
</style>
<div class="ig-icons">
    <div class="heart-container">
        <svg class="heart-icon" viewBox="0 0 24 24">
            <path d="M12.1 8.64l-.1.1-.11-.1C10.14 6.6 7.1 6.24 
                     5.14 8.2c-2.19 2.18-2.19 5.72 0 7.9 
                     L12 22l6.86-5.9c2.19-2.18 2.19-5.72 
                     0-7.9-1.96-1.96-5-1.6-6.76.54z" />
        </svg>
    </div>
 
    <svg class="icon" viewBox="0 0 24 24">
        <path d="M21.99 4c0-1.1-.9-2-2-2H4.01c-1.1 0-2 .9-2 2v14l4-4H19.99
                 c1.1 0 2-.9 2-2V4z"/>
    </svg>

    <svg class="icon" viewBox="0 0 24 24">
        <path d="M2 21l21-9L2 3v7l15 2-15 2z"/>
    </svg>
</div>
       <button class="btn">Logout</button><a href="signup.php">     
         





       
            <b>randaict</b>The Government of Rwanda in partnership with ALX and Anthropic has launched one of Africa’s largest AI for Education initiatives, introducing Chidi, a Claude-powered learning companion for learners and educators. This collaboration “enhances learning, supports educators, and builds a workforce ready for the 21st century,” said Minister Paula Ingabire..
        </div>
    </div>

</div>

</body>
</html>
