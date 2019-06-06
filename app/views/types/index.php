<?php require APPROOT . '/views/inc/header.php'; ?>

<?php require APPROOT . '/views/inc/navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto mt-3">
            <table class="table table-hover">
                <thead class="thead-light">
                <tr>
                    <th scope="col">Expense Name</th>
                    <th scope="col" class="text-right">Change</th>
                </tr>
                </thead>
                <tbody>
                    <?php $all_types=$data['types_array'];?>
                    <?php foreach ($all_types as $type): ?>
                    <tr>
                        <td><?php echo $type['expense_name']; ?></td>
                        <td class="text-right"><a href="<?php echo URLROOT; ?>/types/change/<?php echo $type['id']; ?>">Change/Delete</a></td>
                    </tr>

                    <?php endforeach; ?>


                </tbody>
            </table>
        </div>
    </div>
</div>


<?php require APPROOT . '/views/inc/footer.php';?>