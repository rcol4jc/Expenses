<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <div class="container">
    <a class="navbar-brand" href="<?php echo URLROOT; ?>/pages">Expenses App</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
        <div class="collapse navbar-collapse" id="navbarsExampleDefault">

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLROOT; ?>/expenses/add">Add Expense <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLROOT; ?>/types/add">Add Expense Type</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLROOT; ?>/types/totals" >Get Totals</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Expenses</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/expenses/">View Expenses</a>
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/expenses/add">Add Expense</a>
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/types/totals">Totals</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Expense Types</a>
                <div class="dropdown-menu" aria-labelledby="dropdown02">
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/types/">View Expense Type</a>
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/types/add">Add Expense Type</a>
                </div>
            </li>

        </ul>
    </div>
    </div>
</nav>

