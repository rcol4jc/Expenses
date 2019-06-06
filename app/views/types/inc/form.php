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

                <form action="<?php echo URLROOT ?><?php echo $data['form_action']; ?>" method="post" class="form">
                    <div class="form-group">
                        <label for="expense_title">Name of Expense: <sup>*</sup></label>
                        <input type="text" name="expense_title" class="form-control
                        <?php echo (!empty($data['expense_title_err'])) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $data['expense_title']; ?>" required="required">
                        <span class="invalid-feedback"><?php echo $data['expense_title_err']; ?></span>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="<?php echo $data['submit_value']; ?>" class="btn btn-success btn-block">
                        </div>
                        <?php if ($data['show_delete']):?>
                            <div class="col">
                                <a id="expense_delete" href="<?php echo URLROOT; ?>/types/delete/<?php echo $data['id']; ?>" class="btn btn-block btn-danger text-light">Delete Type</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
