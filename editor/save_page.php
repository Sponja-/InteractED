<?php
session_start();

include "../include/connect.php";

if(!file_exists("../post/content/" . $_POST["id"] . "/"))
	mkdir("../post/content/" . $_POST["id"] . "/images", 0777, true);

file_put_contents("../post/content/" . $_POST["id"] . "/index.html", stripcslashes($_POST["content"]));

if($_POST["name"] != $_SESSION[$_POST["id"] . "-Title"]) {
	$sql = "UPDATE Articles SET Title = '" . $_POST["name"] . "' WHERE MD5(PostID) = '" . $_POST["id"] . "'";
	$conn->query($sql);
}

if($_POST["category"] != $_SESSION[$_POST["id"] . "-Category"]) {
	$sql = "UPDATE Articles SET CategoryID = (SELECT CategoryID FROM Categories WHERE CategoryName = '" . $_POST["category"] . "') WHERE MD5(PostID) = '" . $_POST["id"] . "'";
	$conn->query($sql);
}

$sql = "UPDATE Articles SET
		Transcript = '" . $_POST["transcript"] . "',
		LastEditDate = CURDATE()
		WHERE MD5(PostID) = '" . $_POST["id"] . "'";

$conn->query($sql);

$conn->close();
?>