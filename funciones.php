<?php
    
    session_start();
    $_SESSION["Status"]   = "";
    
    $name   = $mail  = $password = "";

    function set_session_variables($name, $mail, $password, $session) {   
        $_SESSION["Name"]     = $name;
        $_SESSION["Email"]    = $mail;
        $_SESSION["Password"] = $password;
        $_SESSION["Session"]  = $session;
    }
    
    function set_session_status($status) {
        $_SESSION["Status"]   = $status;   
    }

    function set_session_error($error) {
        $_SESSION["Error"]   = $error;   
    }

    function get_session_status() {
        $status = $_SESSION["Status"];
        return $status;
    }

    function connect() {
        $host       = "localhost";
        $root       = "root";
        $passwdroot = "";
        $dbname     = "proyecto";
        // Create connection.
        $conn = new mysqli($host, $root, $passwdroot, $dbname);
        // Check connection
        if ($conn->connect_error) {
            set_session_error("<br><br>Error:".$conn->connect_error."<br>");
            die("Connection failed: ".$conn->connect_error);
        } else {
            // set_session_error("Éxito: Conexión a las base de datos establecida.");
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

    function test_name($name) {
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            set_session_error("<br><br> Error: Solo se aceptan espacios en blancos y letras en el campo de nombre.");
            set_session_status("failed");
        }
    }

    function test_mail($mail) {
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            set_session_error("<br><br> Error: Formato inválido de correo electrónico");
            set_session_status("failed");
        }
    }

    function check_mail($mail) {
        $conn = connect();
        $query  = "SELECT mail FROM users";
        $result = $conn->query($query);
        $x = 1;
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ($row["mail"] == $mail) {
                    $x = 0;
                }
            }  
        }
        mysqli_free_result($result);
        disconnect($conn);
        return $x;
    }
    
    function test_passwords($password1,$password2) {
        if ($password1 != $password2) {
            set_session_status("failed");
            set_session_error("<br><br> Error: Las contraseñas no coinciden.");
            return 0;
        } else {
            return 1;
        }    
    }

    function add_user($name, $mail, $password) {
        $conn  = connect();
        $query = "INSERT INTO proyecto.users (nombre, mail, password) VALUES ('".$name."', '".$mail."', '".$password."')";
        if ($conn->query($query) === TRUE) {
            set_session_variables($name,$mail,$password, "established");
            set_session_error("<br><br>Éxito: Usted ha quedado registado, bienvenido ".$name.".");
            header('Location: registro.php');
        } else {
            set_session_error("<br><br>Error:<br>" . $conn->error);
        }
        disconnect($conn);
    }

    function test_login($mail,$password) {
        $conn   = connect();
        $query  = "SELECT nombre, mail, password FROM users";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ($row["mail"] == $mail && $row["password"] == $password) {
                    set_session_variables($row["nombre"],$mail,$password,"established");
                    set_session_error("
                        <br>¡Hola ".$_SESSION["Name"]."! Es una placer tenerte de vuelta.<br> 
                        <br> Tu correo electrónico es ".$_SESSION["Email"]." y tu contraseña es 
                        ".$_SESSION["Password"].".");   
                    mysqli_free_result($result);
                    disconnect($conn);
                    return 1;
                }
            }
            set_session_variables("","","","");
            set_session_error("<br><br> Error: La contraseña proporcionada es incorrecta.");
            mysqli_free_result($result);
            disconnect($conn);
            return 0;
        } else {
            set_session_variables("","","","");
            set_session_error("<br><br> Error: Base de datos vacía.");
            mysqli_free_result($result);
            disconnect($conn);
            return 0;
        }
    }

    // Register validation form.
    if ((isset($_POST['add'])) && ($_POST['add'] == 'Register')) {
        
        set_session_status("success");
        
        if (empty($_POST["name"])) {
            set_session_status("failed");
        } else {
            $name  = test_input($_POST["name"]); 
            test_name($name);
        }   

        if (empty($_POST["mail"])) {
            set_session_status("failed");
        } else {
            $mail  = test_input($_POST["mail"]);
            test_mail($mail);
            if (check_mail($mail) == 0) {
                set_session_error("<br><br> Error: El correo electrónico proporcionado ya se encuentra registrado.");
                set_session_status("failed");
            }
        }

        if (empty($_POST["password"])) {
            set_session_status("failed");
        }
        
        if (empty($_POST["checkPassword"])) {
            set_session_status("failed");
        } else {
            $password1  = test_input($_POST["password"]);
            $password2  = test_input($_POST["checkPassword"]);
            if (test_passwords($password1, $password2) == 1) {
                $password   = $password1;
            }
        }
        
        if (get_session_status() == "success") {
            add_user($name, $mail, $password);
        } 
        header('Location: registro.php');
    }

    // Log in validation form.
    if ((isset($_POST['add'])) && ($_POST['add'] == 'login')) {
       
        set_session_status("success");
        
        if (empty($_POST["mail"])) {
            set_session_status("failed");
        } else {
            $mail  = test_input($_POST["mail"]);
            if (check_mail($mail) == 1) {
                set_session_error("<br><br> Error: El correo electrónico proporcionado no se encuentra registrado.");
                set_session_status("failed");
            }
            test_mail($mail);
        }
        
        if (empty($_POST["password"])) {
            set_session_status("failed");
        } else {
            $password = test_input($_POST["password"]);
        }
        
        if (get_session_status() == "success") {
            if (test_login($mail,$password) == 1) {
                header('Location: profile.php');   
            } else {
                header('Location: login.php');
            }
        } else {
            header('Location: login.php');
        }
        
    }

    // Log out validation form.
    if ((isset($_POST['add'])) && ($_POST['add'] == 'logOut')) {
        echo "hello world";   
    }

    function add_image($image) {
        $target_dir = "img/uploads/";
        $target_file = $target_dir . basename($_FILES["$image"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
      
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["$image"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                set_session_error("El archivo no es de tipo imagen.");
                $uploadOk = 0;
            }
        }
      
        if (file_exists($target_file)) {
            set_session_error("El archivo ya existe.");
            $uploadOk = 0;
        }
      
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            set_session_error("El archivo solo puede ser de tipo jpg, png o jpeg.");
            $uploadOk = 0;
        }
      
        if ($uploadOk == 0) {
            set_session_error("El archivo no se ha podido cargar.");
        } else {
            $conn = connect();
            $query = "INSERT INTO proyecto.imagen (nombre, path) VALUES ('".basename($_FILES["$image"]["name"])."', '".$target_file."')";
            if (move_uploaded_file($_FILES["$image"]["tmp_name"], $target_file) && $conn->query($query) === TRUE) {
                set_session_error("El archivo ". basename( $_FILES["$image"]["name"]). " ha sido cargado exitosamente.");
            } else {
                set_session_error("<br><br> Error: Hubo un problema al cargar el archivo.");
            }
            disconnect($conn);
        }
    }

    function search_image($image) {
        $conn   = connect();
        $query  = "SELECT nombre, path FROM imagen";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ($row["nombre"] == $image) {
                    $src = $row["path"];
                    mysqli_free_result($result);
                    disconnect($conn);
                    return $src;
                }
            }
            set_session_error("<br><br> Error: La imagen que buscas no existe.");
            mysqli_free_result($result);
            disconnect($conn);
            return null;
        } else {
            set_session_error("<br><br> Error: Base de datos vacía.");
            mysqli_free_result($result);
            disconnect($conn);
            return null;
        }

    }
?>