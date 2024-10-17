<?php

namespace Modules\Core\Http\Controllers\Admin;

use App\User;
use Carbon\Carbon;
use Modules\Listing\Entities\Listing;
use Modules\Transection\Entities\Transection;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use DB;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        
        $usersData = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        $listingsData = Listing::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        $creditsTradedData = Transection::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(credits) as credits'))
            ->groupBy('date')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->where('status', 'Completed')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        $transactionsData = Transection::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->where('status', 'Completed')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        foreach ($usersData as $key => $data) {
            $dates[] = $data->date;
            $userDataArray[$data->date] = [
                'total' => $data->total,
            ];
        }

        foreach ($listingsData as $key => $data) {
            $dates[] = $data->date;
            $listingsDataArray[$data->date] = [
                'total' => $data->total,
            ];
        }

        foreach ($creditsTradedData as $key => $data) { 
            $dates[] = $data->date;
            $creditsTradedDataArray[$data->date] = [
                'credits' => $data->credits,
            ];
        }
        
        foreach ($transactionsData as $key => $data) { 
            $dates[] = $data->date;
            $transactionsDataArray[$data->date] = [
                'total' => $data->total,
            ];
        }

        foreach (array_unique($dates) as $key => $date) { 
            $tableData[] = [
                'date' => $date,
                'users' => (array_key_exists($date, $userDataArray))? $userDataArray[$date]['total'] : 0,
                'listings' => (array_key_exists($date, $listingsDataArray))? $listingsDataArray[$date]['total'] : 0,
                'credits' => (array_key_exists($date, $creditsTradedDataArray))? $creditsTradedDataArray[$date]['credits'] : 0,
                'transactions' => (array_key_exists($date, $transactionsDataArray))? $transactionsDataArray[$date]['total'] : 0,
                'flagged_listings' => 0,
            ];
        
        }

        return $this->dataResponse([
            $tableData,             
        ]);
        
    }
}
