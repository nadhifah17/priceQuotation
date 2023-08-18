@if(session()->has('message_alert'))

    <script>

        iziToast.success({
            timeout: 2000,
            position: 'topRight',
            message: "{{ session('message_alert') }}"
        });

    </script>
@elseif(session()->has('error_message_alert'))
    <script>

        iziToast.error({
            timeout: 2000,
            position: 'topRight',
            message: "{{ session('error_message_alert') }}"
        });

    </script>
@endif