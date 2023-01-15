<?php
    session_start();
    include_once ("connect.php");
    $room_id = $_SESSION['room'];
    $result = mysqli_query($conn, "select field, move_time, is_moving from rooms where id='$room_id'");
    $row = $result->fetch_assoc();
    echo (json_encode($row));
