<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $modules = collect(config('access.modules'))
            ->filter(fn (array $module): bool => $request->user()->hasAbility($module['ability']))
            ->values();

        return view('dashboard', [
            'modules' => $modules,
        ]);
    }
}
