<?php
session_start();

$Search = $_POST["Search"];

require "../include/connect.php";

$sql = 'SELECT UserCode, Name FROM Users WHERE UserCode!=' . $_SESSION['UserCode'] . ' AND (User LIKE "%' . $Search . '%" OR Name LIKE "%' . $Search . '%" OR Email LIKE "%' . $Search . '%")';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $Data = '{';

    while($row = $result->fetch_assoc()) {
        $Extension = pathinfo(glob("../images/" . $row["UserCode"] . ".*")[0])['extension'];
        $Data .= '"' . $row['UserCode'] . '":{"Name":"' . $row['Name'] . '","Extension":"' . $Extension . '"},';
    }

    echo substr($Data, 0, -1) . '}';
}

$conn->close();
?>