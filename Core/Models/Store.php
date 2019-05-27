<?php

namespace Core\Models;

/**
 * Class Store
 *
 */
class Store {

    private $con; // database connection handle

    private $host; // database host ip/domain

    private $dbport; // database port

    private $user; // database username

    private $pass; // database password

    private $dbname; // name of database


    /**
     * Setup database connection.
     * The params are stored in environmental variables from .env file
     *
     * Store constructor.
     */
    public function __construct()
    {
        $this->host = getenv('DB_HOST');
        $this->user = getenv('DB_USER');
        $this->pass = getenv('DB_PASSWORD');
        $this->dbname = getenv('DB_NAME') ;
        $this->dbport = getenv('DB_PORT');

        $this->con = new \mysqli($this->host . ':' . $this->dbport, $this->user, $this->pass, $this->dbname);

    }

    /**
     * Create a group and return the ID
     * The method only create the group of the name does not exists.
     *
     * @param $name  string
     *
     * @return int
     */
    public function createGroup($name)
    {
        $check = $this->getGroupByName($name);
        if($check) return $check->id;
        $statement = $this->con->prepare('INSERT INTO groups (name) VALUES (?)');
        $statement->bind_param('s', $name);
        $statement->execute();
        $statement->close();

        return mysqli_insert_id($this->con);
    }

    /**
     * Get a group by name
     * @param $name
     *
     * @return bool|object
     */
    public function getGroupByName($name)
    {
       return $this->getGroupBy('name', $name);
    }

    /**
     * Get a group by an Id
     *
     * @param $id
     *
     * @return bool|object
     */
    public function getGroupById($id)
    {
        return $this->getGroupBy('id', $id);
    }

    /**
     * This is a private method that is used to fetch group by a field
     * @param $field
     * @param $value
     *
     * @return bool|object|\stdClass
     */
    private function getGroupBy($field, $value)
    {
        $statement = $this->con->prepare("SELECT id, name from groups where $field = ?");
        $statement->bind_param('s', $value);
        $statement->execute();
        $result = $statement->get_result();
        if($result->num_rows === 0)
        {
            $statement->close();
            return false;
        }
        $data = $result->fetch_object();
        $statement->close();
        return $data;
    }

    /**
     * Return all the groups
     *
     * @return array
     */
    public function getAllGroups()
    {
        $output = [];
        $query = $this->con->query('select * from groups');

        while($row = $query->fetch_object()) {
           $output[] = $row;
        }

        return $output;
    }

    /**
     * Delete a group by id
     * @param $id
     *
     * @return bool
     */
    public function deleteGroup($id)
    {
        $statement = $this->con->prepare('DELETE FROM groups where id = ?');
        $statement->bind_param('i', $id);
        $statement->execute();
        $statement->close();
        return true;
    }

    public function updateGroup($name, $id)
    {
        $statement = $this->con->prepare('UPDATE groups SET name = ? WHERE id = ?');
        $statement->bind_param('si', $name, $id);
        $statement->execute();
        $statement->close();
        return true;
    }

    /**
     * Return all the frogs
     *
     * @return array
     */
    public function getAllFrogs()
    {
        $output = [];
        $query = $this->con->query('SELECT f.*, g.name as group_name from frogs f LEFT JOIN groups g ON f.group_id = g.id');

        while($row = $query->fetch_object()) {
            $output[] = $row;
        }

        return $output;
    }


    public function createFrog($weight, $color, $batch='', $group_id=null)
    {

        $statement = $this->con->prepare('INSERT INTO frogs (weight, color, batch, group_id) VALUES (?, ?, ?, ?)');
        if(!$batch) $batch = time();

        $statement->bind_param('issi', $weight, $color, $batch, $group_id);
        $statement->execute();
        $statement->close();
        return mysqli_insert_id($this->con);
    }

}
