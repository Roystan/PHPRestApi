<?php 

class MyModel extends DB
{

    /**
     * List of field types.
     */
    const TYPE_INT = 1;
    const TYPE_BOOL = 2;
    const TYPE_STRING = 3;
    const TYPE_FLOAT = 4;
    const TYPE_DATE = 5;
    const TYPE_HTML = 6;
    const TYPE_NOTHING = 7;
    const TYPE_SQL = 8;

    protected $table;
    protected $primaryKey;

    private $fields = array();
    private $numRows = null;
    private $insertId = null;
    private $affectedRows = null;
    private $returnArray = true;
    
    public function __construct() {
		parent::__construct();
	}

    public function findAll($conditions = null, $fields = '*', $order = null, $start = 0, $limit = null)
    {

        $sqlQuery = "SELECT $fields FROM " . $this->table . "";

        if ($conditions != null)  {
            $sqlQuery .= " WHERE " . $conditions . "";
        }


        if ($order != null) {
            $sqlQuery .= " ORDER BY " . $order . "";
        }

        if ($limit != null)  {
            $sqlQuery .= " LIMIT " . $limit . "";
        }

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;

    }

    public function find($id = null, $fields = '*', $order = null)
    {

        $stmt = $this->findAll("id = $id", $fields, $order, 0, 1);

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ( is_array( $dataRow) ) {
            return $dataRow;
        } else {
            return $dataRow;
        }

    }

    public function insert($data = null) {
        if ($data == null) {
            return false;
        }

        $values = $this->get_sqlvalues($data);

        $sqlQuery = "INSERT INTO
        ". $this->table ."
        (".$values[0].") VALUES ".$values[1].";";

        $stmt = $this->conn->prepare($sqlQuery);

        if ( $stmt->execute() ){
            return true;
        }

        return false;

    }

    public function update($data = null) {

        if ($data == null) {
            return false;
        }

        $sqlQuery = "UPDATE
        ". $this->table ."
        SET " .  $this->get_sqlset( $data ) . "";

        if ($data->id !== null) {
            $sqlQuery .= "WHERE id = $data->id";
        } else {
            // do insert
        }

        $stmt = $this->conn->prepare($sqlQuery);

        if ( $stmt->execute() ){
            return true;
        }

        return false;

    }

    public function delete($id = null) {
        if ($id == null) {
            return false;
        }

        $sqlQuery = "DELETE FROM
        ". $this->table ."
        WHERE id = $id";

        $stmt = $this->conn->prepare($sqlQuery);

        if ( $stmt->execute() ){
            return true;
        }

        return false;
    }

    public function get_sqlvalues($obj, $include_key = false, $alias_value = false) {

        $sql_into = [];
        $sql_value = [];

        foreach ($obj as $key => $value) {
            if (array_search($key, $this->field_arr) === false) {
                array_push($sql_into, $key);
                array_push($sql_value, "'$value'");
            }
        }

        $sql_into = ($sql_into ? implode(",", $sql_into) : false);
        $sql_value = ($sql_value ? "(".implode(",", $sql_value).")" : false);

        return ($sql_into && $sql_value ? [$sql_into, $sql_value] : false);
    }

    public function get_sqlset($obj) {

        $sql_set = "";
        foreach ($this->field_arr as $field_index => $field_item) {
            if ( !isset( $obj->{$field_index})  ) continue;

            // skip primary key
      	  	if ($field_index == $this->primaryKey) continue;

            $value = $obj->{$field_index} ?: $field_item;
            
            $sql_set .= "{$field_index} = '".$value."',";
        }

        return self::strip_end($sql_set, 1);
        
    }

    public static function strip_end($string, $count) { // remove the last "count" letters from a "string"
      	return ($string ? substr($string, 0, strlen($string) - $count) : $string);
    }
}