<?php
    session_start();
    include_once ("../php/connect.php");
    $room_id = $_SESSION['room'];

    // если пользователь является хостом
    if (isset($_SESSION['host'])){
        $query = mysqli_query($conn, "SELECT login, host_score, guest_score FROM rooms,users WHERE rooms.id='$room_id' and users.id = guest_id");
        $guest = $query->fetch_assoc();
        $enemy_name = $guest['login'];
        $user_score = $guest['host_score'];
        $enemy_score = $guest['guest_score'];
    }
    // если пользователь является гостем
    if (isset($_SESSION['guest'])){
        $query = mysqli_query($conn, "SELECT login, host_score, guest_score FROM rooms,users WHERE rooms.id='$room_id' and users.id = host_id");
        $host = $query->fetch_assoc();
        $enemy_name = $host['login'];
        $user_score = $host['guest_score'];
        $enemy_score = $host['host_score'];
    }

    if (!isset($_SESSION['user'])){
        header("Location: start_pg.php");
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
        <a href="start_pg.php"><img src="../assets/logo.svg" alt="logo"></a>
        <div class="drop_menu">
            <button onclick="dropMenu()" class="dropbtn">профиль</button>
            <div id="myDropdown" class="dropdown-content">
                <p style="font-weight: 900;"><?= $_SESSION['user'] ?></p>
                <a href="stat.php">Статистика</a>
                <a href="../php/logout.php">Выход</a>
            </div>
        </div>
    </header>
    <div class="content" style="justify-content: flex-start;">
        <div class="container" style="height: 200px; flex-direction: row; justify-content: center;">
            <div class="game_info" id="first_player" style="color: white; justify-content: flex-start;">
                <div class="counter" id="own_score"><?= $user_score ?></div>
                <div class="info_cont">
                    <div class="info_name" id="own_name"><?= $_SESSION['user'] ?></div>
                </div>
            </div>
            <div class="info_time" id="move_time" style="font-weight: bold; color: #B44444;">--</div>
            <div class="game_info" id="second_player" style="color: white; justify-content: flex-end;">
                <div class="info_cont" style="align-items: flex-end;">
                    <div class="info_name" id="enemy_name"><?= $enemy_name ?></div>
                </div>
                <div class="counter" id="enemy_score"><?= $enemy_score ?></div>
            </div>
        </div>
        <div class="field" style="margin-top: 40px;">
            <div class="field-horizon">
                <div class="field_block-1" id="1" onclick="move(this.id)"></div>
                <div class="field_block-2" id="2" onclick="move(this.id)"></div>
                <div class="field_block-3" id="3" onclick="move(this.id)"></div>
            </div>
            <div class="field-horizon">
                <div class="field_block-4" id="4" onclick="move(this.id)"></div>
                <div class="field_block-5" id="5" onclick="move(this.id)"></div>
                <div class="field_block-6" id="6" onclick="move(this.id)"></div>
            </div>
            <div class="field-horizon">
                <div class="field_block-7" id="7" onclick="move(this.id)"></div>
                <div class="field_block-8" id="8" onclick="move(this.id)"></div>
                <div class="field_block-9" id="9" onclick="move(this.id)"></div>
            </div>
        </div>
        <div class="new_game" id="game_end">
        </div>
    </div>
    <script src="../scripts/jquery.min.js"></script>
    <script>
        let role = get_role();
        let move_now = 'tic';
        let game_end = false;
        let last_field;

        // обновление поля
        function update_field(){
            if (game_end){
                $.ajax({
                    url: "../php/get_scores.php",
                    dataType: "json",
                    type: "post",
                    success: function (result){
                        if (String(result['user']) === "host"){
                            document.getElementById('own_score').innerHTML = String(result['host_score']);
                            document.getElementById('enemy_score').innerHTML = String(result['guest_score']);
                        }
                        else {
                            document.getElementById('own_score').innerHTML = String(result['guest_score']);
                            document.getElementById('enemy_score').innerHTML = String(result['host_score']);
                        }
                        document.getElementById('move_time').innerHTML = '--';
                        document.getElementById('move_time').style.color = 'white';
                    }
                })
                return;
            }

            $.ajax({
                url: "../php/get_field.php",
                dataType: "json",
                type: "post",
                success: function (data){
                    // таймер
                    document.getElementById('move_time').innerHTML = data['move_time'];

                    if (String(data['move_time']) === '0'){
                        if (data['is_moving'] === 'tic'){
                            win('O');
                        }
                        else {
                            win('X');
                        }
                    }

                    if (data['is_moving'] === 'tic'){
                        document.getElementById('move_time').style.color = '#B44444';
                    }
                    else {
                        document.getElementById('move_time').style.color = '#44B4A0';
                    }

                    // обновление поля
                    if (data['field'].length !== 9){
                        window.location.href = 'leaved.php';
                    }
                    if (last_field === data['field']) return;

                    let id = 1;
                    for(let i of data['field']) {
                        if (i === "X") {
                            document.getElementById(String(id)).innerHTML = '<img alt="X" src="../assets/tic.svg" class="TicTac-ico">';
                        }
                        else if (i === "O") {
                            document.getElementById(String(id)).innerHTML = '<img alt="O" src="../assets/tac.svg" class="TicTac-ico">';
                        }
                        else {
                            document.getElementById(String(id)).innerHTML = "";
                        }
                        id++;
                    }
                    check_win(data['field']);
                    last_field = data['field'];
                }
            })
            console.log(game_end);
            setTimeout(update_field, 1000 / 5);
        }
        update_field();

        function win(sign){
            game_end = true;
            let winner;

            <?php
                if (isset($_SESSION['guest'])){?>
                    wait_restart();
            <?php
                }
            ?>

            if (sign === 'X') {
                winner = 'tic';
            }
            else {
                winner = 'tac';
            }

            if (winner === role) {
                if (role === 'tic'){
                    document.body.style.background = 'linear-gradient(90deg, rgba(180,68,68,1) -50%, rgba(24,34,52,1) 50%)';
                }
                else {
                    document.body.style.background = 'linear-gradient(90deg, rgba(68,180,160,1) -50%, rgba(24,34,52,1) 50%)';
                }

                document.getElementById('own_name').style.textDecoration = 'underline';
                <?php
                    if (isset($_SESSION['host'])) { ?>
                        $.ajax({
                            url: "../php/update_scores.php",
                            type: "post",
                            data: {win: 'host'}
                        });
                        $.ajax({
                            url: "../php/game_end.php",
                            type: "post"
                        });
                <?php
                    }
                    else { ?>
                        $.ajax({
                            url: "../php/update_scores.php",
                            type: "post",
                            data: {win: 'guest'}
                        });
                        $.ajax({
                            url: "../php/game_end.php",
                            type: "post"
                        });
                <?php
                    }
                ?>
            }
            else {
                if (role === 'tic'){
                    document.body.style.background = 'linear-gradient(270deg, rgba(68,180,160,1) -50%, rgba(24,34,52,1) 50%)';
                }
                else {
                    document.body.style.background = 'linear-gradient(270deg, rgba(180,68,68,1) -50%, rgba(24,34,52,1) 50%)';
                }
                document.getElementById('enemy_name').style.textDecoration = 'underline';
            }

            <?php
                if(isset($_SESSION['host'])){?>
                    document.getElementById('game_end').innerHTML = '<div onclick="restart()" style="cursor: pointer">Рестарт</div>';
            <?php
                }
                else {?>
                    document.getElementById('game_end').innerHTML = '<div>Ожидание хоста</div>';
            <?php
                }
            ?>
        }
        function draw(){
            $.ajax({
                url: "../php/game_end.php",
                type: "post"
            });

            <?php
                if (isset($_SESSION['guest'])){?>
                    wait_restart();
            <?php
                }
            ?>
            <?php
                if (isset($_SESSION['host'])){?>
                    document.getElementById('game_end').innerHTML = '<div onclick="restart()" style="cursor: pointer">Рестарт</div>';
                    game_end = false;

                    $.ajax({
                        url: "../php/update_scores.php",
                        type: "post",
                        data: {win: 'draw'}
                    });
            <?php
                }
                else {?>
                document.getElementById('game_end').innerHTML = '<div>Ожидание хоста</div>';
            <?php
                }
            ?>

            document.getElementById('own_name').style.textDecoration = 'underline';
            document.getElementById('enemy_name').style.textDecoration = 'underline';
        }

        function check_win(field){
            if (field[0] === field[1] && field[1] === field[2] && field[0] !== '#'){
                win(field[0]);
            }
            else if (field[3] === field[4] && field[4] === field[5] && field[3] !== '#'){
                win(field[3]);
            }
            else if (field[6] === field[7] && field[7] === field[8] && field[6] !== '#'){
                win(field[6]);
            }
            else if (field[6] === field[7] && field[7] === field[8] && field[6] !== '#'){
                win(field[6]);
            }
            else if (field[0] === field[3] && field[3] === field[6] && field[0] !== '#'){
                win(field[0]);
            }
            else if (field[1] === field[4] && field[4] === field[7] && field[1] !== '#'){
                win(field[1]);
            }
            else if (field[2] === field[5] && field[5] === field[8] && field[2] !== '#'){
                win(field[2]);
            }
            else if (field[2] === field[5] && field[5] === field[8] && field[2] !== '#'){
                win(field[2]);
            }
            else if (field[0] === field[4] && field[4] === field[8] && field[0] !== '#'){
                win(field[0]);
            }
            else if (field[2] === field[4] && field[4] === field[6] && field[2] !== '#'){
                win(field[2]);
            }
            else if (field[0] !== '#' && field[1] !== '#' && field[2] !== '#' && field[3] !== '#' && field[4] !== '#' && field[5] !== '#' && field[6] !== '#' && field[7] !== '#' && field[8] !== '#'){
                draw();
            }
        }

        function restart(){
            game_end = false;

            document.getElementById('game_end').innerHTML = '';
            document.getElementById('own_name').style.textDecoration = 'none';
            document.getElementById('enemy_name').style.textDecoration = 'none';
            document.body.style.background = '#182234';

            $.ajax({
                url: "../php/restart_game.php",
                type: "post"
            })
            role = get_role();
            update_field();
            timer();
        }
        function wait_restart(){
            let status = $.ajax({
                async: false,
                url: "../php/wait_restart.php",
                type: "post",
                success: function (result){
                    if (result === 'restart'){
                        document.getElementById('game_end').innerHTML = '';
                        document.getElementById('own_name').style.textDecoration = 'none';
                        document.getElementById('enemy_name').style.textDecoration = 'none';
                        role = get_role();
                    }
                }
            }).responseText;
            if (status === 'restart') {
                game_end = false;
                document.body.style.background = '#182234';
                update_field();
                timer();
                return;
            }
            setTimeout(wait_restart, 300);
        }

        function end() {
            game_end = true;
            $.ajax({
                url: "../php/game_end.php",
                type: "post"
            })
        }

        // распределение ролей
        function get_role(){
            return $.ajax({
                async: false,
                url: "../php/get_role.php",
                type: "post",
                data: $(this).serialize(),
                success: function get(data){
                    if (data === "tic"){
                        $('#first_player').css('color', '#B44444');
                        $('#second_player').css('color', '#44B4A0');

                    }
                    else {
                        $('#first_player').css('color', '#44B4A0');
                        $('#second_player').css('color', '#B44444');
                    }
                }
            }).responseText;
        }

        // запрос актуального состояния поля
        function get_field(){
            return $.ajax({
                async: false,
                url: "../php/get_field.php",
                type: "post",
            }).responseText;
        }

        // обработка хода
        function move(id){
            if (game_end) return;
            $.ajax({
                url: "../php/move.php",
                type: "post",
                data: {block_id: id, role: role}
            })
        }

        function timer(){
            if (game_end) return;
            $.ajax({
                url: "../php/timer.php",
                type: "post"
            })
            setTimeout(timer, 1000);
        }
        timer();

    </script>
</body>
</html>