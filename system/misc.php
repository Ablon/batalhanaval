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

function numero_in_coord($numero) {
    $coordenadas = range('A', 'Z');
    return $coordenadas[$numero - 1];
}

function coord_in_numero($coord) {
    $coordenadas = range('A', 'Z');
    return array_search(strtoupper($coord), $coordenadas) + 1;
}

function convert_coords_post($coords) {
    if (empty($coords)) {
        exit("Erro no post, dados de coordenadas não enviados");
    }

    $num_caracteres = strlen($coords);
    $string = str_split($coords);

    if ($num_caracteres > 3) {
        exit("Não é possível atacar com um número tão grande");
    }

    if (!is_string($string[0])) {
        exit("A primeira letra tem que ser em alfabeto");
    }

    $return['vertical'] = $vertical = coord_in_numero($string[0]);
    $return['horizontal'] = $horizontal = $string[1] . @$string[2]; // bypass erro, numero com dois digítos
    
    return $return; 
}
