<?php
// ---------- CONNECT DATABASE ----------
$conn = new mysqli("localhost", "root", "", "log");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect inputs
    $username = trim($_POST["username"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm"];

    // ---------- VALIDATION ----------
    if ($username == "") $errors[] = "Username is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required";
    if ($password == "") $errors[] = "Password required";
    if ($password !== $confirm) $errors[] = "Passwords do not match";

    // ---------- CHECK EXISTING EMAIL ----------
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

    // ---------- INSERT USER ----------
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO logs (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hash);

        if ($stmt->execute()) {
            header("Location: login3.php"); // redirect to login page
            exit;
        } else {
            $errors[] = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Instagram Sign Up</title>
    <style>
        body {
            background: deeppink;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        .box {
            width: 350px;
            background: hotpink;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
        }
        .logo {
            font-family: 'Billabong', cursive;
            font-size: 48px;
            margin-bottom: 20px;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin: 6px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .btn {
            width: 100%;
            background: #0095f6;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .error-box {
            background: #ffe6e6;
            border: 1px solid #ffb3b3;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .error-box p {
            color: #d8000c;
        }
    </style>
</head>
<body>
<div class="box">
    <h1 class="logo">Instagram</h1>

    <?php if (!empty($errors)) {
        echo "<div class='error-box'>";
        foreach ($errors as $e) echo "<p>$e</p>";
        echo "</div>";
    } ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="confirm" placeholder="Confirm Password">
        <button class="btn" type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="signin.php">Log In</a></p><a href="login3.php"> 
</div>
</body>
</html>
