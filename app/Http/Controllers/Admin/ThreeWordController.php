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

class ThreeWordController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $themes = [
                1 => 'Speak',
                2 => 'Spear',
                3 => 'Table',
                4 => 'Check',
                5 => 'Chump',
            ];
    
            $data = ThreeWordGame::latest()->get();
    
            return DataTables::of($data)
                ->editColumn('theme', function ($row) use ($themes) {
                    return $themes[$row->theme] ?? 'Unknown';
                })
                ->addColumn('action', function ($row) use ($themes) {
                    $delete = "deleteModal('" . $row->id . "','" . $row->letter . "');";
                    $themeText = addslashes($themes[$row->theme] ?? 'Unknown');
    
                    $edit = "editModal('" . $row->id . "', '" . $row->letter . "', '" . $row->date . "', `" . $themeText . "`);";
    
                    return '<button onclick="' . $edit . '" class="btn btn-primary">Edit</button>
                            <button class="btn btn-danger" onclick="' . $delete . '">Delete</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        return view('admin.pages.threeword.index');
    }
    

    

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'letter' => 'required|string|max:3',
            'date' => 'required|string',
           'theme' => 'required|integer|between:1,5',
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
           'theme' => 'required|integer|between:1,5',
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
}
