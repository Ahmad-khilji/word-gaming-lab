<?php

namespace App\Imports;

use App\Models\FiveWordGame;
use App\Models\Theme;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FiveWordImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Get Theme Name from CSV
        $themeName = trim($row['theme']); 
        
        // Find Theme ID
        $theme = Theme::where('theme_name', $themeName)->first();
        
        // Skip row if theme not found
        if (!$theme) {
            return null;
        }

        return new FiveWordGame([
            'letter' => $row['letter'], 
            'date'   => $row['date'],    
            'theme'  => $theme->id,  // Store Theme ID instead of name
        ]);
    }
}
