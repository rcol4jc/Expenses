<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<div class="container">
    <h1><?php echo $data['title']; ?></h1>
    <div class="row">
        <div class="col">
            <a href="<?php echo URLROOT; ?>/types/totals" class="btn btn-secondary btn-block btn-lg">Totals</a>
            <a href="<?php echo URLROOT; ?>/types/add" class="btn btn-secondary btn-block btn-lg">Add Expense Type</a>
            <a href="<?php echo URLROOT; ?>/types" class="btn btn-secondary btn-block btn-lg">View Expense Types</a>
            <a href="<?php echo URLROOT; ?>/expenses/add" class="btn btn-secondary btn-block btn-lg">Add Expense Entry</a>
            <a href="<?php echo URLROOT; ?>/expenses" class="btn btn-secondary btn-block btn-lg">View Expenses</a>
        </div>
    </div>
</div>


<?php require APPROOT . '/views/inc/footer.php';?>