<?php 

class Connection {
    
    final public const HOSTNAME = 'localhost';
    final public const USERNAME = 'root';
    final public const PASSWORD = '';
    final public const DATABASE = 'pdo';

    public $conn;
    public $sql;
    public $result =array();

    public function __construct()
    {
        $this->conn = mysqli_connect(self::HOSTNAME, self::USERNAME, self::PASSWORD, self::DATABASE);

        try 
        {
            $conn = new PDO("mysql:host=".self::HOSTNAME.";dbname=".self::DATABASE."", self::USERNAME, self::PASSWORD);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "DB Connected";
        }
        catch(PDOException $e) {
            echo "ERROR: " . $e->getMessage();
        }
    }

    public function getRecoard($table, $row = '*', $where = null)
    {
        if ($where != null)
        {
            $this->sql = "SELECT $row FROM $table WHERE $where";
        }
        else
        {
            $this->sql = "SELECT $row FROM $table";
        }

        $this->result = $this->conn->query($this->sql);
    }

    public function insert($table, $data = array())
    {
        $table_columns = implode(',', array_keys($data));
        $table_values  = implode("','", $data);

        $this->sql    = "INSERT INTO $table ($table_columns) VALUES ('$table_values')";
        $this->result = $this->conn->query($this->sql);
    }

    public function update($table, $data = array(), $id)
    {
        $param = array();

        foreach ($data as $key => $value)
        {
            $param [] = "$key = '$value'";
        }

        $this->sql  = "UPDATE $table SET ".implode(',',$param);
        $this->sql .= "WHERE $id";

        $this->result = $this->conn->query($this->sql);
    }

    public function delete($table, $id)
    {
        $this->sql = "DELETE FROM $table";
        $this->sql .=" WHERE $id";

        $this->result = $this->conn->query($this->sql);
    }
}

$conn = new Connection();
$conn->getRecoard('book');
foreach ($conn->result as $row) 
{
    print "<hr>" . $row['name'];
}
