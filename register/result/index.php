<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Resultado - Registrarse</title>

        <?php require "../../include/head.html"; ?>

        <link rel="stylesheet" href="../../components/navigation/navigation.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../../components/navigation/navigation.php"; ?>

        <div class="container">
            <div class="card-panel center-align">
                <span><?php
                if (!getimagesize($_FILES["image"]["tmp_name"]))
                    $Code = 1;
                else {
                    require "../../include/connect.php";

                    $User = $_POST["user"];
                    $Password = $_POST["password"];
                    $Email = $_POST["email"];
                    $Name = $_POST["name"];

                    $sql = 'INSERT INTO Users (User, Password, Email, Name)
                            VALUES ("' . $User . '", "' . $Password . '", "' . $Email . '", "' . $Name . '")';

                    if ($conn->query($sql) === TRUE) {
                        $File = "../../images/" . $conn->insert_id . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $File))
                            $Code = 3;
                        else {
                            $Code = 2;
                            $sql = "DELETE FROM Users WHERE UserCode=" . $conn->insert_id;
                            $conn->query($sql);
                        }
                    }
                    else
                        $Code = 2;
                }

                switch ($Code) {
                    case 1:
                        echo "Imagen invalida, por favor suba una imagen valida";
                        break;
                    case 2:
                        echo "Error al registrarse, por favor intente de nuevo";
                        break;
                    case 3:
                        echo "Su cuenta se cre&oacute; correctamente";
                        break;
                    default:
                        echo "Error desconocido, por favor intente de nuevo";
                }
                ?></span>
            </div>
        </div>

        <?php require "../../include/scripts.html"; ?>

        <script src="../../components/navigation/navigation.js"></script>
    </body>
</html>