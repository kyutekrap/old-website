<?php
session_start();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RRM | Login</title>
    <link rel="icon" href="../RRM.png" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Center vertically on the screen */
        }
        
        .logo {
            width: 100px;
            height: 100px;
        }

        .container {
            max-width: 400px;
            margin: 10px;
            margin-top: -30px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
            transition: 0.2s all;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
            outline: 1px solid #7a2fbc;
        }

        input[type="submit"] {
            padding: 8px 20px;
            margin-top: 20px;
            margin-bottom: 10px;
            color: white;
            font-size: 0.8rem;
            border: none;
            border-radius: 5px;
            background-color: #6a1b9a;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #4a148c;
        }
        
        hr {
          border: none;
          height: 1px;
          background-color: #ccc; /* Adjust the color code to your preferred gray */
          margin: 20px 0; /* Adjust margin as needed */
        }
        
        .links {
            font-size: 0.8rem;
            color: #4a148c;
        }
        .links a {
            cursor: pointer;
        }
        
        #forgot_form, #signin_form {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container" id="login_form">
        <img class="logo" src="../RRM.png" />
        <h2>Login to RRM</h2>
        <form method="post" id="loginForm">
            <input type="password" placeholder="Username" required name="username" id="login_username" maxlength=30>
            <input type="password" placeholder="Password" required name="password" id="login_password" maxlength=30>
            <input type="submit" value="Login" name="loginBtn">
            <p style="color: red;" id="login_error"></p>
          </form>
        <hr/>
        <div class="links">
            <a style="float: left;" onclick="recover_account()"></a>
            <a style="float: right;" onclick="setup_account()">No account yet?</a>
        </div>
    </div>
    
    <div class="container" id="signin_form">
        <img class="logo" src="../RRM.png" />
        <h2>Setup Account</h2>
        <form method="post" id="signinForm">
            <input type="email" placeholder="Email" required name="email" id="signin_email" maxlength=240>
            <input type="password" placeholder="Username" required name="username" id="signin_username" maxlength=30>
            <input type="password" placeholder="Password" required name="password" id="signin_password" maxlength=30>
            <input type="submit" value="Register" name="register">
            <p style="color: red;" id="register_error"></p>
          </form>
        <hr/>
        <div class="links">
            <a style="float: left;" onclick="login()">Client login</a>
            <a style="float: right;" onclick="recover_account()"></a>
        </div>
    </div>
    
    <div class="container" id="forgot_form">
        <img class="logo" src="../RRM.png" />
        <h2>Recover Account</h2>
        <form method="post" id="forgotForm">
            <input type="email" placeholder="Email" required name="email" id="forgot_email" maxlength=240>
            <input type="submit" value="Recover" name="recover_account">
            <p style="color: blue;" id="recover_msg"></p>
          </form>
        <hr/>
        <div class="links">
            <a style="float: left;" onclick="login()">Client login</a>
            <a style="float: right;" onclick="setup_account()">No account yet?</a>
        </div>
    </div>
    
    <script>
        function login() {
            var containers = document.querySelectorAll('.container');
              containers.forEach(function(container) {
                container.style.display = 'none';
              });
            document.getElementById("login_form").style.display = "block";
        }
        function setup_account() {
            var containers = document.querySelectorAll('.container');
              containers.forEach(function(container) {
                container.style.display = 'none';
              });
            document.getElementById("signin_form").style.display = "block";
        }
        function recover_account() {
            var containers = document.querySelectorAll('.container');
              containers.forEach(function(container) {
                container.style.display = 'none';
              });
            document.getElementById("forgot_form").style.display = "block";
        }
        
        $('#loginForm').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission
    
            // Get username and password values
            var username = $('#login_username').val();
            var password = $('#login_password').val();
    
            // AJAX request
            $.ajax({
                type: 'POST',
                url: 'login.php',
                data: {
                    username: username,
                    password: password
                },
                success: function(response) {
                    if (response == 200) {
                        window.location.href = "../home";
                    } else {
                        document.getElementById("login_error").innerHTML = response;
                    }
                },
                error: function(xhr, status, error) {
                    document.getElementById("login_error").innerHTML = "Network error";
                }
            });
        });
        
        $('#signinForm').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission
    
            // Get username and password values
            var username = $('#signin_username').val();
            var password = $('#signin_password').val();
            var email = $('#signin_email').val();
    
            // AJAX request
            $.ajax({
                type: 'POST',
                url: 'register.php',
                data: {
                    username: username,
                    password: password,
                    email: email
                },
                success: function(response) {
                    if (response == 200) {
                        window.location.href = "../home";
                    } else {
                        document.getElementById("register_error").innerHTML = response;
                    }
                },
                error: function(xhr, status, error) {
                    document.getElementById("register_error").innerHTML = "Network error";
                }
            });
        });
        
        $('#forgotForm').submit(function(event) {
            event.preventDefault();
            
            var email = $('#forgot_email').val();
    
            // AJAX request
            $.ajax({
                type: 'POST',
                url: 'recover.php',
                data: {
                    email: email
                },
                success: function(response) {
                    document.getElementById("recover_msg").innerHTML = response;
                },
                error: function(xhr, status, error) {
                    //
                }
            });
        });
    </script>
</body>
</html>