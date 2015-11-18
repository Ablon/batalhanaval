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

// opções
define("DEBUG", true);
define("NUM_CASAS", 10);
define("PONTUACAO_MAXIMA", 15);
session_start();

// system
include('system/misc.php');
include('system/database.php');
include('system/jogador.php');
include('system/jogo.php');

// controller
include('controller.php');
