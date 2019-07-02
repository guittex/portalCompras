<?php

    session_start();

    include_once("services/sql_conexao.php");

    $conexao = new conexao();

    $conexao->sql_conexao();   

    $id = $_SESSION['ID_VenFor'];

    $top = $_POST['qtd_excel'];



?>



<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<title>Contato</title>
	<head>
	<body>
		<?php
		// Definimos o nome do arquivo que será exportado
		$arquivo = 'registro_excel.xls';
		// Criamos uma tabela HTML com o formato da planilha
		$html = '';
		$html .= '<table border="1">';
		$html .= '<tr>';
		$html .= '<td colspan="8">REGISTRO DE ORÇAMENTOS</td>';
        $html .= '</tr>';
        
        $html .= '<thead>';     

        $html .= '<tr>';
        $html .= '<th>Descrição </th>';
        $html .= '<th>Quantidade</th>';
        $html .= '<th>Preço</th>';
        $html .= '<th>Entrega</th>';
        $html .= '<th>IPI</th>'; 
        $html .= '<th>ICMS</th>'; 
        $html .= '<th>NCM</th>';
        $html .= '<th>Data do Orçamento</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
		
        $sql_orcamento =  "SELECT TOP $top * FROM  dbo.PT_Cotacao WHERE ID_Fornecedor =  $id order by DT_DataInserido desc ";	
        $query = sqlsrv_query($conexao->con, $sql_orcamento);
        while($array = sqlsrv_fetch_array($query)){
            $html .= '<tr>';
            
            $html .= '<td>'. $array['DE_Descricao'] . "</td>";
            $html .= '<td>'. $array['DE_Qtde'] . "</td>";  
            $html .= '<td>'. $array['DE_Preco'] . "</td>";  
            $html .= '<td>'. $array['DE_EntregaDias'] . "</td>";
            $html .= '<td>'. $array['DE_ICMS'] . "</td>";        
            $html .= '<td>'. $array['DE_Ipi'] . "</td>";        
            $html .= '<td>'. $array['DE_NCM'] . "</td>";
            $html .= '<td>'. $array['DT_DataInserido']->format('d/m/Y H:i:s') . "</td>";              
            
            $html .= '</tr>';
        }	
		
		$html .= '</tbody>';
		$html .= '</table>';
		
		// Configurações header para forçar o download
		header ("Expires: Mon, 07 Jul 2016 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
		header ("Content-Description: PHP Generated Data" );
		// Envia o conteúdo do arquivo
		echo $html;
		exit;?>
	</body>
</html>