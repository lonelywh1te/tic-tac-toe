<?php
    session_start();
    include_once ("../php/connect.php");

    if (isset($_SESSION['room'])){
        $room_id = $_SESSION['room'];
        $query = "delete from rooms where id='$room_id'";
        mysqli_query($conn, $query);
        unset($_SESSION['host']);
        unset($_SESSION['guest']);
        unset($_SESSION['room']);
    }
    if (!isset($_SESSION['user'])){
        header("Location: ../index.php");
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tic-Tak-Toe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main.css">
    <script src="../scripts/dropMenu.js"></script>
</head>
<body>
    <header>
        <a><img src="../assets/logo.svg" alt="logo"></a>
        <div class="drop_menu">
            <button onclick="dropMenu()" class="dropbtn">профиль</button>
            <div id="myDropdown" class="dropdown-content">
                <p style="font-weight: 900;"><?= $_SESSION['user'] ?></p>
                <a href="stat.php">Статистика</a>
                <a href="../php/logout.php">Выход</a>
            </div>
        </div>
    </header>

    <div class="content">
        <div class="container">
            <div class="descr">
                <h2>Добро пожаловать, <?= $_SESSION['user'] ?>!</h2>
            </div>
            <button class="main_btn" onclick="window.location.href = 'create_room.php';">Создать комнату</button>
            <button class="main_btn" onclick="window.location.href = 'conn_room.php';">Подключиться к комнате</button>
            <button class="main_btn" onclick="window.location.href = 'bot_room.php';">Тренировка</button>
        </div>
    </div>
</body>
</html>