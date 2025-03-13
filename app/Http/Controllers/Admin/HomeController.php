<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        // Get the selected date from the request or default to today
        $selectedDate = $request->input('date', Carbon::today()->toDateString());
    
        // Total Users Count for the Selected Date
        $totalUsers = DB::table('games')->whereDate('created_at', $selectedDate)->count();
    
        // Win and Loss Count
        $wins = DB::table('games')->whereDate('created_at', $selectedDate)->where('is_won', 1)->count();
        $losses = DB::table('games')->whereDate('created_at', $selectedDate)->where('is_won', 0)->count();
    
        // Total Games Played
        $totalGames = $wins + $losses;
    
        // Corrected Win & Loss Rate Calculation
        $winRate = ($totalGames > 0) ? round(($wins / $totalGames) * 100, 2) : 0;
        $lossRate = ($totalGames > 0) ? round(($losses / $totalGames) * 100, 2) : 0;
    
        // Average Attempts Based on Selected Word (3, 5, 7 Letter Words)
        $averageAttempts = [
            'threeLetter' => DB::table('games')
                ->whereDate('created_at', $selectedDate)
                ->where('selected_word', 3)
                ->where('is_won', 1)
                ->avg('attempts') ?? 0,
    
            'fiveLetter' => DB::table('games')
                ->whereDate('created_at', $selectedDate)
                ->where('selected_word', 5)
                ->where('is_won', 1)
                ->avg('attempts') ?? 0,
    
            'sevenLetter' => DB::table('games')
                ->whereDate('created_at', $selectedDate)
                ->where('selected_word', 7)
                ->where('is_won', 1)
                ->avg('attempts') ?? 0
        ];
    
        // Average Time Taken (Seconds) for 3, 5, and 7-letter words
        $averageTimeTaken = [
            'threeLetter' => DB::table('games')
                ->whereDate('created_at', $selectedDate)
                ->where('selected_word', 3)
                ->where('is_won', 1)
                ->avg('time_taken') ?? 0,
    
            'fiveLetter' => DB::table('games')
                ->whereDate('created_at', $selectedDate)
                ->where('selected_word', 5)
                ->where('is_won', 1)
                ->avg('time_taken') ?? 0,
    
            'sevenLetter' => DB::table('games')
                ->whereDate('created_at', $selectedDate)
                ->where('selected_word', 7)
                ->where('is_won', 1)
                ->avg('time_taken') ?? 0
        ];
    
        // Categorizing the Difficulty Level for 3-Letter Words
        $threeLetterDifficulty = 'Unknown';
        if ($averageAttempts['threeLetter'] > 0) {
            if ($averageAttempts['threeLetter'] <= 2) {
                $threeLetterDifficulty = 'Easy';
            } elseif ($averageAttempts['threeLetter'] <= 4) {
                $threeLetterDifficulty = 'Normal';
            } else {
                $threeLetterDifficulty = 'Hardest';
            }
        }
    
        $fiveLetterDifficulty = 'Unknown';
        if ($averageAttempts['fiveLetter'] > 0) {
            if ($averageAttempts['fiveLetter'] <= 3) {
                $fiveLetterDifficulty = 'Easy';
            } elseif ($averageAttempts['fiveLetter'] <= 5) {
                $fiveLetterDifficulty = 'Normal';
            } else {
                $fiveLetterDifficulty = 'Hardest';
            }
        }
    
        // Categorizing the Difficulty Level for 7-Letter Words
        $sevenLetterDifficulty = 'Unknown';
        if ($averageAttempts['sevenLetter'] > 0) {
            if ($averageAttempts['sevenLetter'] <= 4) {
                $sevenLetterDifficulty = 'Easy';
            } elseif ($averageAttempts['sevenLetter'] <= 7) {
                $sevenLetterDifficulty = 'Normal';
            } else {
                $sevenLetterDifficulty = 'Hardest';
            }
        }
    
        // Retrieve words from each table with their theme_name
        $wordTables = [
            'three_word_games' => 'threeLetterWord',
            'five_word_games' => 'fiveLetterWord',
            'seven_word_games' => 'sevenLetterWord'
        ];
    
        $words = [];
    
        foreach ($wordTables as $table => $variable) {
            $words[$variable] = DB::table($table)
                ->join('themes', "{$table}.theme", '=', 'themes.id')
                ->select("{$table}.letter", 'themes.theme_name')
                ->whereDate("{$table}.date", $selectedDate)
                ->first();
        }
    
        return view('admin.index', array_merge([
            'totalUsers' => $totalUsers,
            'winRate' => $winRate,
            'lossRate' => $lossRate,
            'wins' => $wins,
            'losses' => $losses,
            'totalGames' => $totalGames,
            'averageAttempts' => $averageAttempts,
            'averageTimeTaken' => $averageTimeTaken,
            'threeLetterDifficulty' => $threeLetterDifficulty,
            'fiveLetterDifficulty' => $fiveLetterDifficulty,
            'sevenLetterDifficulty' => $sevenLetterDifficulty,
            'selectedDate' => $selectedDate, // Pass the selected date to the view
        ], $words));
    }







    public function change()
    {
        return view('admin.pages.user.index');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => ['nullable', 'email', 'unique:users,email,' . Auth::id()],
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required'],
            'confirm_password' => ['required', 'same:new_password'],
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Current password is incorrect'], 422);
        }
        $updateData = ['password' => Hash::make($request->new_password)];

        if (!empty($request->email)) {
            $updateData['email'] = $request->email;
        }

        $user->update($updateData);

        return response()->json(['status' => true, 'message' => 'Profile updated successfully']);
    }
}
