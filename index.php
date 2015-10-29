<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Batalha Naval - BloodStorm</title>
    </head>
    <body>
        <h4>Bem vindo ao # Projeto Batalha Naval #</h4>
<ul>
Grupo:
<li>Alice Mantovani</li>
<li>Eduardo Augusto Ramos</li>
<li>Felipe Pereira</li>
<li>Filipe Giannotto</li>
<li>Lais Vitoria</li>
</ul>

<h4>Regras:</h4>
<ul>
<li>- Cada jogador tem o direito de posicionar 4 navios:</li>
<li>	1 porta-aviões (5 quadrados)</li>
<li>	2 encouraçados (3 quadrados)</li>
<li>	1 submarino (2 quadrados)</li>
<li>- Tabuleiro com dimensão 10x10 (1-10 horizontal) (A-J na vertical)</li>
<li>- 2 jogadores</li>
</ul>

	<form method="POST" action="campo.php">
		<input type="hidden" name="initial">
		<label for="nome">Nome do Jogador:</label>
		<input type="text" name="nome" placeholder="Nome">

		<input type="submit" value="GERAR BATALHA!">
	</form>	
    </body>
</html>
