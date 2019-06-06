<?php


class ExpenseEntry {

    /**
     * @var string $id ID of expense entry from db
     * @var DateTime     $date        Date of the expense that was purchased
     * @var ExpenseType  $expenseType ExpenseType of this Entry
     * @var string       $type_id     Convenience property to get the id of the ExpenseType
     * @var string       $note        Optional note field for any notes about the purchase
     * @var string       $amount      Amount of purchase. Is converted to float in code when needed
     * @var Database     $db          Database object for Database access
     */
    private $id;
    private $date;
    private $expenseType;
    private $type_id;
    private $note;
    private $amount;
    private $db;

    /**
     * ExpenseEntry constructor.  Instantiates now Database object into db property
     */
    public function __construct(){
        $this->db=new Database;

    }

    /**
     * @param $id string   id of row in database to retrieve
     * @throws Exception
     */
    public function getExpense($id){
        /**
         * gets record from database and populated properties
         */
        $this->db->query('SELECT * FROM expenses WHERE id=:id');
        $this->db->bind(':id', $id);
        $result=$this->db->single();

        $this->id=$id;
        $temp_datetime=new DateTimeHelper($result['date']);
        $this->date=$temp_datetime;
        $type=new ExpenseType;
        $type->pullType($result['type_id']);
        $this->expenseType=$type;
        $this->type_id=$this->expenseType->getId();
        $this->note=$result['note'];
        $this->amount=$result['amount'];

    }

    /**
     * @param $date Datetime object
     * @param $expenseType ExpenseType type object
     * @param $amount string or float
     * @param string $note
     * @return bool
     *
     * Creates now expense from scratch
     */
    public function newExpense($date, $expenseType, $amount, $note=''){
        if ($expenseType instanceof ExpenseType){
            $this->expenseType=$expenseType;
            if ($date instanceof DateTime){
                $this->date=$date;
                $this->amount=$amount;
                $this->note=$note;

            } else {
                return false;
            }
        } else {
            return false;

        }
    }

    /**
     * @return true if successfull Saves to database. Will update if existing or will insert if new
     */
    public function save(){
        if (empty($this->id)){
            $this->db->query('INSERT INTO expenses (date, type_id, note, amount) VALUES (:date, :type_id, :note, :amount)');
            $this->db->bind(':date', $this->date->format('Y-m-d'));
            $this->db->bind(':type_id', $this->expenseType->getId());
            $this->db->bind(':note', $this->note);
            $this->db->bind(':amount', $this->amount);
            try {
                $this->id=$this->db->lastInsertedId();
                return true;
            } catch (PDOException $e){
                throw $e;
            }

        } else {

            $this->db->query('UPDATE expenses SET date=:date, type_id=:type_id, note=:note, amount=:amount WHERE id=:id');
            $this->db->bind(':date', $this->date->format('Y-m-d'));
            $this->db->bind(':type_id', $this->type_id);
            $this->db->bind(':note',$this->note);
            $this->db->bind(':amount', $this->amount);
            $this->db->bind(':id', $this->id);
            $this->db->execute();
            if ($this->db->rowCount() == 1){
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @return bool deletes from database.
     */
    public function delete(){
        if (!empty($this->id)){
            $this->db->query('DELETE FROM expenses WHERE id=:id');
            $this->db->bind(':id', $this->id);
            $this->db->execute();
            if ($this->db->rowCount()==1){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    //getter functions
    public function getId(){
        return $this->id;
    }

    public function getDate(){
        return $this->date;
    }

    public function getTypeName(){
        return $this->expenseType->getName();
    }

    public function getTypeId() {
        return $this->type_id;
    }

    public function getNote(){
        return $this->note;
    }

    public function getAmount(){
        return $this->amount;
    }
    public function getExpenseType(){
        return $this->expenseType;
    }

    //setter functions

    public function setTypeId($type_id) {
        $this->type_id=$type_id;
    }

    public function setNote($note){
        $this->note=$note;
    }

    public function setDate($date){
        $this->date=$date;
    }

    public function setAmount($amount){
        $this->amount=$amount;
    }

    /**
     * @param $type_id
     * @param array $datesArray
     * @return mixed
     * @throws Exception
     */
    public function getEntryTotalsForType($type_id, $datesArray=[])
    {
        $type=new ExpenseType;
        $type->pullType($type_id);
        //Check if if Dates array is not empty. If not, we need to use a different SQL statement
        if (!count($datesArray) == 0) {
            //$datesArray is a keyed Array with a startdate and enddate key with a text representation of the date.
            //next we check if neither dates are blank
            if (!empty($datesArray['startdate']) && !empty($datesArray['enddate'])){

                //next we check if they're the same. If so we use the simpler date=date
                if ($datesArray['startdate'] == $datesArray['enddate']){
                    $this->db->query('SELECT SUM(amount) AS Total FROM expenses WHERE type_id=:type_id AND date=:date');
                    $this->db->bind(':date',$datesArray['startdate']);
                } else {

                    $this->db->query('SELECT SUM(amount) AS Total FROM expenses WHERE type_id=:type_id AND date BETWEEN :startdate AND :enddate');
                    $this->db->bind(':startdate', $datesArray['startdate']);
                    $this->db->bind(':enddate', $datesArray['enddate']);

                }
            } else {
                throw new Exception('datesArray must have a value for startdate and enddate');
            }
        } else {
            $this->db->query('SELECT SUM(amount) AS Total FROM expenses WHERE type_id=:type_id');
        }
        $this->db->bind(':type_id', $type->getId());

        $result=$this->db->single();

        return $result;
    }


    /**
     * @param array $search_params
     * @return mixed
     */
    public function getEntries($search_params=[]){
        //First part of querr
        $sql='SELECT * FROM expenses';

        $type_id_present=false;
        $dates_present=false;
        $min_max_present=false;
        $limit_count=0;

        //First see if $search_params is empty

        if (empty($search_params)){
            //sql for getting all Expenses sorted by date
            $this->db->query('SELECT * FROM expenses ORDER BY date DESC');
        } else {

            if (array_key_exists('type_id', $search_params)){
                $type_id_present=true;
            }
            if (array_key_exists('min_dollar', $search_params) && array_key_exists('max_dollar', $search_params)){
                $min_max_present=true;
            }

            if (array_key_exists('startdate', $search_params) && array_key_exists('enddate', $search_params)){
                $dates_present=true;
            }

            if ($type_id_present && !$dates_present && !$min_max_present){

                $sql = 'SELECT * FROM expenses WHERE type_id=:type_id';
                $this->db->query($sql);
                $this->db->bind(':type_id', $search_params['type_id']);
            }

            if (!$type_id_present && $dates_present && !$min_max_present){
                $sql = 'SELECT * FROM expenses WHERE date BETWEEN :startdate AND :enddate ORDER BY date';
                $this->db->query($sql);
                $this->db->bind(':startdate', $search_params['startdate']);
                $this->db->bind(':enddate', $search_params['enddate']);
            }

            if (!$type_id_present && !$dates_present && $min_max_present){
                $sql = 'SELECT * FROM expenses WHERE amount BETWEEN :min_dollar AND :max_dollar ORDER BY amount';
                $this->db->query($sql);
                $this->db->bind(':min_dollar', $search_params['min_dollar']);
                $this->db->bind(':max_dollar', $search_params['max_dollar']);
            }

            if ($type_id_present && $dates_present && !$min_max_present){
                $sql='SELECT * FROM expenses WHERE type_id=:type_id AND date BETWEEN :startdate AND :enddate ORDER BY date';
                $this->db->query($sql);
                $this->db->bind(':type_id', $search_params['type_id']);
                $this->db->bind(':startdate', $search_params['startdate']);
                $this->db->bind(':enddate', $search_params['enddate']);
            }

            if ($type_id_present && !$dates_present && $min_max_present){
                $sql = 'SELECT * FROM expenses WHERE type_id=:type_id AND amount BETWEEN :min_dollar AND :max_dollar ORDER BY amount';
                $this->db->query($sql);
                $this->db->bind(':type_id', $search_params['type_id']);
                $this->db->bind(':min_dollar', $search_params['min_dollar']);
                $this->db->bind(':max_dollar', $search_params['max_dollar']);
            }

            if (!$type_id_present && $dates_present && $min_max_present){
                $sql = 'SELECT * FROM expenses WHERE date BETWEEN :startdate AND :enddate AND amount BETWEEN :min_dollar AND :max_dollar ORDER BY amount, date';
                $this->db->query($sql);
                $this->db->bind(':startdate', $search_params['startdate']);
                $this->db->bind(':enddate', $search_params['enddate']);
                $this->db->bind(':min_dollar', $search_params['min_dollar']);
                $this->db->bind(':max_dollar', $search_params['max_dollar']);
            }

            if ($type_id_present && $dates_present && $min_max_present){
                $sql = 'SELECT * FROM expenses WHERE type_id=:type_id AND date BETWEEN :startdate AND :enddate AND amount BETWEEN :min_dollar AND :max_dollar ORDER BY amount, date';
                $this->db->query($sql);
                $this->db->bind(':type_id', $search_params['type_id']);
                $this->db->bind(':startdate', $search_params['startdate']);
                $this->db->bind(':enddate', $search_params['enddate']);
                $this->db->bind(':min_dollar', $search_params['min_dollar']);
                $this->db->bind(':max_dollar', $search_params['max_dollar']);
            }

        }

        $results=$this->db->resultSet();

        return $results;


    }

    /**
     * @param $oldtype_id
     * @param $newtype_id
     * @param $num_to_change
     * @return bool
     */
    public function changeExpenseType($oldtype_id, $newtype_id, $num_to_change){

        $this->db->query('UPDATE expenses SET type_id=:newtype_id WHERE type_id=:oldtype_id');
        $this->db->bind(':newtype_id', $newtype_id);
        $this->db->bind(':oldtype_id', $oldtype_id);
        $this->db->execute();
        if ($this->db->rowCount()==$num_to_change){
            return true;
        } else {
            return false;
        }


    }
}