<?php
include_once("sql_conexao.php");


class cotacao extends conexao
{
    public function declinadoCotacao($declinadoTexto,$idCotacao,$idFornecedor)
    {
        $this->sql_conexao();

        $sql = "UPDATE dbo.PT_Cotacao SET DE_SolicitacaoStatus = 5 ,DE_DeclinadoTexto = '$declinadoTexto', DT_DataRespondido = GETDATE(),
        DE_Preco = 0, DE_Ipi = 0,  DE_DeclinadoSite = 1,  DE_Portal = 0 where ID_Cotacao = '$idCotacao' and ID_Fornecedor = '$idFornecedor'  ";

        $query = sqlsrv_query($this->con, $sql);
        
        if($query == true)
        {
            header('Location: http://localhost:8089/portal/portal_php/portal/index.php?alert=success&msg=DECLINADO COM SUCESSO');

        }else{
            header('Location: http://localhost:8089/portal/portal_php/portal/index.php?alert=danger&msg=ERRO AO DECLINAR');

        }
    }

    public function validateCotacao($precoCotacao, $diasEntrega, $ipi, $icms, $dataValidade, $infComprador, $condPagamento)
    {
        echo $dataValidade;
    }

}

$cotacao = new cotacao();


if(!empty($_GET['idCotacao'])){
    $idCotacao = $_GET['idCotacao'];

    $declinadoTexto = $_GET['declinadoTexto'];
    
    $idFornecedor = $_GET['idFornecedor'];

    $cotacao->declinadoCotacao($declinadoTexto,$idCotacao, $idFornecedor);
}

if(!empty($_GET['precoCotacao'])){
    $precoCotacao = $_GET['precoCotacao'];
    $diasEntrega = $_GET['diasEntrega'];
    $ipi = $_GET['ipi'];
    $icms = $_GET['icms'];
    $dataValidade = $_GET['dataValidade'];
    $infComprador = $_GET['infComprador'];
    $condPagamento = $_GET['condPagamento'];

    $cotacao->validateCotacao($precoCotacao,$diasEntrega,$ipi,$icms,$dataValidade,$infComprador,$condPagamento);
}else{
    header('Location: http://localhost:8089/portal/portal_php/portal/index.php?alert=danger&msg=ERRO PREÇO DA COTAÇÃO VAZIO');
}

?>
