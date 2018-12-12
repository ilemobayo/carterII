<?php
class Tracks
{

    function getConnection() {
        try {
            $db_username = "DATABASE_NAME";
            $db_password = "********";
            $conn = new PDO('mysql:host=localhost;dbname=root', $db_username, $db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
        return $conn;
    }
    
    function deleteUser($id) {
        $sql = "DELETE FROM restAPI WHERE id=:id";
        try {
            $dbCon = getConnection();
            $stmt = $dbCon->prepare($sql);
            $stmt->bindParam("id", $id);
            $status = $stmt->execute();
            $dbCon = null;
            echo json_encode($status);
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

    function updateUser($id) {
        global $app;
        $req = $app->request();
        $paramName = $req->params('name');
        $paramEmail = $req->params('email');

        $sql = "UPDATE restAPI SET name=:name, email=:email WHERE id=:id";
        try {
            $dbCon = getConnection();
            $stmt = $dbCon->prepare($sql);
            $stmt->bindParam("name", $paramName);
            $stmt->bindParam("email", $paramEmail);
            $stmt->bindParam("id", $id);
            $status = $stmt->execute();

            $dbCon = null;
            echo json_encode($status);
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';         }
    }

    function addUser() {
        global $app;
        $req = $app->request(); // Getting parameter with names
        $paramName = $req->params('name'); // Getting parameter with names
        $paramEmail = $req->params('email'); // Getting parameter with names

        $sql = "INSERT INTO restAPI (`name`,`email`,`ip`) VALUES (:name, :email, :ip)";
        try {
            $dbCon = getConnection();
            $stmt = $dbCon->prepare($sql);
            $stmt->bindParam("name", $paramName);
            $stmt->bindParam("email", $paramEmail);
            $stmt->bindParam("ip", $_SERVER['REMOTE_ADDR']);
            $stmt->execute();
            $user->id = $dbCon->lastInsertId();
            $dbCon = null;
            echo json_encode($user);
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

    function findByName($query) {
        $sql = "SELECT * FROM restAPI WHERE UPPER(name) LIKE :query ORDER BY name";
        try {
            $dbCon = getConnection();
            $stmt = $dbCon->prepare($sql);
            $query = "%".$query."%";
            $stmt->bindParam("query", $query);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbCon = null;
            echo '{"user": ' . json_encode($users) . '}';
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}'; 
        }
    }

    function getUser($id) {
        $sql = "SELECT `name`,`email`,`date`,`ip` FROM restAPI WHERE id=:id";
        try {
            $dbCon = getConnection();
            $stmt = $dbCon->prepare($sql);  
            $stmt->bindParam("id", $id);
            $stmt->execute();
            $user = $stmt->fetchObject();  
            $dbCon = null;
            echo json_encode($user); 
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}'; 
        }
    }

    function getUsers() {
        $sql_query = "select `name`,`email`,`date`,`ip` FROM restAPI ORDER BY name";
        try {
            $dbCon = getConnection();
            $stmt   = $dbCon->query($sql_query);
            $users  = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbCon = null;
            echo '{"users": ' . json_encode($users) . '}';
        }
        catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }    
    }
}

$app->get('/users', 'getUsers'); // Using Get HTTP Method and process getUsers function
$app->get('/users/:id',    'getUser'); // Using Get HTTP Method and process getUser function
$app->get('/users/search/:query', 'findByName'); // Using Get HTTP Method and process findByName function
$app->post('/users', 'addUser'); // Using Post HTTP Method and process addUser function
$app->put('/users/:id', 'updateUser'); // Using Put HTTP Method and process updateUser function
$app->delete('/users/:id',    'deleteUser'); // Using Delete HTTP Method and process deleteUser function
$app->run();


?>