<?php


class Types extends Controller
{
    public function __construct() {
        $this->typeModel=$this->model('ExpenseType'); //Expense Type Model
        $this->entryModel=$this->model('ExpenseEntry'); //Expense Entry Model
    }

    public function index(){
        $data=[
            'title'=>'All Expense Types',
            'types_array'=>'', //Will hold the array of all ExpenseTypes

        ];

        //instantiate the model class of the type Model
        $this->typeModel=new ExpenseType();
        $data['types_array']=$this->typeModel->getAllExpenseTypes();



        $this->view('types/index',$data);
    }

    public function add(){
        //instantiate the theExpenseType
        $this->typeModel = new ExpenseType;

        //sets initial data fields
        $data = [
            'title'=>'Add a new Expense Type',
            'expense_title'=>'',
            'form_action'=>'/types/add',
            'submit_value'=>'Add Expense Type',
            'id'=>'',
            'show_delete'=>false,
            'expense_title_err'=>'',
            'operation_success'=>'',
            'operation_failure'=>''
        ];
        //If this is a post from the form
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $cleaned_post=array_map('htmlspecialchars', $_POST);

            //checks if the expense_title text field is not blank
            if (!empty($cleaned_post['expense_title'])){
                try {
                    $this->typeModel->setName($cleaned_post['expense_title']);
                    $added = $this->typeModel->save();
                    if ($added){
                        $data['id']=$this->typeModel->getId();
                        $data['operation_success']='Expense Type successfully Added';
                        $data['expense_title']='';

                    } else {
                        $data['operation_failure']='Expense Type not added';
                        $data['expense_title']=$cleaned_post['expense_title'];
                    }
                } catch (PDOException $e){

                    //checks for sqlcode 23000 which, in this context, means that a duplicate expense_title has been
                    //attempted. If so, a user-friendly message is put in the added_failure key.
                   if ($e->getCode()== 23000){
                        $data['operation_failure']='There is already an Expanse type by that name. Please type another';
                    } else {
                        //If it's not a code 23000, just spit out the message
                        $data['operation_failure']=$e->getMessage();
                    }
                    //put the expense title back in so the user does not have to retype it
                    $data['expense_title']=$cleaned_post['expense_title'];
                }
            } else {
                $data['expense_title_error']='Expense Title cannot be blank';
            }
        }
        $this->view('types/add', $data);

    }

    public function change($id) {

        $data = [
            'title'=>'Change Expense Type',
            'expense_title'=>'',
            'form_action'=>'/types/change/'.$id,
            'submit_value'=>'Change Expense Type',
            'show_delete'=>true,
            'id'=>$id,
            'expense_title_err'=>'',
            'operation_success'=>'',
            'operation_failure'=>''
        ];

        $this->typeModel=new ExpenseType;
        $this->typeModel->pullType($id);
        $data['expense_title']=$this->typeModel->getName();

        if ($_SERVER['REQUEST_METHOD']=='POST'){

            $cleaned_post = array_map('htmlspecialchars', $_POST);

            if (!empty($cleaned_post['expense_title'])){

                $this->typeModel->setName($cleaned_post['expense_title']);

                try {
                    if ($this->typeModel->save()){
                        $data['expense_title']=$cleaned_post['expense_title'];
                        $data['operation_success']='Type changed successfully';
                    } else {
                        $data['operation_failure']='Could not change type';
                    }

                } catch (PDOException $e){
                    if ($e->getCode()== 23000){
                        $data['expense_title_err']='There is already an Expanse type by that name. Please type another';
                    } else {
                        //If it's not a code 23000, just spit out the message
                        $data['operation_failure']=$e->getMessage();
                    }
                    //put the expense title back in so the user does not have to retype it
                    $data['expense_title']=$cleaned_post['expense_title'];
                }

            } else {
                $data['expense_title_err']='The name cannot be blank';
            }
        }
        $this->view('types/change',$data);


    }

    public function delete($id){
        $data = [
            'title'=>'Delete Expense Type',
            'id'=>$id,
            'new_expense_type'=>'',
            'new_expense_type_err'=>'',
            'num_of_entries'=>'',
            'delete_success'=>'',
            'delete_failure'=>'',
        ];
        $this->typeModel=new ExpenseType;
        $this->typeModel->pullType($id);
        $this->entryModel = new ExpenseEntry;
        $search_array=['type_id'=>$id];

        $entries=$this->entryModel->getEntries($search_array);



        if (count($entries) > 0){
            $data['num_of_entries']=count($entries);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            if (!empty($data['num_of_entries'])){
                if ($_POST['new_expense_type'] != 0){
                    if ($this->entryModel->changeExpenseType($data['id'], $_POST['new_expense_type'],$data['num_of_entries'])){
                        $this->typeModel->delete();
                        header('Location: ' . URLROOT . '/types');
                    } else {
                        $data['delete_failure']='Delete Failed!';
                    }
                } else {
                    $data['new_expense_type_err']='You must chose a new expense type!';

                }
            } else {
                $this->typeModel->delete();
                header('Location: ' . URLROOT . '/types');
            }

        }

        $this->view('types/delete',$data);

    }

    public function totals(){
        $data=[
            'title'=>'Expense Totals',
            'totals_array'=>[],
            'startdate'=>'',
            'enddate'=>'',
            'is_postback'=>false,
            'startdate_err'=>'',
            'enddate_err'=>'',
            'failure'=>''
        ];

        //Create DateTimeHelper object with date now
        $now=new DateTimeHelper('now');

        //Create last year's data by removing one year
        $lastYear=$now->modify('-1 year');

        //create a startdate and enddate that is the last calendar year. This is the default date range

        $startdate = new DateTimeHelper('01/01/'. $lastYear->format('Y'));
        $enddate= new DateTimeHelper('12/31/' . $lastYear->format('Y'));



        $data['startdate']=$startdate->getDisplayFormat();
        $data['enddate']=$enddate->getDisplayFormat();




        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            //set postback var to true to show the table of results
            $data['is_postback']=true;

            //Clean $_POST variables
            $cleaned_post=array_map('htmlspecialchars',$_POST);

            //Error count starts at zero
            $error_count=0;

            //blank array to hold the ExpenseTypes and totals that are produced to display to the user
            $typesAndTotalsArray=[];
            $this->typeModel = new ExpenseType;
            $all_types=$this->typeModel->getAllExpenseTypes(); //

            $user_start_date = new DateTimeHelper($cleaned_post['startdate']);
            $user_end_date = new DateTimeHelper($cleaned_post['enddate']);
            if ($user_end_date >= $user_start_date){
                $data['startdate']=$cleaned_post['startdate'];
                $data['enddate'] = $cleaned_post['enddate'];
                $dateArray=[];
                $dateArray['startdate']=$user_start_date->getDbFormat();
                $dateArray['enddate']=$user_end_date->getDbFormat();
                foreach ($all_types as $this_type) {
                    $this->entryModel=new ExpenseEntry;
                    $result=$this->entryModel->getEntryTotalsForType($this_type['id'],$dateArray);

                    //if an expense type has no entries, null will be returned in the Total column. This will replace
                    //it with 0.00
                    if ($result['Total'] == null){
                        $result['Total']='0.00';
                    }
                    $result['Expense Name']=$this_type['expense_name'];

                    $typesAndTotalsArray[]=$result;
                }

                $data['totals_array']=$typesAndTotalsArray;

            } else {
                $data['enddate_err']='Your End Date cannot be before your start date';
                $error_count++;
            }

        }

       $this->view('types/totals',$data);

    }

}