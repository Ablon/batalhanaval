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

/**
 * Classe principal, que da alma ao jogo, quase tudo oq acontece nas páginas de
 * jogo é processado aqui
 */
class Jogo {

    /**
     * array com todos as posições do jogo que fica escondido apenas para o programa
     *
     * @var array
     */
    public $tabuleiro = array();

    /**
     * array com todos as posições para o jogador vizualiar
     *
     * @var array
     */
    public $tabuleiroVisivel = array();

    /**
     * pontuacao
     *
     * @var int
     */
    public $pontuacao = 0;

    /**
     * mensagem
     *
     * @var string
     */
    public $mensagem = '';

    /**
     * Constructor
     *
     * função para associar o jogador ao jogo existente, senão cria um novo jogo
     * 
     * @return void
     */
    public function __construct() {
        if (isset($_SESSION['jogador_bind'])) {
            $this->id = $_SESSION['jogador_bind'];
        } else {
            $this->create();
        }
    }

    /**
     * Função para criar um novo jogo, para isso ela checa quais jogadores estão disponíveis
     *
     * @return void
     */
    public function create() {
        $stmt = Database::select("select count(*) RecordsPerGroup, jogo_bind as jogo_id from jogador where jogo_bind IN(SELECT id FROM jogo WHERE jogador_winner = 0) group by jogo_bind", array(), true);
        $control = false;
        foreach ($stmt as $game) {
            if ($game['RecordsPerGroup'] < 2) {
                $this->id = $game['jogo_id'];
                $control = true;
            }
        }

        if ($control == false) {
            $novo = Database::insert('jogo', array('data' => date("Y-m-d")));
            $this->id = Connection::getConnection()->lastInsertId();
            if ($novo == false && DEBUG) {
                exit("ERRO NA CRIAÇÃO DE UM NOVO JOGO!");
            }
        }

        $_SESSION['jogador_bind'] = $this->id; // previnir de falhas
    }
    
    /**
     * verifica qual estágio do jogo o jogador está (montagem do tabuleiro || jogo)
     *
     * @return void
     */
    public function first_run() {
        $stmt = Database::select("SELECT id, first_run FROM jogador WHERE id = ?", array(1 => $_SESSION['jogador_id']), true);
        if ($stmt[0]['first_run'] == 1) {
            $this->modo = 'criacao';
        } else {
            $this->modo = 'jogar';
        }
    }

    /**
     * função para verificar qual jogador está associado e com qual jogo
     *
     * @return void
     */
    public function two_players() {
        $stmt = Database::select("SELECT * FROM jogador WHERE id = ?", array(1 => $this->id));
        if ($stmt->rowCount() > 0) {
            $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($stmt[0]['jogo_bind'] != $_SESSION['jogador_bind']) { // atualiza o jogo bind para o novo
                Database::update('jogador', array('jogo_bind' => $this->id), array('id' => $_SESSION['jogador_id']));
            }
        }

        return Database::select("SELECT * FROM jogador WHERE jogo_bind = ? AND id != ?", array(1 => $this->id, 2 => $_SESSION['jogador_id']), true);
    }
    
    /**
     * função que verifica se é a vez do jogador e se alguém ganhou
     *
     * @return void
     */
    public function avaliable() {
        $stmt = Database::select("SELECT jogador_last_turn, jogador_winner FROM jogo WHERE id = ?", array(1 => $this->id), true);
        $winner = $stmt[0]['jogador_winner'];
        if ($winner != 0) {
            if ($winner == $_SESSION['jogador_id']) {
                return 'ganhou';
            } else {
                return 'perdeu';
            }
        }

        if ($stmt[0]['jogador_last_turn'] == $_SESSION['jogador_id']) {
            return 'aguarde';
        } else {
            return 'jogar';
        }
    }
    
      
    /**
     * função para alterar o modo para jogar, não mais criação do tabuleiro
     *
     * @return void
     */
    public function modoJogo() {
        Database::update('jogador', array('first_run' => 0), array('id' => $_SESSION['jogador_id']));
    }

    /**
     *  função para pegar a pontuação do jogador 
     *
     * @return void
     */
    public function getPontuacao() {
        $stmt = Database::select("SELECT pontuacao FROM jogador WHERE id = ?", array(1 => $_SESSION['jogador_id']), true);
        $this->pontuacao = $stmt[0]['pontuacao'];
    }
    
    /**
     * função que gera o tabuleiro
     *
     * @return void
     */
    public function geraTabuleiro() {
        for ($h = 1; $h <= NUM_CASAS; $h++) { // h = horizontal
            for ($v = 1; $v <= NUM_CASAS; $v++) { // v = vertical
                $this->tabuleiro[$h][$v] = '';
                $this->tabuleiroVisivel[$h][$v] = '';
            }
        }

        // se jogo já existir, preencher com X's o tabuleiro
        $result = Database::select("SELECT * FROM jogadas WHERE jogo_id = ? AND jogador_id = ?", array(1 => $this->id, 2 => $_SESSION['jogador_id']));
        if ($result->rowCount() > 0) {
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if ($row['navio'] != '' && $row['navio'] != 'X') {
                    $casa = '<img style="margin: 0 -40px; width: 100px" src="template/img/navios/' . $row['navio'] . '.png">';
                } else {
                    $casa = '<img style="margin: 0 -20px; width: 100px" src="template/img/bomb.png" >';
                }
                $this->tabuleiro[$row['coordHorizontal']][$row['coordVertical']] = 'X';
                $this->tabuleiroVisivel[$row['coordHorizontal']][$row['coordVertical']] = $casa;
            }
        }
    }

    /**
     * função para colocar os navios no tabuleiro
     *
     * @return void
     */
    public function colocar($horizontal, $vertical, $navio) {
        $this->check_navio($horizontal, $vertical, $navio);
        switch ($navio) {
            case 'encouracado':
                $this->coloca_navio($horizontal, $vertical, 'encouracado_part1');
                $this->coloca_navio($horizontal, $vertical + 1, 'encouracado_part2');

                $_SESSION['encouracado'] --; // retira um navio do cookie

                break;
            case 'submarino':
                $this->coloca_navio($horizontal, $vertical, 'submarino_part1');
                $this->coloca_navio($horizontal, $vertical + 1, 'submarino_part2');
                $this->coloca_navio($horizontal, $vertical + 2, 'submarino_part3');

                $_SESSION['submarino'] --;
                break;

            case 'porta-avioes':
                $this->coloca_navio($horizontal, $vertical, 'porta_part1');
                $this->coloca_navio($horizontal, $vertical + 1, 'porta_part2');
                $this->coloca_navio($horizontal, $vertical + 2, 'porta_part3');
                $this->coloca_navio($horizontal, $vertical + 3, 'porta_part4');

                $_SESSION['porta-avioes'] --;
                break;

            case 'navio-guerra':
                $this->coloca_navio($horizontal, $vertical, 'navio-guerra_part1');
                $this->coloca_navio($horizontal, $vertical + 1, 'navio-guerra_part2');
                $this->coloca_navio($horizontal, $vertical + 2, 'navio-guerra_part3');

                $_SESSION['navio-guerra'] --;
                break;

            default:
                exit("TIPO DE NAVIO INEXISTENTE");
        }
    }
    
    /**
     * função para colocar um navio no tabuleiro
     *
     * @return void
     */
    public function coloca_navio($horizontal, $vertical, $navio) {
        if (isset($this->tabuleiro[$horizontal][$vertical])) {
            $this->tabuleiro[$horizontal][$vertical] = $navio;
            $this->tabuleiroVisivel[$horizontal][$vertical] = "<img src='template/img/navios/$navio.png' style='width: 100px; margin: -20px;'>";
        } 
    }
    
    public function check_navio($horizontal, $vertical, $navio){
        switch($navio){
            case 'encouracado':
                if(!isset($this->tabuleiro[$horizontal][$vertical]) ||
                   !isset($this->tabuleiro[$horizontal][$vertical + 1])){
                    exit("NÃO PODE COLCOAR NAVIO AQUI <meta http-equiv='refresh' content='3'>");
                }
            break;
            
            case 'submarino':
                if(!isset($this->tabuleiro[$horizontal][$vertical]) ||
                   !isset($this->tabuleiro[$horizontal][$vertical + 1]) ||
                   !isset($this->tabuleiro[$horizontal][$vertical + 2])) {
                    exit("NÃO PODE COLCOAR NAVIO AQUI <meta http-equiv='refresh' content='3'>");
                }
            break;
            
            case 'porta-avioes':
                if(!isset($this->tabuleiro[$horizontal][$vertical]) ||
                   !isset($this->tabuleiro[$horizontal][$vertical + 1]) ||
                   !isset($this->tabuleiro[$horizontal][$vertical + 2]) ||
                   !isset($this->tabuleiro[$horizontal][$vertical + 3])) {
                    exit("NÃO PODE COLCOAR NAVIO AQUI <meta http-equiv='refresh' content='3'>");
                }
            break;
            
            case 'navio-guerra':
                if(!isset($this->tabuleiro[$horizontal][$vertical]) ||
                   !isset($this->tabuleiro[$horizontal][$vertical + 1]) ||
                   !isset($this->tabuleiro[$horizontal][$vertical + 2])) {
                    exit("NÃO PODE COLCOAR NAVIO AQUI <meta http-equiv='refresh' content='3'>");
                }
            break;
        }
    }
    
    /**
     * função para atacar um navio no tabuleiro
     *
     * @return void
     */
    public function ataca($horizontal, $vertical) {
        if ($horizontal > NUM_CASAS || $vertical > NUM_CASAS) {
            exit("As coordenadas não podem ser maiores que " . NUM_CASAS);
        }

        $casa = $this->tabuleiro[$horizontal][$vertical];

        if (!empty($this->tabuleiroVisivel[$horizontal][$vertical])) { // checa se já foi atacada essa casa
            $this->mensagem = "Já foi atacada essa casa";
        }

        if ($casa != '') {
            $this->tabuleiroVisivel[$horizontal][$vertical] = '<img style="margin: 0 -40px;  width: 100px;" src="template/img/navios/' . $casa . '.png">'; // marca posições para jogador ver!
            $this->pontuar();
        } else {
            $navio = '';
            $this->tabuleiroVisivel[$horizontal][$vertical] = '<img style="margin: 0 -40px; width: 100px" src="template/img/bomb.png">'; // marca posições para jogador ver!
            $this->mensagem = "ERROUU!!!!";
        }

        $this->guardaJogada($horizontal, $vertical, $casa);
    }
    
    
    
    /**
     * guarda a jogadada do jogador no banco de dados
     *
     * @return void
     */
    public function guardaJogada($horizontal, $vertical, $navio = '') {
        Database::insert('jogadas', array('jogador_id' => $_SESSION['jogador_id'], 'jogo_id' => $this->id, 'coordHorizontal' => $horizontal, 'coordVertical' => $vertical, 'navio' => $navio));
        Database::update('jogo', array('jogador_last_turn' => $_SESSION['jogador_id']), array('id' => $this->id));
    }
    
    /**
     * guarda o tabuleiro no banco de dados e também coloca os navios no tabuleiro
     *
     * @return void
     */
    public function guardaNavios() {
    
        if ($this->modo == 'criacao') {
            $jogador_id = $_SESSION['jogador_id'];
        } else {
            $stmt = Database::select('SELECT id FROM jogador WHERE jogo_bind = ?', array(1 => $this->id), true);
            foreach ($stmt as $jogador) {
                if ($jogador['id'] != $_SESSION['jogador_id']) {
                    $jogador_id = $jogador['id'];
                }
            }
            if (!isset($jogador_id)) {
                exit("Aguarde outro jogador entrar! <meta http-equiv='refresh' content='3'>");
            }
        }

        $result = Database::select("SELECT * FROM tabuleiro WHERE jogo_id = ? AND jogador_id = ?", array(1 => $this->id, 2 => $jogador_id), false, true);

        for ($h = 1; $h <= 10; $h++) {
            for ($v = 1; $v <= 10; $v++) {
                if (!empty($this->tabuleiro[$h][$v])) {
                    $navio = $this->tabuleiro[$h][$v];
                    if ($navio != 'X') {
                        Database::insert('tabuleiro', array('jogo_id' => $this->id, 'jogador_id' => $_SESSION['jogador_id'], 'coordHorizontal' => $h, 'coordVertical' => $v, 'navio' => $navio));
                    }
                }
            }
        }

        if ($result->rowCount() < 15)  {
            if ($this->modo == 'jogar') {
                exit("Aguarde seu oponente terminar de montar seu tabuleiro! <meta http-equiv='refresh' content='1'>");
            }
        }

        if ($result->rowCount() > 0) {
            $navios = $result->fetchAll(PDO::FETCH_ASSOC);

            foreach ($navios as $navio) {
                $this->tabuleiro[$navio['coordHorizontal']][$navio['coordVertical']] = $navio['navio'];
                if ($this->modo == 'criacao') {
                    $this->tabuleiroVisivel[$navio['coordHorizontal']][$navio['coordVertical']] = '<img src="template/img/navios/' . $navio['navio'] . '.png" style="width: 100px; margin: -20px;">';
                }
            }
        }
    }
    
    /**
     * função para pontuar os jogadores, e verificar quem ganhou
     *
     * @return void
     */
    public function pontuar() {
        $sql = "UPDATE jogador SET pontuacao = pontuacao + 1 WHERE id = " . $_SESSION['jogador_id'];
        $stmt = Connection::getConnection()->prepare($sql)->execute();

        $pontuacao = Database::select("SELECT pontuacao FROM jogador WHERE id = ?", array(1 => $_SESSION['jogador_id']));
        if ($pontuacao->rowCount() >= 1) {
            $result = $pontuacao->fetchAll(PDO::FETCH_ASSOC)[0];
            $this->pontuacao = $result['pontuacao'];
            if ($result['pontuacao'] >= PONTUACAO_MAXIMA) {
                Database::update('jogo', array('jogador_winner' => $_SESSION['jogador_id']), array('id' => $this->id));
            }
        }
    }
}