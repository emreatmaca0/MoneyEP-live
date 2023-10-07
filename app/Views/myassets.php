<?= $this->extend('default_dashboard') ?>
<?= $this->section('dash_content') ?>

<div style="display: flex; flex-wrap: wrap; justify-content: flex-start;align-items: flex-start;">


    <?php foreach ($accounts as $account): ?>
        <?php if ($account['type'] == "cash"): ?>
            <div class="card" style="margin: 10px; background: linear-gradient(to right, #49a23a, #0f9b0f); border: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="108" height="108" viewBox="0 0 24 24"
                     style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                    <path d="M16 12h2v4h-2z"></path>
                    <path d="M20 7V5c0-1.103-.897-2-2-2H5C3.346 3 2 4.346 2 6v12c0 2.201 1.794 3 3 3h15c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zM5 5h13v2H5a1.001 1.001 0 0 1 0-2zm15 14H5.012C4.55 18.988 4 18.805 4 18V8.815c.314.113.647.185 1 .185h15v10z"></path>
                </svg>
                <div style="font-size: 30px; color: white"><?php echo $account['name'] ?></div>
                <div style="font-size: 30px; color: white" data-bs-whatever="cash" data-bs-currency="<?php echo $account['currency']?>"><?php if ($account['currency'] == 'lira'): echo '₺ '; elseif ($account['currency'] == 'dollar'): echo '$ '; elseif ($account['currency'] == 'euro'): echo '€ '; endif;
                    if ($account['amount']==0): echo '0'; else: echo $account['amount']+0; endif; ?></div>
                <div class="overlay"></div>
                <button class="card-btn" data-bs-toggle="modal" data-bs-target="#EditAccountModal" data-bs-whatever="<?php echo $account['id']; ?>">Edit</button>
            </div>
        <?php endif; ?>
        <?php if ($account['type'] == "bank-account"): ?>
            <div class="card" style="margin: 10px;background: linear-gradient(to right, #ce3333, #dd1818);border: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="108" height="108" viewBox="0 0 24 24"
                     style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                    <path d="M2 8v4.001h1V18H2v3h16l3 .001V21h1v-3h-1v-5.999h1V8L12 2 2 8zm4 10v-5.999h2V18H6zm5 0v-5.999h2V18h-2zm7 0h-2v-5.999h2V18zM14 8a2 2 0 1 1-4.001-.001A2 2 0 0 1 14 8z"></path>
                </svg>
                <div style="font-size: 30px; color: white"><?php echo $account['name'] ?></div>
                <div style="font-size: 30px; color: white" data-bs-whatever="bank-account" data-bs-currency="<?php echo $account['currency']?>"><?php if ($account['currency'] == 'lira'): echo '₺ '; elseif ($account['currency'] == 'dollar'): echo '$ '; elseif ($account['currency'] == 'euro'): echo '€ '; endif;
                    if ($account['amount']==0): echo '0'; else: echo $account['amount']+0; endif; ?></div>
                <div class="overlay"></div>
                <button class="card-btn" data-bs-toggle="modal" data-bs-target="#EditAccountModal" data-bs-whatever="<?php echo $account['id']; ?>">Edit</button>
            </div>
        <?php endif; ?>
        <?php if ($account['currency'] == "bitcoin"): ?>
            <div class="card"
                 style="margin: 10px;background-image: linear-gradient(-45deg, #ff3a3a 0%, #EF8E19 100% );border: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="108" height="108" viewBox="0 0 24 24"
                     style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                    <path d="M8 13v4H6v2h3v2h2v-2h2v2h2v-2.051c1.968-.249 3.5-1.915 3.5-3.949 0-1.32-.65-2.484-1.64-3.213A3.982 3.982 0 0 0 18 9c0-1.858-1.279-3.411-3-3.858V3h-2v2h-2V3H9v2H6v2h2v6zm6.5 4H10v-4h4.5c1.103 0 2 .897 2 2s-.897 2-2 2zM10 7h4c1.103 0 2 .897 2 2s-.897 2-2 2h-4V7z"></path>
                </svg>
                <div style="font-size: 30px; color: white"><?php echo $account['name'] ?></div>
                <div style="font-size: 30px; color: white" data-bs-whatever="crypto-wallet" data-bs-currency="<?php echo $account['currency']?>"><?php echo $temizlenmisSayi = rtrim(number_format($account['amount'], 10, '.', ''), '0'); ?></div>
                <div class="overlay"></div>
                <button class="card-btn" data-bs-toggle="modal" data-bs-target="#EditAccountModal" data-bs-whatever="<?php echo $account['id']; ?>">Edit</button>
            </div>
        <?php endif; ?>
        <?php if ($account['currency'] == "ethereum"): ?>
            <div class="card"
                 style="margin: 10px;background-image: linear-gradient(-45deg, #3F3F3F 0%, #909090 100% );border: none;">
                <svg xmlns="http://www.w3.org/2000/svg" height="108" width="108" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#ffffff}</style><path d="M311.9 260.8L160 353.6 8 260.8 160 0l151.9 260.8zM160 383.4L8 290.6 160 512l152-221.4-152 92.8z"/></svg>
                <div style="font-size: 30px; color: white"><?php echo $account['name'] ?></div>
                <div style="font-size: 30px; color: white" data-bs-whatever="crypto-wallet" data-bs-currency="<?php echo $account['currency']?>"><?php echo $temizlenmisSayi = rtrim(sprintf("%.10f", $account['amount']), '0')+0;  ?></div>
                <div style="color: white">= ₺ 25</div>
                <div class="overlay"></div>
                <button class="card-btn" data-bs-toggle="modal" data-bs-target="#EditAccountModal" data-bs-whatever="<?php echo $account['id']; ?>">Edit</button>
            </div>
        <?php endif; ?>
        <?php if ($account['currency'] == "tether"): ?>
            <div class="card"
                 style="margin: 10px;background: #26A69A;border: none;">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="108" height="108" viewBox="0 0 48 48">
                    <circle cx="24" cy="24" r="20" fill="#26a69a"></circle><rect width="18" height="5" x="15" y="13" fill="#fff"></rect><path fill="#fff" d="M24,21c-4.457,0-12,0.737-12,3.5S19.543,28,24,28s12-0.737,12-3.5S28.457,21,24,21z M24,26 c-5.523,0-10-0.895-10-2c0-1.105,4.477-2,10-2s10,0.895,10,2C34,25.105,29.523,26,24,26z"></path><path fill="#fff" d="M24,24c1.095,0,2.093-0.037,3-0.098V13h-6v10.902C21.907,23.963,22.905,24,24,24z"></path><path fill="#fff" d="M25.723,25.968c-0.111,0.004-0.223,0.007-0.336,0.01C24.932,25.991,24.472,26,24,26 s-0.932-0.009-1.387-0.021c-0.113-0.003-0.225-0.006-0.336-0.01c-0.435-0.015-0.863-0.034-1.277-0.06V36h6V25.908 C26.586,25.934,26.158,25.953,25.723,25.968z"></path>
                </svg>
                <div style="font-size: 30px; color: white"><?php echo $account['name'] ?></div>
                <div style="font-size: 30px; color: white" data-bs-whatever="crypto-wallet" data-bs-currency="<?php echo $account['currency']?>"><?php if ($account['amount']==0): echo '0'; else: echo $account['amount']+0; endif; ?></div>
                <div style="color: white">= ₺ 25</div>
                <div class="overlay"></div>
                <button class="card-btn" data-bs-toggle="modal" data-bs-target="#EditAccountModal" data-bs-whatever="<?php echo $account['id']; ?>">Edit</button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<button class="btnn" data-bs-toggle="modal" data-bs-target="#NewAccountModal">Add Account</button>


<div class="modal fade" id="NewAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 10px">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="NewAccountModalLabel">New Account</h1>
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

                <form id="create-account-form" action="create-account" method="post">
                    <div class="mb-3">
                        <label for="account-name-input" class="col-form-label">Account Name</label>
                        <input type="text" class="form-control" id="account-name-input" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="type-input" class="col-form-label">Type of Account</label>
                        <select class="form-select" aria-label="Type of account" id="type-input" name="type">
                            <option selected value="cash">Cash</option>
                            <option value="bank-account">Bank Account</option>
                            <option value="crypto-wallet">Crypto Wallet</option>
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
                <button type="button" class="btn btn-primary" id="create-account-form-submitbutton">Create Account
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="EditAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 10px">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="EditAccountModalLabel">Edit Account</h1>
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
                <form id="delete-account-form" action="delete-account" method="post">
                    <input type="hidden" id="del_acc_num" name="id">
                </form>

                <form id="edit-account-form" action="edit-account" method="post">
                    <input type="hidden" id="acc_num" name="id">
                    <div class="mb-3">
                        <label for="edit-account-name-input" class="col-form-label">Account Name</label>
                        <input type="text" class="form-control" id="edit-account-name-input" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="edit-type-input" class="col-form-label">Type of Account</label>
                        <select class="form-select" aria-label="Type of account" id="edit-type-input" disabled>

                        </select>
                    </div>
                    <div class="mb-3 currency-container">
                        <label for="edit-currency-input" class="col-form-label">Currency</label>
                        <select class="form-select" aria-label="Currency" id="edit-currency-input" disabled>

                        </select>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="delete-account-form-submitbutton">Delete Account</button>
                <button type="button" class="btn btn-primary" id="edit-account-form-submitbutton">Save changes</button>
            </div>
        </div>
    </div>
</div>
<style>

    .card {
        position: relative;
        width: 350px;
        height: 250px;
        background-image: linear-gradient(-45deg, #f89b29 0%, #ff0f7b 100%);
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
        background-image: linear-gradient(-45deg, #f89b2980 0%, #ff0f7b80 100%);
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

    const editModal = document.getElementById('EditAccountModal')
    if (editModal) {
        editModal.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const button = event.relatedTarget
            // Extract info from data-bs-* attributes
            const recipient = button.getAttribute('data-bs-whatever')
            const name=button.parentElement.firstElementChild.nextElementSibling.innerText;
            const type=button.parentElement.firstElementChild.nextElementSibling.nextElementSibling.getAttribute('data-bs-whatever');
            const currency=button.parentElement.firstElementChild.nextElementSibling.nextElementSibling.getAttribute('data-bs-currency');
            // If necessary, you could initiate an Ajax request here
            // and then do the updating in a callback.

            // Update the modal's content.
            const modalId = editModal.querySelector('#acc_num');
            const modalDelId= editModal.querySelector('#del_acc_num');
            const modalName = editModal.querySelector('#edit-account-name-input');
            const modalType = editModal.querySelector('#edit-type-input');
            const modalCurrency = editModal.querySelector('#edit-currency-input');


            modalId.value = recipient;
            modalDelId.value = recipient;
            modalName.value = name;
            const option = document.createElement("option");
            if (type=="cash"){
                option.value = "cash";
                option.innerText = "Cash";
            }
            else if (type=="bank-account"){
                option.value = "bank-account";
                option.innerText = "Bank Account";
            }
            else if (type=="crypto-wallet"){
                option.value = "crypto-wallet";
                option.innerText = "Crypto Wallet";
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
            else if (currency=="bitcoin"){
                option2.value = "bitcoin";
                option2.innerText = "Bitcoin";
            }
            else if (currency=="ethereum"){
                option2.value = "ethereum";
                option2.innerText = "Ethereum";
            }
            else if (currency=="tether"){
                option2.value = "tether";
                option2.innerText = "Tether";
            }
            modalCurrency.appendChild(option2);
        })
    }




    function formsub() {
        document.getElementById("create-account-form").submit();
    }

    document.getElementById("create-account-form-submitbutton").addEventListener("click", formsub);

    function editformsub() {
        document.getElementById("edit-account-form").submit();
    }

    document.getElementById("edit-account-form-submitbutton").addEventListener("click", editformsub);

    function deleteformsub() {
        document.getElementById("delete-account-form").submit();
    }
    document.getElementById("delete-account-form-submitbutton").addEventListener("click", deleteformsub);



    function optionChanged() {
        var selectedOption = document.getElementById("type-input").value;
        if (selectedOption == "cash") {
            document.getElementById("currency-input").innerHTML = '<option selected value="lira">₺</option><option value="dollar">$</option><option value="euro">€</option>';
        } else if (selectedOption == "bank-account") {
            document.getElementById("currency-input").innerHTML = '<option selected value="lira">₺</option><option value="dollar">$</option><option value="euro">€</option>';
        } else if (selectedOption == "crypto-wallet") {
            document.getElementById("currency-input").innerHTML = '<option selected value="bitcoin">Bitcoin</option><option value="ethereum">Ethereum</option><option value="tether">Tether</option>';
        }
    }

    document.getElementById("type-input").addEventListener("change", optionChanged);

</script>

<?= $this->endSection() ?>
