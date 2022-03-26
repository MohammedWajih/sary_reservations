<?php

namespace App\Http\Controllers;

use App\Models\ReservationModel;
use App\Models\ResTableModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ResTableController extends Controller
{
    // retrieve all tables
    public function getAll()
    {
        if (Auth::user()->role == 'Admin') {
            return ResTableModel::paginate(10);
        } else {
            return response([
                "message" => " Not have Permission to access for this page",
            ], 401);
        }
    }

    // Add new table
    public function store(Request $request)
    {
        if (Auth::user()->role == 'Admin') {

            if ((int) $request->input('no_of_seats') > 12 || (int) $request->input('no_of_seats') == 0) {
                return response([
                    "message" => " The number of seats must be between 1 to 12  ",
                ]);
            }

            if (ResTableModel::where("table_no", $request->input('table_no'))->get()->count() > 0) {
                return response([
                    "message" => " The table is already exist ",
                ]);
            }
            return ResTableModel::create([
                'table_no' => $request->input('table_no'),
                'no_of_seats' => $request->input('no_of_seats'),
            ]);
        } else {
            return response([
                "message" => " Not have Permission to access for this page",
            ], 401);
        }
    }

    // delete table
    public function delete($id)
    {
        if (Auth::user()->role == 'Admin') {
            $table = ResTableModel::find($id);
            if ($table) {
                $check = ReservationModel::where([
                    'table_no' => $table->table_no,
                ])->get()->count();
                if ($check > 0) {
                    return response([
                        "message" => " Can't delete, there is a reservation on this table ",
                    ]);
                } else {
                    $id = $table->delete();
                    return response([
                        "message" => "success",
                    ]);
                }
            } else {
                return response([
                    "message" => "  can't found this table ",
                ]);
            }
        } else {
            return response([
                "message" => " Not have Permission to access for this page",
            ], 401);
        }

    }

}
