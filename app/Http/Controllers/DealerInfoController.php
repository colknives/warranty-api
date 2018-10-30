<?php

namespace App\Http\Controllers;

use App\Repositories\DealerInfoRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DealerInfoController extends Controller
{

    /**
     * Dealer Info Repository Instance
     *
     * @var
     */
    protected $dealerInfoRepository;

    /**
     * DealerInfoController constructor.
     * 
     * @param DealerInfoRepository $dealerInfoRepository
     * @return void
     */
    public function __construct(
        DealerInfoRepository $dealerInfoRepository)
    {   
        $this->dealerInfoRepository = $dealerInfoRepository;
    }

    /**
     * Get dealer list instance.
     *
     * @return void
     */
    public function nameList(Request $request)
    {
        $this->validate($request, [
            'type' => "required"
        ]);

        $list = $this->dealerInfoRepository->dealerList($request->get('type'));

        return response()->json([
            "message" => $list->message,
            "dealers" => $list->model,
        ]);
    }
}
