<?=$this->extend('default_dashboard')?>
<?=$this->section('dash_content')?>

<div style="display: flex; flex-wrap: wrap; justify-content: flex-start;align-items: flex-start;">
    <div class="card" style="margin: 10px">
        <svg xmlns="http://www.w3.org/2000/svg" width="108" height="108" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;"><path d="M16 12h2v4h-2z"></path><path d="M20 7V5c0-1.103-.897-2-2-2H5C3.346 3 2 4.346 2 6v12c0 2.201 1.794 3 3 3h15c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zM5 5h13v2H5a1.001 1.001 0 0 1 0-2zm15 14H5.012C4.55 18.988 4 18.805 4 18V8.815c.314.113.647.185 1 .185h15v10z"></path></svg>
        <div style="font-size: 30px; color: white">Turkish Lira Debt</div>
        <div style="font-size: 30px; color: white">₺ 0</div>
        <div class="overlay"></div>
        <button class="card-btn" data-bs-toggle="modal" data-bs-target="#EditAccountModal">Edit</button>
    </div>
</div>
<button class="btnn"  data-bs-toggle="modal" data-bs-target="#NewAccountModal">Add Debt</button>






<div class="modal fade" id="NewAccountModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 10px">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="NewAccountModalLabel">New Debt</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!--                <div class="row g-3 align-items-center">-->
                <!--                    <div class="col-auto">-->
                <!--                        <label for="inputPassword6" class="col-form-label">Type of transaction</label>-->
                <!--                    </div>-->
                <!--                    <div class="col-auto">-->
                <!--                        <select class="form-select" aria-label="Default select example">-->
                <!--                            <option selected>Revenue</option>-->
                <!--                            <option value="1">Expense</option>-->
                <!--                            <option value="2">Remittance</option>-->
                <!--                        </select>-->
                <!--                    </div>-->
                <!--                </div>-->

                <form id="create-debt-form" action="create-debt" method="post">
                    <div class="mb-3">
                        <label for="account-name-input" class="col-form-label">Debt Name</label>
                        <input type="text" class="form-control" id="account-name-input" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="type-input" class="col-form-label">Type of Debt</label>
                        <select class="form-select" aria-label="Type of account" id="type-input" name="type">
                            <option selected value="cash">Cash</option>
                            <option value="credit">Credit</option>
                            <option value="credit-card">Credit Card</option>
                        </select>
                    </div>
                    <div class="mb-3 currency-container">
                        <label for="currency-input" class="col-form-label">Currency</label>
                        <select class="form-select" aria-label="Currency" id="currency-input" name="currency">
                            <option selected value="lira">₺</option>
                            <option value="dollar">$</option>
                            <option value="euro">€</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date-input" class="col-form-label">Payment Due Date</label>
                        <input type="date" class="form-control" id="date-input" min="<?php echo date('Y-m-d'); ?>" name="date">
                    </div>

                    <?php if (isset($validation_error)): ?>
                        <div class="mb-3">
                            <ul>


                                <?php foreach ($validation_error as $item): ?>
                                    <li style="color: red"><?php echo $item; ?></li>
                                <?php endforeach; ?>


                            </ul>
                        </div>
                    <?php endif; ?>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="create-debt-form-submitbutton">Create Debt</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="EditAccountModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 10px">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="EditAccountModalLabel">Edit Debt</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!--                <div class="row g-3 align-items-center">-->
                <!--                    <div class="col-auto">-->
                <!--                        <label for="inputPassword6" class="col-form-label">Type of transaction</label>-->
                <!--                    </div>-->
                <!--                    <div class="col-auto">-->
                <!--                        <select class="form-select" aria-label="Default select example">-->
                <!--                            <option selected>Revenue</option>-->
                <!--                            <option value="1">Expense</option>-->
                <!--                            <option value="2">Remittance</option>-->
                <!--                        </select>-->
                <!--                    </div>-->
                <!--                </div>-->

                <form>
                    <div class="mb-3">
                        <label for="edit-account-name-input" class="col-form-label">Debt Name</label>
                        <input type="text" class="form-control" id="edit-account-name-input">
                    </div>
                    <div class="mb-3">
                        <label for="edit-type-input" class="col-form-label">Type of Debt</label>
                        <select class="form-select" aria-label="Type of account" id="edit-type-input" disabled>
                            <option selected value="cash">Cash</option>
                            <option value="credit">Credit</option>
                            <option value="credit-card">Credit Card</option>
                        </select>
                    </div>
                    <div class="mb-3 currency-container">
                        <label for="edit-currency-input" class="col-form-label">Currency</label>
                        <select class="form-select" aria-label="Currency" id="edit-currency-input" disabled>
                            <option selected value="lira">₺</option>
                            <option value="dollar">$</option>
                            <option value="euro">€</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date-input" class="col-form-label">Payment Due Date</label>
                        <input type="date" class="form-control" id="date-input" min="<?php echo date('Y-m-d'); ?>">
                    </div>


                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger">Delete Account</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<style>

    .card {
        position: relative;
        width: 350px;
        height: 250px;
        background-image: linear-gradient(-45deg, #f89b29 0%, #ff0f7b 100% );
        border-radius: 10px;
        display: flex;
        padding: 10px 30px;
        flex-direction: column;
        gap: 10px;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }



    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        background-color: rgba(0, 0, 0, 0.6);
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .card:hover .overlay {
        opacity: 1;
        pointer-events: auto;
    }

    .card .card-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        font-weight: 600;
        padding: 10px 20px;
        font-size: 16px;
        transform: translate(-50%, -50%);
        background-color: #ffffff;
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 999;
        border: none;
        opacity: 0;
        scale: 0;
        transform-origin: 0 0;
        box-shadow: 0 0 10px 10px rgba(0, 0, 0, 0.15);
        transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
    }

    .card:hover .card-btn {
        opacity: 1;
        scale: 1;
    }

    .card .card-btn:hover {
        box-shadow: 0 0 0 5px rgba(0, 0, 0, 0.3);
    }

    .card .card-btn:active {
        scale: 0.95;
    }

    .overlay::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        width: 100%;
        height: 100%;
        background-image: linear-gradient(-45deg, #f89b2980 0%, #ff0f7b80 100% );
        transition: transform 0.5s ease;
    }

    .card:hover .overlay::after {
        transform: translate(-50%, -50%) scale(2);
    }

    .btnn {
        transition: all 0.3s ease-in-out;
        font-family: "Dosis", sans-serif;
    }

    .btnn {
        width: 150px;
        height: 60px;
        border-radius: 50px;
        background-image: linear-gradient(135deg, #feb692 0%, #ea5455 100%);
        box-shadow: 0 20px 30px -6px rgba(238, 103, 97, 0.5);
        outline: none;
        cursor: pointer;
        border: none;
        font-size: 14px;
        color: white;
        position: fixed;
        bottom: 20px; /* Sayfanın alt kenarına ne kadar yakın olmasını istediğinizi ayarlayın */
        right: 20px;
        z-index: 999;
    }

    .btnn:hover {
        transform: translateY(3px);
        box-shadow: none;
    }

    .btnn:active {
        opacity: 0.5;
    }



</style>

<script>

    function formsub() {
        document.getElementById("create-debt-form").submit();
    }

    document.getElementById("create-debt-form-submitbutton").addEventListener("click", formsub);

</script>

<?=$this->endSection()?>
