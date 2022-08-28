<?php
 require('db/conexao.php');
//  comando para deletar
$sql = $pdo->prepare("DELETE FROM clientes WHERE id=?");
$sql->execute(array(4));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>inserindo dados</title>
    <style>
        table{
            border-collapse:collapse;
            width:100%;
        }
        th,td{
            padding:10px;
            text-align:center;
            border:1px solid #ccc;
        }
        .oculto{
            display:none;
        }
        button{
            cursor: pointer;
        }
    </style>
</head>
<body>
        <div>
            <h1>Tabela de clientes</h1>
        <form id="form_salva" method="post">
            <input type="text" name="nome">
            <input type="email" name="email">
            <button type="submit" name="salvar">Salvar</button>
        </form>
        <form class="oculto" id="form_atualiza" method="post">
            <input type="hidden" id="id_editado" name="id_editado">
            <input type="text" id="nome_editado" name="nome_editado">
            <input type="email" id="email_editado" name="email_editado">
            <button type="submit" id="atualizar" name="atualizar">Atualizar</button>
            <button id="cancelar" name="cancelar">cancelar</button>
        </form>
        <form class="oculto" id="form_deleta" method="post">
            <input type="hidden" id="id_deleta" name="id_deleta">
            <strong>Tem certeza que deseja deletar o cliente <span id="cliente"></span>?</strong>
            <button type="submit" id="deletar" name="deletar">Confimar</button>
            <button id="cancelar_delete" name="cancelar_delete">cancelar</button>
        </form>
        </br>
        <?php 
            //inserir um dado no banco modo simples
            //  $sql = $pdo->prepare("INSERT INTO clientes VALUES (null, 'samuel','test@test.com','27/08/2022')");
            //  $sql->execute();

            // modo correto anti sql injection
            if(isset($_POST['salvar'])&& isset($_POST['nome'])&& isset($_POST['email'])){
                $nome=limparPost($_POST['nome']);
                $email=limparPost($_POST['email']);
                $data=date('d/m/Y');

                //validação de campo vazio
                if($nome=="" || $nome==null){
                    echo "<strong style='color:red'>Nome não pode ser vazio</strong>"; 
                    exit();
                }
                if($nome=="" || $nome==null){
                    echo "<strong style='color:red'>Nome não pode ser vazio</strong>";
                    exit();
                }
                //validação de nome e email
                if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
                    echo "<strong style='color:red'>Somente permitido letras e espaços em branco para o nome</strong>";
                    exit();
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "<strong style='color:red'>Formato de email inválido!</strong>";
                    exit();
                }
                
                $sql = $pdo->prepare("INSERT INTO clientes VALUES (null, ?,?,?)");
                $sql->execute(array($nome, $email, $data));

                echo "<strong style='color:green'>Cliente inserido com sucesso!</strong>";
            }
        ?>
        <?php 
        //processo de atualização
        if(isset($_POST['atualizar'])&& isset($_POST['id_editado'])&& isset($_POST['nome_editado'])&& isset($_POST['email_editado'])){
            $id=limparPost($_POST['id_editado']);
            $nome=limparPost($_POST['nome_editado']);
            $email=limparPost($_POST['email_editado']);
            //validação de campo vazio
            if($nome=="" || $nome==null){
                echo "<strong style='color:red'>Nome não pode ser vazio</strong>"; 
                exit();
            }
            if($nome=="" || $nome==null){
                echo "<strong style='color:red'>Nome não pode ser vazio</strong>";
                exit();
            }
            //validação de nome e email
            if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
                echo "<strong style='color:red'>Somente permitido letras e espaços em branco para o nome</strong>";
                exit();
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<strong style='color:red'>Formato de email inválido!</strong>";
                exit();
            }
             //comando para atualizar
            $sql = $pdo->prepare("UPDATE clientes SET nome=?, email=? WHERE id=?");
            $sql->execute(array($nome, $email, $id));
            echo "Atualizado ".$sql->rowCount()." registro!";
        }
        ?>
        <?php 
        if(isset($_POST['deletar']) && ($_POST['id_deleta'])){
            $id=limparPost($_POST['id_deleta']);
            //  comando para deletar
            $sql = $pdo->prepare("DELETE FROM clientes WHERE id=?");
            $sql->execute(array($id));
            echo "Cliente deletado com sucesso!";
        }
        ?>
        <?php 
            //selecionar dados da tabela
            $sql = $pdo->prepare("SELECT * FROM clientes");
            $sql->execute();
            $dados = $sql->fetchAll();
        ?>
        <?php
        $valor2="";
        // echo $valor2;
        if(count($dados) >0){
            echo "<table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>";
            foreach($dados as $chave => $valor){
                echo "
                <tr>
                    <th>".$valor['id']."</th>
                    <th>".$valor['nome']."</th>
                    <th>".$valor['email']."</th>
                    <th><button href='#' class='btn-atualizar' data-id='".$valor['id']."' data-nome='".$valor['nome']."' data-email='".$valor['email']."'>Atualizar</button> | <button href='#' class='btn-deletar' data-id='".$valor['id']."' data-nome='".$valor['nome']."' data-email='".$valor['email']."'>Deletar</button></th>
                </tr>";
                //  $valor2 = $_GET['data-nome'];
            }
            echo "</table>";

        }else{
            echo "<p>Nenhum cliente cadastrado</p>";
        }
        ?>
        </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(".btn-atualizar").click(function(){
            var id =$(this).attr('data-id');
            var nome = $(this).attr('data-nome');
            var email = $(this).attr('data-email');

            $("#form_salva").addClass('oculto');
            $("#form_atualiza").removeClass('oculto');
            $("#form_deleta").addClass('oculto');


            $("#id_editado").val(id);
            $("#nome_editado").val(nome);
            $("#email_editado").val(email);
        });
        $(".btn-deletar").click(function(){
            var id =$(this).attr('data-id');
            var nome = $(this).attr('data-nome');
            var email = $(this).attr('data-email');

            $("#form_salva").addClass('oculto');
            $("#form_atualiza").addClass('oculto');
            $("#form_deleta").removeClass('oculto');

            $("#id_deleta").val(id);
            $("#cliente").html(nome);
        });
        $('#cancelar').click(function(){
            $("#form_salva").removeClass('oculto');
            $("#form_atualiza").addClass('oculto');
            $("#form_deleta").addClass('oculto');

        })
        $('#cancelar_delete').click(function(){
            $("#form_salva").removeClass('oculto');
            $("#form_atualiza").addClass('oculto');
            $("#form_deleta").addClass('oculto');
        })
    </script>
</body>

<!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.7.8/dist/vue.js"></script> -->
</html>
<!-- <script>
    new Vue({
        el: '#app',
        data(){
            return{
                id:"",
                nome:"",
                email:"",
            }
        },
        methods:{
            atualizar(){
                var b = document.querySelector("button")
                this.id = b.getAttribute('data-nome')
                console.log(this.id)
            }
        }
    });
</script> -->