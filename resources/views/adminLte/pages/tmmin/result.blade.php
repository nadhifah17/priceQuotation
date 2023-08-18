<!DOCTYPE html>
<html>
<head>
    <title>Price Quotation</title>
    <style>
        @page {
            size: landscape;
            margin: 1cm 2cm; /* top/bottom margin: 1cm, left/right margin: 2cm */
        }
        
        .container {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-gap: 10px;
        }
        
        .col {
            grid-column: span 12; /* Occupies all 12 columns */
        }
        
        .col-6 {
            grid-column: span 6; /* Occupies 6 columns */
        }

        .border{
             border: 1px solid black;
        }
        
        /* Add more .col-N classes for different column spans as needed */
        
        /* Additional styles for the content */
        body {
            font-family: Arial, sans-serif;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .border th,
        .border td{
            border: 1px solid black;
        }
        th, td {
           
            padding: 5px;
        }
        
        /* Add more styles for the layout and content as needed */
    </style>
</head>
<body>
    <div class="container">
        <div class="col-6">
            <table >
                <!-- Institution logo and details -->
                <!-- ... -->
                <tbody>
				    <tr>
					    <td rowspan="5">
                            <img src="{{ asset('assets/images/logotbina.png') }}"  height="70">
                            
                         </td>
					    <td align="left"><b>PT. TOYOTA BOSHOKU INDONESIA</b></td>
				    </tr>
			    </tbody>
            </table>
        </div>
        <div class="col">
            <table>
                <!-- Card header -->
                <!-- ... -->
                <tbody>
                <tr>
                    <td colspan="2" align="center">
                        <b><u>PRICE QUOTATION</u></b>
                        <br>
                        <b><span>&#40 {{ $cost->number }} {{ $cost->name }}&#41;</span></b>
                    </td>
                </tr>
			</tbody>
            </table>
        </div>
        <div class="col-6">
            <table>
                <!-- Student information -->
                <!-- ... -->
                <tr>
                    <td align="left" width="50%">
                        <b>Material</b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col">
            <table class="border">
                <!-- Course data -->
                <!-- ... -->
                <thead>
                    <tr class="border">
                        <th>Part No.</th>
                        <th>Part Name</th>
                        <th>Material Group</th>
                        <th>Spec</th>
                        <th>Period</th>
                        <th>Currency</th>
                        <th>Material Rate</th>
                        <th>Exchange Rate</th>
                        <th>Usage Part</th>
                        <th>O/H</th>
                        <th>Total</th
                    </tr>
                </thead>
                <tbody >
                    @php
                        $material_total = 0;
                    @endphp
                    @foreach ($mat as $item)
                        <tr>
                            <td>{{ $item->part_no }}</td>
                            <td>{{ $item->part_name }}</td>
                            <td>{{ DB::table('materials')->where('id',$item->material_group)->first()->name }}</td>
                            <td>{{ DB::table('material_specs')->where('id',$item->spec)->first()->specification }}</td>
                            <td>{{ $item->period }}</td>
                            <td>{{ $item->currency_value }}</td>
                            <td>{{ $item->material_rate }}</td>
                            <td>{{ $item->exchange_rate }}</td>
                            <td>{{ $item->usage_part }}</td>
                            <td>{{ $item->overhead }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                        @php
                            $material_total += $item->total;
                        @endphp
                    @endforeach
                </tbody>
                </table>
                </div>
         <div class="col-6">
            <table>
                <!-- Student information -->
                <!-- ... -->
                <tr>
                    <td align="left" width="50%">
                        <b>Process</b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col">
            <table class="border">
                <thead>
                    <tr>
                        <th>Part No.</th>
                        <th>Part Name</th>
                        <th>Process Group</th>
                        <th>Process Code</th>
                        <th>Process Rate</th>
                        <th>Cycle Time</th>
                        <th>O/H</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="body-list-process">
                    @php
                        $process_total = 0;
                    @endphp
                    @foreach (DB::table('process_cost_tmmin')->where('cost_id', $cost->id)->get() as $item)
                        <tr>
                            <td>{{ $item->part_no }}</td>
                            <td>{{ $item->part_name }}</td>
                            <td>{{ DB::table('process')->where('id',$item->process_group)->first()->name }}</td>
                            <td>{{ DB::table('process_code')->where('id',$item->process_code)->first()->name }}
                            </td>
                            <td>{{ $item->process_rate }}</td>
                            <td>{{ $item->cycle_time }}</td>
                            <td>{{ $item->overhead }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                        @php
                            $process_total += $item->total;
                        @endphp
                    @endforeach
                </tbody>
                </table>
                </div>
          <div class="col-6">
            <table>
                <!-- Student information -->
                <!-- ... -->
                <tr>
                    <td align="left" width="50%">
                        <b>Purchase</b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col">
        <table class="border">
                <thead>
                    <tr>
                        <th>Part No</th>
                        <th>Part Name</th>
                        <th>Currency</th>
                        <th>Type Currency</th>
                        <th>Period</th>
                        <th>Value Currency</th>
                        <th>Cost</th>
                        <th>Quantity</th>
                        <th>O/H</th>
                        <th>Total</th>
                    </tr>
                </thead>
                    <tbody id="body-list-purchase">
                    @php
                        $purchase_total = 0;
                    @endphp
                    @foreach (DB::table('purchase_cost_tmmin')->where('cost_id', $cost->id)->get() as $key => $item)
                        <tr>
                            <td>{{ $item->part_no }}</td>
                            <td>{{ $item->part_name }}</td>
                            <td>{{ DB::table('currency_group')->where('id',$item->currency)->first()->name }}</td>
                            <td>{{ $item->type_currency == 1 ? 'slide' : 'non slide' }}</td>
                            <td>{{ $item->period }}</td>
                            <td>{{ $item->value_currency }}</td>
                            <td>{{ $item->cost }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->overhead }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                        @php
                            $purchase_total += $item->total;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col">
            <table>
                <!-- Summary information -->
                <!-- ... -->
                <tr>
                    <td align="left" width="50%">
                        <b>Summary</b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col">
            <table class="border">
                <thead>
                    <tr>
                        <th>Part No</th>
                        <th>Part Name</th>
                        <th>Material Cost</th>
                        <th>Process Cost</th>
                        <th>Purchase Cost</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <!-- Signature and barcode -->
                <!-- ... -->
                <tbody>
                <td>{{$sum->part_no}}</td>
                <td>{{ $sum->part_name }}</td>
                <td>{{ $sum->material_cost }}</td>
                <td>{{ $sum->process_cost }}</td>
                <td>{{ $sum->purchase_cost }}</td>
                <td>{{ $sum->total }}</td>
                </tbody>
            </table>
        </div>

        <div class="col">
            <table>
                <!-- Student information -->
                <!-- ... -->
                <tr>
                    <br>
                    <td align="right" width="50%">
                        <h2><b>Harga Jual Part/Pcs = Rp  {{ $sum->total }}</b></h2>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>
