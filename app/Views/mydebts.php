<?=$this->extend('default_dashboard')?>
<?=$this->section('dash_content')?>

<div style="display: flex; flex-wrap: wrap; justify-content: flex-start;align-items: flex-start;">

    <?php if (!empty($debts)) {
        foreach ($debts as $debt): ?>
        <?php if ($debt['type'] == "cash"): ?>
                <div class="card"
                     style="margin: 10px;background: linear-gradient(to right, #11998e, #38ef7d); border: none;">
                    <svg width="108px" height="108px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M694.59 511.92c-121.21 0-219.47 98.26-219.47 219.47s98.26 219.47 219.47 219.47 219.47-98.26 219.47-219.47-98.26-219.47-219.47-219.47z m0 365.79c-80.69 0-146.33-65.64-146.33-146.33 0-80.69 65.64-146.33 146.33-146.33 80.68 0 146.33 65.64 146.33 146.33 0 80.69-65.65 146.33-146.33 146.33z" fill="#ffffff"></path><path d="M200.01 814.55c-23.52-39.54-24.45-87.29-2.48-127.71L411.7 292.57h127.24l101.25 184.96 64.14-35.11-82.03-149.85h35.02v-73.14h-33.38l69.95-146.29H255.02l76.34 146.29H291.6v73.14h36.84l-195.2 359.34c-34.39 63.34-32.93 138.11 3.91 200.04s101.86 98.91 173.91 98.91h126.66v-73.14H311.06c-46.02-0.01-87.53-23.61-111.05-63.17z m175.68-668.26h202.14l-34.98 73.14H413.86l-38.17-73.14zM722.03 633.93h-54.86v112.66l89.87 89.09 38.61-38.97-73.62-72.98z" fill="#ffffff"></path></g></svg>
                    <div style="font-size: 30px; color: white"><?php echo $debt['name'] ?></div>
                    <div style="font-size: 30px; color: white" data-bs-whatever="cash" data-bs-currency="<?php echo $debt['currency']?>" data-bs-date="<?php echo $debt['date']?>"><?php if ($debt['currency'] == 'lira'): echo '₺ '; elseif ($debt['currency'] == 'dollar'): echo '$ '; elseif ($debt['currency'] == 'euro'): echo '€ '; endif;
                        if ($debt['amount']==0): echo '0'; else: echo $debt['amount']+0; endif; ?></div>
                    <div style="color: white">Payment Due Date: <?php $date = date("d-m-Y", strtotime($debt['date'])); echo $date; ?></div>
                    <div class="overlay"></div>
                    <button class="card-btn" data-bs-toggle="modal" data-bs-target="#EditDebtModal" data-bs-whatever="<?php echo $debt['id']; ?>">Edit</button>
                </div>
        <?php endif; ?>
            <?php if ($debt['type'] == "credit"): ?>
                <div class="card"
                     style="margin: 10px;background: linear-gradient(to right, #ffb75e, #ed8f03); border: none;">
                    <svg width="108px" height="108px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#ffffff" stroke-width="2.304"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><circle cx="26.67" cy="32.67" r="2.67"></circle><circle cx="37.33" cy="43.33" r="2.67"></circle><line x1="37.33" y1="30" x2="26.67" y2="46"></line><rect x="8" y="24" width="48" height="28"></rect><line x1="12" y1="18" x2="52" y2="18"></line><line x1="16" y1="12" x2="48" y2="12"></line></g></svg>
                    <div style="font-size: 30px; color: white"><?php echo $debt['name'] ?></div>
                    <div style="font-size: 30px; color: white" data-bs-whatever="credit" data-bs-currency="<?php echo $debt['currency']?>" data-bs-date="<?php echo $debt['date']?>"><?php if ($debt['currency'] == 'lira'): echo '₺ '; elseif ($debt['currency'] == 'dollar'): echo '$ '; elseif ($debt['currency'] == 'euro'): echo '€ '; endif;
                        if ($debt['amount']==0): echo '0'; else: echo $debt['amount']+0; endif; ?></div>
                    <div style="color: white">Payment Due Date: <?php $date = date("d-m-Y", strtotime($debt['date'])); echo $date; ?></div>
                    <div class="overlay"></div>
                    <button class="card-btn" data-bs-toggle="modal" data-bs-target="#EditDebtModal" data-bs-whatever="<?php echo $debt['id']; ?>">Edit</button>
                </div>
            <?php endif; ?>
            <?php if ($debt['type'] == "credit-card"): ?>
                <div class="card"
                     style="margin: 10px;background: linear-gradient(to right, #e53935, #e35d5b); border: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="108" height="108" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;"><path d="M20 4H4c-1.103 0-2 .897-2 2v2h20V6c0-1.103-.897-2-2-2zM2 18c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2v-6H2v6zm3-3h6v2H5v-2z"></path></svg>
                    <div style="font-size: 30px; color: white"><?php echo $debt['name'] ?></div>
                    <div style="font-size: 30px; color: white" data-bs-whatever="credit-card" data-bs-currency="<?php echo $debt['currency']?>" data-bs-date="<?php echo $debt['date']?>"><?php if ($debt['currency'] == 'lira'): echo '₺ '; elseif ($debt['currency'] == 'dollar'): echo '$ '; elseif ($debt['currency'] == 'euro'): echo '€ '; endif;
                        if ($debt['amount']==0): echo '0'; else: echo $debt['amount']+0; endif; ?></div>
                    <div style="color: white">Payment Due Date: <?php $date = date("d-m-Y", strtotime($debt['date'])); echo $date; ?></div>
                    <div class="overlay"></div>
                    <button class="card-btn" data-bs-toggle="modal" data-bs-target="#EditDebtModal" data-bs-whatever="<?php echo $debt['id']; ?>">Edit</button>
                </div>
            <?php endif; ?>
        <?php endforeach;
    } ?>






</div>
<button class="btnn"  data-bs-toggle="modal" data-bs-target="#NewDebtModal">Add Debt</button>






<div class="modal fade" id="NewDebtModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 10px">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="NewDebtModalLabel">New Debt</h1>
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
                        <label for="debt-name-input" class="col-form-label">Debt Name</label>
                        <input type="text" class="form-control" id="debt-name-input" name="name">
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

<div class="modal fade" id="EditDebtModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 10px">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="EditDebtModalLabel">Edit Debt</h1>
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

                <form id="delete-debt-form" action="delete-debt" method="post">
                    <input type="hidden" id="del_acc_num" name="id">
                </form>

                <form id="edit-debt-form" action="edit-debt" method="post">
                    <input type="hidden" id="acc_num" name="id">
                    <div class="mb-3">
                        <label for="edit-debt-name-input" class="col-form-label">Debt Name</label>
                        <input type="text" class="form-control" id="edit-debt-name-input" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="edit-type-input" class="col-form-label">Type of Debt</label>
                        <select class="form-select" aria-label="Type of account" id="edit-type-input" disabled>

                        </select>
                    </div>
                    <div class="mb-3 currency-container">
                        <label for="edit-currency-input" class="col-form-label">Currency</label>
                        <select class="form-select" aria-label="Currency" id="edit-currency-input" disabled>

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-date-input" class="col-form-label">Payment Due Date</label>
                        <input type="date" class="form-control" id="edit-date-input" min="<?php echo date('Y-m-d'); ?>" name="date">
                    </div>


                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="delete-debt-form-submitbutton">Delete Debt</button>
                <button type="button" class="btn btn-primary" id="edit-debt-form-submitbutton">Save changes</button>
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

    const editModal = document.getElementById('EditDebtModal')
    if (editModal) {
        editModal.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const button = event.relatedTarget
            // Extract info from data-bs-* attributes
            const recipient = button.getAttribute('data-bs-whatever')
            const name=button.parentElement.firstElementChild.nextElementSibling.innerText;
            const type=button.parentElement.firstElementChild.nextElementSibling.nextElementSibling.getAttribute('data-bs-whatever');
            const currency=button.parentElement.firstElementChild.nextElementSibling.nextElementSibling.getAttribute('data-bs-currency');
            const date=button.parentElement.firstElementChild.nextElementSibling.nextElementSibling.getAttribute('data-bs-date');
            // If necessary, you could initiate an Ajax request here
            // and then do the updating in a callback.

            // Update the modal's content.
            const modalId = editModal.querySelector('#acc_num');
            const modalDelId= editModal.querySelector('#del_acc_num');
            const modalName = editModal.querySelector('#edit-debt-name-input');
            const modalType = editModal.querySelector('#edit-type-input');
            const modalCurrency = editModal.querySelector('#edit-currency-input');
            const modalDate = editModal.querySelector('#edit-date-input');


            modalId.value = recipient;
            modalDelId.value = recipient;
            modalName.value = name;
            const option = document.createElement("option");
            if (type=="cash"){
                option.value = "cash";
                option.innerText = "Cash";
            }
            else if (type=="credit"){
                option.value = "credit";
                option.innerText = "Credit";
            }
            else if (type=="credit-card"){
                option.value = "credit-card";
                option.innerText = "Credit Card";
            }
            option.setAttribute("selected", "true");
            modalType.appendChild(option);
            const option2 = document.createElement("option");
            option2.setAttribute("selected", "true");
            if (currency=="lira"){
                option2.value = "lira";
                option2.innerText = "₺";
            }
            else if (currency=="dollar"){
                option2.value = "dollar";
                option2.innerText = "$";
            }
            else if (currency=="euro"){
                option2.value = "euro";
                option2.innerText = "€";
            }
            modalCurrency.appendChild(option2);
            modalDate.value = date;
        })
    }



    function formsub() {
        document.getElementById("create-debt-form").submit();
    }

    document.getElementById("create-debt-form-submitbutton").addEventListener("click", formsub);

    function editformsub() {
        document.getElementById("edit-debt-form").submit();
    }

    document.getElementById("edit-debt-form-submitbutton").addEventListener("click", editformsub);

    function deleteformsub() {
        document.getElementById("delete-debt-form").submit();
    }
    document.getElementById("delete-debt-form-submitbutton").addEventListener("click", deleteformsub);

</script>

<?=$this->endSection()?>
