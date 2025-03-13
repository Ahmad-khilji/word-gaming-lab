<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\ThreeWordGame;
use App\Models\WordGame;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ThreeWordImport;
use App\Models\Theme;

class ThreeWordController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $themes = Theme::pluck('theme_name', 'id')->toArray();
    
            $data = ThreeWordGame::get();
    
            return DataTables::of($data)
                ->editColumn('theme', function ($row) use ($themes) {
                    return $themes[$row->theme] ?? 'Unknown';
                })
                ->addColumn('action', function ($row) {
                    $delete = "deleteModal('" . $row->id . "','" . $row->letter . "');";
                    $edit = "editModal('" . $row->id . "', '" . addslashes($row->letter) . "', '" . $row->date . "', '" . $row->theme . "');";
    
                    return '<button onclick="' . $edit . '" class="btn btn-primary">Edit</button>
                            <button class="btn btn-danger" onclick="' . $delete . '">Delete</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        $themes = Theme::all(); // Fetch themes for dropdown
        return view('admin.pages.threeword.index', compact('themes'));
    }
    
    
    

    

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'letter' => 'required|string|max:3',
            'date' => 'required|string',
           'theme' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $category = ThreeWordGame::create([
                'letter' => $request->letter,
                'date' => $request->date,
                'theme' => $request->theme,
            ]);
            DB::commit();
            return $this->SuccessResponse(message: 'Three Word detail saved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            info('ThreeWordController@store');
            info('Error: ' . $e->getMessage());
            info('On Line: ' . $e->getLine());
            return $this->ErrorResponse('Failed to save Three Word and Detail' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'letter' => 'required|string|max:3',
            'date' => 'required|string',
           'theme' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $category = ThreeWordGame::findOrFail($id);
            $category->update([
                'letter' => $request->letter,
                'date' => $request->date,
                'theme' => $request->theme,
            ]);
            DB::commit();
            return $this->SuccessResponse(message: 'Three Word detail Update successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            info('ThreeWordController@update');
            info('Error: ' . $e->getMessage());
            info('On Line: ' . $e->getLine());
            return $this->ErrorResponse('Failed to update Three Word  and Detail' . $e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        DB::beginTransaction();
        try {
            // Find the category
            $category = ThreeWordGame::findOrFail($request->id);
            // Delete the category
            $category->delete();

            DB::commit();
            return $this->SuccessResponse(message: 'Three Word deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            info('ThreeWordController@delete');
            info('Error: ' . $e->getMessage());
            info('On Line: ' . $e->getLine());
            return $this->ErrorResponse('Failed to delete Three Word: ' . $e->getMessage());
        }
    }

   

    public function importThreeWord(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);
    
        $file = $request->file('file');
        $handle = fopen($file, "r");
        $headers = fgetcsv($handle); 
    
        // Correct Column Indexes
        $letterColumnIndex = 0;  // Letter Column (First column)
        $themeColumnIndex = 2;   // Theme Column (Third column)
    
        while (($row = fgetcsv($handle)) !== false) {
            // **Letter Validation** (3 letters only)
            if (!isset($row[$letterColumnIndex])) {
                return redirect()->back()->with('error', 'Invalid CSV format.');
            }
    
            $letterValue = trim($row[$letterColumnIndex]);
            if (!preg_match('/^[a-zA-Z]{3}$/u', $letterValue)) {
                return redirect()->back()->with('error', "Invalid value in letter column. It must have exactly 3 letters.");
            }
    
            // **Theme Validation** (Database me exist hona chahiye)
            if (!isset($row[$themeColumnIndex])) {
                return redirect()->back()->with('error', 'Theme column is missing.');
            }
    
            $themeValue = trim($row[$themeColumnIndex]);
            $themeExists = Theme::where('theme_name', $themeValue)->exists();
    
            if (!$themeExists) {
                return redirect()->back()->with('error', "Theme Name does not exist. Please add it first.");
            }
        }
    
        fclose($handle);
    
        // Import if all validations pass
        Excel::import(new ThreeWordImport, $file);
    
        return redirect()->back()->with('success', 'CSV file imported successfully!');
    }
    
    
}
