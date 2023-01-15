<?php
    session_start();
    include_once("connect.php");
    $move = $_POST['block_id'];
    $role = $_POST['role'];

    $room_id = $_SESSION['room'];
    $result = mysqli_query($conn, "select * from rooms where id='$room_id'");
    $row = $result->fetch_assoc();

    if ($row['is_moving'] == $role){
        $field = $row['field'];

        if ($field[$move - 1] == "#"){
            if ($role === "tic"){
                $field[$move - 1] = "X";
                mysqli_query($conn, "update rooms set is_moving='tac' where id='$room_id'");
                echo 'tac';
            }
            else {
                $field[$move - 1] = "O";
                mysqli_query($conn, "update rooms set is_moving='tic' where id='$room_id'");
                echo 'tic';
            }
            mysqli_query($conn, "update rooms set field='$field', move_time=60 where id='$room_id'");
        }
    }



