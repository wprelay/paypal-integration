<style>

    #wp-relay-paypal-main {
        margin: auto;
        width: 60%
    }
</style>
<div class="wrap">

    <div id="wp-relay-paypal-main">
        <form id="wprelay-paypal-form" action="<?= admin_url('admin-ajax.php') ?>" method="POST">
            <div>
                <input type="hidden" name="_wp_relay_paypal_nonce" id="_wp_relay_paypal_nonce"
                       value="<?= wp_create_nonce('_wprelay_papal_nonce') ?>">
            </div>
            <input type="hidden" name="action"
                   value="save_paypal_details">
            <div>
                <label for="client_id">Client ID:</label>
                <input type="text" name="client_id" value="<?= esc_attr__($client_id ?? '') ?>"><br><br>
            </div>

            <div>
                <label for="client_secret">Client SECRET:</label>
                <input type="text" name="client_secret" value=<?= esc_attr__($client_secret ?? '') ?>><br><br>
            </div>

            <div>
                <label for="country">Payment Type: </label>

                <select name="payment_type" id="payment_type">
                    <option value="standard"  <?= $payment_type ?? '' == 'standard' ? 'selected' : '' ?>>Authenticated API</option>
                    <option value="mass_payment" <?= $payment_type ?? '' == 'mass_payment' ? 'selected' : '' ?>>Mass Payment</option>
                </select>
            </div>

            <div>
                <label for="country">Account Type </label>

                <select name="account_type" id="account_type">
                    <option value="live" <?= $account_type ?? '' == 'live' ? 'selected' : '' ?>>Live</option>
                    <option value="sandbox" <?= $account_type ?? '' == 'sandbox' ? 'selected' : '' ?>>SandBox</option>
                </select>
            </div>

            <div>
                <input type="submit" value="Submit">
            </div>
        </form>

    </div>

</div>

<script>
    jQuery(document).ready(function ($) {
        $('#wprelay-paypal-form').submit(function (e) {
            e.preventDefault();
            let details = new FormData(this)
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: details,
                processData: false,
                contentType: false,
                beforeSend: function () {

                },
                success: function (response) {
                }
            });
        });
    });
</script>