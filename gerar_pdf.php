<?php	
    session_start();
    include_once("services/sql_conexao.php");
    $conexao = new conexao();
    $conexao->sql_conexao();    
    
    $id = $_SESSION['ID_VenFor'];
    $top = $_POST['qtd_registro'];

    $html = '<table border=1 ';    
    
    $html .= '<thead>';  
    

    $html .= '<tr>';
    $html .= '<th>Cotação </th>';
    $html .= '<th>Descrição </th>';
    $html .= '<th>QTDE</th>';
    $html .= '<th>Preço</th>';
    $html .= '<th>Entrega</th>';
    $html .= '<th>IPI</th>'; 
    $html .= '<th>ICMS</th>'; 
    $html .= '<th>NCM</th>';
    $html .= '<th>Data Orçamento</th>';
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
    
    //$sql_usuario = " ";
    $sql_orcamento =  "SELECT TOP $top * FROM  dbo.PT_Cotacao WHERE ID_Fornecedor =  $id order by DT_DataInserido desc ";	
    $query = sqlsrv_query($conexao->con, $sql_orcamento);
	while($array = sqlsrv_fetch_array($query)){
        $html .= '<tr>';
        $html .= '<td>'. $array['ID_Cotacao'] . "</td>";
        $html .= '<td>'. $array['DE_Descricao'] . "</td>";
        $html .= '<td>'. $array['DE_Qtde'] . "</td>";  
        $html .= '<td>'. $array['DE_Preco'] . "</td>";  
        $html .= '<td>'. $array['DE_EntregaDias'] . "</td>";
        $html .= '<td>'. $array['DE_Ipi'] . "</td>";
        $html .= '<td>'. $array['DE_ICMS'] . "</td>";        
        $html .= '<td>'. $array['DE_NCM'] . "</td>";
        $html .= '<td>'. $array['DT_DataInserido']->format('d/m/Y H:i:s') . "</td>";      
    
        
        
        $html .= '</tr>';
	}
	
	$html .= '</tbody>';
	$html .= '</table';

	
	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF();
	
	// Carrega seu HTML
	$dompdf->load_html('
			<h1 style="text-align: center;">Relatório de Orçamentos</h1>
			'. $html .'
		');

	//Renderizar o html
	$dompdf->render();

	//Exibibir a página
	$dompdf->stream(
		"relatorio_user.pdf", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>