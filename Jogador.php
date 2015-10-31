<?php

class Jogador {

	public $jogador_id = 1;
	public $nome = '';
	
	public function __construct($nome){
		$this->nome = $nome;

		if(empty($_SESSION['nome'])){
			$this->cadastra();
			$_SESSION['nome'] = $this->nome;
				
			$result = Database::select("SELECT id, nome FROM jogador WHERE nome = '$this->nome';", true);
			$this->jogador_id = $result[0]['id'];

			$_SESSION['jogador_id'] = $this->jogador_id;


		} else {
			$this->jogador_id = $_SESSION['jogador_id'];
			$this->nome = $_SESSION['nome'];
		}
	}

	private function cadastra(){
		$stmt = Database::select("SELECT id, nome FROM jogador WHERE nome = '$this->nome';");

		if($stmt->rowCount() == 0){ // não tem nenhum jogador, pode cadastrar!
			$sql = Database::insert('jogador', array('nome' => $this->nome, 'pontuacao' => 0));
			if($sql){
				if(DEBUG) {
					echo "Jogador cadastrado com sucesso";
				}
			} else {
				if(DEBUG) {
					echo "Erro ao cadastrar o jogador";
					exit;
				}
			}
		} else {
			if(DEBUG){
				echo "Já existe um jogador com esse nome";
			}
		}
	}
}