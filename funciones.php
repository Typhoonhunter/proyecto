<?php
    session_start();
    // Set session variables.
    
    $_SESSION["status"]    = "";
    //$_SESSION["Name"]      = "";
    // Set variable session
    //$_SESSION["Error"]     = "Error:";

    // Set variables.
    $name   = $email  = $password = "";

    function connect() {
        $host       = "localhost";
        $root       = "root";
        $passwdroot = "";
        $dbname     = "proyecto";
        // Create connection.
        $conn = new mysqli($host, $root, $passwdroot, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            // echo "Connection successful.<br>";
        }
        return $conn;
    }

    function disconnect($conn) {
        mysqli_close($conn);
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;    
    }

    // Register form
    // -----------------------------------------------------------------------------------------------------------------------------
    if ((isset($_POST['add'])) && ($_POST['add'] == 'Register')){

        $_SESSION["status"] = "success";
        if (empty($_POST["name"])) {
            //$nameE = " * Name req'd.";
            $_SESSION["status"] = "failed";
        } else {
            $name  = test_input($_POST["name"]); 
            if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
                //$generalE = "Only letters and white space allowed in the name field.";
                $_SESSION["Error"]  = "<br><br> Error: Solo se aceptan espacios en blancos y letras en el campo de nombre.";
                $_SESSION["status"] = "failed";
            } else {
                $_SESSION["Name"]   = $name;
            }
        }   

        if (empty($_POST["mail"])) {
            //$emailE = " * Email req'd.";
            $_SESSION["status"] = "failed";
        } else {
            $mail  = test_input($_POST["mail"]);
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                //$generalE = "Invalid email format";
                $_SESSION["Error"]  = "<br><br> Error: Formato inválido de email.";
                $_SESSION["status"] = "failed";
            } else {
                $conn = connect();
                //$_SESSION["Email"]  = $email;
                $query  = "SELECT mail FROM users";
                $result = $conn->query($query);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()) {
                        if ($row["mail"] == $mail){
                            //$emailE   = " * Error";
                            //$generalE = "Email already registered"; 
                            $_SESSION["Error"]  = "<br><br> Error: El email proporcionado ya se encuentra registrado.";
                            $_SESSION["status"] = "failed";
                        }
                    }  
                }
                mysqli_free_result($result);
                disconnect($conn);
            }
        }

        if (empty($_POST["password"])) {
            //$password1E = " * Field req'd.";
            $_SESSION["status"] = "failed";
        }
        if (empty($_POST["checkPassword"])) {
            //$password2E = " * Field req'd.";
            $_SESSION["status"] = "failed";
        } else {
            $password1  = test_input($_POST["password"]);
            $password2  = test_input($_POST["checkPassword"]);
            
            if ($password1 != $password2) {
                //$generalE = "Passwords are different!";
                $_SESSION["status"] = "failed";
                $_SESSION["Error"]  = "<br><br> Error: Las contraseñas no coinciden.";
            } else {
                //$_SESSION["Password"] = $password1;
                $password = $password1;
            }
        }
        
        if ($_SESSION["status"] == "success") {
            $conn  = connect();
            $query = "INSERT INTO proyecto.users (nombre, mail, password) VALUES ('".$name."', '".$mail."', '".$password."')";
            
            if ($conn->query($query) === TRUE) {
                //echo "New record created successfully";
                //$generalE = "New record created successfully!";
                $_SESSION["Error"]  = "<br><br>Éxito: Usted ha quedado registado, bienvenido ".$name.".";
                //$Letrero = "Éxito";
                header('Location: registro.php');
                
            } else {
                $_SESSION["Error"]  = "Error:<br>" . $conn->error;
            }
            disconnect($conn);
        } 
        header('Location: registro.php');
    }
    //----------------------------------------------------------------------------------------------------------------------------

    // Login form:
    //----------------------------------------------------------------------------------------------------------------------------

    $flag1 = "";
    $flag2 = "";
    if ((isset($_POST['add'])) && ($_POST['add'] == 'login')){
        $_SESSION["status"] = "success";
        if (empty($_POST["mail"])) {
            //$emailE = " * Email req'd.";
            $_SESSION["status"] = "failed";
            $flag1 = 0;
        } else {
            $email  = test_input($_POST["mail"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                //$generalE = "Invalid email format";
                $_SESSION["Error"]  = "<br><br> Error: Formato inválido de email.";
                $_SESSION["status"] = "failed";
            } else {
                $flag1    = 1; 
            }
        }
        if (empty($_POST["password"])) {
            $flag2     = 0;
            $_SESSION["status"] = "failed";
        } else {
            $password  = test_input($_POST["password"]);
            $flag2     = 1;
        }
        if ($flag1 == 1 && $flag2 == 1 && $_SESSION["status"] == "success") {
            $conn = connect();
            $query  = "SELECT nombre, mail, password FROM users";
            $result = $conn->query($query);
            if ($result->num_rows > 0){
                while($row = $result->fetch_assoc()) {
                    if ($row["mail"] == $email && $row["password"] == $password){
                        $_SESSION["Email"]    = $email;
                        $_SESSION["Password"] = $password;
                        $_SESSION["Name"]     = $row["nombre"];
                        $_SESSION["Error"]  = "<br><br> Éxito: ¡Bienvenido!";
                        disconnect($conn);
                        mysqli_free_result($result);
                        header('Location: profile.php');   
                    } else {
                        //$generalE = "Incorrect data, please verify the information provided or click on register."; 
                        $_SESSION["Error"]  = "<br><br> Error: El E-mail o la contraseña son incorrectas.";
                        disconnect($conn);
                    }
                }
            } else {
                disconnect($conn);
                mysqli_free_result($result);
                header('Location: login.php');
                // echo "0 results";
            } 
            
        } else {
            disconnect($conn);
            mysqli_free_result($result);
            header('Location: login.php');
        }
         
    }
?>