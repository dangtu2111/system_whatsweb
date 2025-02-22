<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
	public function index()
	{
		$title = 'Reports';
		return view('reports.index', compact('title'));
	}
}
