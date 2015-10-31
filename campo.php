<?php
session_start();
define("DEBUG", false);
include('Model/database.php');
include('Jogo.php');


$jogo = new Jogo();
$jogo->start("DASKDABS");
$jogo->geraTabuleiro();
$jogo->colocaNavios();

if (isset($_POST['coordenadas'])) {
    $string = str_split($_POST['coordenadas']);

    $vertical = $jogo->coord_in_numero($string[0]); 
    $horizontal = $string[1] . @$string[2]; // bypass erro, numeros com dois digitos

    $jogo->ataca($vertical, $horizontal); // inverter funciona, são sei o porquê?!?!?!?
}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="5">
        <title>Batalha Naval - BloodStorm</title>
    </head>
    <body>
        <style>
            td { padding: 10px }
        </style>

        <?php if (DEBUG) { ?>
            <h2> Tabuleiro de Debug</h2>
            <table border="1">
                <?php for ($h = 1; $h <= $jogo->num_horizontal; $h++) { ?>
                    <tr>
                        <?php for ($v = 1; $v <= $jogo->num_vertical; $v++) { ?>
                            <td><?= $jogo->tabuleiro[$h][$v] ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>


        <h1>Jogada: </h1>
        <h2>Você é o jogador: <span style="color: red"><?= $jogo->jogador_id ?></span></h2>

        <form method="POST">
            <label for="coordenadas">Coordenada:</label>
            <input type="text" maxlength="3" placeholder="A1" name="coordenadas"> 

            <input type="submit" value="ATACAR!!">
        </form>    

        <h2> Tabuleiro Vísivel</h2>
        <table border="1">

            <?php for ($i = 0; $i <= $jogo->num_horizontal; $i++) { ?>    
                <td bgcolor='red'><?php echo ($i == 0 ? '' : $i) ?></td>
            <?php } ?>

            <?php for ($h = 1; $h <= $jogo->num_horizontal; $h++) { ?>
                <tr><td bgcolor='yellow'><?= $jogo->numero_in_coord($h) ?></td>
                    <?php for ($v = 1; $v <= $jogo->num_vertical; $v++) { ?>

                        <td><?= $jogo->tabuleiroVisivel[$h][$v] ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </body>
</html>
