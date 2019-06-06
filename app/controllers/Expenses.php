<?php

class Expenses extends Controller {
    public function __construct() {
        echo 'in construct';
        $this->entryModel=$this->model('ExpenseEntry');
        echo 'after entry model';
        $this->typeModel=$this->model('ExpenseType');
        echo 'after type model';
    }

    public function index(){
        $data=[
            'title'=>'View your Expenses',
            'results_array'=>'',
            'sort_by'=>'',
            'type_id'=>'',
            'startdate'=>'',
            'enddate'=>'',
            'min_dollar'=>'',
            'max_dollar'=>'',
            'startdate_err'=>'',
            'enddate_err'=>'',
            'min_dollar_err'=>'',
            'max_dollar_err'=>'',
            'is_postback'=>false
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            //error count
            $error_count=0;
            $search_params=[];

            //set 'is_postbank' to true
            $data['is_postback']=true;

            //sanitize variables
            $cleaned_post=array_map('htmlspecialchars', $_POST);

            //Check for min max
            //First, populate data with the post values so showing back to the user
            $data['min_dollar']=$cleaned_post['min_dollar'];
            $data['max_dollar']=$cleaned_post['max_dollar'];

            if (!empty($cleaned_post['min_dollar']) || !empty($cleaned_post['max_dollar'])){
                //We know that at least min or max has been set. Now we have to make sure that both are filled out
                if (!empty($cleaned_post['min_dollar'] && !empty($cleaned_post['max_dollar']))){
                    //Now check for properly formatted dollar amounts
                    if (checkMoneyAmount($cleaned_post['min_dollar'])  && checkMoneyAmount($cleaned_post['max_dollar'])){
                        //check that min_dollar is less than max_dollar
                        if (floatval($cleaned_post['min_dollar'] < floatval($cleaned_post['max_dollar']))){

                            //if all is good, populate search params for min/max
                            $search_params['min_dollar']=$cleaned_post['min_dollar'];
                            $search_params['max_dollar']=$cleaned_post['max_dollar'];
                        } else {
                            $data['max_dollar_err'] = 'Maximum dollar has to be less than Minimum dollar amount';
                            $error_count++;
                        }
                    } else {
                        $data['min_dollar_err']='Min or max dollar is in the wrong format';
                        $error_count++;
                    }
                } else {
                    $data['min_dollar_err']='You must have a min and max dollar amount if you want to search by amount';
                    $error_count++;
                }
            }


            //Check for DateRange
            //put variables back into data to show the user
            $data['startdate']=$cleaned_post['startdate'];
            $data['enddate']=$cleaned_post['enddate'];
            //create variables for DateTimeHelper objects to be put into
            $startdate='';
            $enddate='';

            //check for an entry in either start date or end date
            if (!empty($cleaned_post['startdate'] || !empty($cleaned_post['enddate']))){
                //check for an entry in both startdate and enddate
                if (!empty($cleaned_post['startdate'] && !empty($cleaned_post['enddate']))){
                    try {
                        $startdate=DatetimeFactory::createDateTimeHelper($cleaned_post['startdate']);
                        if (!$startdate instanceof DateTimeHelper){
                            $data['startdate_err']='Start Date is in the wrong format. Must be MM/DD/YYYY';
                            $error_count++;
                        } else {
                            $data['startdate']=$startdate->getDisplayFormat();
                        }
                    } catch (Exception $e){
                        $data['startdate_err']='Start Date is in the wrong format. Must be MM/DD/YYYY';
                        $error_count++;
                    }

                    try {
                        $enddate=DatetimeFactory::createDateTimeHelper($cleaned_post['enddate']);
                        if (!$enddate instanceof DateTimeHelper){
                            $data['enddate_err']='End Date is in the wrong format. Must be MM/DD/YYYY';
                            $error_count++;
                        } else {
                            $data['enddate']=$enddate->getDisplayFormat();

                        }
                    } catch (Exception $e){
                        $data['enddate_err']='End Date is in the wrong format. Must be MM/DD/YYYY';
                        $error_count++;
                    }

                    //now check to see if startdate is less than enddate
                    if ($startdate <= $enddate){

                        //put date variables into $search params
                        $search_params['startdate']=$startdate->getDbFormat();
                        $search_params['enddate']=$enddate->getDbFormat();

                    } else {
                        $data['enddate_err']='End date must be later than Start Date';
                        $error_count++;
                    }


                } else {
                    $data['startdate_err']='To search by date, both start and end dates have to be filled out';
                    $error_count++;
                }
            }

            //Check for Expense Type being chosen
            if (intval($cleaned_post['type_id'] > 0)){
                $search_params['type_id']=$cleaned_post['type_id'];
                $data['type_id']=$cleaned_post['type_id'];
            }

            //if no errors, then get the expense types from the database

            if ($error_count == 0){
                //send the search params to the
                $this->entryModel=new ExpenseEntry;
                $results=$this->entryModel->getEntries($search_params);

                $data['results_array']=$results;
            }
        }
        $this->view('expenses/index', $data);

    }

    public function add(){
        $data=[
            'title'=>'Add a New Expense',
            'form_action'=>'/expenses/add',
            'submit_value'=>'Save Expense',
            'id'=>'0',
            'show_delete'=>false,
            'all_expense_types'=>'',
            'type_id'=>'',
            'date'=>'',
            'note'=>'',
            'amount'=>'',
            'type_id_err'=>'',
            'date_err'=>'',
            'note_err'=>'',
            'amount_err'=>'',
            'operation_success'=>'',
            'operation_failure'=>''
        ];

        $this->typeModel=new ExpenseType();
        $data['all_expense_types']=$this->typeModel->getAllExpenseTypes();

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            //Clean post variables
            $cleaned_post=array_map('htmlspecialchars', $_POST);


            $error_count=0;


            //Check for an Expense type being chosen
            if (!$cleaned_post['type_id']==0){
                $data['type_id']=$cleaned_post['type_id'];
            } else {
                $data['type_id_err']='Please choose an expense type';
                $error_count++;
            }

            //Check for proper date
            if (!empty($cleaned_post['date'])){
                try {
                    $new_date=DatetimeFactory::createDateTimeHelper($cleaned_post['date']);

                    if (!$new_date instanceof DateTimeHelper){
                        $data['date_err']='Date is in the wrong format. Must be mm/dd/yyyy';
                        $error_count++;
                    } else {
                        $data['data']=$new_date->getDisplayFormat();
                    }

                } catch (Exception $e){

                    $data['date_err']='Date is in the wrong format. Must be mm/dd/yyyy';
                    $error_count++;
                }
            } else {
                $data['date_err']='Date cannot be blank!';
                $error_count++;
            }

            if (!empty($cleaned_post['amount'])){
                if (checkMoneyAmount($cleaned_post['amount'])){

                    $data['amount']=$cleaned_post['amount'];

                } else {
                    $data['amount_err']='This is not a valid dollar amount';
                    $error_count++;
                }
            } else {
                $data['amount_err']='Amount cannot be blank';
                $error_count++;
            }

            if (!empty($cleaned_post['note'])){
                $data['note']=$cleaned_post['note'];
            }

            if ($error_count==0){
                $this->entryModel = new ExpenseEntry;
                $this->typeModel = new ExpenseType;

                $this->typeModel->pullType($cleaned_post['type_id']);
                $this->entryModel->newExpense($new_date,$type,$cleaned_post['amount'],$cleaned_post['note']);
                try {
                    $this->entryModel->save();
                    sleep(2);
                    header('Location: ' . URLROOT . '/expenses/add');
                } catch (PDOException $e){
                    $data['operation_failure']=$e->getMessage();
                }
            }
        }

        $this->view('expenses/add', $data);
    }

    public function change($id){
        $data = [
            'title'=>'Change or Delete Expense',
            'form_action'=>'/expenses/change/'.$id,
            'submit_value'=>'Save Changes',
            'id'=>'',
            'show_delete'=>true,
            'type_id'=>'',
            'date'=>'',
            'note'=>'',
            'amount'=>'',
            'date_err'=>'',
            'type_id_err'=>'',
            'note_err'=>'',
            'amount_err'=>'',
            'operation_success'=>'',
            'operation_failure'=>''
        ];
        $this->entryModel = new ExpenseEntry;
        $this->entryModel->getExpense($id);
        $data['id']=$this->entryModel->getId();
        $data['type_id']=$this->entryModel->getExpenseType()->getId();
        $data['date']=$this->entryModel->getDate()->format('m/d/Y');
        $data['amount']=$this->entryModel->getAmount();
        $data['note']=$this->entryModel->getNote();

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $error_count=0;
            $cleaned_post=array_map('htmlspecialchars', $_POST);
            if ($cleaned_post['type_id']==0){
                $data['type_id_err']='Please choose an expense type';
                $error_count++;
            } else {
                $data['type_id']=$cleaned_post['type_id'];
            }
            if (!empty($cleaned_post['date'])) {
                try {
                    $new_date = DatetimeFactory::createDateTimeHelper($cleaned_post['date']);

                    if (!$new_date instanceof DateTimeHelper) {
                        $data['date_err'] = 'Date is in the wrong format. Must be mm/dd/yyyy';
                        $error_count++;
                    } else {
                        $data['date'] = $new_date->getDisplayFormat();
                    }

                } catch (Exception $e) {

                    $data['date_err'] = 'Date is in the wrong format. Must be mm/dd/yyyy';
                    $error_count++;
                }
            }

            if (!empty($cleaned_post['amount'])){
                if (checkMoneyAmount($cleaned_post['amount'])){

                    $data['amount']=$cleaned_post['amount'];

                } else {
                    $data['amount_err']='This is not a valid dollar amount';
                    $error_count++;
                }
            } else {
                $data['amount_err']='Amount cannot be blank';
                $error_count++;
            }

            if (!empty($cleaned_post['note'])){
                $data['note']=$cleaned_post['note'];
            }

            if ($error_count==0){

                $this->entryModel->setTypeId($data['type_id']);
                $this->entryModel->setAmount($data['amount']);

                $this->entryModel->setNote($data['note']);
                $this->entryModel->setDate($new_date);


                if ($this->entryModel->save()){
                    $data['operation_success']='Item changed successfully!';

                } else {
                    $data['operation_failure']='Could not change item';
                }
            }
        }

        $this->view('expenses/change', $data);
    }

    public function delete($id){
        $data=[
            'title'=>'Delete Entry',
            'id'=>$id,
            'delete_failure'=>''
        ];

        if ($_SERVER['REQUEST_METHOD']=='POST'){

            $this->expenseModel= new ExpenseEntry;
            $this->entryModel->getExpense($id);

            if ($this->entryModel->delete()){
                header('Location: ' . URLROOT . '/expenses');
            } else {
                $data['delete_failure']='Entry not deleted';
            }
        }

        $this->view('expenses/delete', $data);
    }

}

