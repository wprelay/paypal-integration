<div class="wrap">
    <div id="wp-relay-paypal-main">
        //content rendered from react
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