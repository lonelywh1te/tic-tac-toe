<?php
    session_start();
    include_once ('connect.php');
    $room_id = $_SESSION['room'];

    $query = mysqli_query($conn, "select * from rooms where id='$room_id'");
    $result = $query->fetch_assoc();

    if ($result['game_end'] == 1){
        exit();
    }

    if (isset($_SESSION['host'])){
        mysqli_query($conn, "update rooms set move_time=`move_time`-1 where id='$room_id'");
    }

