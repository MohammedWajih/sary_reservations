<?php

namespace App\Http\Controllers;

use App\Models\ReservationModel;
use App\Models\ResTableModel;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{

    private $restaurant_open;
    private $restaurant_close;

    public function __construct()
    {
        $this->restaurant_open = '12:00 PM';
        $this->restaurant_close = '11:59 PM';
    }

    public function checkReservationAvailable(Request $request)
    {

        $listReservation = [];
        $seats_no = $request->input('seats_no');
        $tableAvailable = ResTableModel::whereIn(
            'no_of_seats', [$seats_no, $seats_no - 1, $seats_no + 1]
        )->get();

        foreach ($tableAvailable as $table) {
            $reservation_today = ReservationModel::where([
                'table_no' => $table->table_no,
            ])->where('starting', '>=', Carbon::now())->orderBy('starting')->get();

            $next_slot = date("H:i A");
            // $reservation_today = [];
            if (count($reservation_today) == 0) {
                $listReservation[$table->table_no][] = $next_slot . " - " . $this->restaurant_close;
            }
            foreach ($reservation_today as $res) {
                if ((date('H:i A', (int) $next_slot)) < (date("H:i A", strtotime($res->starting)))) {
                    $listReservation[$table->table_no][] = $next_slot . " - " . $res->starting;
                    $next_slot = date("H:i A", strtotime($res->ending));
                } else {
                    $next_slot = date("H:i A", strtotime($res->ending));
                    break;
                }

            }
        }
        return $listReservation;
    }

    public function newReservation(Request $request)
    {
        $isReserved = false;
        $isClosed = false;
        if ((date('H:i A', strtotime($request->input('starting')))) < (date("H:i A", strtotime($this->restaurant_open))) || (date('H:i A', (int) strtotime($request->input('ending')))) > (date("H:i A", strtotime($this->restaurant_close)))) {
            $isClosed = true;
        }

        if ($isClosed) {
            return Response([
                "message" => " This slot is out of range  ",
            ]);
        }

        $res = ReservationModel::where([
            'table_no' => $request->input('table_no'),
        ])->get();

        foreach ($res as $r) {
            if ((date('H:i A', strtotime($request->input('starting')))) >= (date("H:i A", strtotime($r->starting))) && (date('H:i A', (int) strtotime($request->input('ending')))) <= (date("H:i A", strtotime($r->ending)))) {
                $isReserved = true;
                break;
            }
        }
        if ($isReserved) {
            return Response([
                "message" => " This slot is reserved ",
            ]);
        }
        return ReservationModel::create([
            'table_no' => $request->input('table_no'),
            'starting' => $request->input('starting'),
            'ending' => $request->input('ending'),
            'reservation_date' => date('Y-m-d'),
        ]);
    }

    public function getALL(Request $request)
    {
        if (Auth::user()->role == 'Admin') {
            $cond = [];
            if ($request->input('table_no')) {
                $cond = [
                    "table_no" => $request->input('table_no'),
                ];
            }
            if ($request->input('reservation_date')) {
                $cond = [
                    "reservation_date" => $request->input('reservation_date'),
                ];
            }
            return ReservationModel::where($cond)->paginate(10);
        } else {
            return response([
                "message" => " Not have Permission to access for this page",
            ], 401);
        }
    }

    public function delete($id)
    {
        return ReservationModel::find($id)->delete();

    }

}
