<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Eduardo Ramos, Felipe Pereira, Laís Vitória, Alice Mantovani, Filipe Gianotto">

        <title>BloodStorm - Batalha Naval | Projeto de PHP</title>

        <link href="template/css/bootstrap.min.css" rel="stylesheet">
        <link href="template/css/main.css" rel="stylesheet">

        <link href="template/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="template/font-awesome/css/fonts.css" rel="stylesheet" type="text/css">
        
        <meta http-equiv="refresh" content="10">
    </head>

    <body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

        <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand page-scroll" href="#page-top">
                        <i class="fa fa-play-circle"></i>  <span class="light">Blood</span> Storm
                    </a>
                </div>

                <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                    <ul class="nav navbar-nav">
                        <li class="hidden">
                            <a href="#page-top"></a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#jogar">Jogar</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="index.php?modo=normal#sobre">Sobre</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <header class="intro">
            <div class="intro-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="POST">

                                <h2>Criação do Tabuleiro</h2>
                                <h3>Você está jogando com: <span style="color: red"><?= $oponente ?></span></h3>

                                <label for="tipo_navios">Selecione um navio</label>
                                <select name="tipo_navio">
                                    <?php if ($_SESSION['encouracado'] != 0) { ?>
                                        <option value="encouracado">Encouraçado (2 casas)</option>
                                    <?php } ?>

                                    <?php if ($_SESSION['submarino'] != 0) { ?>    
                                        <option value="submarino">Submarino (3 casas) - 2 navios</option>
                                    <?php } ?>

                                    <?php if ($_SESSION['porta-avioes'] != 0) { ?>    
                                        <option value="porta-avioes">Porta-aviões (4 casas)</option>
                                    <?php } ?>

                                    <?php if ($_SESSION['navio-guerra'] != 0) { ?>        
                                        <option value="navio-guerra">Navio de guerra (3 casas)</option>
                                    <?php } ?>
                                </select>
                                <table class="table" border="1">
                                    <?php for ($i = 0; $i <= NUM_CASAS; $i++) { ?>    
                                        <td class="head">
                                            <?php echo ($i == 0 ? '' : $i) // não mostra o número 0 ?>
                                        </td>
                                    <?php } ?>

                                    <?php for ($h = 1; $h <= NUM_CASAS; $h++) { ?>
                                        <tr>
                                            <td class="side"><?= numero_in_coord($h) ?></td>

                                            <?php for ($v = 1; $v <= NUM_CASAS; $v++) { ?>
                                                <?php
                                                $casa = $jogo->tabuleiroVisivel[$h][$v];
                                                if (empty($casa)) {
                                                    $print = "<button name='coordenadas_colocar' class='btn btn-default' type='submit' value='" . numero_in_coord($h) . $v . "'>COLOCAR</button>";
                                                } else {
                                                    $print = $casa;
                                                }
                                                ?>
                                                <td><?= $print ?> </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <footer>
            <div class="container text-center">
                <p>Copyright &copy; 2 EMIA 2015</p>
            </div>
        </footer>

        <script src="template/js/jquery.js"></script>
        <script src="template/js/bootstrap.min.js"></script>
        <script src="template/js/jquery.easing.min.js"></script>
        <script src="template/js/grayscale.js"></script>

    </body>
</html>