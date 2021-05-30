<html>
	<head>
		<title> Главная </title>
		<meta charset="utf-8">
		<link href="mystyle.css" rel="stylesheet" type="text/css">
	</head>
	
	<?php
		//Добавляем config
		require_once "config.php";
 
		// Создаем переменные
		$username = $password = $confirm_password = $email = "";
		$username_err = $password_err = $confirm_password_err = $email_err = "";
 
		
		
		if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["username"])){
		echo "test1";
		// Проверяем username
		if(empty(trim($_GET["username"]))){
			$username_err = "Please enter a username.";
		} else{
			// Подготавливаем высказывание
			$sql = "SELECT id FROM users WHERE username = ?";
			
			if($stmt = mysqli_prepare($link, $sql)){
				// Привязываем переменные к параметрам высказывания
				mysqli_stmt_bind_param($stmt, "s", $param_username);
            
				// Устанавливаем параметры
				$param_username = trim($_GET["username"]);
            
            // Выполняем высказывание
            if(mysqli_stmt_execute($stmt)){
                /* Сохраняем результат */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_GET["username"]);
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }

            //Закрываем высказывание
            mysqli_stmt_close($stmt);
        }
    }
    
    // Проверяем пароль
    if(empty(trim($_GET["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_GET["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_GET["password"]);
    }
    
    
    if(empty(trim($_GET["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_GET["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
	// Проверяем почту
    if(empty(trim($_GET["email"]))){
        $email_err = "Please enter an email.";     
    } else{
        $email = trim($_GET["email"]);
    }
	
    // Проверяем на ошибки
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) ){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_email);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
			$param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
				echo '<script language="javascript"> alert("Регистрация успешно завершена, сейчас вы будете перенаправленны на страницу входа");</script>';
                header("location: login.php?temp=success");
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
		<form method="get">
	<div class="container">
    <h1>Регистрация</h1>
    <p>Пожалуйста, заполните все поля</p>
    <hr>

	<label for="username"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" required>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" required>

    <label for="psw"><b>Пароль</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>

    <label for="psw-repeat"><b>Повторите пароль</b></label>
		<input type="password" placeholder="Repeat Password" name="confirm_password" required>
    <hr>

			
				<button type="submit" class="registerbtn">Зарегистрироваться</button>
	</div>

			<div class="container signin">
				<p>Уже есть аккаунт? <a href="main.php">Войти</a>.</p>
			</div>
		</form>
		</div>
	
	</div>
	</body>	
</html>