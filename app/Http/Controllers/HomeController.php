<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the career / profile home page.
     */
    public function index(): View
    {
        $personalInfo = $this->findProfile();

        return view('home.index', [
            'personalInfo' => $personalInfo,
        ]);
    }

    /**
     * The site shows one profile. It is null until the database has been seeded.
     */
    private function findProfile(): ?PersonalInfo
    {
        return PersonalInfo::query()
            ->with('education')
            ->first();
    }
}
