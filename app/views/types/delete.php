<?php require APPROOT . '/views/inc/header.php'; ?>

<?php require APPROOT . '/views/inc/navbar.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto mt-3">
            <div class="card card-body bg-light mt-5">
                <h3><?php echo $data['title']; ?></h3>

                <form class="form" action="<?php echo URLROOT; ?>/types/delete/<?php echo $data['id']; ?>" method="post">
                    <h4>Are you sure you want to delete?</h4>
                    <?php if (!empty($data['num_of_entries'])):?>
                        <h5 class="text-danger">NOTE: THERE ARE <?php echo $data['num_of_entries']; ?> EXPENSE ENTRIES THAT USE THIS EXPENSE TYPE! YOU MUST CHOOSE ANOTHER EXPENSE TYPE
                            TO PUT THESE UNDER</h5>
                    <div class="form-group">
                        <label for="new_expense_type">New Expense Type: <sup>*</sup></label>
                        <select class="custom-select-sm form-control<?php echo (!empty($data['new_expense_type_err'])) ? ' is-invalid' : ''; ?>" name="new_expense_type">">
                            <option selected value="0">***Please choose new a new expense type***</option>
                            <?php $type=new ExpenseType();
                                $allTypes=$type->getAllExpenseTypes();
                            ?>
                            <?php foreach ($allTypes as $expensetype): ?>
                                <?php if ($expensetype['id'] != $data['id']): ?>
                                    <option value="<?php echo $expensetype['id']?>"><?php echo $expensetype['expense_name']; ?></option>
                                <?php endif; ?>

                            <?php endforeach;?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['new_expense_type_err']; ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Yes" class="btn btn-block btn-danger"/>
                        </div>
                        <div class="col">
                            <a href="<?php echo URLROOT; ?>/types" class="btn btn-block btn-success">No</a>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php';?>
