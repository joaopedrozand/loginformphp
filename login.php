<?php
//inicializar sessao
session_start();
 
// checar se já está logado, se sim, redirecionar para a pagina
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// arquivo de login
require_once "conex.php";
 
// inicializar campos em branco
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// processamento de informacoes
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // ver se usuario esta vazio
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // ver se a senha esta vazia
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // validar credenciais
    if(empty($username_err) && empty($password_err)){
        // query select
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // atribui os parametros
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;
            
            // tentativa de executar o comando
            if(mysqli_stmt_execute($stmt)){
                // armazena o return
                mysqli_stmt_store_result($stmt);
                
                // verifica se o usuario existe e depois a senha
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // atribui os parametros
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // se a senha estiver correta, inicializa a sessao
                            session_start();
                            
                            // armazena os dados na sessao
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // redireciona para a pagina
                            header("location: welcome.php");
                        } else{
                            // credenciais invalidas
                            $login_err = "Usuário ou senha inválido.";
                        }
                    }
                } else{
                    // usuario nao existe
                    $login_err = "Usuário ou senha inválido";
                }
            } else{
                echo "Erro! tente novamente mais tarde.";
            }

            // fecha
            mysqli_stmt_close($stmt);
        }
    }
    
    // fecha
    mysqli_close($link);
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="wrapper">
        <div class="form">
            <h2>Login</h2>
            <p>Preencha com seus dados para login.</p>
            <form name="formlogin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <input type="text" name="username" id="fa" placeholder="Usuário" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group">
                    <input type="password" name="password" id="fa" placeholder="Senha" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <?php 
            if(!empty($login_err)){
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }        
            ?>
            <script type="text/javascript">
                document.formlogin.reset();
            </script>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" style="float: right;width:100px;" value="Login">
                </div>
                <p>Não tem uma conta? <a href="create.php">Criar agora</a>.</p>
            </form>
        </div>
    </div>
</body>
</html>