<html>
	<head>
		<title> Главная </title>
		<meta charset="utf-8">
		<link href="mystyle.css" rel="stylesheet" type="text/css">
	</head>
	
	<?php
	// Include config file
	require_once "config.php";
	// Check if the user is already logged in, if yes then redirect him to welcome page
	
	//Убийство сессии после логаута
	if(isset($_GET["action"])){	
	
		$sqlcheck = "UPDATE sessions SET active = 0 WHERE sessionhash = ?";
         
								if($stmtch = mysqli_prepare($link, $sqlcheck)){
								// Bind variables to the prepared statement as parameters
								mysqli_stmt_bind_param($stmtch, "s", $param_sessionhash);
            
								// Set parameters
								
								$param_sessionhash =  $_COOKIE["sessionid"]; // Checks password hash
								
								if(mysqli_stmt_execute($stmtch))
								{		
									setcookie("sessionid");
									setcookie("loggedin");
									setcookie("id");
									setcookie("username"); 
									
								}
								// Close statement
								mysqli_stmt_close($stmtch);
								}			
		
	}


	
	//100% рабочий вариант
	if(isset($_COOKIE["loggedin"]) && $_COOKIE["loggedin"] == true){
		$num_of_rows=0;
		$sqlcheck = "SELECT username,sessionhash,active from sessions where active=true and username = ? and sessionhash = ?";
         
								if($stmtch = mysqli_prepare($link, $sqlcheck)){
								// Bind variables to the prepared statement as parameters
								mysqli_stmt_bind_param($stmtch, "ss", $param_username, $param_sessionhash);
            
								// Set parameters
								$param_username = $_COOKIE["username"];
								$param_sessionhash =  $_COOKIE["sessionid"]; // Checks password hash
								
								if(mysqli_stmt_execute($stmtch))
								{
								 mysqli_stmt_store_result($stmtch);
								$num_of_rows=mysqli_stmt_num_rows($stmtch);
								}
								// Close statement
								mysqli_stmt_close($stmtch);
								}		
		if($num_of_rows>0){
			header("location: main.php");
			echo "$test";
			exit;
		}
		
	}
 
	
 
	// Define variables and initialize with empty values
	$username = $password = "";
	$username_err = $password_err = $login_err = "";
 
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["username"])){
 
		// Check if username is empty
		if(empty(trim($_GET["username"]))){
			$username_err = "Пожалуйста введите имя";
		} else{
			$username = trim($_GET["username"]);
		}
    
		// Check if password is empty
		if(empty(trim($_GET["password"]))){
			$password_err = "Пожалуйста введите пароль";
		} else{
			$password = trim($_GET["password"]);
		}
    
		// Validate credentials
		if(empty($username_err) && empty($password_err)){
			// Prepare a select statement
			$sql = "SELECT id, username, password FROM users WHERE username = ?";
				
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "s", $param_username);
            
				// Set parameters
				$param_username = $username;
            
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					// Store result
					mysqli_stmt_store_result($stmt);
                
					// Check if username exists, if yes then verify password
					if(mysqli_stmt_num_rows($stmt) == 1){                    
						// Bind result variables
						mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
						if(mysqli_stmt_fetch($stmt)){
							if(password_verify($password, $hashed_password)){
								// Password is correct, so start a new session
								
								
								// Store data in session variables
								setcookie("loggedin",true);
								setcookie("id",$id);
								setcookie("username",$username);                          
                            
								
								
								$sqlcheck = "INSERT INTO sessions (username, sessionhash, active) VALUES (?, ?, true)";
         
								if($stmtch = mysqli_prepare($link, $sqlcheck)){
								// Bind variables to the prepared statement as parameters
								mysqli_stmt_bind_param($stmtch, "ss", $param_username, $param_sessionhash);
            
								// Set parameters
								$param_username = $username;
								$param_sessionhash = password_hash(random_int(1,1000), PASSWORD_DEFAULT); // Creates a password hash
								
								mysqli_stmt_execute($stmtch);
								setcookie("sessionid",$param_sessionhash);

								// Close statement
								mysqli_stmt_close($stmtch);
								}
								// Redirect user to welcome page
								//
								header("location: main.php?success");								
								//								
							} else{
								// Password is not valid, display a generic error message
								$username_err = "Неправильное имя или пароль";
							}
						}
					} else{
						// Username doesn't exist, display a generic error message
						$username_err = "Неправильное имя или пароль";
					}
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}

				// Close statement
				mysqli_stmt_close($stmt);
			}
		}
		
		// Close connection
		mysqli_close($link);
		
	
	}
	?>
	
	
	<body>
	<div id="main">
		<div id="fone">
		<form name = "f2" method="get">
		<div class="container">
			
			<h1>Войти</h1>
			<hr>
			<span class="string"> Введите Username <input type="text" name="username" placeholder="hehe1234@hehe.ru"> </span>
				
			<span class="string"> Введите Пароль <input type="password" name="password" placeholder=""> </span>
				<div class="warning"><?php echo "$username_err";?></div>
			<span class="string"> 
			<div class="warning"><?php echo "$password_err";?></div>
				
			
				 
			<hr>
			<span class="string">
				<input type="submit" name="button" value="Войти" class="registerbtn" </span>
			</div>
								

			
		</form>	
			<div class="container signin">
				<p>Нет аккаунта? <a href="reg.php">Зарегистрироваться</a>.</p>
			</div>
		</div>
	</div>
	</body>	
</html>