<?php
include_once("sql_conexao.php"); 

$conexao = new conexao();

$conexao->sql_conexao();

//print_r($conexao->con);

$id =  $_GET['ID'];

$sql = "SELECT * FROM dbo.PT_Usuario WHERE ID_Usuario = '$id' "; 

$query = sqlsrv_query($conexao->con, $sql);

$registro = sqlsrv_fetch_array($query);

$id = $registro['ID_Usuario'];
$email = $registro['DE_Email'];
//print_r($senha);

header('Location: ../listar_usuarios.php?status=1');

$msg = 
"Ola, identificamos que foi solicitado uma recuperacao de senha.

Copie e cole esse codigo, " . $id . 98745 . " , neste link para confirmar a autencidade: http://localhost:8089/portal/portal_php/portal/autenticacao_usuario.php?cod=" .base64_encode($id).".

Caso nao tenha solicitado, acesse sua conta e altere sua senha.";

mail($email,'Identificacao de Senha',$msg, "From: sistema@fresadorasantana.com.br");


//Para retornar para tela de login clique aqui: http://localhost:8089/portal/portal_php/portal/login.php.




?>