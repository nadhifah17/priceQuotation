@extends('layouts.base')
@php
    $materials = \App\Models\Material::all();
    $materialRoute = collect($materials)->pluck('name')->all();
    $materialRoute = collect($materialRoute)->map(function($item) {return 'material.' . $item;})->all();
    $currency_type = [
        [
            'id' => \App\Models\CurrencyValue::SLIDE_TYPE,
            'name' => 'Slide',
            'slug' => 'slide'
        ],
        [
            'id' => \App\Models\CurrencyValue::NON_SLIDE_TYPE,
            'name' => 'Non Slide',
            'slug' => 'non-slide'
        ]
    ];

    $currency_group = [
        ['name' => 'IDR'],
        ['name' => 'USD'],
        ['name' => 'JPY'],
        ['name' => 'THB'],
    ];

    $currency_route = [];
    foreach ($currency_type as $type) {
        foreach ($currency_group as $group) {
            $currency_route[] = 'currency.' . $type['slug'] . '.' . $group['name'];
        }
    }
@endphp
@section('pageTitle')
    {!! $pageTitle !!}
    <head>
    <script src="https://kit.fontawesome.com/ba36379ece.js" crossorigin="anonymous"></script>
    </head>
@endsection

@section('content')

  <style>
    .card-container {
        display: grid; /* Use flexbox to create a horizontal layout */
        grid-template-columns: auto auto auto ;
    }

    .card {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 10px;
        width: 300px;
        height: 200px;
        text-align: center;
        margin: 20px;
    }

    .card img {
        width: 20%;
        display: block; /* Convert the image to a block-level element */
        margin: 0 auto; /* Set margins to auto to center the image horizontally */
        border-radius: 8px 8px 0 0; /* Rounded top corners for the thumbnail */
    }

    .cost-grid {
        display: grid; /* Use flexbox to create a horizontal layout */
        grid-template-columns: auto auto;
    }

    .inside {
        display: grid; /* Use flexbox to create a horizontal layout */
        grid-template-columns: auto auto auto auto ;
    }

    /* CSS untuk ukuran tiap manajemen */
    .manage-setting-card .card {
        /* Ganti properti CSS untuk manage-setting */
        width: 400px;
        height: 100px;
    }

    .manage-master-tmmin-card .card {
        /* Ganti properti CSS untuk manage-master-tmmin */
        width: 400px;
        height: 150px;
    }

    .manage-cost-tmmin-card .card {
        /* Ganti properti CSS untuk manage-cost-tmmin */
        width: 600px;
        height: 100px;
    }
  </style>
  <body>
    @if (auth()->user()->can('manage-setting'))
      <div class="card-container manage-setting-card">
          <div class="card" style="background-color: #FFC0CB;">
            <h2>USERS</h2>
            
            <a href="http://127.0.0.1:8000/admin/users">Read More</a>
          </div>

          <div class="card" style="background-color: #ADD8E6;">
            <h2>PERMISSIONS</h2>
            
            <a href="http://127.0.0.1:8000/admin/permissions">Read More</a>
          </div>

          <div class="card" style="background-color: #c4fcc4;">
            <h2>ROLES</h2>
            <a href="http://127.0.0.1:8000/admin/roles">Read More</a>
          </div>
      </div>
    @endif

    @if (auth()->user()->can('manage-master-tmmin'))
      <div class="card-container manage-master-tmmin-card">
          <div class="card" style="background-color: #FFC0CB;">
            <h2>Material</h2>
            <div class="inside">
                <!-- all type -->
                @foreach ($materials as $material)
                    <div>
                        <a href="{{ route('material.' . $material->name, $material->name) }}">
                            {{ ucfirst($material->name) }}
                        </a>
                    </div>
                @endforeach
            </div>
          </div>

          <div class="card" style="background-color: #ADD8E6;">
            <h2>Process</h2>
            <a href="http://127.0.0.1:8000/admin/process">Process</a>
          </div>

          <div class="card" style="background-color: #98FB98;">
            <h2>Currency</h2>
            <div class="cost-grid">
                <!-- all type -->
                @foreach ($currency_type as $type)
                        <p style="padding: 5px;">{{ $type['name'] }}</p>
                    
                        <ul class="nav nav-treeview">
                            <!-- all type -->
                            @foreach ($currency_group as $group)
                                    <a href="{{ route('currency.' . $type['slug'] . '.' . $group['name']) }}" style="padding: 5px;">
                                        {{ ucfirst($group['name']) }}
                                    </a>
                            @endforeach
                        </ul>
                @endforeach
            </div>
          </div>

          <div class="card" style="background-color: #FFD700;">
            <h2>Summary Cost TMMIN</h2>
                <a href="{{ route('tmmin.index') }}">
                    <p>{{ __('view.cost_tmmin') }}</p>
                </a>
          </div>

          <div class="card" style="background-color: #FFA500;">
            <h2>Sistem Pendukung Keputusan</h2>
                <a href="{{ route('data.index') }}">
                    <p>{{ __('view.spk_tmmin') }}</p>
                </a>
          </div>
      </div>
    @endif

    @if (auth()->user()->can('manage-cost-tmmin'))
      <div class="card-container manage-cost-tmmin-card">
          <div class="card" style="background-color: #FFC0CB;">
            <h2>Part Cost</h2>
            <a href="http://127.0.0.1:8000/admin/tmmin">Read More</a>
          </div>
           <div class="card" style="background-color: #ADD8E6;">
                <h2>Sistem Pendukung Keputusan</h2>
                <a href="http://127.0.0.1:8000/admin/data">Read More</a>
           </div>
       </div>
    @endif
  </body>
    
@endsection
