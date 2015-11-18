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
                            <a class="page-scroll" href="#sobre">Sobre</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <header class="intro">
            <div class="intro-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h1 class="brand-heading">BloodStorm</h1>
                            <p class="intro-text">Batalha Naval Jogo em PHP</p>
                            <a href="#jogar" class="btn btn-circle page-scroll">
                                <i class="fa fa-angle-double-down animated"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section id="jogar" class="content-section text-center">
            <div class="download-section">
                <div class="container">
                    <div class="col-lg-8 col-lg-offset-2">
                        <h2>ENTRAR</h2>
                        <?php if (!isset($_SESSION['jogador_nome'])) { ?>
                            <form method="GET" action="index.php">
                                <input type="hidden" name="modo" value="criacao">
                                <input type="text" placeholder="Entre com seu nome" name="nome" class="form-control" required> 

                                <input type="submit" value="JOGAR!" class="btn btn-default btn-lg">
                            </form>
                        <?php } else { ?>
                            <a href="index.php?modo=jogo" class="btn btn-default btn-lg">JOGAR!</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="sobre" class="container about-section" >
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <h2 class="text-center">Sobre o projeto</h2>
                    <p class="text-center">Toda o sistema do projeto foi desenvolvido com PHP e o font-end foi desenvolvido com ajuda do <a href="http://startbootstrap.com/">Bootstrap</a> junto a um template feito <a href="http://startbootstrap.com/template-overviews/grayscale/">[Grayscale]</a></p>
                    <p>Projeto feito com <3 por:</p>
                    <ul class="list-unstyled">
                        <li>Alice Mantovani</li>
                        <li>Eduardo Augusto Ramos</li>
                        <li>Felipe Pereira Jorge</li>
                        <li>Laís Vitória</li>
                        <li>Filipe Gianotto</li>
                    </ul>
                </div>
                
                
            </div>
            
            <div class="container text-center">
                    <p>Copyright &copy; 2 EMIA 2015</p>
                </div>
        </section>
        <script src="template/js/jquery.js"></script>
        <script src="template/js/bootstrap.min.js"></script>
        <script src="template/js/jquery.easing.min.js"></script>
        <script src="template/js/grayscale.js"></script>
    </body>
</html>