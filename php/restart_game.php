<?php
    session_start();
    include_once ('connect.php');
    $room_id = $_SESSION['room'];

    $query = mysqli_query($conn, "select tic, tac from rooms where id='$room_id'");
    $role = $query->fetch_assoc();

    if (rand(0, 10) % 2 == 0){
        $tic = $role['tic'];
        $tac = $role['tac'];
    }
    else {
        $tic = $role['tac'];
        $tac = $role['tic'];
    }

    mysqli_query($conn, "update rooms set tic='$tic', tac='$tac', field=default, is_moving=default, game_end=default, move_time=default where id='$room_id'");
    