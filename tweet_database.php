<?php
class Tweet{

    // database connection and table name
    private $conn;
    private $table_name = "tweet";

    // object properties
    public $tweet_id;
    public $text;
    public $time;
    public $profile_url;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read all tweets
    function read(){

        // select all query
        $query = "SELECT
                    `tweet_id`, `text`, `time`, `profile_url`
                    FROM
                    " . $this->table_name . "
                ORDER BY
                    id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }


    // create tweet
    function create(){

        // query to insert record
        $query = "INSERT INTO  ". $this->table_name ."
                        (`tweet_id`, `text`, `time`, `profile_url`)
                  VALUES
                        ('".$this->tweet_id."', '".$this->text."', '".$this->time."', '".$this->profile_url."')";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // execute query
        if($stmt->execute()){

            return true;
        }

        return false;
    }
}