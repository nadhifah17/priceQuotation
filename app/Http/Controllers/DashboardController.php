<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pageTitle = setPageTitle(__('view.dashboard'));
        $title = __('view.dashboard');
        return view('adminLte.pages.dashboard.index', compact('pageTitle', 'title'));
    }
}
