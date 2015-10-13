<?php
include('Jogo.php');

$jogo = new Jogo();
$jogo->geraTabuleiro();
$jogo->colocaNavio('submarino');
$jogo->colocaNavio('porta-aviões');
$jogo->colocaNavio('encouraçado');

if(isset($_POST['coordHorizontal'])){
    $jogo->start();
    $jogo->ataca($_POST['coordHorizontal'],$_POST['coordVertical']);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <style>
            td {
                padding: 10px
            }
        </style>
        
        <h2> Tabuleiro de Debug</h2>
        <table border="1">
            <?php for($h =1; $h <= 10;$h++){ ?>
            <tr>
            <?php for($v = 1; $v <= 10; $v++){ ?>
            <td><?= $jogo->tabuleiro[$h][$v] ?></td>
            <?php } ?>
            </tr>
            <?php } ?>
        </table>
        
        
        
        <h1>Jogada: </h1>
        
        <form method="POST">
            <label for="coordHorizontal">Coordenada Horizontal: </label>
            <input type="number" name="coordHorizontal">
          
            <label for="coordVertical">Coordenada Vertical: </label>
            <input type="number" name="coordVertical">
            
            
            <input type="submit" value="ATACAR!!">
        </form>    
        
        <h2> Tabuleiro Vísivel</h2>
        <table border="1">
            <?php for($h =1; $h <= 10;$h++){ ?>
            <tr>
            <?php for($v = 1; $v <= 10; $v++){ ?>
            <td><?= $jogo->tabuleiroVisivel[$h][$v] ?></td>
            <?php } ?>
            </tr>
            <?php } ?>
        </table>
    </body>
</html>
