<?php
if ( !defined('AREA') ) {
    die('Access denied');
}
/**
 * @started 2014-11-27
 * @by: HieuBD
 * @class: Lớp xử lý CSDL với công nghệ PDO
 */

class PDO {
    // properties
    private $hostname;
    private $user;
    private $pass;
    private $dbname;
    private $dbprefix;
    private $encode;
    private $connectionString;
    private $errMesseage='';
    private $isConnected=false;
    private $conn;

    // methods
    function __construct() {
        $this->hostname=DB_HOSTNAME;
        $this->user=DB_USERNAME;
        $this->pass=DB_PASSWORD;
        $this->dbname=DB_DATABSE;
        $this->dbprefix=DB_TABLE_PREFIX;
        $this->encode='utf8';
        $this->connectionString='mysql:hosts='.$this->hostname.';dbname='.$this->dbname.'';
        $this->connect($host, $user, $password, $name, $table_prefix, $names, $connectionString);
    }

    /**
     * @by HieuBD
     * @function kết nối tới Mysql trả lại đối tượng pdo
     * @param <type> $host
     * @param <type> $user
     * @param <type> $password
     * @param <type> $name
     * @param <type> $table_prefix
     * @param <type> $names
     * @param <type> $conString
     */
    private function connect($host, $user, $password, $name, $table_prefix = '', $names = 'utf8',$conString='') {
        $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $names",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_EMULATE_PREPARES => false
        );
        try {
            $this->conn = new PDO($conString, $user, $password, $options);
            $this->isConnected = true;
        } catch (Exception $e) {
            $this->errMesseage = $e->getMessage();
        }
    }

    /**
     * @by HieuBD
     * @function Hàm ngắt kết nối tới MySQL
     */
    private function disconnect(){
        $this->conn=null;
    }


}


?>
