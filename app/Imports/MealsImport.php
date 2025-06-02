<?php

namespace App\Imports;

use App\Models\Meal;
use Maatwebsite\Excel\Concerns\ToModel;

class MealsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row);
        return new Meal([
            'title'       => $row[0],               // Column: "Breakfast"
            'description' => $row[1],             // Column: "Description"
            'note'        => $row[2],                           // Column: "Notes / Variations"
            'user_id'     => auth()->id(),                    // Optional: link to current user
            'created_at' => now(),
            'u[dated_at' => now(),
        ]);
    }
}
