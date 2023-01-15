<?php
    session_start();
    include_once("../php/connect.php");

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
    <title>Tic-Tac-Toe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main.css">
    <script src="../scripts/dropMenu.js"></script>
</head>
<body>
<header>
    <a href="../content/start_pg.php"><img src="../assets/logo.svg" alt="logo"></a>
    <div class="drop_menu">
        <button onclick="dropMenu()" class="dropbtn">профиль</button>
        <div id="myDropdown" class="dropdown-content">
            <p style="font-weight: 900;"><?= $_SESSION['user'] ?></p>
            <a href="../content/stat.php">Статистика</a>
            <a href="../php/logout.php">Выход</a>
        </div>
    </div>
</header>
<div class="content" style="justify-content: flex-start;">
    <div class="container" style="height: 200px; flex-direction: row; justify-content: center;">
        <div class="game_info" id="first_player" style="color: white; justify-content: flex-start;">
            <div class="counter" id="own_score">0</div>
            <div class="info_cont">
                <div class="info_name" id="own_name"><?= $_SESSION['user'] ?></div>
            </div>
        </div>
        <div class="game_info" id="second_player" style="color: white; justify-content: flex-end;">
            <div class="info_cont" style="align-items: flex-end;">
                <div class="info_name" id="enemy_name">Компьютер</div>
            </div>
            <div class="counter" id="enemy_score">0</div>
        </div>
    </div>
    <div class="field" style="margin-top: 40px;">
        <div class="field-horizon">
            <div class="field_block-1" id="1" onclick="move(this.id, 'user')"></div>
            <div class="field_block-2" id="2" onclick="move(this.id, 'user')"></div>
            <div class="field_block-3" id="3" onclick="move(this.id, 'user')"></div>
        </div>
        <div class="field-horizon">
            <div class="field_block-4" id="4" onclick="move(this.id, 'user')"></div>
            <div class="field_block-5" id="5" onclick="move(this.id, 'user')"></div>
            <div class="field_block-6" id="6" onclick="move(this.id, 'user')"></div>
        </div>
        <div class="field-horizon">
            <div class="field_block-7" id="7" onclick="move(this.id, 'user')"></div>
            <div class="field_block-8" id="8" onclick="move(this.id, 'user')"></div>
            <div class="field_block-9" id="9" onclick="move(this.id, 'user')"></div>
        </div>
    </div>
    <div class="new_game" id="game_end">
    </div>
</div>
<script src="../scripts/jquery.min.js"></script>
<script>
    let role = get_role('user');
    let bot_role = get_role('bot');
    let score = 0;
    let bot_score = 0;
    let field = ['#', '#', '#', '#', '#', '#', '#', '#', '#'];
    let is_moving = 'X';
    let game_end = false;

    if (bot_role === is_moving) bot_move();

    function update_field(){
        let id = 1;
        for(let i of field) {
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
        check_win(field);
    }

    function move(id, player){
        if (player === 'user'){
            if (role !== is_moving) return;
        }
        else {
            if (bot_role !== is_moving) return;
        }


        if (field[id - 1] === '#'){
            field[id - 1] = is_moving;
        }
        else {
            return;
        }

        if (is_moving === 'X'){
            is_moving = 'O';
        }
        else {
            is_moving = 'X'
        }
        update_field();
        if (bot_role === is_moving) bot_move();
    }

    function get_role(player){
        if (player === 'user'){
            let rand = Math.floor(Math.random() * 10) + 1;
            if (rand > 5){
                document.getElementById('first_player').style.color = '#B44444';
                document.getElementById('second_player').style.color = '#44B4A0';
                return 'X';
            }
            document.getElementById('first_player').style.color = '#44B4A0';
            document.getElementById('second_player').style.color = '#B44444';
            return 'O';
        }
        else {
            if (role === 'X'){
                return 'O'
            }
            return 'X';
        }

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

    function win(sign){
        if (role === sign){
            score++;
            document.getElementById('own_name').style.textDecoration = 'underline';
            document.getElementById('own_score').innerHTML = String(score);
        }
        else {
            bot_score++;
            document.getElementById('enemy_name').style.textDecoration = 'underline';
            document.getElementById('enemy_score').innerHTML = String(bot_score);
        }

        game_end = true;
        document.getElementById('game_end').innerHTML = '<div onclick="restart()" style="cursor: pointer">Рестарт</div>';
    }

    function draw(){
        game_end = true;
        document.getElementById('own_name').style.textDecoration = 'underline';
        document.getElementById('enemy_name').style.textDecoration = 'underline';
        document.getElementById('game_end').innerHTML = '<div onclick="restart()" style="cursor: pointer">Рестарт</div>';
    }

    function restart(){
        role = get_role('user');
        bot_role = get_role('bot');
        field = ['#', '#', '#', '#', '#', '#', '#', '#', '#'];
        is_moving = 'X';
        document.getElementById('game_end').innerHTML = '';
        document.getElementById('own_name').style.textDecoration = 'none';
        document.getElementById('enemy_name').style.textDecoration = 'none';
        game_end = false;
        if (bot_role === is_moving) bot_move();
        update_field();
    }

    async function bot_move() {
        await sleep(Math.random() * 1000)
        if (game_end === true) return;
        let can_move = [];
        let enemy_moves = [];

        // изучает поле
        let id = 0;
        for (let i in field) {
            if (field[i] === '#') can_move.push(id);
            else if (field[i] === role) enemy_moves.push(id);
            id++;
        }
        if (can_move.length === 0) return;

        // делает ход.
        // if (enemy_moves.length >= 2){
        //     for (let i = 0; i < 3; i++ ){
        //         for (let k = 0; k < 2; k++){
        //             if (enemy_moves.indexOf(i * 3 + k) !== enemy_moves.indexOf(i * 3 + 1 + k) && enemy_moves.indexOf(i * 3 + k) !== -1 && enemy_moves.indexOf(i * 3 + 1 + k) !== -1){
        //                 console.log(enemy_moves.indexOf(i * 3 + k), enemy_moves.indexOf(i * 3 + 1 + k));
        //                 if (field[i * 3 + 2 + k] === '#'){
        //                     move(i * 3 + 3 + k);
        //                     return;
        //                 }
        //                 else continue;
        //             }
        //         }
        //     }
        //     for (let i = 0; i < 3; i++ ){
        //         for (let k = 0; k < 2; k++){
        //             if (enemy_moves.indexOf(i + k * 3) !== enemy_moves.indexOf(i + k * 3 + 3) && enemy_moves.indexOf(i + k * 3) !== -1 && enemy_moves.indexOf(i + k * 3 + 3) !== -1){
        //                 console.log('second');
        //                 console.log(i + k * 3 + 7);
        //                 if (field[i + k * 3 + 6] === '#') {
        //                     move(i + k * 3 + 7);
        //                     return;
        //                 }
        //                 else if (field[i + k * 3 + 4] === '#'){
        //                     move(i + k * 3 - 1);
        //                     return;
        //                 }
        //                 else continue;
        //             }
        //         }
        //     }
        //
        //     if (enemy_moves.indexOf(0) !== enemy_moves.indexOf(4) && enemy_moves.indexOf(0) !== -1 && enemy_moves.indexOf(4) !== -1){
        //         console.log(enemy_moves);
        //         move(9);
        //         return;
        //     }
        //     if (enemy_moves.indexOf(4) !== enemy_moves.indexOf(8) && enemy_moves.indexOf(4) !== -1 && enemy_moves.indexOf(8) !== -1){
        //         move(1);
        //         return;
        //     }
        //     if (enemy_moves.indexOf(2) !== enemy_moves.indexOf(4) && enemy_moves.indexOf(2) !== -1 && enemy_moves.indexOf(4) !== -1){
        //         move(7);
        //         return;
        //     }
        //     if (enemy_moves.indexOf(4) !== enemy_moves.indexOf(6) && enemy_moves.indexOf(4) !== -1 && enemy_moves.indexOf(6) !== -1){
        //         move(3);
        //         return;
        //     }
        //     if (enemy_moves.indexOf(0) !== enemy_moves.indexOf(8) && enemy_moves.indexOf(0) !== -1 && enemy_moves.indexOf(8) !== -1){
        //         move(5);
        //         return;
        //     }
        //     if (enemy_moves.indexOf(2) !== enemy_moves.indexOf(6) && enemy_moves.indexOf(2) !== -1 && enemy_moves.indexOf(6) !== -1){
        //         move(4);
        //         return;
        //     }
        // }

        let move_id = Math.floor(Math.random() * 10) + 1;
        while (field[move_id - 1] !== '#') {
            move_id = Math.floor(Math.random() * 10) + 1;
        }
        move(move_id, 'bot');
        console.log(enemy_moves);
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
</script>
</body>
</html>