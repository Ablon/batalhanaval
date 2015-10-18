<?php
include('Jogo.php');

$jogo = new Jogo();
$jogo->start();
$jogo->geraTabuleiro();
$jogo->colocaNavios();

if(isset($_POST['coordHorizontal'])){
    
    $jogo->ataca($jogo->coord_in_numero($_POST['coordVertical']), $_POST['coordHorizontal']); // inverter funciona, são sei o porquê?!?!?!?
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
        
        <?php if($jogo->debug){ ?>
        <h2> Tabuleiro de Debug</h2>
        <table border="1">
            <?php for($h =1; $h <= $jogo->num_horizontal;$h++){ ?>
            <tr>
            <?php for($v = 1; $v <= $jogo->num_vertical; $v++){ ?>
            <td><?= $jogo->tabuleiro[$h][$v] ?></td>
            <?php } ?>
            </tr>
            <?php } ?>
        </table>
        <?php } ?>
        
        
        <h1>Jogada: </h1>
        
        <form method="POST">
            <label for="coordHorizontal">Coordenada Horizontal: </label>
            <input type="number" name="coordHorizontal">
          
            <label for="coordVertical">Coordenada Vertical: </label>
            <input type="text" name="coordVertical">
            
            
            <input type="submit" value="ATACAR!!">
        </form>    
        
        <h2> Tabuleiro Vísivel</h2>
        <table border="1">
            
            <?php for($i =0; $i <= $jogo->num_horizontal;$i++){ ?>    
            <td bgcolor='red'><?php echo ($i == 0 ? '' : $i) ?></td>
            <?php } ?>

            <?php for($h =1; $h <= $jogo->num_horizontal;$h++){ ?>
            <tr><td bgcolor='yellow'><?= $jogo->numero_in_coord($h) ?></td>
            <?php for($v = 1; $v <= $jogo->num_vertical; $v++){ ?>

            <td><?= $jogo->tabuleiroVisivel[$h][$v] ?></td>
            <?php } ?>
            </tr>
            <?php } ?>
        </table>
    </body>
</html>
