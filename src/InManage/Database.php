<?php

declare(strict_types=1);

class Database
{
    private $dbConnection;

    public function __construct($host, $username, $password, $database)
    {
        $this->dbConnection = new mysqli($host, $username, $password, $database);

        if ($this->dbConnection->connect_error) {
            die("Connection failed: " . $this->dbConnection->connect_error);
        }
    }

    public function query($sql)
    {
        return $this->dbConnection->query($sql);
    }

    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", $data) . "'";

        $query = "INSERT INTO $table ($columns) VALUES ($values)";

        return $this->dbConnection->query($query);
    }

    public function update($table, $data, $condition = "")
    {
        // Function to update data in a table.
        $set = array();
        foreach ($data as $column => $value) {
            $set[] = "$column = '$value'";
        }
        $set = implode(', ', $set);

        $query = "UPDATE $table SET $set";

        if (!empty($condition)) {
            $query .= " WHERE $condition";
        }

        return $this->dbConnection->query($query);
    }

    public function delete($table, $condition = "")
    {
        // Function to delete data from a table.
        $query = "DELETE FROM $table";

        if (!empty($condition)) {
            $query .= " WHERE $condition";
        }

        return $this->dbConnection->query($query);
    }

    public function select($table, $condition = "")
    {
        // Function to select data from a table.
        $query = "SELECT * FROM $table";

        if (!empty($condition)) {
            $query .= " WHERE $condition";
        }

        $result = $this->dbConnection->query($query);

        if ($result) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function create_table($table, $columns)
    {
        // Ensure the table name and columns are provided
        if (empty($table) || empty($columns)) {
            return false;
        }

        // Create the SQL statement for table creation
        $sql = "CREATE TABLE IF NOT EXISTS $table (";
        foreach ($columns as $columnName => $columnDefinition) {
            $sql .= "$columnName $columnDefinition, ";
        }
        $sql = rtrim($sql, ", "); // Remove the trailing comma and space
        $sql .= ");";

        // Execute the SQL statement to create the table
        return $this->dbConnection->query($sql);
    }
    public function close()
    {
        // Close the database connection when you're done.
        $this->dbConnection->close();
    }
}
