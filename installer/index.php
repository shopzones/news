<?php
// installer/index.php - Simple installation wizard
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['db_host'];
    $dbname = $_POST['db_name'];
    $user = $_POST['db_user'];
    $pass = $_POST['db_pass'];
    $adminEmail = $_POST['admin_email'];
    $adminPass = $_POST['admin_pass'];

    // Create database config
    $config = "<?php\n";
    $config .= "class Database {\n";
    $config .= "    private \$host = \"$host\";\n";
    $config .= "    private \$dbname = \"$dbname\";\n";
    $config .= "    private \$username = \"$user\";\n";
    $config .= "    private \$password = \"$pass\";\n";
    $config .= "    private \$conn;\n\n";
    $config .= "    public function getConnection() {\n";
    $config .= "        \$this->conn = null;\n";
    $config .= "        try {\n";
    $config .= "            \$this->conn = new PDO(\"mysql:host=\" . \$this->host . \";dbname=\" . \$this->dbname . \";charset=utf8mb4\", \$this->username, \$this->password);\n";
    $config .= "        } catch (PDOException \$e) {\n";
    $config .= "            die(\"Connection failed\");\n";
    $config .= "        }\n";
    $config .= "        return \$this->conn;\n";
    $config .= "    }\n";
    $config .= "}\n";

    file_put_contents('../config/database.php', $config);

    // Success
    $_SESSION['installed'] = true;
    header('Location: success.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>News Portal - Installer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark border-secondary">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4">News Portal Installer</h3>
                    <form method="post">
                        <h6 class="text-danger">Database Configuration</h6>
                        <div class="mb-3"><input type="text" name="db_host" class="form-control" placeholder="localhost" value="localhost" required></div>
                        <div class="mb-3"><input type="text" name="db_name" class="form-control" placeholder="news_portal" value="news_portal" required></div>
                        <div class="mb-3"><input type="text" name="db_user" class="form-control" placeholder="root" required></div>
                        <div class="mb-3"><input type="text" name="db_pass" class="form-control" placeholder="password"></div>
                        
                        <h6 class="text-danger mt-4">Admin Account</h6>
                        <div class="mb-3"><input type="email" name="admin_email" class="form-control" placeholder="admin@example.com" required></div>
                        <div class="mb-4"><input type="password" name="admin_pass" class="form-control" placeholder="Admin Password" required></div>
                        
                        <button type="submit" class="btn btn-danger w-100 py-2">Install Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>