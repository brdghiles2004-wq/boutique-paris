<?php

namespace App\Http\Controllers;

use App\Models\Commune;

class CommuneController extends Controller
{
    public function index($wilaya)
    {
        return Commune::where('wilaya_code', $wilaya)
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}