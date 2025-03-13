<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FiveWordGame;
use App\Models\Game;
use App\Models\SevenWordGame;
use App\Models\Theme;
use App\Models\ThreeWordGame;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WordController extends Controller
{
    use ResponseTrait;

   public function userAttempt(Request $request)
    {
        $request->validate([
            'target_word' => [
                'required',
                'string',
                'max:255',
                'regex:/^\w{3}$|^\w{5}$|^\w{7}$/',
            ],
            'attempts' => 'required|integer|max:500',
            'is_won' => 'required|boolean',
            'time_taken' => 'required|string',
            'selected_word' => 'required|integer|in:3,5,7',
        ], [
            'target_word.regex' => 'The target word must be exactly 3, 5, or 7 characters long.',
        ]);
    
        DB::beginTransaction();
        try {
            // Naya record insert karna
            $userAttempt = Game::create([
                'target_word' => $request->target_word,
                'attempts' => $request->attempts,
                'is_won' => $request->is_won,
                'time_taken' => $request->time_taken,
                'selected_word' => $request->selected_word,
            ]);
    
            $rank = null;
            if ($userAttempt->is_won) {
                // Jeetne wale users ko `attempts` aur `time_taken` ke mutabiq sort karna
                $rankedUsers = Game::where('is_won', 1)
                    ->orderBy('attempts', 'asc')
                    ->orderBy('time_taken', 'asc')
                    ->get(['id', 'attempts', 'time_taken']);
    
                // Rank Calculation (Dense Ranking)
                $rank = 1;
                $prevAttempts = null;
                $prevTime = null;
                $rankMapping = [];
    
                foreach ($rankedUsers as $index => $user) {
                    if ($user->attempts !== $prevAttempts || $user->time_taken !== $prevTime) {
                        $rank = $index + 1;
                    }
                    $rankMapping[$user->id] = $rank;
                    $prevAttempts = $user->attempts;
                    $prevTime = $user->time_taken;
                }
    
                // Naye user ka rank find karna
                $rank = $rankMapping[$userAttempt->id] ?? null;
            }
    
            DB::commit();
    
            return $this->SuccessResponse(message: 'User Attempt recorded successfully', data: [
                array_merge($userAttempt->toArray(), [
                    'rank_message' => "You are the top " . $rank ."%"
                ])
            ]);
            
            
            
        } catch (\Exception $e) {
            DB::rollBack();
            info('WordleController@store');
            info('Error: ' . $e->getMessage());
            info('On Line: ' . $e->getLine());
    
            return response()->json([
                'status' => false,
                'message' => 'Failed to record attempt',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'statusCode' => 400
            ], 400);
        }
    }


    public function getStatistics()
    {
        try {
            $topWord = Game::selectRaw("LOWER(target_word) as word, COUNT(*) as count")
                ->groupBy('word')
                ->orderByDesc('count')
                ->first();

            $averageGuesses = intval(Game::average('attempts') ?? 0);
            $averageTimeTaken = intval(Game::average('time_taken') ?? 0);

            $topWordText = $topWord ? $topWord->word : 'No data';
            $topWordLength = $topWord ? strlen($topWord->word) : 0;

            return $this->SuccessResponse(message: 'Game Statistics', data: [
                'top_word' => $topWordText,
                'top_word_length' => $topWordLength,
                'average_guesses' => $averageGuesses,
                'average_time_taken' => $averageTimeTaken
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getStatistics: ' . $e->getMessage());
            return $this->ErrorResponse('Failed to retrieve statistics');
        }
    }

    // public function fetchdataAll()
    // {
    //     $themes = Theme::select('id', 'theme_name', 'start_date', 'end_date')->get(); // Include start_date and end_date
    
    //     $threeWord = ThreeWordGame::with('themeData:id,theme_name')->get();
    //     $fiveWord = FiveWordGame::with('themeData:id,theme_name')->get();
    //     $sevenWord = SevenWordGame::with('themeData:id,theme_name')->get();
    
    //     if ($threeWord->isEmpty() && $fiveWord->isEmpty() && $sevenWord->isEmpty()) {
    //         return $this->ErrorResponse('Not Found Record');
    //     }
    
    //     return $this->SuccessResponse(message: 'Word List', data: [
    //         'theme' => $themes,
    //         'threeletter' => $this->formatWords($threeWord),
    //         'fiveletters' => $this->formatWords($fiveWord),
    //         'sevenletters' => $this->formatWords($sevenWord)
    //     ]);
    // }
    

    // private function formatWords($words)
    // {
    //     return $words->map(function ($word) {
    //         return [
    //             'id' => $word->id,
    //             'letter' => $word->letter,
    //             'date' => $word->date,
    //             'theme' => $word->themeData ? $word->themeData->theme_name : null,
    //             'created_at' => $word->created_at,
    //             'updated_at' => $word->updated_at
    //         ];
    //     });
    // }
    public function fetchAll()
    {
        // Fetch all themes with required columns
        $themes = Theme::select('id', 'theme_name', 'start_date', 'end_date')->get(); 
    
        $data = [
            'themes' => $themes, 
            'threeletters' => [],
            'fiveletters' => [],
            'sevenletters' => [],
        ];
    
        // Fetch three-letter words
        $threeWords = ThreeWordGame::with(['themeData:id,theme_name'])->get();
        $data['threeletters'] = $this->formatWordsWithThemeIndex($threeWords);
    
        // Fetch five-letter words
        $fiveWords = FiveWordGame::with(['themeData:id,theme_name'])->get();
        $data['fiveletters'] = $this->formatWordsWithThemeIndex($fiveWords);
    
        // Fetch seven-letter words
        $sevenWords = SevenWordGame::with(['themeData:id,theme_name'])->get();
        $data['sevenletters'] = $this->formatWordsWithThemeIndex($sevenWords);
    
        // Check if any data is found
        if (empty($data['threeletters']) && empty($data['fiveletters']) && empty($data['sevenletters'])) {
            return $this->ErrorResponse('No records found');
        }
    
        return $this->SuccessResponse(message: 'All Words & Themes Fetched Successfully', data: $data);
    }
    
    
    /**
     * Format words and add theme index
     */
    private function formatWordsWithThemeIndex($words)
    {
        // Define theme order
        $themesOrder = [
            'General' => 1,
            'Spring' => 2,
            'Summer' => 3,
            'Science' => 4,
            'Nature' => 5,
            'Explore' => 6,
            'Mystery' => 7
        ];
    
        return $words->map(function ($word) use ($themesOrder) {
            $themeName = $word->themeData->theme_name ?? null;
            return [
                'id' => $word->id,
                'letter' => $word->letter,
                'date' => isset($word->date) ? \Carbon\Carbon::parse($word->date)->format('Y-m-d') : null, // Fetching from DB
                'theme' => $themeName,
                'theme_index' => $themesOrder[$themeName] ?? null, 
                'created_at' => $word->created_at,
                'updated_at' => $word->updated_at,
            ];
        });
    }
    
    

 
    public function getUserStatistics()
    {
        try {
            $totalUsers = Game::count();
            $usersWith3Words = Game::whereRaw('LENGTH(target_word) = 3')->count();
            $usersWith5Words = Game::whereRaw('LENGTH(target_word) = 5')->count();
            $usersWith7Words = Game::whereRaw('LENGTH(target_word) = 7')->count();
            $topWord = Game::selectRaw("target_word, COUNT(*) as count")
                ->groupBy('target_word')
                ->orderByDesc('count')
                ->first();
    
            return $this->SuccessResponse(message: 'User Statistics', data: [
                'total_users' => $totalUsers,
                'users_with_3_words' => $usersWith3Words,
                'users_with_5_words' => $usersWith5Words,
                'users_with_7_words' => $usersWith7Words,
                'most_played_word' => $topWord ? $topWord->target_word : 'No data',
                'most_played_word_count' => $topWord ? $topWord->count : 0
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getUserStatistics: ' . $e->getMessage());
            return $this->ErrorResponse('Failed to retrieve user statistics');
        }
    }
    
}
