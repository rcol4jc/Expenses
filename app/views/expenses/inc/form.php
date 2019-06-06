
<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <?php if (!empty($data['operation_success'])){
                ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $data['operation_success']?>
                </div>
                <?php
            } ?>
            <?php if (!empty($data['operation_failure'])){
                ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $data['operation_failure']?>
                </div>
                <?php
            } ?>
            <div class="card card-body bg-light mt-5">

                <h3 class="display-5"><?php echo $data['title']; ?></h3>

                <form action="<?php echo URLROOT; ?><?php echo $data['form_action']; ?>" method="post" class="form">
                    <div class="form-group">
                        <label for="date">Date Purchased: <sup>*</sup></label>
                        <input type="text" id="datepicker" name="date" class="form-control
                        <?php echo (!empty($data['date_err'])) ? 'is-invalid' : ''; ?>"
                               value="<?php
                               if (empty($data['date'])) {
                                   if ($now= DatetimeFactory::createDateTimeHelper('now')){
                                       echo $now->getDisplayFormat();
                                   }
                               } else {
                                   echo $data['date'];
                               }
                               ?>" required="required">
                        <span class="invalid-feedback"><?php echo $data['date_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="type_id">Expense Type: <sup>*</sup></label>
                        <select class="custom-select-sm form-control<?php echo (!empty($data['type_id_err'])) ? ' is-invalid' : ''; ?>" name="type_id">

                            <?php
                            if (empty($data['type_id'])){
                                echo '<option selected value="0">**Please Choose an Expense Type</option>';
                            } else {
                                echo '<option value="0">**Please Choose an Expense Type</option>';
                            }


                            $all_Events = $data['all_expense_types'];

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
                    <div class="form-group">

                        <label for="amount">Amount: <sup>*</sup></label>
                        <input type="text" name="amount" value="<?php echo $data['amount']; ?>"
                               class="form-control<?php echo (!empty($data['amount_err'])) ? ' is-invalid' : ''; ?>" />
                        <span class="text-danger" id="amount_feedback"></span>
                        <span class="invalid-feedback"><?php echo $data['amount_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="note">Notes: (Optional)</label>
                        <textarea name="note" class="form-control
                        <?php echo (!empty($data['note_err'])) ? ' is-invalid' : ''; ?>"><?php echo $data['note']; ?></textarea>
                        <span class="invalid-feedback"><?php echo $data['note_err']; ?></span>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="<?php echo $data['submit_value']; ?>" class="btn btn-success btn-block">
                        </div>
                        <?php if ($data['show_delete']):?>
                            <div class="col">
                                <a id="expense_delete" href="<?php echo URLROOT; ?>/expenses/delete/<?php echo $data['id']; ?>" class="btn btn-block btn-danger text-light">Delete Entry</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
