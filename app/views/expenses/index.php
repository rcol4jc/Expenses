<?php require APPROOT . '/views/inc/header.php'; ?>

<?php require APPROOT . '/views/inc/navbar.php'; ?>
<div class="container">
    <div class="row">
         <div class="col-lg-6 mx-auto">
             <div class="card card-body bg-light mt-5">
                 <h3 class="display-5"><?php echo $data['title']; ?></h3>
                 <form action="<?php echo URLROOT ?>/expenses/" method="post" class="form">
                     <div class="row">
                         <div class="col">
                             <div class="form-group">
                                 <label for="min_dollar">Minimum Dollar Amount: </label>
                                 <input type="text" value="<?php echo $data['min_dollar']; ?>" class="form-control
                                 <?php echo (!empty($data['min_dollar_err'])) ? ' is-invalid' : ''; ?>" name="min_dollar"/>
                                 <span class="invalid-feedback"><?php echo $data['min_dollar_err']; ?></span>
                             </div>
                         </div>
                         <div class="col">
                             <div class="form-group">
                                 <label for="min_dollar">Maximum Dollar Amount: </label>
                                 <input type="text" value="<?php echo $data['max_dollar']; ?>" class="form-control
                                 <?php echo (!empty($data['max_dollar_err'])) ? ' is-invalid' : ''; ?>" name="max_dollar"/>
                                 <span class="invalid-feedback"><?php echo $data['max_dollar_err']; ?></span>
                             </div>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col">
                             <div class="form-group">
                                 <label for="startdate">Start Date: </label>
                                 <input type="text" value="<?php echo $data['startdate']; ?>" class="form-control
                                 <?php echo (!empty($data['startdate_err'])) ? ' is-invalid' : ''; ?>" id="index_start_date" name="startdate"/>
                                 <span class="invalid-feedback"><?php echo $data['startdate_err']; ?></span>
                             </div>
                         </div>
                         <div class="col">
                             <div class="form-group">
                                 <label for="enddate">End Date: </label>
                                 <input type="text" value="<?php echo $data['enddate']; ?>" class="form-control
                                 <?php echo (!empty($data['enddate_err'])) ? ' is-invalid' : ''; ?>" id="index_end_date" name="enddate"/>
                                 <span class="invalid-feedback"><?php echo $data['enddate_err']; ?></span>
                             </div>
                         </div>
                     </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="type_id">Expense Type: <sup>*</sup></label>
                                    <select class="custom-select-sm form-control<?php echo (!empty($data['type_id_err'])) ? ' is-invalid' : ''; ?>" name="type_id">

                                        <?php
                                        if (empty($data['type_id'])){
                                            echo '<option selected value="0">**Please Choose an Expense Type</option>';
                                        } else {
                                            echo '<option value="0">**Please Choose an Expense Type</option>';
                                        }

                                        $type=new ExpenseType;
                                        $all_Events = $type->getAllExpenseTypes();

                                        foreach ($all_Events as $type) {

                                            if ($data['type_id'] == $type['id']){
                                                echo '<option selected value="' . $type['id'] . '">' . $type['expense_name'] . '</option>';
                                            } else {
                                                echo '<option value="' . $type['id'] . '">' . $type['expense_name'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['type_id_err']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input type="submit" class="btn btn-success btn-block" value="Search" />
                            </div>
                        </div>
                 </form>
             </div>
         </div>
    </div>

    <?php if ($data['is_postback']):?>

        <?php if (!empty($data['results_array'])): ?>

            <div class="row">
                <div class="col-lg-10 mx-auto mt-5">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Type</th>
                                <th scope="col">Notes</th>
                                <th scope="col"class="text-right">Amount</th>
                                <th scope="col" class="text-right">Change</th>
                            </tr>
                        </thead>
                        <tbody>

                                <?php $all_expenses=$data['results_array']; ?>
                                <tr>
                                <?php foreach ($all_expenses as $expense): ?>
                                    <td><?php
                                        $date=new DateTime($expense['date']);
                                        echo $date->format('m/d/Y');
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $type=new ExpenseType;
                                        $type->pullType($expense['type_id']);
                                        echo $type->getName();
                                        ?>
                                    </td>
                                    <td><?php echo $expense['note']; ?></td>
                                    <td class="text-right">$<?php echo $expense['amount']; ?> </td>
                                    <td class="text-right"><a href="<?php echo URLROOT; ?>/expenses/change/<?php echo $expense['id']; ?>">Change/Delete</a> </td>

                                    </tr>
                                <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>
            </div>

        <?php else: ?>


        <?php endif; ?>


    <?php endif; ?>


</div>



<?php require APPROOT . '/views/inc/footer.php';?>
