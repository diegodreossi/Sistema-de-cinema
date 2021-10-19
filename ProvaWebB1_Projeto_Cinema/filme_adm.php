<?php
include('config/conexao.php');

//Verificando se o parâmetro Nome foi enviado pelo get_browser
if(isset($_GET['Id'])){
    //Limpa os dados de sql injection
    
    $Id = mysqli_real_escape_string($conn,$_GET['Id']);
    
    //Monta a query
	$sql = "SELECT * FROM filme WHERE Id = $Id;";
    //echo $sql;
    //Executa a query e guarda em $result
	$result = mysqli_query($conn,$sql);
    //echo $result;

	//Busca o resultado (uma linha) em forma de vetor
	$filme = mysqli_fetch_assoc($result);
    
    
    mysqli_free_result($result);

    //-------------------Testes-------------------
    $tam =  "SELECT COUNT(*) FROM sessao WHERE Id_filme = $Id;";
    $restam = mysqli_query($conn,$tam);
    $tamanho_sessoes =  mysqli_fetch_assoc($restam);
    //echo $tamanho_sessoes['COUNT(*)'];

    //-----------------Sessões-------------------
    $se_sql = "SELECT * FROM sessao WHERE Id_filme = $Id;";
    $se_result = mysqli_query($conn,$se_sql);
    $sessoes =  mysqli_fetch_all($se_result, MYSQLI_ASSOC);//Mais de 1 linha
    mysqli_free_result($se_result);
    //-------------------------------------------
   
    //echo $sessoes;
		
	mysqli_close($conn);
}else{
    //echo 'Erro';
}

//Deletar filme do banco de dados
if(isset($_POST['deletar'])){
    //Limpando a query
    $Id = mysqli_real_escape_string($conn,$_POST['Id']);

    
 
    //Montando a query
    $del_lugares = "DELETE FROM lugar WHERE Id_sessao IN (SELECT Id_sessao FROM `sessao` WHERE Id_filme = $Id);";
    $del_sessoes = "DELETE FROM sessao WHERE Id_filme = $Id;";
    $sql = "DELETE FROM filme WHERE Id = $Id";
 
    //Removendo do BD
    if(mysqli_query($conn,$del_lugares)){
     if(mysqli_query($conn,$del_sessoes)){    
      if(mysqli_query($conn,$sql)){
        //Sucesso
        header('Location: index.php');
    }else{
        echo 'Deletar filme query error'.mysqli_error($conn);
    }
    }else{
        echo 'Problema deleção de sessoes'.mysqli_error($conn);;
    }

    }else{
        echo 'Problema deleção de lugares'.mysqli_error($conn);;
    }
 
 }

?>

<!DOCTYPE html>

<?php include('templates/header.php'); ?>

<div style="padding-left: 50px; padding-right: 50px;  padding-top:30px">
    <div  style="border: 2px solid; color:black;     background-color:white">
        <div class="row" style="border: 5px solid; color:grey; display:flex; ">
            
            <div class="column" style="border: 5px solid; color:red; width:150px; height:200px">
            
            <h6>Imagem</h6>
            

            
            </div>
            <!--Segunda coluna--> 
            <div class="column card-content" style="font-size: 15px; color: black">
                <p>Nome: <?php echo htmlspecialchars($filme['Nome']) ?></p>
                <p>Ano: <?php echo htmlspecialchars($filme['Ano']) ?> </p>
                <p>Diretor: <?php echo htmlspecialchars($filme['Diretor']) ?></p>
                <p>Genêro: <?php echo htmlspecialchars($filme['Genero']) ?> </p>
                <p>Estúdio: <?php echo htmlspecialchars($filme['Estudio']) ?></p>
                <p>Duração: <?php echo htmlspecialchars($filme['Duracao']) ?> minutos</p>
                <p>Protagonista: <?php echo htmlspecialchars($filme['Protagonista']) ?></p>
                <p>Sinopse: <?php echo htmlspecialchars($filme['Sinopse']) ?></p>
                <p>Status: <?php echo htmlspecialchars($filme['Status_filme']) ?></p>
            </div>  
            
            

        </div>
        <!--Botôes -->
        

        
                
    </div>
<br>

<!--Botôes-->
<div class="center-align" >            
        
<!--Botão de editar-->
    <form action="editar_filme.php" method="POST" class="center" style="display: inline-block;">
       <input type="hidden" name="Id" value = "<?php echo $filme['Id'];?>">
       <input type = "submit" name ="editar" value = "Editar" class="btn green  z-depth-0"> 
            
    </form> 

    <!--Botão de excluir -->
    <form action="filme_adm.php" method="POST" class="center" style="display: inline-block;">
        <input type="hidden" name="Id" value = "<?php echo $filme['Id'];?>">
        <input type = "submit" name ="deletar" value = "Deletar" class="btn red z-depth-0"> 
    </form>  

    <!--Botão de adicionar sessão-->
    <form action="addsessao.php" method="POST" class="center" style="display: inline-block;">
        <input type="hidden" name="Id" value = "<?php echo $filme['Id'];?>">
        <input type = "submit" name ="adses" value = "Adicionar sessão" class="btn brand z-depth-0"> 
            
    </form>
</div>  
</div>




<?php include('templates/footer.php'); ?>

</html>