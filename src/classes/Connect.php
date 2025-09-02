<?php
namespace Classes;

use PDO;
use Dotenv\Dotenv;
use Exception;

class Connect{

    private ?PDO $conn;

    public function __construct(){
        $this->connect();
    }

    public function connect(){
        try
        {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__).'/src');
            $dotenv->load();

            $host = $_ENV['HOST'];
            $dbname = $_ENV['DBASE'];
            $user = $_ENV['USER'];
            $password = $_ENV['PASS'];

            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", 
                $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false]);
        }
        catch(Exception $e)
        {
            error_log("Connection error ". $e->getMessage() ."". $e->getTraceAsString());
            $this->conn = null;
        }
    }

    public function getConn(){
        return $this->conn;
    }

    public function close(){
        $this->conn = null;
    }

    public function query(string $q){
        if($this->conn != null){
            try{
                $stmt = $this->conn->prepare($q);
                $stmt->execute();
                $r = $stmt.fetchAll(PDO::FETCH_ASSOC);
                return $r;
            }
            catch(Exception $e){
                error_log("query error ". $e->getMessage() ."". $e->getTraceAsString());
                return null;
            }
        }
        else{
            error_log("query error, connectin = null");
            return null;
        }
    }

}

?>