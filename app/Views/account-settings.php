<?= $this->extend('default_dashboard') ?>
<?= $this->section('dash_content') ?>

<div style="display: flex; flex-wrap: wrap; align-items: center; flex-direction: column; justify-content: space-around;">
    <div class="card" style="font-family: ABeeZee, sans-serif;width: 100%;height: min-content;box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;border-radius: 15px;margin-top: 30px">

        <div class="card-body" style="margin: 15px">
            <h5 class="card-title" style="color: #7A899A;margin-bottom: 30px;font-size: 22px">Account Details</h5>
            <div class="mb-3">
                <label for="name-input" class="col-form-label" style="color: #566A7F;font-size: 15px">Name</label>
                <input type="text" class="form-control" id="name-input" name="name" style="border-radius: 5px;border: solid 0.5px groove;" value="<?php echo $user['name']; ?>">
            </div>
            <div class="mb-3">
                <label for="email-input" class="col-form-label" style="color: #566A7F;font-size: 15px">Email</label>
                <input type="email" class="form-control" id="email-input" name="email" style="border-radius: 5px;border: solid 0.5px groove;" value="<?php echo $user['email']; ?>">
            </div>
            <div class="mb-3">
                <label for="currency-input" class="col-form-label" style="color: #566A7F;font-size: 15px">Default
                    Currency</label>
                <select class="form-select" aria-label="Currency" id="currency-input" name="currency" style="border-radius: 5px;border: solid 0.5px groove;">
                    <?php if ($user['default_currency'] == 'lira'): ?>
                        <option value="lira" selected>Lira</option>
                        <option value="dollar">Dollar</option>
                        <option value="euro">Euro</option>
                    <?php elseif ($user['default_currency'] == 'dollar'): ?>
                        <option value="lira">Lira</option>
                        <option value="dollar" selected>Dollar</option>
                        <option value="euro">Euro</option>
                    <?php else: ?>
                        <option value="lira">Lira</option>
                        <option value="dollar">Dollar</option>
                        <option value="euro" selected>Euro</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="account-submit-button">Save Changes</button>
            </div>
        </div>

    </div>
    <div class="card" style="font-family: ABeeZee, sans-serif;width: 100%;height: min-content;box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;border-radius: 15px;margin-top: 30px">

        <div class="card-body" style="margin: 15px">
            <h5 class="card-title" style="color: #7A899A;margin-bottom: 30px;font-size: 22px">Change Password</h5>
            <div class="mb-3">
                <label for="password-input" class="col-form-label" style="color: #566A7F;font-size: 15px">Current Password</label>
                <input type="password" class="form-control" id="password-input" name="password" style="border-radius: 5px;border: solid 0.5px groove;">
            </div>
            <div class="mb-3">
                <label for="new-password-input" class="col-form-label" style="color: #566A7F;font-size: 15px">New Password</label>
                <input type="password" class="form-control" id="new-password-input" name="new_password" style="border-radius: 5px;border: solid 0.5px groove;">
            </div>
            <div class="mb-3">
                <label for="confirm-password-input" class="col-form-label" style="color: #566A7F;font-size: 15px">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password-input" name="confirm-password" style="border-radius: 5px;border: solid 0.5px groove;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="account-submit-button">Save Changes</button>
            </div>
        </div>

    </div>
</div>


<?= $this->endSection() ?>
