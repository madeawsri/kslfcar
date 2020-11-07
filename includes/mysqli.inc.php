<?php
class DbMySQLi extends FnBase
{
    public $host = Config::MY_HOST;
    public $database;
    public $connect_db;
    public $selectdb;
    public $db;
    public $sql;
    public $table;

    public function __construct()
    {
        return ($this->connectdb(Config::MY_DB, Config::MY_USER, Config::MY_PASS));
    }

    public function __destruct()
    {
        $this->setSQL("");
        mysqli_close($this->connect_db);
    }

    public function connectdb($db_name = "database", $user = "username", $pwd = "password")
    {
        $this->database = $db_name;
        $this->username = $user;
        $this->password = $pwd;
        $this->connect_db = mysqli_connect($this->host, $this->username, $this->password, $this->database) or $this->_error();
        $this->db = $this->connect_db;
        mysqli_query($this->connect_db, "SET NAMES utf8");
        mysqli_query($this->connect_db, "SET character_set_results=utf8");
        return true;
    }


    public function closedb()
    {
        mysqli_close($this->connect_db) or $this->_error();
    }

    public function add_db($table = "table", $data = "data", $show_sql=0)
    {
        $key = array_keys($data);
        $value = array_values($data);
        $sumdata = count($key);
        for ($i = 0; $i < $sumdata; $i++) {
            if (empty($add)) {
                $add = "(";
            } else {
                $add = $add . ",";
            }
            if (empty($val)) {
                $val = "(";
            } else {
                $val = $val . ",";
            }
            $add = $add . $key[$i];
            $val = $val . "'" . $value[$i] . "'";
        }
        $add = $add . ")";
        $val = $val . ")";
        $sql = "INSERT INTO " . $table . " " . $add . " VALUES " . $val;
        if ($show_sql) {
            return $sql;
        }
        $this->setSQL($sql);
        mysqli_query($this->connect_db, "BEGIN");
        if (mysqli_query($this->connect_db, $sql)) {
            $strSql = 'select last_insert_id() as lastId';
            $result = mysqli_query($this->connect_db, $strSql);
            while ($row = @mysqli_fetch_assoc($result)) {
                $lastId = $row['lastId'];
            }
            // Free result set
            if ($result) {
                mysqli_free_result($result);
            }
            mysqli_query($this->connect_db, "COMMIT");
            return $lastId;
        } else {
            $this->_error();
            mysqli_query($this->connect_db, "ROLLBACK");
            return false;
        }
    }

    public function insert($table = "table", $data = "data", $show_sql=0)
    {
        $key = array_keys($data);
        $value = array_values($data);
        $sumdata = count($key);
        for ($i = 0; $i < $sumdata; $i++) {
            if (empty($add)) {
                $add = "(";
            } else {
                $add = $add . ",";
            }
            if (empty($val)) {
                $val = "(";
            } else {
                $val = $val . ",";
            }
            $add = $add . $key[$i];
            $val = $val . "'" . $value[$i] . "'";
        }
        $add = $add . ")";
        $val = $val . ")";
        $sql = "INSERT INTO " . $table . " " . $add . " VALUES " . $val;
        if ($show_sql) {
            return $sql;
        }
        $this->setSQL($sql);
        mysqli_query($this->connect_db, "BEGIN");
        if (mysqli_query($this->connect_db, $sql)) {
            $strSql = 'select last_insert_id() as lastId';
            $result = mysqli_query($this->connect_db, $strSql);
            while ($row = @mysqli_fetch_assoc($result)) {
                $lastId = $row['lastId'];
            }
            // Free result set
            if ($result) {
                mysqli_free_result($result);
            }
            mysqli_query($this->connect_db, "COMMIT");
            return $lastId;
        } else {
            $this->_error();
            mysqli_query($this->connect_db, "ROLLBACK");
            return false;
        }
    }

    public function update_db($table = "table", $data = "data", $where = "where", $q=0)
    {
        $key = array_keys($data);
        $value = array_values($data);
        $sumdata = count($key);
        $set = "";
        for ($i = 0; $i < $sumdata; $i++) {
            if (!empty($set)) {
                $set = $set . ",";
            }
            $set = $set . $key[$i] . "='" . $value[$i] . "'";
        }
        $sql = "UPDATE " . $table . " SET " . $set . " WHERE " . $where;
        if ($q) {
            return $sql;
        }
        $this->setSQL($sql);
        mysqli_query($this->connect_db, "BEGIN");
        if (mysqli_query($this->connect_db, $sql)) {
            mysqli_query($this->connect_db, "COMMIT");
            return true;
        } else {
            $this->_error();
            mysqli_query($this->connect_db, "ROLLBACK");
            return false;
        }
    }

    public function update($table = "table", $set = "set", $where = "where", $show_sql = 0)
    {
        $sql = "UPDATE " . $table . " SET " . $set . " WHERE " . $where;
        if ($show_sql) {
            return $sql;
        }
        $this->setSQL($sql);
        mysqli_query($this->connect_db, "BEGIN");
        if (mysqli_query($this->connect_db, $sql)) {
            mysqli_query($this->connect_db, "COMMIT");
            return true;
        } else {
            $this->_error();
            mysqli_query($this->connect_db, "ROLLBACK");
            return false;
        }
    }

    public function del($table = "table", $where = "where", $q=0)
    {
        $sql = "DELETE FROM " . $table . " WHERE " . $where;
        $this->setSQL($sql);
        if ($q) {
            return $sql;
        }
        mysqli_query($this->connect_db, "BEGIN");
        if (mysqli_query($this->connect_db, $sql)) {
            mysqli_query($this->connect_db, "COMMIT");
            return true;
        } else {
            $this->_error();
            mysqli_query($this->connect_db, "ROLLBACK");
            return false;
        }
    }

    public function num_rows($table = "table", $field = "field", $where = "where")
    {
        if ($where == "") {
            $where = "";
        } else {
            $where = " WHERE " . $where;
        }
        $sql = "SELECT " . $field . " FROM " . $table . $where;
        $this->setSQL($sql);
        if ($res = mysqli_query($this->connect_db, $sql)) {
            $data = mysqli_num_rows($res);
            // Free result set
            if ($res) {
                mysqli_free_result($res);
            }
            return $data;
        } else {
            $this->_error();
            return false;
        }
    }
    public function getDataAll($sql)
    {
        $data = array();
        $rs = $this->select_query($sql);
        while ($ar = $this->fetch($rs)) {
            $data[] = $ar;
        }
        // Free result set
        if ($rs) {
            mysqli_free_result($rs);
        }
        return $data;
    }
    public function select_query($sql = "sql")
    {
        $this->setSQL($sql);
        if ($res = mysqli_query($this->connect_db, $sql)) {
            return $res;
        } else {
            $this->_error();
            return false;
        }
    }

    public function execs($sql)
    {
        $this->setSQL($sql);
        return  $this->connect_db->multi_query($sql);
    }

    public function rows($sql = "sql")
    {
        $this->setSQL($sql);
        if ($res = mysqli_num_rows($sql)) {
            return $res;
        } else {
            $this->_error();
            return false;
        }
    }

    public function fetch($sql = "sql")
    {
        $this->setSQL($sql);
        if ($res = @mysqli_fetch_assoc($sql)) {
            return $res;
        } else {
            $this->_error();
            return false;
        }
    }

    public function fetch_row($sql = "sql")
    {
        $this->setSQL($sql);
        if ($res = mysqli_fetch_row($sql)) {
            return $res;
        } else {
            $this->_error();
            return false;
        }
    }


    public function _error()
    {
        $this->error[] = mysqli_errno($this->connect_db);
    }

    public function exec($sql)
    {
        $this->setSQL($sql);
        $rs = $this->select_query($sql);
        while ($ar = $this->fetch($rs)) {
            $data[] = $ar;
        }
        return $data;
    }

    public function getMYSQLValues($a, $b, $c = " 1=1 ")
    {
        $sql = "select {$b}  from {$a} where {$c} ";
        $this->setSQL($sql);
        $rs = $this->select_query($sql);
        $ar = $this->fetch($rs);
        // Free result set
        if ($rs) {
            mysqli_free_result($rs);
        }
        return $ar;
    }

    public function getMYSQLValue($a, $b, $c = " 1=1 ", $q=0)
    {
        $sql = "select {$b} as val from {$a} where {$c} ";
        if ($q) {
            return $sql;
        }
        $this->sql = $sql;
        $ar = $this->fetch($this->select_query($sql));
        return $ar['val'];
    }

    public function getMYSQLValueAll($a, $b = "*", $c = " 1=1 ", $q=0)
    {
        $sql = "select {$b}  from {$a} where {$c} ";
        if ($q) {
            return $sql;
        }
        $this->setSQL($sql);
        $data = array();
        $rs = $this->select_query($sql);
        while ($ar = $this->fetch($rs)) {
            $data[] = $ar;
        }

        // Free result set
        if ($rs) {
            mysqli_free_result($rs);
        }
        return $data;
    }

    public function test()
    {
        return "ConnectMySQL";
    }

    public function getSQL()
    {
        return $this->sql;
    }
    private function setSQL($sql)
    {
        $this->sql = $sql;
    }
}