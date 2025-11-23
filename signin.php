






<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Instagram Sign Up</title>
</head>

<style>
body {
    background: deeppink;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Main container */
.container {
    width: 100%;
    display: flex;
    justify-content: center;
}

/* White box */
.box {
    width: 350px;
    background: hotpink;
    border: 1px solid #dbdbdb;
    padding: 40px 40px 30px;
    text-align: center;
}

/* Instagram logo text */
.logo {
    font-family: 'Billabong', cursive;
    font-size: 48px;
    margin-bottom: 20px;
}

/* Input fields */
form input {
    width: 100%;
    padding: 10px;
    margin: 6px 0;
    border: 1px solid #dbdbdb;
    border-radius: 3px;
    background: #fafafa;
    font-size: 14px;
}

form input:focus {
    border-color: #a8a8a8;
    outline: none;
}

/* Normal button */
.btn {
    width: 100%;
    background: #0095f6;
    border: none;
    padding: 10px;
    border-radius: 4px;
    color: white;
    font-weight: 600;
    margin-top: 10px;
    cursor: pointer;
    transition: 0.2s;
}

.btn:hover {
    background: #007ad6;
}

/* Google Button */
.google-btn {
    margin-top: 15px;
    width: 100%;
    background: white;
    border: 1px solid #dbdbdb;
    padding: 10px;
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.google-btn img {
    width: 18px;
}

.google-btn:hover {
    background: #f0f0f0;
}

/* Switch links */
.switch {
    margin-top: 20px;
    font-size: 14px;
}

.switch a {
    color: #0095f6;
    text-decoration: none;
    font-weight: 600;
}

.switch a:hover {
    text-decoration: underline;
}

/* Error box */
.error-box {
    background: #ffe6e6;
    border: 1px solid #ffb3b3;
    padding: 10px;
    border-radius: 3px;
    margin-bottom: 15px;
}

.error-box p {
    color: #d8000c;
    font-size: 14px;
}
</style>

<body>

<div class="container">
    <div class="box">
        <h1 class="logo">Instagram</h1>

        <form method="POST">
            <input type="text" name="username" placeholder="Username">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Password">

            <button class="btn">Sign Up</button>

            <!-- Google Login Button -->
            <button type="button" class="google-btn">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg">
                Sign in with Google
            </button>
        </form>

        <p class="switch">
            Already have an account?
            <a href="login3.php">login</a>
        </p>
    </div>
</div>

</body>
</html>
