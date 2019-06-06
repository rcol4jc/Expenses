<?php

//require 'ExpenseEntry.php';
class ExpenseType
{
    /**
     * @var string $id             Id from database of the expenseType
     * @var string $expense_name   The expense_name of the type. This corresponds to the field in the db
     * @var Database object        Variable for the Database object
     */
    private $id;
    private $expense_name;
    private $db;


    /**
     * ExpenseType constructor.
     */
    public function __construct() {
        //Instantiates new Database object
        $this->db=new Database;
    }

    /**
     * Clears the properties after delete from the database
     */
    private function clearAfterDelete(){
        $this->id='';
        $this->expense_name='';

    }

    /**
     * @param $id Id of row in the database to pull
     * @return bool true if it pulls the record, false if not
     */
    public function pullType($id){

        $this->db->query('SELECT * FROM types WHERE id=:id');
        $this->db->bind(':id',$id);

        $result=$this->db->single();

        //if the result set is not empty, then populate the class properties from the database
        if (!empty($result)){
            $this->id = $result['id'];
            $this->expense_name = $result['expense_name'];
            return true;
        } else {
            return false;
        }

    }


    /**
     * @return string Getter for private $expense_name
     */
    public function getName(){
        return $this->expense_name;
    }

    /**
     * @return string Getter for private $id
     */
    public function getId(){
        return $this->id;
    }


    /**
     * @param $name Setter for private $expense_name
     */
    public function setName($name){
        $this->expense_name=$name;
    }

    /**
     * @return bool  True if the instance of ExpenseType was inserted into database. False if not
     */
    public function save(){
        //if this is a brand new ExpenseType, then insert into the database and populate the id with the last inserted
        //id
        if (empty($this->id)){
            $this->db->query("INSERT INTO types (expense_name) VALUES (:expense_name)");
            $this->db->bind(':expense_name',$this->expense_name);
            $this->id = $this->db->lastInsertedId();
            return true;

        } else {
            //If this is an existing ExpenseType pulled from the database, then Update the database
            $this->db->query("UPDATE types SET expense_name=:expense_name WHERE id=:id");
            $this->db->bind(':expense_name',$this->expense_name);
            $this->db->bind(':id', $this->id);
            $this->db->execute();
            $changed=$this->db->rowCount();
            if ($changed == 1){
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @return bool True if deleted (rowcount of effected is 1). False if not
     */
    public function delete(){
        if (!empty($this->id)){
            $this->db->query('DELETE FROM types WHERE id=:id');
            $this->db->bind(':id', $this->id);
            $this->db->execute();
            if ($this->db->rowCount()== 1){
                $this->clearAfterDelete();
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @return array   Gets all the expense types for drop down lists or for use in getting totals.
     */
    public function getAllExpenseTypes(){
        $this->db->query('SELECT * FROM types ORDER BY expense_name ASC');
        $results=$this->db->resultSet();
        return $results;
    }




}