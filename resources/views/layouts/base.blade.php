<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Quotation Web App" />
    <meta name="keywords" content="Quotation Web App" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="QUOTATION TBINA MKT" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- global stylesheet bundle --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}">
    {{-- theme stylesheet --}}
    <link rel="stylesheet" href="{{ asset('assets/lte/css/adminlte.css') }}">
    {{-- select2 stylesheet --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    {{-- DataTable stylesheet --}}
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}">
    {{-- iziToast --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/izitoast/dist/css/iziToast.min.css') }}">
    {{-- datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/datepicker/css/datepicker.css') }}">
    {{-- global javascript bundle --}}
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/lte/js/scripts.bundle.js') }}"></script>
    {{-- bootstrap icon --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.1/font/bootstrap-icons.css">
    {{-- custom css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    {{-- jquery-ui --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/jquery-ui/jquery-ui.css') }}">
    {{-- jquery --}}
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    {{-- jquery-ui --}}
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.js') }}"></script>

    <style>
        .form-check .form-check-input {
            margin-top: 0.5em !important;
        }

        .logo {
            width: 55px;
            height: 55px;
        }

        .main-sidebar {
            background: #CE2658;
            position: fixed !important;
        }

        .main-header {
            position: fixed;
            width: 100%;
        }

        .main-sidebar .nav-link {
            color: #D6CCCC;
        }

        .main-sidebar .nav-item:hover .nav-link {
            color: #D6CCCC;
        }

        .main-page-title {
            font-size: 30px;
            color: #8F7E7E;
            padding-top: 60px;
        }

        .btn-item {
            border: 3px solid #2F5CCF;
            border-radius: 5px;
        }

        .bg-primary-blue {
            background: #2F5CCF;
            color: #fff;
        }

        .bg-primary-success {
            background: #57BC27;
            color: #fff;
            border-radius: 5px;
        }

        .bg-primary-warning {
            background: #FEBE18;
            color: #fff;
            border-radius: 5px;
        }

        .bg-primary-danger {
            background: #FF0000;
            color: #fff;
            border-radius: 5px;
        }

        .btn-item.bg-primary-blue:hover {
            background: #2F5CCF;
            color: #fff;
        }

        .modal-custom-header {
            background: #1C58F2;
            padding: 9px 13px;
        }

        .modal-custom-header > .title {
            margin: 0;
            padding: 0;
            color: #fff;
            font-size: 15px;
        }

        .modal-q .body {
            padding: 19px 17px;
            background: #F0EEEE !important;
        }

        .modal-q .footer,
        .modal-footer {
            padding: 10px 16px;
            background: #E6DADA;
        }

        label.required:after {
            /* position: absolute; */
            content: '*';
            top: 0;
            right: 0;
        }

        .datepicker {
            z-index: 20000;
        }

        .sidebar .nav-link.active {
            font-weight: bold;
            color: #fff;
            background: transparent;
        }

        /* begin::pagination */
        .pagination .page-link {
            color: #8F7E7E;
        }

        .pagination > .page-item.active .page-link {
            background: #D9D9D9 !important;
            color: #fff;
            border-color: #D9D9D9;
        }
        /* end::pagination */

        .ui-autocomplete {
            position: absolute;
            z-index: 2150000000 !important;
            cursor: default;
            border: 2px solid #ccc;
            padding: 5px 0;
            border-radius: 2px;
        }
    </style>
    
    <title>{{ $title }}</title>
</head>
<body>
    
    {{-- begin::header --}}
    @include('adminLte.components.header')
    {{-- end::header --}}

    {{-- begin::sidebar --}}
    @include('adminLte.components.aside')
    {{-- end::sidebar --}}

    {{-- begin::main-content --}}
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                @yield('pageTitle')
                @yield('content')
            </div>
        </section>
    </div>
    {{-- end::main-content --}}

    {{-- begin::footer --}}
    @include('adminLte.components.footer')
    {{-- end::footer --}}

    {{-- Bootstrap --}}
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- adminlte --}}
    <script src="{{ asset('assets/lte/js/adminlte.js') }}"></script>
    {{-- select2 --}}
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    {{-- datatable --}}
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    {{-- datepicker --}}
    <script src="{{ asset('assets/plugins/datepicker/js/bootstrap-datepicker.js') }}"></script>
    {{-- iziToast --}}
    <script src="{{ asset('assets/plugins/izitoast/dist/js/iziToast.min.js') }}"></script>
    {{-- sweetalert --}}
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2@10.js') }}"></script>
    {{-- Show message alert from session flash --}}
    @include('adminLte.helpers.message-alert')

    <script>
        function setDataTable(tableId, columns, route, sort = null) {
            let sortOption = [[0, 'desc']];
            if (sort) {
                sortOption = [[sort, 'desc']];
            }
            let option = {
                processing: true,
				serverSide: true,
				responsive: true,
				scrollX: true,
				ajax: route,
				columns: columns,
				drawCallback: function (settings, json) {},
				order: sortOption
            };

			let dt = $('#' + tableId).DataTable(option);
			return dt;
		}

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

		function setLoading(id, start) {
			let elem = $('#' + id);
			let loading = `<div class="spinner-border" style="width: 1em; height: 1em" role="status">
					<span class="visually-hidden"></span>
					</div>`;
			if (start) {
				elem.html(loading);
				elem.prop('disabled', true);
			} else {
				elem.html(`{{ __('view.submit') }}`);
				elem.prop('disabled', false);
			}
		}

		function setNotif(error, message) {
			if (error) {
				if (typeof message == 'object' || typeof message == 'array') {
					for (let a = 0; a < message.length; a++) {
						iziToast.error({
							message: message[a],
							position: "topRight"
						});
					}
				} else {
					iziToast.error({
						message: message,
						position: "topRight"
					});
				}
			} else {
				iziToast.success({
					message: message,
					position: "topRight"
				});
			}
		}

		function resetForm(id) {
			document.getElementById(id).reset();
		}

        function deleteMaster(title, cancelText, confirmText, url, dt) {
			Swal.fire({
                title: title,
                showCancelButton: true,
                cancelButtonText: cancelText,
                confirmButtonText: confirmText,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'delete',
                        url: url,
                        success: function(res) {
                            setNotif(false, res.message);
                            dt.ajax.reload();
                        },
                        error: function(err) {
                            setNotif(true, err.responseJSON == undefined ? err.responseText : err.responseJSON.message);
                        }
                    })
                }
            })
		}
    </script>

    {{-- custom javascript --}}
    @stack('scripts')
</body>
</html>