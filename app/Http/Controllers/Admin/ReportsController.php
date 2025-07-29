<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * عرض صفحة التقارير العامة للعقد الموحد
     */
    public function unified()
    {
        return view('admin.reports.unified');
    }
} 