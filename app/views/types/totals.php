<?php require APPROOT . '/views/inc/header.php'; ?>

<?php require APPROOT . '/views/inc/navbar.php'; ?>

<div class="container">

    <div class="row no-print">

        <div class="col-lg-6 mx-auto">
            <?php if (!empty($data['failure'])){
                ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $data['added_failure']?>
                </div>
                <?php
            } ?>
            <div class="card card-body bg-light mt-5">

                <h3 class="display-4"><?php echo $data['title']; ?></h3>
                <form action="<?php echo URLROOT; ?>/types/totals" method="post" class="form">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="startdate">Start Date:  <sup>*</sup></label>
                                <input type="text" class="form-control <?php echo (!empty($data['startdate_err'])) ? ' is-invalid' : '';?>"
                                       value="<?php echo $data['startdate']; ?>" name="startdate" id="totals-start-date" required/>
                                <span class="invalid-feedback"><?php echo $data['startdate_err']; ?></span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="enddate">End Date:  <sup>*</sup></label>
                                <input type="text" class="form-control <?php echo (!empty($data['enddate_err'])) ? ' is-invalid' : '';?>"
                                       value="<?php echo $data['enddate']; ?>" name="enddate" id="totals-end-date" required/>
                                <span class="invalid-feedback"><?php echo $data['enddate_err']; ?></span>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Show Totals" class="btn btn-success btn-block" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php if ($data['is_postback']):?>
    <div class="row mt-3">
        <div class="col-lg-6 mx-auto">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <th class="h3" scope="col">Expense Name</th>
                    <th class="h3 text-right" scope="col">Total</th>
                </thead>
                <tbody>
                <?php foreach ($data['totals_array'] as $item):?>
                    <tr>
                        <td class="h4"><?php echo $item['Expense Name']; ?></td>
                        <td class="h4 text-right"><?php echo $item['Total']; ?></td>
                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/inc/footer.php';?>