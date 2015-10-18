<?php
class Jogo {

    public $jogador_id = 1;
    public $jogo_id = 1;
    public $tabuleiro = array();
    public $tabuleiroVisivel = array();
    public $coordenadas = array();
    public $num_horizontal = 10;
    public $num_vertical = 10;
    public $pontuacao_maxima = 9; // melhorar isso daqui

    public function start() {
        $this->coordenadas = range('A', 'Z');
        
        $jogo = Database::select("SELECT id FROM jogo WHERE id = $this->jogo_id;"); // checa se o jogo existe, senão cria um
        if ($jogo->rowCount() == 0) {
            $novo = Database::insert("INSERT INTO jogo VALUES ($this->jogo_id, " . date("Y-m-d") . ");");
            if ($novo == false && DEBUG) {
                exit("ERRO NA CRIAÇÃO DE UM NOVO JOGO!");
            }
        }
        
        $jogador = Database::select("SELECT id FROM jogador WHERE id = $this->jogo_id;"); // checa se o jogador existe, senão cria um
        if ($jogador->rowCount() == 0) {
            $novo = Conexao::insert("INSERT INTO jogador VALUES ($this->jogador_id, 'EDUARDO TESTE', 0);");
            if ($novo == false && DEBUG) {
                exit("ERRO NA CRIAÇÃO DE UM NOVO JOGADOR!");
            }
        }
    }

    public function geraTabuleiro() {
        // h = horizontal
        // v = vertical
        for ($h = 1; $h <= $this->num_horizontal; $h++) {
            for ($v = 1; $v <= $this->num_vertical; $v++) {
                $this->tabuleiro[$h][$v] = '';
                $this->tabuleiroVisivel[$h][$v] = '';
            }
        }

        // se jogo já existir, preencher com X's o tabuleiro
        $result = Database::select("SELECT * FROM jogadas WHERE jogo_id = $this->jogo_id;");
        if ($result->rowCount() > 0) {
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if ($row['navio'] != '') {
                    $casa = 'X' . $row['navio'] . ' X';
                } else {
                    $casa = 'X';
                }
                $this->tabuleiro[$row['coordHorizontal']][$row['coordVertical']] = 'X';
                $this->tabuleiroVisivel[$row['coordHorizontal']][$row['coordVertical']] = $casa;
            }
        }
    }

    public function colocaNavio($tipoNavio, $orientacao) {
        // gerando aleatoriamente
        $controle = false;

        switch ($tipoNavio) {
            case 'submarino':

                // LÓGICA MAIS MALUCA QUE CONSEGUI INVENTAR, NÃO SEI COMO ESSA MERDA FUNCIONA, MAS FUNCIONA!
                while ($controle == false) {
                    $horizontal = rand(1, $this->num_horizontal);
                    $vertical = rand(1, $this->num_vertical);
                
                    if (isset($this->tabuleiro[$horizontal][$vertical]) && 
                        empty($this->tabuleiro[$horizontal][$vertical])) {
                        $this->tabuleiro[$horizontal][$vertical] = "SUB_part_1";
                        $controle = true;
                    }
                }


                break;

            case 'porta-aviões':

                // LÓGICA MAIS MALUCA QUE CONSEGUI INVENTAR, NÃO SEI COMO ESSA MERDA FUNCIONA, MAS FUNCIONA!
                while ($controle == false) {
                    $horizontal = rand(1, $this->num_horizontal);
                    $vertical = rand(1, $this->num_vertical);

                    if (isset($this->tabuleiro[$horizontal][$vertical]) &&
                            isset($this->tabuleiro[$horizontal][$vertical + 1]) &&
                            isset($this->tabuleiro[$horizontal][$vertical + 2]) &&
                            isset($this->tabuleiro[$horizontal][$vertical + 3]) &&
                            isset($this->tabuleiro[$horizontal][$vertical + 4]) &&
                            empty($this->tabuleiro[$horizontal][$vertical]) &&
                            empty($this->tabuleiro[$horizontal][$vertical + 1]) &&
                            empty($this->tabuleiro[$horizontal][$vertical + 2]) &&
                            empty($this->tabuleiro[$horizontal][$vertical + 3]) &&
                            empty($this->tabuleiro[$horizontal][$vertical + 4])
                    ) {

                        $this->tabuleiro[$horizontal][$vertical] = "PORTA_part_1";
                        $this->tabuleiro[$horizontal][$vertical + 1] = "PORTA_part_2";
                        $this->tabuleiro[$horizontal][$vertical + 2] = "PORTA_part_3";
                        $this->tabuleiro[$horizontal][$vertical + 3] = "PORTA_part_4";
                        $this->tabuleiro[$horizontal][$vertical + 4] = "PORTA_part_5";
                        $controle = true;
                    }
                }

                break;

            case 'encouraçado':
                while ($controle == false) {
                    $horizontal = rand(1, $this->num_horizontal);
                    $vertical = rand(1, $this->num_vertical);

                    if (isset($this->tabuleiro[$horizontal][$vertical]) &&
                            isset($this->tabuleiro[$horizontal][$vertical + 1]) &&
                            isset($this->tabuleiro[$horizontal][$vertical + 2]) &&
                            empty($this->tabuleiro[$horizontal][$vertical]) &&
                            empty($this->tabuleiro[$horizontal][$vertical + 1]) &&
                            empty($this->tabuleiro[$horizontal][$vertical + 2])
                    ) {

                        $this->tabuleiro[$horizontal][$vertical] = "ENCO_part_1";
                        $this->tabuleiro[$horizontal][$vertical + 1] = "ENCO_part_2";
                        $this->tabuleiro[$horizontal][$vertical + 2] = "ENCO_part_3";
                        $controle = true;
                    }
                }
                break;
        }
    }

    public function colocaNavios() {
        $result = Database::select("SELECT * FROM tabuleiro WHERE jogo_id = $this->jogo_id;");
        if ($result->rowCount() == 0) {

            $this->colocaNavio('submarino', 'horizontal');
            $this->colocaNavio('porta-aviões', 'horizontal');
            $this->colocaNavio('encouraçado', 'horizontal');

            // guarda novo tabuleiro aleatório
            $sql = 'INSERT INTO `tabuleiro` (`jogo_id`, `coordHorizontal`, `coordVertical`, `navio`) VALUES ';
            for ($h = 1; $h <= $this->num_horizontal; $h++) {
                for ($v = 1; $v <= $this->num_vertical; $v++) {
                    if (!empty($this->tabuleiro[$h][$v])) {
                        $navio = $this->tabuleiro[$h][$v];
                        $queries[] = "($this->jogo_id, $h, $v, '$navio')";
                    }
                }
            }

            // não cria várias conexões com o banco de dados, ou senão vai sobrecarregar ele!
            $sql .= implode(', ', $queries) . "; ";
            $stmt = Conexao::getConexao()->prepare($sql);
            $stmt->execute();
        } else {
            // pega os navios
            $navios = $result->fetchAll(PDO::FETCH_ASSOC);

            foreach ($navios as $navio) {
                $this->tabuleiro[$navio['coordHorizontal']][$navio['coordVertical']] = $navio['navio'];
            }
        }
    }

    public function ataca($coordHorizontal, $coordVertical) {
        if ($coordHorizontal > $this->num_horizontal || $coordVertical > $this->num_vertical) {
            exit("As coordenadas não podem ser maiores que $this->num_horizontal!");
        }
        $casa = $this->tabuleiro[$coordHorizontal][$coordVertical];

        if (!empty($this->tabuleiroVisivel[$coordHorizontal][$coordVertical])) { // checa se já foi atacada essa casa
            exit("Já foi atacada essa casa");
        }

        if ($casa != '') {
            $navio = $casa;
            $this->tabuleiroVisivel[$coordHorizontal][$coordVertical] = 'X' . $navio . ' X'; // marca posições para jogador ver!
            echo "ACERTOU UM " . $navio;
            $this->pontuar();
        } else {
            $navio = '';
            $this->tabuleiroVisivel[$coordHorizontal][$coordVertical] = 'X'; // marca posições para jogador ver!
            echo "ERROUU!";
        }

        $this->guardaJogada($coordHorizontal, $coordVertical, $navio);
    }

    public function guardaJogada($horizontal, $vertical, $navio = '') {
        $stmt = Database::insert("INSERT INTO `jogadas` (`jogador_id`, `jogo_id`, `coordHorizontal`, `coordVertical`, `navio`) VALUES ($this->jogador_id, $this->jogo_id, $horizontal, $vertical, '$navio');");
    }

    public function limpaJogo() {
        $sql = "DELETE FROM `jogadas` WHERE jogo_id = $this->jogo_id;";
        $stmt = Conexao::getConexao()->prepare($sql)->execute();
    }

    public function numero_in_coord($numero) {
        return $this->coordenadas[$numero - 1];
    }

    public function coord_in_numero($coord) {
        return array_search(strtoupper($coord), $this->coordenadas) + 1;
    }

    public function pontuar() {

        $sql = "UPDATE jogador SET pontuacao = pontuacao + 1 WHERE id = $this->jogador_id;";
        $stmt = Conexao::getConexao()->prepare($sql)->execute();


        $stmt = Conexao::getConexao()->prepare("SELECT pontuacao FROM jogador WHERE id = $this->jogador_id");
        $stmt->execute();
        if ($stmt->rowCount() >= 1) {

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

            if ($result['pontuacao'] >= $this->pontuacao_maxima) {

                // jogador atingiu o máximo, ou seja, ganhou!!!
                ECHO "########################### JOGADOR GANHOU ##########################################";

                // reseta pontuacao
                $sql = "UPDATE jogador SET pontuacao = 0 WHERE id = $this->jogador_id;";
                $stmt = Conexao::getConexao()->prepare($sql)->execute();
                exit;
            }
        }
    }

}
