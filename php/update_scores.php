<?php
    session_start();
    include_once ('connect.php');
    $room_id = $_SESSION['room'];

    $query = mysqli_query($conn, "SELECT * from rooms WHERE id='$room_id'");
    $result = $query->fetch_assoc();

    if ($result['game_end'] === '1') exit();

    $host_id = $result['host_id'];
    $guest_id = $result['guest_id'];

    if ($_POST['win'] == 'host'){
        mysqli_query($conn, "update rooms set host_score=`host_score`+1 where id='$room_id'");
        mysqli_query($conn, "update stats set win=`win`+1 where user_id='$host_id'");
        mysqli_query($conn, "update stats set lose=`lose`+1 where user_id='$guest_id'");
        echo 'host_win';
    }
    else if ($_POST['win'] == 'guest') {
        mysqli_query($conn, "update rooms set guest_score=`guest_score`+1 where id='$room_id'");
        mysqli_query($conn, "update stats set win=`win`+1 where user_id='$guest_id'");
        mysqli_query($conn, "update stats set lose=`lose`+1 where user_id='$host_id'");
        echo 'guest_win';
    }
    else {
        mysqli_query($conn, "update stats set draw=`draw`+1 where user_id='$guest_id' or '$host_id'");
        echo 'draw';
    }
