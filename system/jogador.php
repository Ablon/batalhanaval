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
class Jogador {

    public $nome;
    public $id;
    public $jogo_bind;

    public function __construct($nome, $jogo_id) {
        $this->nome = $nome;
        $this->jogo_bind = $jogo_id;

        if (empty($_SESSION['jogador_nome'])) {
            $_SESSION['jogador_nome'] = $this->nome;
            $_SESSION['jogador_bind'] = $this->jogo_bind;

            $this->cadastra();

            $_SESSION['jogador_id'] = $this->id;
        }

        if ($nome != $_SESSION['jogador_nome']) {
            $this->cadastra();
            $_SESSION['jogador_nome'] = $this->nome;
            $_SESSION['jogador_bind'] = $this->jogo_bind;
            $_SESSION['jogador_id'] = $this->id;
        }
    }

    private function cadastra() {
        $stmt = Database::select("SELECT id, nome FROM jogador WHERE nome = ?", array(1 => $this->nome));

        if ($stmt->rowCount() == 0) { // não tem nenhum jogador, pode cadastrar!
            Database::insert('jogador', array('nome' => $this->nome, 'pontuacao' => 0, 'jogo_bind' => $this->jogo_bind, 'first_run' => 1));
            $this->id = Connection::getConnection()->lastInsertId();
        } else {
            Database::update('jogador', array('jogo_bind' => $this->jogo_bind, 'first_run' => 1), array('nome' => $this->nome));
            $result = $stmt->fetchAll(PDO::FETCH_BOTH);
            $this->id = $result[0]['id'];

            if (DEBUG) {
                echo "Já existe um jogador com esse nome";
            }
        }
    }

}
