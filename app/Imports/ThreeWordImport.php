<?php

namespace App\Imports;

use App\Models\ThreeWordGame;
use App\Models\Theme;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ThreeWordImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $themeName = trim($row['theme']); 
        $theme = Theme::where('theme_name', $themeName)->first();
        if (!$theme) {
            return null;
        }

        return new ThreeWordGame([
            'letter' => $row['letter'], 
            'date'   => $row['date'],    
            'theme'  => $theme->id, 
        ]);
    }
}
