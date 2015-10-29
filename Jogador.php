<?php

class Jogador {

	public $jogador_id = 1;
	public $nome = '';
	
	public function __construct($nome){
		$this->cadastra();

		$result = Database::select("SELECT id, nome FROM jogador WHERE nome = '$this->nome';", true);
		$this->jogador_id = $result[0]['id'];
	}

	public function cadastra(){

		$sql = "SELECT id, nome FROM jogador WHERE nome = '$this->nome';";
		$stmt = Conexao::getConexao()->prepare($sql);
		$stmt->execute();

		if($stmt->rowCount() == 0){ // não tem nenhum jogador, pode cadastrar!
			$sql = Database::insert('jogador', array('nome' => $this->nome, 'pontuacao' => 0));
			if($sql){
				echo "Jogador cadastrado com sucesso";
			} else {
				echo "Erro ao cadastrar o jogador";
				exit;
			}
		} else {
			if(DEBUG){
				echo "Já existe um jogador com esse nome";
			}
		}
	}

	public function remove($nome){
		$sql = "DELETE FROM jogador WHERE nome = '$nome';";
		// @TODO
	}
}