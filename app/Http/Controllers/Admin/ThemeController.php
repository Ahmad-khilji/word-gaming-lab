<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ThemeController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Theme::get();

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $delete = "deleteModal('" . $row->id . "','" . addslashes($row->theme_name) . "');";
                $edit = "editModal('" . $row->id . "', '" . addslashes($row->theme_name) . "', '" . $row->start_date . "', '" . $row->end_date . "');";

                return '<button onclick="' . $edit . '" class="btn btn-primary">Edit</button>
                        <button class="btn btn-danger" onclick="' . $delete . '">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('admin.pages.theme.index');
}


    

public function store(Request $request)
{
    $request->validate([
        'theme_name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    // Check if any existing theme conflicts with the new date range
    $themeExists = Theme::where(function ($query) use ($request) {
        $query->whereBetween('start_date', [$request->start_date, $request->end_date])
              ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
              ->orWhere(function ($query) use ($request) {
                  $query->where('start_date', '<=', $request->start_date)
                        ->where('end_date', '>=', $request->end_date);
              });
    })->exists();

    if ($themeExists) {
        return $this->ErrorResponse('A theme already exists in the selected date range.');
    }

    DB::beginTransaction();

    try {
        Theme::create([
            'theme_name' => $request->theme_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        DB::commit();
        return $this->SuccessResponse(message: 'Theme details saved successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        info('ThemeController@store');
        info('Error: ' . $e->getMessage());
        info('On Line: ' . $e->getLine());
        return $this->ErrorResponse('Failed to save Theme details. Error: ' . $e->getMessage());
    }
}

public function update(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'theme_name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    DB::beginTransaction();

    try {
        // Check if any existing theme overlaps with the given start_date and end_date
        $existingTheme = Theme::where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->exists();

        if ($existingTheme) {
            return $this->ErrorResponse('A theme already exists within the selected date range.');
        }

        // Update the theme if no conflict exists
        $category = Theme::findOrFail($id);
        $category->update([
            'theme_name' => $request->theme_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        DB::commit();
        return $this->SuccessResponse(message: 'Theme details updated successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        info('ThemeController@update');
        info('Error: ' . $e->getMessage());
        info('On Line: ' . $e->getLine());
        return $this->ErrorResponse('Failed to update Theme details. Error: ' . $e->getMessage());
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
            $category = Theme::findOrFail($request->id);
            // Delete the category
            $category->delete();

            DB::commit();
            return $this->SuccessResponse(message: 'Theme deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            info('ThemeController@delete');
            info('Error: ' . $e->getMessage());
            info('On Line: ' . $e->getLine());
            return $this->ErrorResponse('Failed to delete Theme: ' . $e->getMessage());
        }
    }

   
    public function getLastThemeDate()
    {
        $lastTheme = Theme::orderBy('end_date', 'desc')->first();
    
        if ($lastTheme) {
            return response()->json([
                'success' => true,
                'end_date' => \Carbon\Carbon::parse($lastTheme->end_date)->format('Y-m-d')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No themes found'
            ]);
        }
    }
    
    
}
