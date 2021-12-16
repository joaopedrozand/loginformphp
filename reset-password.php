<?php
//inicia sessao
session_start();
 
// verifica se ja esta logado, se nao redireciona para logar
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// arq de conexao ao banco
require_once "conex.php";
 
// define e inicia as variaveis com valor em branco
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// processamento de dados
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // valida a nova senha
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // valida se a confirmacao esta igual
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    //verifica erros antes de enviar ao banco
    if(empty($new_password_err) && empty($confirm_password_err)){
        // query para atualizar a senha
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // atribui as variaveis
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // atribui os parametros
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // tenta executar a query
            if(mysqli_stmt_execute($stmt)){
                // se der certo redireciona para logar novamente
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Erro! tente novamente mais tarde.";
            }

            // fecha o statment
            mysqli_stmt_close($stmt);
        }
    }
    
    // fecha a conexao
    mysqli_close($link);
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir senha</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="wrapper">
        <div class="form">
            <h2>Redefinir senha</h2>
            <p>Por favor, preencha os campos para redefinir sua senha.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <div class="form-group">
                    <label>Nova senha</label>
                    <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                    <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirme a nova senha</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Alterar">
                    <a class="btn btn-link ml-2" href="welcome.php">Cancel</a>
                </div>
            </form>
        </div>
    </div>    
</body>
</html>