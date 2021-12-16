<?php
// arquivo de configuração do banco
require_once "conex.php";
 
// define e inicializa as variaveis em branco
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// processamento de dados
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // valida o usuario
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // query sql
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // atribui as variaveis
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // atribui os parametros
            $param_username = trim($_POST["username"]);
            
            // tenta executar a query
            if(mysqli_stmt_execute($stmt)){
                /* armazena o resultado */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Esse usuário já existe.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Erro! tente novamente mais tarde.";
            }

            // fecha a query
            mysqli_stmt_close($stmt);
        }
    }
    
    // valida a senha
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor, digite uma senha.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "As senhas devem ter no mínimo 6 digitos.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // confirma se as senhas sao iguais
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor confirme a senha.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Senhas não são iguais.";
        }
    }
    
    // verifica entrada antes de enviar ao banco
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // query sql
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // atribui as variaveis
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // atribui os parametros
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // criptografa a senha
            
            // tenta executar a query
            if(mysqli_stmt_execute($stmt)){
                // Redireciona para pagina de login
                header("location: login.php");
            } else{
                echo "Erro! tente novamente mais tarde.";
            }

            // fecha query
            mysqli_stmt_close($stmt);
        }
    }
    
    // fecha conexao
    mysqli_close($link);
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="wrapper">
        <div class="form">
            <h2>Registrar</h2>
            <p>Preencha com seus dados para criar a conta.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Usuário</label>
                    <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirme a senha</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary"  value="Criar">
                    <input type="reset" class="btn btn-secondary ml-2" value="Limpar campos">
                </div>
                <p>Já tem uma conta? <a href="login.php">Acesse aqui</a>.</p>
            </form>
        </div>
    </div>    
</body>
</html>