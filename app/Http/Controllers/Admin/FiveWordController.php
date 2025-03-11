<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\FiveWordImport;
use App\Models\FiveWordGame;
use App\Models\Theme;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class FiveWordController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $themes = Theme::pluck('theme_name', 'id')->toArray();
            
    
            $data = FiveWordGame::latest()->get();
    
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
        return view('admin.pages.fiveword.index', compact('themes'));
    }
    
    
    
    

    

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'letter' => 'required|string|max:5',
            'date' => 'required|string',
           'theme' => 'required|string', 
        ]);

        DB::beginTransaction();

        try {
            $category = FiveWordGame::create([
                'letter' => $request->letter,
                'date' => $request->date,
                'theme' => $request->theme,
            ]);
            DB::commit();
            return $this->SuccessResponse(message: 'Five Word detail saved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            info('FiveWordController@store');
            info('Error: ' . $e->getMessage());
            info('On Line: ' . $e->getLine());
            return $this->ErrorResponse('Failed to save Five Word and Detail' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'letter' => 'required|string|max:5',
            'date' => 'required|string',
           'theme' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $category = FiveWordGame::findOrFail($id);
            $category->update([
                'letter' => $request->letter,
                'date' => $request->date,
                'theme' => $request->theme,
            ]);
            DB::commit();
            return $this->SuccessResponse(message: 'Five Word detail Update successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            info('FiveWordController@update');
            info('Error: ' . $e->getMessage());
            info('On Line: ' . $e->getLine());
            return $this->ErrorResponse('Failed to update Five Word  and Detail' . $e->getMessage());
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
            $category = FiveWordGame::findOrFail($request->id);
            // Delete the category
            $category->delete();

            DB::commit();
            return $this->SuccessResponse(message: 'Five Word deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            info('FiveWordController@delete');
            info('Error: ' . $e->getMessage());
            info('On Line: ' . $e->getLine());
            return $this->ErrorResponse('Failed to delete Five Word: ' . $e->getMessage());
        }
    }
    public function importFiveWord(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:csv,txt'
    ]);

    $file = $request->file('file');
    $handle = fopen($file, "r");
    $headers = fgetcsv($handle); 

    // Letter aur theme ka column index
    $letterColumnIndex = 0;  // Suppose first column letter ka hai
    $themeColumnIndex = 2;   // Suppose second column theme ka hai

    while (($row = fgetcsv($handle)) !== false) {
        // **Letter Validation** (5 letters only)
        if (!isset($row[$letterColumnIndex])) {
            return redirect()->back()->with('error', 'Invalid CSV format.');
        }

        $letterValue = trim($row[$letterColumnIndex]);
        if (!preg_match('/^[a-zA-Z]{5}$/u', $letterValue)) {
            return redirect()->back()->with('error', "Invalid value in letter column. It must have exactly 5 letters.");
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
    Excel::import(new FiveWordImport, $file);

    return redirect()->back()->with('success', 'CSV file imported successfully!');
}

    
}
