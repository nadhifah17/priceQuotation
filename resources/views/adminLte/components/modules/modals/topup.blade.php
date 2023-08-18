<div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="topupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="topupModalLabel">Top Up</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="topup-form" action="{{fr_route('transactions.topup')}}" onsubmit="submitTopup(event)">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="amount">Amount to topup:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp.</span>
                            </div>
                            <input type="text" name="amount" class="form-control" id="amount" placeholder="Enter Amount">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Top Up</button>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    $paymentSettings = resolve(\Modules\Payments\Entities\PaymentSetting::class)->toArray();
    $midtrans_settings = json_decode($paymentSettings['midtrans'], true);
    $clientKey = $midtrans_settings['MIDTRANS_CLIENT_KEY'] ?? '';
@endphp

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
<script>
    // Format input

    var $form = $("#topup-form");
    var $input = $('#amount');

    $input.on("keyup", function (event) {

        // When user select text in the document, also abort.
        const selection = window.getSelection().toString();
        if (selection !== '') {
            return;
        }

        // When the arrow keys are pressed, abort.
        if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
            return;
        }


        const $this = $(this);

        // Get the value.
        let input = $this.val();

        input = input.replace(/[\D\s\._\-]+/g, "");
        input = input ? parseInt(input, 10) : 0;

        $this.val(function () {
            return (input === 0) ? "" : input.toLocaleString("in-ID");
        });
        console.log(input);
    });

    function submitTopup (e) {
        e.preventDefault();
        const form = $(e.target);

        const arr = form.serializeArray();

        for (let i = 0; i < arr.length; i++) {
            arr[i].value = arr[i].value.replace(/[($)\s\._\-]+/g, ''); // Sanitize the values.
        }

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: arr,
            success: function (response) {
                const windowProxy = window.open(
                    response.toString(),
                    '_blank',
                    'toolbar=0,location=0,menubar=0'
                );
                const timer = setInterval(function () {
                    if (windowProxy.closed) {
                        clearInterval(timer);
                        window.location.href = '{{ Request::url() }}';
                    }
                }, 1000);
            },
            error: function (e) {
                console.log(e);
            }
        })
    }
</script>
