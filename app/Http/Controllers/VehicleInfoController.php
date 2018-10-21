<?php

namespace App\Http\Controllers;

use App\Repositories\VehicleInfoRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VehicleInfoController extends Controller
{

    /**
     * Vehicle Info Repository Instance
     *
     * @var
     */
    protected $vehicleInfoRepository;

    /**
     * VehicleInfoController constructor.
     * 
     * @param VehicleInfoRepository $vehicleInfoRepository
     * @return void
     */
    public function __construct(
        VehicleInfoRepository $vehicleInfoRepository)
    {   
        $this->vehicleInfoRepository = $vehicleInfoRepository;
    }

    /**
     * Get make list instance.
     *
     * @return void
     */
    public function getMake($keyword = '')
    {

        $list = $this->vehicleInfoRepository->getMake($keyword);

        return response()->json([
            "message" => $list->message,
            "items" => $list->make,
        ]);
    }

    /**
     * Get model list instance.
     *
     * @return void
     */
    public function getModel($keyword = '')
    {

        $list = $this->vehicleInfoRepository->getModel($keyword);

        return response()->json([
            "message" => $list->message,
            "items" => $list->model,
        ]);
    }
}
