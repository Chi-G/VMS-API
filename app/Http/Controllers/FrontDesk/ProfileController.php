<?php
namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        // Fetch profile details from the database
        $profile = [
            'name' => 'Julianne Roberts Smith',
            'email' => 'julianerobs@gmail.com',
            'position' => 'Secretary General',
            'company' => 'Sydani Technologies',
            'employee_id' => '110/123456',
            'role' => '👑Staff',
            'image' => 'profile.jpg',
        ];

        return view('frontdesk.profile.index', compact('profile'));
    }
}
