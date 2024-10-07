<?php
class Gudang {
    private $database;
    private $table_name = "Gudang";
    
    public $id;
    public $name;
    public $location;
    public $capacity;
    public $status;
    public $opening_hour;
    public $closing_hour;

    public function __construct($database){
        $this->database = $database; 
    }

    public function create(){
        $query = "INSERT INTO $this->table_name (name, location, capacity, status, opening_hour, closing_hour) 
                  VALUES (:name, :location, :capacity, :status, :opening_hour, :closing_hour)";
        
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':capacity', $this->capacity);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':opening_hour', $this->opening_hour);
        $stmt->bindParam(':closing_hour', $this->closing_hour);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function read($limit = null, $offset = 0, $search = '') {
        $query = "SELECT * FROM " . $this->table_name;
        if ($search) {
            $query .= " WHERE name LIKE :search OR location LIKE :search OR status LIKE :search";
        }
        if ($limit !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }
        $stmt = $this->database->prepare($query);
        if ($search) {
            $search = "%{$search}%";
            $stmt->bindParam(":search", $search);
        }
        if ($limit !== null) {
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt;
    }
    public function readOne(){
        $query = "SELECT id, name, location, capacity, status, opening_hour, closing_hour 
                  FROM $this->table_name WHERE id = :id LIMIT 1";
        
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->name = $row['name'];
            $this->location = $row['location'];
            $this->capacity = $row['capacity'];
            $this->status = $row['status'];
            $this->opening_hour = $row['opening_hour'];
            $this->closing_hour = $row['closing_hour'];
            return true;
        } else {
            return false;
        }
    }

    public function update(){
        $query = "UPDATE $this->table_name 
                  SET name = :name, location = :location, capacity = :capacity, status = :status, 
                  opening_hour = :opening_hour, closing_hour = :closing_hour
                  WHERE id = :id";
        
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':capacity', $this->capacity);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':opening_hour', $this->opening_hour);
        $stmt->bindParam(':closing_hour', $this->closing_hour);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function delete(){
        $query = "DELETE FROM $this->table_name WHERE id = :id";
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function show($id) {
        $query = "SELECT * FROM gudang WHERE id = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if($row) {
            $this->name = $row['name'];
            $this->location = $row['location'];
            $this->capacity = $row['capacity'];
            $this->status = $row['status'];
            $this->opening_hour = $row['opening_hour'];
            $this->closing_hour = $row['closing_hour'];
            return true;
        }
    
        return false;
    }
    public function count($search = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        if ($search) {
            $query .= " WHERE name LIKE :search OR location LIKE :search OR status LIKE :search";
        }
        $stmt = $this->database->prepare($query);
        if ($search) {
            $search = "%{$search}%";
            $stmt->bindParam(":search", $search);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}


?>
