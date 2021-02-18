<?php
include_once "WebHelper.php";

$helper = new WebHelper();
$conn = $helper->getConnection();

$username = $password = "";
$errorMsg = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $errorMsg = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $errorMsg = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($errorMsg)){

        $errorMsg = $helper->login($username, $password);
    }

    // Close connection
    mysqli_close($conn);
}

?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Dansk Ejendomsforvaltning - Klagesystem</title>
</head>
<body class="login">
<div>
    <div class="w3-container w3-center">
        <h1>Dansk ejendomsforvaltning</h1>
    </div>
    <div class="w3-row">
        <div class="w3-col m3 w3-center">
            &nbsp;
        </div>
        <div class="w3-col m6 w3-center">
            <p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <table width="100px" class="loginBox">
                    <tr>
                        <th>Username</th>
                        <td><input name="username" id="username" type="text" value=""></td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td><input name="password" type="password" value=""></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right">
                            <input type="submit" id="loginBtn" value="Login">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center" class="errorCell">
                            <?php echo $errorMsg; ?>
                        </td>
                    </tr>
                </table>
            </form>

            </p>
        </div>
        <div class="w3-col m3 w3-center">
            &nbsp;
        </div>
    </div>

</div>

<script>
    $(document).ready(function(){
        $("#username").focus();
        $("INPUT").hover(function(){
            $(this).css("background-color", "white");
        }, function(){
            $(this).css("background", "none");
        });
    });
</script>
</body>
