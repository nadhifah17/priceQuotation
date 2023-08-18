@extends('layouts.base')

@section('pageTitle')
    {{-- {!! $pageTitle !!} --}}
@endsection

{{-- style --}}

<head>
    <style>
        .main-footer {
            display: none
        }

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

        .modal-custom-header>.title {
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

        .pagination>.page-item.active .page-link {
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

        .select2-container--default .select2-selection--single {
            padding-left: .2rem;
            padding-right: .2rem;
        }

        .cost-disabled {
            pointer-events: none;
            background: #F0EEEE;
        }

        #summary_material_cost,
        #summary_process_cost,
        #summary_purchase_cost {
            pointer-events: none;
        }

        .title-section-calculate {
            font-size: 30px;
            font-weight: bold;
            color: #8F7E7E;
        }

        .title-section-calculate>span {
            font-weight: normal;
            margin: 0;
        }

        .content-table {
            border: 1px solid #E6DADA;
        }

        .content-table>.header {
            background: #E6DADA;
            width: 100%;
            height: 17px;
        }

        .content-table>.body {
            padding: 11px 14px;
        }

        .label-grey {
            color: #8F7E7E;
            font-weight: normal !important;
            font-size: 11px;
            padding-bottom: 0;
        }

        .border-radius-5 {
            border-radius: 5px;
        }

        .times {
            color: #fff;
            text-align: center;
        }

        .table-cost>tbody>tr>td,
        .table-cost>thead>tr>th {
            border: 1px solid #E6DADA !important;
        }

        .table-cost>thead>tr>th,
        .table-cost>tbody>tr>td {
            color: #8F7E7E;
            font-size: 11px;
            padding: 4px 6px;
            vertical-align: middle;
        }

        .cell-disabled {
            background: #8F7E7E;
        }

        .title-table-cost {
            color: #8F7E7E;
            font-size: 13px;
            margin: 0 0 9px 0;
            font-weight: bold;
        }
    </style>
    {{-- link --}}
    {{-- <link href="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
        rel="stylesheet">
    {{-- end of link --}}
</head>
{{-- end of style --}}

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div style="padding-top: 70px;">
                <div class="card">
                    <div class="card-body">
                        <form action="" id="form-calculate">
                            <p class="title-section-calculate">
                                Material Cost <span>{{ $cost->number }} &#40;{{ $cost->name }}&#41;</span>
                            </p>
                            <div class="content-table">
                                <div class="header"></div>
                                <div class="body">
                                    <div class="material-cost-form">
                                        <input type="hidden" id="calculate_material_rate_id">
                                        <input type="hidden" id="calculate_material_currency_id">
                                        <input type="hidden" id="delete_id_material" name="delete_id_material">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label for="material_part_no_m_cost"
                                                        class="col-form-label label-grey">Child Part No.</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="material_part_no_m_cost" value="">
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label for="material_part_name_m_cost"
                                                        class="col-form-label label-grey">Child Part Name</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="material_part_name_m_cost" value="">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="material_currency_m_cost"
                                                        class="col-form-label label-grey">Currency</label>
                                                    <select name="" id="currency" class="form-control">
                                                        <option value="" selected>Select Currency</option>
                                                        @foreach (DB::table('currency_value')->groupBy(['currency_group_id', 'currency_type_id'])->get() as $item)
                                                            <option
                                                                value="{{ $item->currency_type_id }}-{{ $item->currency_group_id }}">
                                                                {{ DB::table('currency_group')->where('id', $item->currency_group_id)->first()->name }}
                                                                {{ $item->currency_type_id == 1 ? 'slide' : 'non-slide' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="material_group_m_cost"
                                                        class="col-form-label label-grey">Material
                                                        Group</label>
                                                    <select name="" id="material" class="form-control">
                                                        <option value="" selected>Select Material Group</option>
                                                        @foreach (DB::table('materials')->get() as $item)
                                                            <option data-name="{{ $item->name }}"
                                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="material_spec_m_cost"
                                                        class="col-form-label label-grey">Spec</label>
                                                    <select name="" id="spec" class="form-control">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="material_period_m_cost"
                                                        class="col-form-label label-grey">Period</label>
                                                    <input type="text" id='period'
                                                        class="form-control form-control-sm" id="material_period_m_cost">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="material_rate_m_cost"
                                                        class="col-form-label label-grey">Material Rate</label>
                                                    <input type="number" id="material_rate"
                                                        class="form-control form-control-sm cost-disabled" readonly=""
                                                        value="0" id="  ">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="material_exchange_rate_m_cost"
                                                        class="col-form-label label-grey">Exchange Rate</label>
                                                    <input type="number" id="exchange_rate"
                                                        class="form-control form-control-sm cost-disabled" readonly=""
                                                        value="0" id="material_exchange_rate_m_cost">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="material_usage_part_m_cost"
                                                        class="col-form-label label-grey">Usage Part</label>
                                                    <input type="number" id="usage_part"
                                                        class="form-control form-control-sm" value="0"
                                                        id="material_usage_part_m_cost">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="material_over_head_m_cost"
                                                        class="col-form-label label-grey">Over Head</label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        value="0" id="overhead">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="material_total_m_cost"
                                                        class="col-form-label label-grey">Total</label>
                                                    <input type="number"
                                                        class="form-control form-control-sm cost-disabled" readonly=""
                                                        value="0" step="0.001" id="subtotal">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="text-right">
                                                <button class="btn btn-sm bg-primary-blue border-radius-5"
                                                    onclick="addMaterialtoList()" type="button">+ Add to List
                                                    Table</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col">
                                            <p class="title-table-cost">List Material Cost</p>
                                            <div class="table-responsive">
                                                <table class="table table-cost">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Part No.</th>
                                                            <th>Part Name</th>
                                                            <th>Currency</th>
                                                            <th>Material Group</th>
                                                            <th>Spec</th>
                                                            <th>Period</th>
                                                            <th>Material Rate</th>
                                                            <th>Exchange Rate</th>
                                                            <th>Usage Part</th>
                                                            <th>O/H</th>
                                                            <th>Total</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="body-list-material">
                                                        <tr class="material-empty-state ">
                                                            <td colspan="12" class="text-center">Empty Data</td>
                                                        </tr>
                                                        <tr class="material-total-row d-none">
                                                            <td colspan="10"><b>Total</b></td>
                                                            <td class="material-total-item">
                                                                0
                                                            </td>
                                                            <input type="hidden" class="material-total-item-input"
                                                                value="0">
                                                            <td class="cell-disabled"></td>
                                                        </tr>
                                                    </tbody>
                                                    <tr class="material-total-row ">
                                                        <td colspan="10"><b>Total</b></td>
                                                        <td id="total_material_cost_span" class="material-total-item">
                                                        </td>
                                                        <input type="hidden" class="material-total-item-input"
                                                            value="">
                                                        <td class="cell-disabled"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            {{-- <div class="text-right">
                                                <button class="btn btn-sm bg-primary-success" type="button"
                                                    onclick="addToSummary('material')">+ Add to Summary Cost</button>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- proses cost --}}
                            <p class="title-section-calculate mt-5">
                                Process Cost <span>{{ $cost->number }} &#40;{{ $cost->name }}&#41;</span>
                            </p>
                            <div class="content-table">
                                <div class="header"></div>
                                <div class="body">
                                    <div class="material-cost-form">
                                        <input type="hidden" id="calculate_process_rate_id">
                                        <input type="hidden" id="delete_id_process" name="delete_id_process">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="process_part_no_p_cost"
                                                        class="col-form-label label-grey">Child Part No.</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="process_part_no_p_cost" value="">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="process_part_name_p_cost"
                                                        class="col-form-label label-grey">Child Part Name</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="process_part_name_p_cost" value="">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="process_group_p_cost"
                                                        class="col-form-label label-grey">Process Group</label>
                                                    <select name="" id="proccess" class="form-control">
                                                        <option value="" selected>Select Process Group</option>
                                                        @foreach (DB::table('process')->get() as $item)
                                                            <option data-name="{{ $item->name }}"
                                                                value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="process_code_p_cost"
                                                        class="col-form-label label-grey">Process Code</label>
                                                    <select name="" id="procces_code" class="form-control">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="process_rate_p_cost"
                                                        class="col-form-label label-grey">Process Rate</label>
                                                    <input type="number"
                                                        class="form-control form-control-sm cost-disabled" readonly=""
                                                        value="0" id="process_rate">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="process_cycle_time_p_cost"
                                                        class="col-form-label label-grey">Cycle Time</label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        oninput="getTotal('process')" value="0" id="cycle_time">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="process_over_head_p_cost"
                                                        class="col-form-label label-grey">Over Head</label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        oninput="getTotal('process')" value="0"
                                                        id="overhead_proccess">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="process_total_p_cost"
                                                        class="col-form-label label-grey">Total</label>
                                                    <input type="number"
                                                        class="form-control form-control-sm cost-disabled" readonly=""
                                                        oninput="getTotal('process')" value="0"
                                                        id="subtotalproccess">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="text-right">
                                                <button class="btn btn-sm bg-primary-blue border-radius-5"
                                                    onclick="addProcessToList()" type="button">+ Add to List
                                                    Table</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col">
                                            <p class="title-table-cost">List Process Cost</p>
                                            <div class="table-responsive">
                                                <table class="table table-cost">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Part No.</th>
                                                            <th>Part Name</th>
                                                            <th>Process Group</th>
                                                            <th>Process Code</th>
                                                            <th>Process Rate</th>
                                                            <th>Cycle Time</th>
                                                            <th>O/H</th>
                                                            <th>Total</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="body-list-process">
                                                        <tr class="process-empty-state ">
                                                            <td colspan="10" class="text-center">Empty Data</td>
                                                        </tr>
                                                    </tbody>
                                                    <tr class="material-total-row ">
                                                        <td colspan="8"><b>Total</b></td>
                                                        <td id="total_process_cost_span" class="material-total-item">
                                                        </td>
                                                        <input type="hidden" class="material-total-item-input"
                                                            value="">
                                                        <td class="cell-disabled"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <p class="title-section-calculate mt-5">
                                Purchase Cost <span>{{ $cost->number }} &#40;{{ $cost->name }}&#41;</span>
                            </p>
                            <div class="content-table">
                                <div class="header"></div>
                                <div class="body">
                                    <div class="purchase-cost-form">

                                        <input type="hidden" id="calculate_purchase_rate_id">
                                        <input type="hidden" id="delete_id_purchase" name="delete_id_purchase" >

                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="purchase_part_no_pc_cost"
                                                        class="col-form-label label-grey">Child Part No.</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="purchase_part_no_pc_cost" value="">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="purchase_part_name_pc_cost"
                                                        class="col-form-label label-grey">Child Part Name</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="purchase_part_name_pc_cost" value="">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="purchase_currency_pc_cost"
                                                        class="col-form-label label-grey">Qurrency</label>
                                                    <select name="" id="purchase_currency_pc_cost"
                                                        class="form-control">
                                                        <option  value="">Select Qurrency</option>
                                                        @foreach (DB::table('currency_group')->get() as $item)
                                                            <option data-name="{{ $item->name }}"
                                                                value="{{ $item->id }}">
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="purchase_currency_type_pc_cost"
                                                        class="col-form-label label-grey">Type Currency</label>
                                                    <select name="" id="purchase_currency_type_pc_cost"
                                                        class="form-control">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="purchase_period_pc_cost"
                                                        class="col-form-label label-grey">Period</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="purchase_period_pc_cost">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="purchase_value_pc_cost"
                                                        class="col-form-label label-grey">Value Currency</label>
                                                    <input type="number"
                                                        class="form-control form-control-sm cost-disabled" readonly=""
                                                        value="0" id="purchase_value_pc_cost">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="purchase_cost_pc_cost"
                                                        class="col-form-label label-grey">Cost</label>
                                                    <input type="number" oninput="getTotal('purchase')"
                                                        class="form-control form-control-sm" value="0"
                                                        id="purchase_cost_pc_cost">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="purchase_quantity_pc_cost"
                                                        class="col-form-label label-grey">Quantity</label>
                                                    <input type="number" oninput="getTotal('purchase')"
                                                        class="form-control form-control-sm" value="0"
                                                        id="purchase_quantity_pc_cost">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="purchase_over_head_pc_cost"
                                                        class="col-form-label label-grey">Over Head</label>
                                                    <input type="number" oninput="getTotal('purchase')"
                                                        class="form-control form-control-sm" value="0"
                                                        id="purchase_over_head_pc_cost">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="purchase_total_pc_cost"
                                                        class="col-form-label label-grey">Total</label>
                                                    <input type="number" oninput="getTotal('purchase')"
                                                        class="form-control form-control-sm cost-disabled" readonly=""
                                                        value="0" id="purchase_total_pc_cost">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="text-right">
                                                <button class="btn btn-sm bg-primary-blue border-radius-5"
                                                    onclick="addPurchaseToList()" type="button">+ Add to List
                                                    Table</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col">
                                            <p class="title-table-cost">List Purchase Cost</p>
                                            <div class="table-responsive">
                                                <table class="table table-cost">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Part No.</th>
                                                            <th>Part Name</th>
                                                            <th>Currency</th>
                                                            <th>Type Currency</th>
                                                            <th>Period</th>
                                                            <th>Value Currency</th>
                                                            <th>Cost</th>
                                                            <th>Quantity</th>
                                                            <th>O/H</th>
                                                            <th>Total</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="body-list-purchase">
                                                        <tr class="purchase-empty-state ">
                                                            <td colspan="12" class="text-center">Empty Data</td>
                                                        </tr>
                                                        <tr class="purchase-total-row d-none">
                                                            <td colspan="10"><b>Total</b></td>
                                                            <td class="purchase-total-item">
                                                                0
                                                            </td>
                                                            <input type="hidden" class="purchase-total-item-input"
                                                                value="0">
                                                            <td class="cell-disabled"></td>
                                                        </tr>
                                                    </tbody>
                                                    <tr class="material-total-row ">
                                                        <td colspan="10"><b>Total</b></td>
                                                        <td id="total_purchase_cost_span" class="material-total-item">
                                                        </td>
                                                        <input type="hidden" class="material-total-item-input"
                                                            value="">
                                                        <td class="cell-disabled"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>


                            <p class="title-section-calculate" style="margin-top: 18px;">
                                Summary Cost <span>{{ $cost->number }} &#40;{{ $cost->name }}&#41;</span>
                            </p>
                            <div class="content-table">
                                <div class="header"></div>
                                <div class="body">
                                    <div class="summary-cost-form">
                                        <div class="row">
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="mother_part_no" class="col-form-label label-grey">Mother
                                                        Part No.</label>
                                                    <input type="text" value="{{ $cost->number }}"
                                                        class="form-control form-control-sm" id="mother_part_no">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="mother_part_name" class="col-form-label label-grey">Mother
                                                        Part Name</label>
                                                    <input type="text" value="{{ $cost->name }}"
                                                        class="form-control form-control-sm" id="mother_part_name">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="summary_material_cost"
                                                        class="col-form-label label-grey">Material Cost</label>
                                                    <input type="number" value="0"
                                                        class="form-control form-control-sm" readonly=""
                                                        id="summary_material_cost">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="summary_process_cost"
                                                        class="col-form-label label-grey">Process Cost</label>
                                                    <input type="number" value="0"
                                                        class="form-control form-control-sm" readonly=""
                                                        id="summary_process_cost">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="summary_purchase_cost"
                                                        class="col-form-label label-grey">Purchase Cost</label>
                                                    <input type="number" value="0"
                                                        class="form-control form-control-sm" readonly=""
                                                        id="summary_purchase_cost">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="summary_total_cost"
                                                        class="col-form-label label-grey">Total</label>
                                                    <input type="number" value="0"
                                                        class="form-control form-control-sm cost-disabled" readonly=""
                                                        id="summary_total_cost">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button class="btn btn-sm bg-primary-success" type="button"
                                            onclick="submitHandler()" id="summary-submit-cost-btn">Submit</button>
                                    </div>

                                </div>
                            </div>
                        br
                        <a href="{{ route('cost.download', ['id'=>$cost->id]) }}" class="btn btn-warning float-right">Download to excel</a>
                    </div>
                </div>
            </div>
        </div>

        <br><br>

    </section>




    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        material_cost = [];
        process_cost = [];
        purchase_cost = [];
        total_cost = [];

        function remove(arr, pid) {
            console.log(pid);
            return arr.filter((e, key) => key !== pid);
        }
    </script>
    <script>
        $("#material").change(function() {
            name = $(this).val();
            var html = '';
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/api/get-spec/" + name,
                success: function(data) {
                    for (var i = 0; i < data.length; i++) {
                        html += '<option data-name=' + data[i].specification + ' id=' + data[i].id +
                            ' value=' + data[i].id + '>' + data[i]
                            .specification + '</option>';
                    }
                    $('#spec').html(html);
                }
            });
        });
        $("#period").on('input', function() {
            date = $(this).val();
            currency = $('#currency').val();
            material = $('#material').val();
            material_spec = $('#spec').val();
            var html = '';
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/api/get-mat-ex-rate/" + date + '/' + currency + '/' + material + '/' + material_spec,
                success: function(data) {
                    $('#material_rate').val(data.material_rate.rate)
                    $('#exchange_rate').val(parseFloat(data.currency_value.value) * parseFloat(data.material_rate.rate))
                }
            });
        });
        $("#usage_part").on('input', function() {
            usage_part = $(this).val();
            exchange_rate = $('#exchange_rate').val();
            overhead = $('#overhead').val();
            solve = parseFloat(exchange_rate) * parseFloat(usage_part) + parseFloat(overhead)
            $('#subtotal').val(solve)
        });
        $("#overhead").on('input', function() {
            overhead = $(this).val();
            exchange_rate = $('#exchange_rate').val();
            usage_part = $('#usage_part').val();
            solve = parseFloat(exchange_rate) * parseFloat(usage_part) + parseFloat(overhead)
            $('#subtotal').val(solve)
        });
        $('#body-list-material').on('click', '.button-delete-material', function(e) {
            material_cost = remove(material_cost, $(this).data('id'));
            this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
            summary_material_cost = 0
            material_cost.forEach((element, key) => {
                summary_material_cost += parseFloat(element.subtotal)
            });
            $('#summary_material_cost').val(summary_material_cost)
            $('#total_material_cost_span').html(summary_material_cost)
            $('#summary_total_cost').val(parseFloat($('#summary_material_cost').val()) + parseFloat($(
                '#summary_process_cost').val()) + parseFloat($('#summary_purchase_cost').val()))
        });

        function addMaterialtoList() {
            var val = {
                "part_no": $('#material_part_no_m_cost').val(),
                "part_name": $('#material_part_name_m_cost').val(),
                "currency": $('#currency').val(),
                "material_group": $('#material').val(),
                "spec": $('#spec').val(),
                "period": $('#period').val(),
                "material_rate": $('#material_rate').val(),
                "exchange_rate": $('#exchange_rate').val(),
                "usage_part": $('#usage_part').val(),
                "overhead": $('#overhead').val(),
                "subtotal": $('#subtotal').val()
            };
            material_cost.push(val);
            summary_material_cost = 0;
            html = '';
            material_cost.forEach((element, key) => {
                html += `   <tr>
                    <td>${key + 1}</td>
                    <td>${element.part_no}</td>
                    <td>${element.part_name}</td>
                    <td>${$('#material').find(':selected').data('name')}</td>
                    <td>${$('#spec').find(':selected').data('name')}</td>
                    <td>${element.period}</td>
                    <td>${element.material_rate}</td>
                    <td>${element.exchange_rate}</td>
                    <td>${element.usage_part}</td>
                    <td>${element.overhead}</td>
                    <td>${element.subtotal}</td>
                    <td><button type="button" class="btn btn-danger button-delete-material" data-id="${key}">DEL</button></td>
                </tr>`
                summary_material_cost += parseFloat(element.subtotal)
            });
            $('#summary_material_cost').val(summary_material_cost)
            $('#total_material_cost_span').html(summary_material_cost)
            $('#summary_total_cost').val(parseFloat($('#summary_material_cost').val()) + parseFloat($(
                '#summary_process_cost').val()) + parseFloat($('#summary_purchase_cost').val()))
            $('#body-list-material').html(html)
            $('#form-calculate')[0].reset();
        }




        // PROCCESS COST
        $("#proccess").change(function() {
            name = $(this).val();
            var html = '';
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/api/get-process/" + name,
                success: function(data) {
                    html += '<option  value="">Select Proccess code</option>';
                    for (var i = 0; i < data.length; i++) {
                        html += '<option data-name=' + data[i].name + ' id=' + data[i].id + ' value=' +
                            data[i].id + '>' + data[i]
                            .name + '</option>';
                    }
                    $('#procces_code').html(html);
                }
            });
        });
        $("#procces_code").change(function() {
            proccess_code = $(this).val();
            proccess = $('#proccess').val();
            var html = '';
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/api/get-process-rate/" + proccess + '/' + proccess_code,
                success: function(data) {
                    $('#process_rate').val(data.proccess_rate.rate)
                }
            });
        });
        $("#cycle_time").on('input', function() {
            usage_part = $(this).val();
            exchange_rate = $('#exchange_rate').val();
            overhead = $('#overhead').val();
            solve = parseFloat(exchange_rate) * parseFloat(usage_part) + parseFloat(overhead)
            $('#subtotal').val(solve)
        });
        $("#overhead_proccess").on('input', function() {
            overhead = $(this).val();
            cycle_time = $('#cycle_time').val();
            process_rate = $('#process_rate').val();
            solve = parseFloat(process_rate) * parseFloat(cycle_time) + parseFloat(overhead)
            $('#subtotalproccess').val(solve)
        });

        $('#body-list-process').on('click', '.button-delete-proccess', function(e) {
            process_cost = remove(process_cost, $(this).data('id'));
            this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
            summary_process_cost = 0
            process_cost.forEach((element, key) => {
                summary_process_cost += parseFloat(element.subtotal)
            });
            total_process_cost_span
            $('#total_process_cost_span').html(summary_process_cost)
            $('#summary_process_cost').val(summary_process_cost)
            $('#summary_total_cost').val(parseFloat($('#summary_material_cost').val()) + parseFloat($(
                '#summary_process_cost').val()) + parseFloat($('#summary_purchase_cost').val()))

        });

        function addProcessToList() {
            var val = {
                "part_no": $('#process_part_no_p_cost').val(),
                "part_name": $('#process_part_name_p_cost').val(),
                "process_group": $('#proccess').val(),
                "procces_code": $('#procces_code').val(),
                "process_rate": $('#process_rate').val(),
                "cycle_time": $('#cycle_time').val(),
                "overhead": $('#overhead_proccess').val(),
                "subtotal": $('#subtotalproccess').val()
            };
            process_cost.push(val);
            html = '';
            summary_process_cost = 0;
            process_cost.forEach((element, key) => {
                html += `<tr>
                    <td>${ key + 1 }</td>
                    <td>${element.part_no}</td>
                    <td>${element.part_name}</td>
                    <td>${$('#proccess').find(':selected').data('name')}</td>
                    <td>${$('#procces_code').find(':selected').data('name')}</td>
                    <td>${element.process_rate}</td>
                    <td>${element.cycle_time}</td>
                    <td>${element.overhead}</td>
                    <td>${element.subtotal}</td>
                    <td><button type="button" data-id="${key}" class="btn btn-danger button-delete-proccess">DEL</button></td>
                </tr>`
                summary_process_cost += parseFloat(element.subtotal)
            });
            $('#total_process_cost_span').html(summary_process_cost)
            $('#summary_process_cost').val(summary_process_cost)
            $('#summary_total_cost').val(parseFloat($('#summary_material_cost').val()) + parseFloat($(
                '#summary_process_cost').val()) + parseFloat($('#summary_purchase_cost').val()))
            $('#body-list-process').html(html)
            $('#form-calculate')[0].reset();
        }


        // PURCHASE COST
        $("#purchase_currency_pc_cost").change(function() {
            name = $(this).val();
            var html = '';
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/api/get-type-currency/" + name,
                success: function(data) {
                    for (var i = 0; i < data.length; i++) {
                        console.log(data[i]);
                        if (data[i].currency_type_id == 1) {
                            html += '<option data-name="Slide" id=' + data[i].currency_type_id +
                                ' value=' + data[i].currency_type_id + '>Slide</option>';
                        } else {
                            html += '<option data-name="Non Slide" id=' + data[i].currency_type_id +
                                ' value=' + data[i].currency_type_id + '>Non Slide</option>';
                        }
                    }
                    console.log(html);
                    $('#purchase_currency_type_pc_cost').html(html);
                }
            });
        });
        $("#purchase_period_pc_cost").change(function() {
            purchase_period_pc_cost = $(this).val();
            purchase_currency_pc_cost = $('#purchase_currency_pc_cost').val();
            purchase_currency_type_pc_cost = $('#purchase_currency_type_pc_cost').val();
            var html = '';
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/api/get-value-currency/" + purchase_currency_pc_cost + '/' +
                    purchase_currency_type_pc_cost + '/' + purchase_period_pc_cost,
                success: function(data) {
                    $('#purchase_value_pc_cost').val(data.currency_value.value)
                }
            });
        });
        $("#purchase_cost_pc_cost").on('input', function() {
            purchase_value_pc_cost = $('#purchase_value_pc_cost').val();
            purchase_cost_pc_cost = $('#purchase_cost_pc_cost').val();
            purchase_quantity_pc_cost = $('#purchase_quantity_pc_cost').val();
            purchase_over_head_pc_cost = $('#purchase_over_head_pc_cost').val();
            solve = parseFloat(purchase_value_pc_cost) * parseFloat(purchase_cost_pc_cost) * parseFloat(
                purchase_quantity_pc_cost) + parseFloat(purchase_over_head_pc_cost)
            $('#purchase_total_pc_cost').val(solve)
        });
        $("#purchase_quantity_pc_cost").on('input', function() {
            purchase_value_pc_cost = $('#purchase_value_pc_cost').val();
            purchase_cost_pc_cost = $('#purchase_cost_pc_cost').val();
            purchase_quantity_pc_cost = $('#purchase_quantity_pc_cost').val();
            purchase_over_head_pc_cost = $('#purchase_over_head_pc_cost').val();
            solve = parseFloat(purchase_value_pc_cost) * parseFloat(purchase_cost_pc_cost) * parseFloat(
                purchase_quantity_pc_cost) + parseFloat(purchase_over_head_pc_cost)
            $('#purchase_total_pc_cost').val(solve)
        });
        $("#purchase_over_head_pc_cost").on('input', function() {
            purchase_value_pc_cost = $('#purchase_value_pc_cost').val();
            purchase_cost_pc_cost = $('#purchase_cost_pc_cost').val();
            purchase_quantity_pc_cost = $('#purchase_quantity_pc_cost').val();
            purchase_over_head_pc_cost = $('#purchase_over_head_pc_cost').val();
            solve = parseFloat(purchase_value_pc_cost) * parseFloat(purchase_cost_pc_cost) * parseFloat(
                purchase_quantity_pc_cost) + parseFloat(purchase_over_head_pc_cost)
            $('#purchase_total_pc_cost').val(solve)
        });

        $('#body-list-purchase').on('click', '.button-delete-purchase', function(e) {
            purchase_cost = remove(purchase_cost, $(this).data('id'));
            this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
            summary_purchase_cost = 0
            purchase_cost.forEach((element, key) => {
                summary_purchase_cost += parseFloat(element.subtotal)
            });

            $('#total_purchase_cost_span').html(summary_purchase_cost)
            $('#summary_purchase_cost').val(summary_purchase_cost)
            $('#summary_total_cost').val(parseFloat($('#summary_material_cost').val()) + parseFloat($(
                '#summary_process_cost').val()) + parseFloat($('#summary_purchase_cost').val()))

        });

        function addPurchaseToList() {
            var val = {
                "part_no": $('#purchase_part_no_pc_cost').val(),
                "part_name": $('#purchase_part_name_pc_cost').val(),
                "currency": $('#purchase_currency_pc_cost').val(),
                "currency_type": $('#purchase_currency_type_pc_cost').val(),
                "period": $('#purchase_period_pc_cost').val(),
                "currency_value": $('#purchase_value_pc_cost').val(),
                "cost": $('#purchase_cost_pc_cost').val(),
                "quantity": $('#purchase_quantity_pc_cost').val(),
                "overhead": $('#purchase_over_head_pc_cost').val(),
                "subtotal": $('#purchase_total_pc_cost').val()
            };
            purchase_cost.push(val);
            html = '';
            summary_purchase_cost = 0;
            purchase_cost.forEach((element, key) => {
                html += `<tr>
                    <td>${ key + 1 }</td>
                    <td>${element.part_no}</td>
                    <td>${element.part_name}</td>
                    <td>${$('#purchase_currency_pc_cost').find(':selected').data('name')}</td>
                    <td>${$('#purchase_currency_type_pc_cost').find(':selected').data('name')}</td>
                    <td>${element.period}</td>
                    <td>${element.currency_value}</td>
                    <td>${element.cost}</td>
                    <td>${element.quantity}</td>
                    <td>${element.overhead}</td>
                    <td>${element.subtotal}</td>
                    <td><button class="btn btn-danger button-delete-purchase" type="button" data-id="${key}">DEL</button></td>
                </tr>`
                summary_purchase_cost += parseFloat(element.subtotal)
            });

            $('#total_purchase_cost_span').html(summary_purchase_cost)
            $('#summary_purchase_cost').val(summary_purchase_cost)
            $('#summary_total_cost').val(parseFloat($('#summary_material_cost').val()) + parseFloat($(
                '#summary_process_cost').val()) + parseFloat($('#summary_purchase_cost').val()))
            $('#body-list-purchase').html(html)
            $('#form-calculate')[0].reset();
        }

        // SUMMARY COST
        function addSummaryToList() {
            var val = {
                "part_no": $('#mother_part_no').val(),
                "part_name": $('#mother_part_name').val(),
                "summary_material_cost": $('#summary_material_cost').val(),
                "summary_process_cost": $('#summary_process_cost').val(),
                "summary_purchase_cost": $('#summary_purchase_cost').val(),
                "summary_total_cost": $('#summary_total_cost').val(),
            };
            total_cost = [];
            total_cost.push(val);
            html = '';
            total_cost.forEach((element, key) => {
                html += `<tr>
                    <td>${ key + 1 }</td>
                    <td>${element.part_no}</td>
                    <td>${element.part_name}</td>
                    <td>${element.summary_material_cost}</td>
                    <td>${element.summary_process_cost}</td>
                    <td>${element.summary_purchase_cost}</td>
                    <td>${element.summary_total_cost}</td>
                    <td><button class="btn btn-danger">DEL</button></td>
                </tr>`
            });
            $('#body-list-summary').html(html)




        }

        function submitHandler() {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/api/submit-cost",
                data: {
                    "cost_id": @json($cost->id),
                    "material_cost": material_cost,
                    "process_cost": process_cost,
                    "purchase_cost": purchase_cost,
                    "total_cost": total_cost
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success Add to data',
                        text: 'Hurray !',
                    })
                }
            });
        }
    </script>

    {{-- script date picker --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        $("#period").datepicker({
            format: "yyyy-mm",
            startView: "months",
            minViewMode: "months"
        }).change(function() {
            date = $(this).val();
            currency = $('#currency').val();
            material = $('#material').val();
            material_spec = $('#spec').val();
            var html = '';
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/api/get-mat-ex-rate/" + date + '/' + currency + '/' + material + '/' + material_spec,
                success: function(data) {
                    $('#material_rate').val(data.material_rate.rate)
                    $('#exchange_rate').val(parseFloat(data.currency_value.value) * parseFloat(data.material_rate.rate))
                }
            });
        });

        $("#purchase_period_pc_cost").datepicker({
            format: "yyyy-mm",
            startView: "months",
            minViewMode: "months"
        }).change(function() {
            purchase_period_pc_cost = $(this).val();
            purchase_currency_pc_cost = $('#purchase_currency_pc_cost').val();
            purchase_currency_type_pc_cost = $('#purchase_currency_type_pc_cost').val();
            var html = '';
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/api/get-value-currency/" + purchase_currency_pc_cost + '/' +
                    purchase_currency_type_pc_cost + '/' + purchase_period_pc_cost,
                success: function(data) {
                    $('#purchase_value_pc_cost').val(data.currency_value.value)
                }
            });
        });
    </script>
@endsection
