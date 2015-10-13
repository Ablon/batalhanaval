<?php
include('Conexao.php');

class Jogo {
    
    public $jogador = 1;
    public $tabuleiro = array();
    public $tabuleiroVisivel = array();
    public $coordenadas = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
       
    public function geraTabuleiro(){
        // h = horizontal
        // v = vertical
        for($h = 1; $h <= 10; $h++){
            for($v = 1; $v <= 10; $v++){
                $this->tabuleiro[$h][$v] = '';
                $this->tabuleiroVisivel[$h][$v] = '';
            }
        }
    }
    
    public function colocaNavio($tipoNavio){
        /* if(!in_array($tipoNavio, $this->navios)){
            echo "Navio $tipoNavio não existe";
            return;
        } */
        
        switch ($tipoNavio){
            case 'submarino':
                $this->tabuleiro[1][10] = "SUB";
            break;
        
            case 'porta-aviões':
                $this->tabuleiro[3][2] = "PORTA";
                $this->tabuleiro[3][3] = "PORTA";
                $this->tabuleiro[3][4] = "PORTA";
                $this->tabuleiro[3][5] = "PORTA";
                $this->tabuleiro[3][6] = "PORTA";
            break;
        
            case 'encouraçado':
                $this->tabuleiro[5][4] = "ENCO";
                $this->tabuleiro[5][5] = "ENCO";
                $this->tabuleiro[5][6] = "ENCO";
            break;
        }
                
    }
    
    public function ataca($coordHorizontal, $coordVertical){
        if($this->tabuleiro[$coordHorizontal][$coordVertical] != ''){
            echo "TEM NAVIO AQUI!!!!";
        } else {
            echo "ERROUU!";
        }
        
         $this->tabuleiroVisivel[$coordHorizontal][$coordVertical] = 'X';
    } 
    
}
