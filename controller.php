<?php

/**
 * BloodStorm : BatalhaNaval
 * 2 EMIA - 2015
 *
 * Feito com <3 por:
 * Eduardo Augusto Ramos
 * Felipe Pereira Jorge
 * Laís Vitória
 * Alice Mantovani
 * Filipe Gianotto
 *
 */

// a mágica começa aqui!
if (!isset($_GET['modo'])) {
    include('template/index.php');
} else {

    $jogo = new Jogo();

    if (!isset($_GET['nome'])) {
        $nome_jogador = $_SESSION['jogador_nome'];
    } else {
        $nome_jogador = $_GET['nome'];
    }
    $jogador = new Jogador($nome_jogador, $jogo->id);

    $jogo->geraTabuleiro();
    $jogo->first_run();

    $oponente = $jogo->two_players();
    $jogo->getPontuacao();
    if (!isset($oponente[0]['nome'])) {
        $aguarde_entrar = true;
    } else {
        $aguarde_entrar = false;
        $oponente = $oponente[0]['nome'];
    }
    
    if(is_array($oponente)){
        $oponente = "AGUARDANDO";
    }
    
    // jogando
    if ($_GET['modo'] == 'jogo') {
        $jogo->guardaNavios();

        if (isset($_POST['coordenadas'])) {
            $coords = convert_coords_post($_POST['coordenadas']);

            $jogo->ataca($coords['vertical'], $coords['horizontal']);
        }

        include('template/batalha.php');
    } else if ($_GET['modo'] == 'criacao') {
        if (!isset($_SESSION['encouracado'])) {
            $_SESSION['encouracado'] = 1;
            $_SESSION['submarino'] = 2;
            $_SESSION['porta-avioes'] = 1;
            $_SESSION['navio-guerra'] = 1;
        }

        if (isset($_POST['coordenadas_colocar'])) {
            $string = str_split($_POST['coordenadas_colocar']);

            $vertical = coord_in_numero($string[0]);
            $horizontal = $string[1] . @$string[2]; // bypass erro, numero com dois digítos

            $jogo->colocar($vertical, $horizontal, $_POST['tipo_navio']);
        }
        
        if ($_SESSION['encouracado'] == 0 && $_SESSION['submarino'] == 0 && $_SESSION['porta-avioes'] == 0 && $_SESSION['navio-guerra'] == 0) {
            header('Location: index.php?modo=jogo&nome=' . $_GET['nome']);
            $jogo->modoJogo();
        }

        $jogo->guardaNavios();
        include('template/criacao.php');
    } else if($_GET['modo'] == 'novo') { // novo jogo
        session_unset(); // limpa cookies
        header("Location: index.php");
    }
}