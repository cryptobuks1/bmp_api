<?php


namespace Api\Models;

use PDO;
use Redis;

abstract class ApiModel {

    /**
     * Object of PDO
     * @var object 
     */
    protected $pdo;

    /**
     * Object of Redis
     * @var object 
     */
    protected $redis;

    /**
     * All column names of customers table as an array
     * @var array 
     */
    public $fields = [];

    const PDO_FETCH_ASSOC = PDO::FETCH_ASSOC;

    /**
     * Create a new Model instance.
     *
     * @return void
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->fields = $this->getAllTableFields(); //Get all table column names 
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    abstract function getAllTableFields();
}
