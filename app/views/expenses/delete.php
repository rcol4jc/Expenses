<?php require APPROOT . '/views/inc/header.php'; ?>

<?php require APPROOT . '/views/inc/navbar.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <?php if (!empty($data['delete_failure'])){
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $data['delete_failure']?>
                    </div>
                    <?php
                } ?>
                <h3 class="display-5"><?php echo $data['title']; ?></h3>
                <p>Are you sure you want to delete this entry?</p>
                <form class="form" action="<?php echo URLROOT; ?>/expenses/delete/<?php echo $data['id']; ?>" method="post">
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Yes" class="btn btn-block btn-danger" />
                        </div>
                        <div class="col">
                            <a href="<?php echo URLROOT ?>/expenses/change/<?php echo $data['id']; ?>" class="btn btn-success btn-block">No</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
