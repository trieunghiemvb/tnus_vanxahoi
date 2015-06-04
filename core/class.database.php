<?php

//if ( !defined('AREA') ) {
//    die('Access denied');
//}
/**
 * @started 2014-11-16
 * @by: HieuBD
 * $class: Lớp CORE xử lý cơ sở dữ liệu
 */
class Database extends Core {

    private $_dbtype;
    private $_hostname;
    private $_user;
    private $_pass;
    private $_dbname;
    private $_dbprefix;
    private $_encode;
    private $_pdo;
    public $lastId = null;

    public function __construct() {
        $this->_dbtype = DB_TYPE;
        $this->_hostname = DB_HOSTNAME;
        $this->_user = DB_USERNAME;
        $this->_pass = DB_PASSWORD;
        $this->_dbname = DB_DATABSE;
        $this->_dbprefix = DB_TABLE_PREFIX;
        $this->_encode = 'utf8';
        $this->_pdo = null;
        $this->initiate();
        parent::__construct();
    }

    /**
     * Hàm khởi tạo các thông số với giá trị truyền vào
     * @author HieuBD
     * @param <string> $host
     * @param <string> $user
     * @param <string> $password
     * @param <string> $name
     * @param <string> $table_prefix
     * @param <string> $names
     */
    public function init($dbtype, $host, $user, $password, $name, $table_prefix = '', $names = 'utf8', $pdo = null) {
        $this->_dbtype = $dbtype;
        $this->_hostname = $host;
        $this->_user = $user;
        $this->_pass = $password;
        $this->_dbname = $name;
        $this->_encode = $names;
        $this->_dbprefix = $table_prefix;
        $this->_pdo = $this->connectDB($this->_dbtype, $this->_hostname, $this->_dbname, $this->_user, $this->_pass);
        $this->initiate();
    }

    function initiate() {
        $this->_pdo = $this->connectDB($this->_dbtype, $this->_hostname, $this->_dbname, $this->_user, $this->_pass);
        $_encode = $this->_encode;
        if (!empty($this->_pdo)) {
            return $this->_pdo;
        } else {
            die('Kết nối cơ sở dữ liệu lỗi.');
        }
        return false;
    }

    public function connectDB() {
        try {
            // Thuc hien ket noi den database
            $conn = new PDO("$this->_dbtype:host=$this->_hostname;dbname=$this->_dbname", $this->_user, $this->_pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Luu thong tin vao trong $instances de tranh trung lap
            $conn->exec("SET character_set_results=$this->_encode");
            $conn->query("SET NAMES $this->_encode");
            return $conn;
        } catch (PDOException $e) {
            die('Lỗi kết nối tới Database: ' . $e->getMessage());
        }
    }

    private function setWhere($where) {
        if (is_array($where)) {
            ksort($where);
            $whereDetails = NULL;
            foreach ($where as $key => $value) {
                if (is_array($value) && isset($value[1])) {
                    $whereDetails .= " AND $key $value[1] :_$key";
                } else {
                    $whereDetails .= " AND $key = :_$key";
                }
            }
            $whereDetails = ltrim($whereDetails, ' AND ');
            return " WHERE " . $whereDetails;
        } else if ($where != '') {
            return " WHERE " . $where;
        } else {
            return '';
        }
    }

    private function bindWhere($stmt, $where) {
        if (is_array($where)) {
            ksort($where);
            foreach ($where as $key => $value) {
                if (is_array($value) && isset($value[1]) && strtolower($value[1]) == 'like') {
                    $stmt->bindValue(":_$key", "%{$value[0]}%", PDO::PARAM_STR);
                } else if (is_array($value)) {
                    if (is_int($value[0])) {
                        $stmt->bindValue(":_$key", $value[0], PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue(":_$key", $value[0], PDO::PARAM_STR);
                    }
                } else {
                    if (is_int($value)) {
                        $stmt->bindValue(":_$key", $value, PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue(":_$key", $value, PDO::PARAM_STR);
                    }
                }
            }
        }
    }

    /**
     * Hàm xử lý truy vấn
     * @param string $sql Câu lệnh truy vấn. Ex: "SELECT * FROM user"
     * @param array $params Các tham số truyền vào. Ex: array('id'=>1, 'name'=>'abc')
     * @param PDO::FETCH $fetchMode Định dạng dữ liệu trả lại
     * @return boolean 
     */
    public function query($sql, $params = array(), $fetchMode = PDO::FETCH_ASSOC) {
        try {
            $stmt = $this->_pdo->prepare($sql);
            foreach ($params as $key => $value) {
                if (is_int($value)) {
                    $stmt->bindValue("$key", $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue("$key", $value, PDO::PARAM_STR);
                }
            }
            $stmt->execute();
            if ('SELECT' == mb_strtoupper(substr($sql, 0, 6))) {
                $data = $stmt->fetch($fetchMode);
                return $data;
            } else
                return true;
        } catch (PDOException $e) {
            echo '[query] Lỗi:' . $sql . '<br />' . $e;
            return false;
        }
    }

    /**
     * Query all by $sql structure
     * Insert by Duyld2108 - 18/12/2014
     * @param  [type] $sql    [description]
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function queryAll($sql, $params = array(), $fetchMode = PDO::FETCH_ASSOC) {
        try {
            $stmt = $this->_pdo->prepare($sql);
            foreach ($params as $key => $value) {
                if (is_int($value)) {
                    $stmt->bindValue("$key", $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue("$key", $value, PDO::PARAM_STR);
                }
            }
            $stmt->execute();
            $data = $stmt->fetchAll($fetchMode);
            //print_r($data);
            return $data;
        } catch (PDOException $e) {
            echo '[query] Lỗi:' . $sql . '<br />' . $e;
            return false;
        }
    }

    /**
     * 
     * @param string $table
     * @param type $data
     * @return boolean
     */
    public function insert($table, $data = array()) {
        if (empty($data) || !is_array($data)) {
            echo 'Dữ liệu cần chèn không hợp lệ';
            return false;
        }

        $table = $this->_dbprefix . $table;
        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));
        $str_insert = "INSERT INTO {$table} ({$fieldNames}) VALUES ({$fieldValues})";

        try {
            $stmt = $this->_pdo->prepare("INSERT INTO {$table} ({$fieldNames}) VALUES ({$fieldValues})");
            foreach ($data as $key => $value) {
                if (is_int($value)) {
                    $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
                }
            }
            $stmt->execute();
            $this->lastId = $this->_pdo->lastInsertId();
            return true;
        } catch (PDOException $e) {
            echo '[insert] Lỗi:' . $str_insert . '<br />' . $e;
            return false;
        }
    }

    public function update($table, $data, $where = "") {
        if (empty($data) || !is_array($data)) {
            echo 'Dữ liệu cập nhật không hợp lệ';
            return false;
        }
        ksort($data);
        $table = $this->_dbprefix . $table;
        $fieldDetails = NULL;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :$key, ";
        }
        $fieldDetails = rtrim($fieldDetails, ', ');
        $whereDetails = self::setWhere($where);
        $str_update = "UPDATE {$table} SET $fieldDetails $whereDetails";
        try {
            $stmt = $this->_pdo->prepare($str_update);
            foreach ($data as $key => $value) {
                if (is_int($value)) {
                    $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
                }
            }
            self::bindWhere($stmt, $where);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo '[update] Lỗi:' . $str_update . '<br />' . $e;
            return false;
        }
    }

    public function delete($table, $where) {
        $table = $this->_dbprefix . $table;
        if (!empty($where)) {
            $whereDetails = self::setWhere($where);
            $str_delete = "DELETE FROM {$table} $whereDetails ";
            try {
                $stmt = $this->_pdo->prepare($str_delete);
                self::bindWhere($stmt, $where);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                echo '[delete] Lỗi:' . $str_delete . '<br />' . $e;
                return false;
            }
        }
    }

    public function getValue($table, $column, $where, $fetchMode = PDO::FETCH_ASSOC) {
        $table = $this->_dbprefix . $table;
        $whereDetails = self::setWhere($where);
        $str_sql = "SELECT $column FROM $table $whereDetails";
        try {
            $stmt = $this->_pdo->prepare($str_sql);
            self::bindWhere($stmt, $where);
            $stmt->execute();
            $result = $stmt->fetch($fetchMode);
//            print_r($result);
            return $result;
        } catch (PDOException $e) {
            echo '[getValue] Lỗi:' . $str_sql . '<br />' . $e;
            return false;
        }
    }

    public function getRow($table, $columns = "*", $where, $fetchMode = PDO::FETCH_ASSOC) {
        $table = $this->_dbprefix . $table;
        $whereDetails = self::setWhere($where);
        $str_sql = "SELECT $columns FROM $table $whereDetails";
        try {
            $stmt = $this->_pdo->prepare($str_sql);
            self::bindWhere($stmt, $where);
            $stmt->execute();
            return $stmt->fetch($fetchMode);
        } catch (PDOException $e) {
            echo '[getRow] Lỗi:' . $str_delete . '<br />' . $e;
            return false;
        }
    }

    public function getRowsDistinct($table, $columns = "*", $where = "", $fetchMode = PDO::FETCH_ASSOC) {
        $table = $this->_dbprefix . $table;
        $whereDetails = self::setWhere($where);
        $str_sql = "SELECT DISTINCT $columns FROM $table $whereDetails";
        try {
            $stmt = $this->_pdo->prepare($str_sql);
            self::bindWhere($stmt, $where);
            $stmt->execute();
            $data = $stmt->fetchAll($fetchMode);
            return $data;
        } catch (PDOException $e) {
            echo '[getRows] Lỗi:' . $str_sql . '<br />' . $e;
            return false;
        }
    }

    /**
     * Hàm lấy nhiều dòng dữ liêu trong bảng
     * @param string $table Tên bảng dữ liệu. Ex: 'product'
     * @param String $columns Các cột cần lấy
     * @param string| array $where Điều kiện lấy (xâu hoặc mảng). Ex: " id=1 "; array('id'=>1)
     * @param type $fetchMode Kiểu dữ liệu trả về
     * @return boolean biến kiểm tra kết quả 
     */
    public function getRows($table, $columns = "*", $where = "", $fetchMode = PDO::FETCH_ASSOC) {
        $table = $this->_dbprefix . $table;
        $whereDetails = self::setWhere($where);
        $str_sql = "SELECT $columns FROM $table $whereDetails";
        try {
            $stmt = $this->_pdo->prepare($str_sql);
            self::bindWhere($stmt, $where);
            $stmt->execute();
            $data = $stmt->fetchAll($fetchMode);
            return $data;
        } catch (PDOException $e) {
            echo '[getRows] Lỗi:' . $str_sql . '<br />' . $e;
            return false;
        }
    }

    /**
     * Join 2 tables
     * Insert by Duyld2108 - 18/12/2014
     * @param  [type] $sql    [description]
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function join2Table($table1, $column1 = '*', $table2, $column2 = '*', $on, $where = '', $param, $fetchMode = PDO::FETCH_ASSOC) {
        $table1 = $this->_dbprefix . $table1;
        $table2 = $this->_dbprefix . $table2;
        if ($column1 != '*') {
            $column1 = explode(',', $column1);
            $column1 = implode(', tb1.', $column1);
        }
        if ($column2 != '*') {
            $column2 = explode(',', $column2);
            $column2 = implode(', tb2.', $column2);
        }
        $whereDetails = self::setWhere($where);
        $str_sql = "SELECT tb1.{$column1}, tb2.{$column2} FROM {$table1} tb1 JOIN {$table2} tb2 ON tb1.{$on[0]} = tb2.{$on[1]} {$whereDetails}";
        $stmt = $this->_pdo->prepare($str_sql);
        $stmt->execute($param);
        $data = $stmt->fetchAll($fetchMode);
        return $data;
    }

    private function getLastIdInsert($table) {
        $str_sql = "SELECT MAX(id) AS `lastid` FROM $table";
        $data = $this->query($str_sql);
        return $data['lastid'];
    }

    /**
     * Thực thi truy vấn dựa vào câu lệnh SQL
     * Inserted by Duyld2108 - 28/12/2014
     * @param string $sql Câu lệnh truy vấn
     * @param array $param Mảng dữ liệu execute
     * @return bool
     */
    public function executesql($sql, $param) {
        try {
            $stmt = $this->_pdo->prepare($sql);
            $stmt->execute($param);
            return true;
        } catch (PDOException $e) {
            echo '[ExecuteSQL] Error: ' . $sql . '<br/>' . $e;
            return false;
        }
    }

    /**
     * Dem so dong
     * Inserted by Duyld2108 - 28/12/2014
     * @param string $table Table name
     * @param string $column Column name
     * @param string $where Search conditions
     * @param array $param
     * @return int
     */
    public function countRows($table, $column = '*', $where = "", $param = null) {
        try {
            $table = $this->_dbprefix . $table;
            $sql = "SELECT COUNT($column) AS total FROM $table";
            if ($where)
                $sql .= " WHERE $where";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->execute($param);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            echo '[ExecuteSQL] Error: ' . $sql . '<br/>' . $e;
            return false;
        }
    }

}

?>