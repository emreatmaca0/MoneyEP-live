<?php //if (!is_null($user_name)): ?>
<!--    <p>Hello --><?php //echo $user_name; ?><!--</p>-->
<?php //else: ?>
<!--    <p>No user found.</p>-->
<?php //endif; ?>


<?=$this->extend('default_dashboard')?>
<?=$this->section('dash_content')?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div style="display: flex; flex-wrap: wrap; justify-content: center;align-items: flex-start;">
    <div class="card" style="display: inline-block;padding: 27px;margin: 31px;border-radius: 15px;box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="1em" height="1em" fill="currentColor" style="margin: 3;color: #71dd37;font-size: 53px;">
            <!--! Font Awesome Free 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. -->
            <path d="M507.9 196.4l-104-153.8C399.4 35.95 391.1 32 384 32H127.1C120 32 112.6 35.95 108.1 42.56l-103.1 153.8c-6.312 9.297-5.281 21.72 2.406 29.89l231.1 246.2C243.1 477.3 249.4 480 256 480s12.94-2.734 17.47-7.547l232-246.2C513.2 218.1 514.2 205.7 507.9 196.4zM382.5 96.59L446.1 192h-140.1L382.5 96.59zM256 178.9L177.6 80h156.7L256 178.9zM129.5 96.59L205.1 192H65.04L129.5 96.59zM256 421L85.42 240h341.2L256 421z"></path>
        </svg>
        <p class="text-muted" style="font-size: 20px;margin-bottom: 0px;margin-left: 3px;">Monthly Income</p>
        <p style="font-size: 49px;margin-bottom: 0px;font-family: ABeeZee, sans-serif;">72,24₺</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="-64 0 512 512" width="1em" height="1em" fill="currentColor" style="margin: 0;font-size: 22px;color: #71DD37;">
            <!--! Font Awesome Free 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. -->
            <path d="M374.6 246.6C368.4 252.9 360.2 256 352 256s-16.38-3.125-22.62-9.375L224 141.3V448c0 17.69-14.33 31.1-31.1 31.1S160 465.7 160 448V141.3L54.63 246.6c-12.5 12.5-32.75 12.5-45.25 0s-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0l160 160C387.1 213.9 387.1 234.1 374.6 246.6z"></path>
        </svg><small style="color: #71DD37;font-size: 22px;">+72.80%</small>
    </div>
    <div class="card" style="display: inline-block;padding: 27px;margin: 31px;border-radius: 15px;box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -32 576 576" width="1em" height="1em" fill="currentColor" style="margin: 3;color: #696CFF;font-size: 53px;">
            <!--! Font Awesome Free 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. -->
            <path d="M400 96L399.1 96.66C394.7 96.22 389.4 96 384 96H256C239.5 96 223.5 98.08 208.2 102C208.1 100 208 98.02 208 96C208 42.98 250.1 0 304 0C357 0 400 42.98 400 96zM384 128C387.5 128 390.1 128.1 394.4 128.3C398.7 128.6 402.9 129 407 129.6C424.6 109.1 450.8 96 480 96H512L493.2 171.1C509.1 185.9 521.9 203.9 530.7 224H544C561.7 224 576 238.3 576 256V352C576 369.7 561.7 384 544 384H512C502.9 396.1 492.1 406.9 480 416V480C480 497.7 465.7 512 448 512H416C398.3 512 384 497.7 384 480V448H256V480C256 497.7 241.7 512 224 512H192C174.3 512 160 497.7 160 480V416C125.1 389.8 101.3 349.8 96.79 304H68C30.44 304 0 273.6 0 236C0 198.4 30.44 168 68 168H72C85.25 168 96 178.7 96 192C96 205.3 85.25 216 72 216H68C56.95 216 48 224.1 48 236C48 247 56.95 256 68 256H99.2C111.3 196.2 156.9 148.5 215.5 133.2C228.4 129.8 241.1 128 256 128H384zM424 240C410.7 240 400 250.7 400 264C400 277.3 410.7 288 424 288C437.3 288 448 277.3 448 264C448 250.7 437.3 240 424 240z"></path>
        </svg>
        <p class="text-muted" style="font-size: 20px;margin-bottom: 0px;margin-left: 3px;">My Assets</p>
        <p style="font-size: 49px;margin-bottom: 0px;font-family: ABeeZee, sans-serif;">14,85₺</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="-64 0 512 512" width="1em" height="1em" fill="currentColor" style="margin: 0;font-size: 22px;color: #FF3E1D;">
            <!--! Font Awesome Free 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. -->
            <path d="M374.6 310.6l-160 160C208.4 476.9 200.2 480 192 480s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 370.8V64c0-17.69 14.33-31.1 31.1-31.1S224 46.31 224 64v306.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0S387.1 298.1 374.6 310.6z"></path>
        </svg><small style="color: #FF3E1D;font-size: 22px;">-28.14%</small>
    </div>
    <div class="card" style="display: inline-block;padding: 27px;margin: 31px;border-radius: 15px;box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="1em" height="1em" fill="currentColor" style="margin: 3;color: #FF3E1D;font-size: 53px;">
            <!--! Font Awesome Free 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. -->
            <path d="M144.6 24.88C137.5 14.24 145.1 0 157.9 0H354.1C366.9 0 374.5 14.24 367.4 24.88L320 96H192L144.6 24.88zM332.1 136.4C389.7 172.7 512 250.9 512 416C512 469 469 512 416 512H96C42.98 512 0 469 0 416C0 250.9 122.3 172.7 179 136.4C183.9 133.3 188.2 130.5 192 128H320C323.8 130.5 328.1 133.3 332.1 136.4V136.4zM336.1 288.1C346.3 279.6 346.3 264.4 336.1 255C327.6 245.7 312.4 245.7 303 255L256 302.1L208.1 255C199.6 245.7 184.4 245.7 175 255C165.7 264.4 165.7 279.6 175 288.1L222.1 336L175 383C165.7 392.4 165.7 407.6 175 416.1C184.4 426.3 199.6 426.3 208.1 416.1L256 369.9L303 416.1C312.4 426.3 327.6 426.3 336.1 416.1C346.3 407.6 346.3 392.4 336.1 383L289.9 336L336.1 288.1z"></path>
        </svg>
        <p class="text-muted" style="font-size: 20px;margin-bottom: 0px;margin-left: 3px;">Monthly Expense</p>
        <p style="font-size: 49px;margin-bottom: 0px;font-family: ABeeZee, sans-serif;">24,46₺</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="-64 0 512 512" width="1em" height="1em" fill="currentColor" style="margin: 0;font-size: 22px;color: #71DD37;">
            <!--! Font Awesome Free 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. -->
            <path d="M374.6 310.6l-160 160C208.4 476.9 200.2 480 192 480s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 370.8V64c0-17.69 14.33-31.1 31.1-31.1S224 46.31 224 64v306.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0S387.1 298.1 374.6 310.6z"></path>
        </svg><small style="color: #71DD37;font-size: 22px;">-14.82%</small>
    </div>
    <div class="card" style="display: inline-block;padding: 27px;margin: 31px;border-radius: 15px;box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="-32 0 512 512" width="1em" height="1em" fill="currentColor" style="margin: 3;color: #03C3EC;font-size: 53px;">
            <!--! Font Awesome Free 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. -->
            <path d="M272 0C289.7 0 304 14.33 304 32C304 49.67 289.7 64 272 64H256V98.45C293.5 104.2 327.7 120 355.7 143L377.4 121.4C389.9 108.9 410.1 108.9 422.6 121.4C435.1 133.9 435.1 154.1 422.6 166.6L398.5 190.8C419.7 223.3 432 262.2 432 304C432 418.9 338.9 512 224 512C109.1 512 16 418.9 16 304C16 200 92.32 113.8 192 98.45V64H176C158.3 64 144 49.67 144 32C144 14.33 158.3 0 176 0L272 0zM248 192C248 178.7 237.3 168 224 168C210.7 168 200 178.7 200 192V320C200 333.3 210.7 344 224 344C237.3 344 248 333.3 248 320V192z"></path>
        </svg>
        <p class="text-muted" style="font-size: 20px;margin-bottom: 0px;margin-left: 3px;">My Debts</p>
        <p style="font-size: 49px;margin-bottom: 0px;font-family: ABeeZee, sans-serif;">42,75₺</p><svg xmlns="http://www.w3.org/2000/svg" viewBox="-64 0 512 512" width="1em" height="1em" fill="currentColor" style="margin: 0;font-size: 22px;color: #71DD37;">
            <!--! Font Awesome Free 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. -->
            <path d="M374.6 310.6l-160 160C208.4 476.9 200.2 480 192 480s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 370.8V64c0-17.69 14.33-31.1 31.1-31.1S224 46.31 224 64v306.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0S387.1 298.1 374.6 310.6z"></path>
        </svg><small style="color: #71DD37;font-size: 22px;">-14.45%</small>
    </div>




</div>

<div style="display: flex; flex-wrap: wrap; justify-content: space-around;align-items: flex-start;">
    <div class="card" style="font-family: ABeeZee, sans-serif;width: 440px;height: 500px;box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;border-radius: 15px;">
        <div class="card-header d-flex justify-content-between align-items-center"><button type="button" style="border: solid 1px gainsboro;border-radius: 15px;padding: 5px;" id="myBtn" data-bs-toggle="modal" data-bs-target="#NewRecordModal">New record...</button><span>
                <select class="form-select-sm">
                    <optgroup label="This is a group">
                        <option value="12" selected="">This is item 1</option>
                        <option value="13">This is item 2</option>
                        <option value="14">This is item 3</option>
                    </optgroup>
                </select></span></div>
        <div class="card-body table-responsive">
            <table class="table table-hover" id="table">
                <thead>
                <tr>
                    <th scope="col" onclick="sortTable(0)" class="tarihth">Date</th>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                    <th scope="col">Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td scope="row">01/08/2023</td>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
                <tr>
                    <td scope="row">2</td>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                </tr>
                <tr>
                    <td scope="row">3</td>
                    <td>Larry the Bird</td>
                    <td>@twitter</td>
                    <td>Cell 4</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Cell 2</td>
                    <td>Cell 3</td>
                    <td>Cell 4</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Cell 2</td>
                    <td>Cell 3</td>
                    <td>Cell 4</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Cell 2</td>
                    <td>Cell 3</td>
                    <td>Cell 4</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Cell 2</td>
                    <td>Cell 3</td>
                    <td>Cell 4</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>Cell 2</td>
                    <td>Cell 3</td>
                    <td>Cell 4</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div class="chart-container" style="position: relative; height:60vh; width:40vw; padding-bottom: 60px;">
        <h2 class="" style="padding: 5px; color: #566A7F;">Expense Statistics</h2>
    <canvas id="chDonut1" style="margin: 0"></canvas>
    </div>

</div>


































<div class="modal fade" id="NewRecordModal" tabindex="-1" aria-labelledby="NewRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 10px">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="NewRecordModalLabel">New Record</h1>
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

                <form id="create-record-form" action="create-record" method="post">
                    <div class="mb-3">
                        <label for="date-input" class="col-form-label">Date</label>
                        <input type="date" class="form-control" id="date-input" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" name="date">
                    </div>
                    <div class="mb-3">
                        <label for="type-input" class="col-form-label">Type of transaction</label>
                        <select class="form-select" aria-label="Type of transaction" id="type-input" name="type">
                            <option selected value="revenue">Revenue</option>
                            <option value="expense">Expense</option>
                            <option value="remittance">Remittance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount-input" class="col-form-label">Amount</label>
                        <div class="input-group mb-3">
                            <select class="form-select" aria-label="Currency" name="currency">
                                <option selected value="lira">₺</option>
                                <option value="dollar">$</option>
                                <option value="euro">€</option>
                            </select>
                            <input type="text" class="form-control" placeholder="102.55" aria-label="Amount" id="amount-input" name="amount">
                        </div>
                    </div>
                    <div class="mb-3 category-container">
                        <label for="category-input" class="col-form-label">Category</label>
                        <select class="form-select" aria-label="Category" id="category-input" name="category">
                            <option selected value="salary">Salary</option>
                            <option value="rental-income">Rental Income</option>
                            <option value="freebie">Freebie</option>
                            <option value="investment">Investment</option>
                            <option value="sales">Sales</option>
                            <option value="debt">Debt</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="account-input" class="col-form-label">Account</label>
                        <select class="form-select" aria-label="Account" id="account-input" name="account">
                            <?php foreach ($accounts as $account): ?>
                            <option value="<?php echo $account['id']; ?>"><?php echo $account['name']; ?></option>
                            <?php endforeach; ?>
                            <option value="0">Create a new account...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description-input" class="col-form-label">Description</label>
                        <input type="text" class="form-control" id="description-input" name="description">
                    </div>
                    <div class="mb-3">
                        <label for="dd-input" class="col-form-label">Detailed Description</label>
                        <textarea class="form-control" id="dd-input" name="dd"></textarea>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<script>


const amount= document.getElementById("category-input").parentElement.previousElementSibling;
const clone = amount.cloneNode(true);
clone.firstElementChild.innerHTML="Commission";
clone.firstElementChild.setAttribute("for","commission-input");
clone.firstElementChild.for="commission-input";
clone.lastElementChild.firstElementChild.name="commission_currency";
clone.lastElementChild.lastElementChild.value=0;
clone.lastElementChild.lastElementChild.name="commission_amount";
clone.id="commission-area";
clone.lastElementChild.lastElementChild.id="commission-input";
clone.lastElementChild.lastElementChild.ariaLabel="Commission";




    // Seçenek değiştikçe çalışacak işlevi tanımlayın
    function optionChanged() {
        var selectedOption = document.getElementById("type-input").value;
        if (selectedOption == "revenue") {
            if(document.getElementById("debt-container")!=null){
                document.getElementById("debt-container").remove();
            }
            if(document.getElementById("commission-area")!=null){
                document.getElementById("commission-area").remove();
            }
            document.getElementById("category-input").previousElementSibling.innerHTML="Category";
            document.getElementById("category-input").name="category";
            document.getElementById("category-input").innerHTML
            ='<option selected value="salary">Salary</option><option value="rental-income">Rental Income</option><option value="freebie">Freebie</option> <option value="investment">Investment</option> <option value="sales">Sales</option><option value="debt">Debt</option> <option value="other">Other</option>';
        }
        else if(selectedOption=='expense'){
            if(document.getElementById("debt-container")!=null){
                document.getElementById("debt-container").remove();
            }
            if (document.getElementById("commission-area")!=null){
                document.getElementById("commission-area").remove();
            }
            document.getElementById("category-input").previousElementSibling.innerHTML="Category";
            document.getElementById("category-input").name="category";
            document.getElementById("category-input").innerHTML
            ='<option selected value="rent-expense">Rent Expense</option><option value="food">Food</option> <option value="transport">Transport</option> <option value="Education">Education</option> <option value="medical">Medical</option> <option value="entertainment">Entertainment</option> <option value="clothing">Clothing</option> <option value="debt-payment">Debt Payment</option><option value="tax">Tax</option><option value="insurance-premium">Insurance Premium</option><option value="bills">Bills</option> <option value="other">Other</option>';

        }
        else {
            if(document.getElementById("debt-container")!=null){
                document.getElementById("debt-container").remove();
            }
            var category_input=document.getElementById("category-input");
            category_input.parentElement.previousElementSibling.append(clone);
            category_input.previousElementSibling.innerHTML="Source Account";
            category_input.name="source";
            category_input.innerHTML="<?php foreach ($accounts as $account): ?><option value='<?php echo $account['id']; ?>'><?php echo $account['name']; ?></option><?php endforeach; ?>";
        }
    }

    // Seçenek değişikliğini dinlemek için olayı ekleyin
    document.getElementById("type-input").addEventListener("change", optionChanged);
    document.getElementById("category-input").addEventListener("change", categoryChanged);

    function categoryChanged(){
        var category_input=document.getElementById("category-input");
        if(category_input.value=="debt"||category_input.value=="debt-payment")
        {
            const debt= category_input.parentElement;
            const clone = debt.cloneNode(true);
            clone.id="debt-container"
            clone.firstElementChild.innerHTML="Debt";
            clone.firstElementChild.setAttribute("for","debt-input");
            clone.lastElementChild.id="debt-input";
            clone.lastElementChild.ariaLabel="Debt";
            clone.lastElementChild.name="debt";
            clone.lastElementChild.innerHTML=
                <?php foreach ($debts as $debt): ?>
                '<option value="<?php echo $debt['id']; ?>"><?php echo $debt['name']; ?></option>'+
                <?php endforeach; ?>
            category_input.parentElement.append(clone);
        }
        else
        {
            document.getElementById("debt-container").remove();
        }

    }

    document.getElementById("account-input").addEventListener("change", accountChanged);
    function accountChanged(){
        var account_input=document.getElementById("account-input");
        if(account_input.value==0)
        {
            window.location.href="/myassets";
        }
    }



    var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d'];
    var donutOptions = {
        cutoutPercentage: 85,
        legend: {position:'bottom', padding:5, labels: {pointStyle:'circle', usePointStyle:true}}
    };

    // donut 1
    var chDonutData1 = {
        labels: ['Bootstrap', 'Popper', 'Other'],
        datasets: [
            {
                backgroundColor: colors.slice(0,3),
                borderWidth: 0,
                data: [75, 12.5, 12.5]
            }
        ]
    };

    var chDonut1 = document.getElementById("chDonut1");
    if (chDonut1) {
        new Chart(chDonut1, {
            type: 'pie',
            data: chDonutData1,
            options: donutOptions
        });
    }


    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("table");
        switching = true;
        // Set the sorting direction to ascending:
        dir = "asc";
        /* Make a loop that will continue until
        no switching has been done: */
        while (switching) {
            // Start by saying: no switching is done:
            switching = false;
            rows = table.rows;
            /* Loop through all table rows (except the
            first, which contains table headers): */
            for (i = 1; i < (rows.length - 1); i++) {
                // Start by saying there should be no switching:
                shouldSwitch = false;
                /* Get the two elements you want to compare,
                one from current row and one from the next: */
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /* Check if the two rows should switch place,
                based on the direction, asc or desc: */
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                /* If a switch has been marked, make the switch
                and mark that a switch has been done: */
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                // Each time a switch is done, increase this count by 1:
                switchcount ++;
            } else {
                /* If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again. */
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
</script>
<?=$this->endSection()?>






