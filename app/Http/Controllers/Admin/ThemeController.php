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
        $data = Theme::latest()->get();

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

    DB::beginTransaction();

    try {
        $category = Theme::create([
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
            $category = Theme::findOrFail($id);
            $category->update([
                'theme_name' => $request->theme_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);
            DB::commit();
            return $this->SuccessResponse(message: 'Theme detail Update successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            info('ThemeController@update');
            info('Error: ' . $e->getMessage());
            info('On Line: ' . $e->getLine());
            return $this->ErrorResponse('Failed to update Theme  and Detail' . $e->getMessage());
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

   

}
