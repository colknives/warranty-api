<?php
/**
 * Vehicle Info Repository
 */

namespace App\Repositories;

use App\Models\VehicleInfo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

/**
 * Class VehicleInfoRepository
 * @package App\Repositories
 */
class VehicleInfoRepository extends CrudRepository
{
    const APPENDS = [];

    const PERPAGE = 10;

    /**
     * @var $model Model Holds the model instance
     */
    protected $model;


    /**
     * VehicleInfoRepository constructor.
     * @param Design $model
     */
    public function __construct(
        VehicleInfo $model
    )
    {
        parent::__construct("vehicle_infos", $model);
        $this->model = $model;
    }

    /**
     * Get make of the model
     *
     * @param $keyword
     * @return object
     */
    public function getMake($keyword)
    {

        $model = [];

        $result = $this->model
                     ->select(['make'])
                     ->where('make', 'like', $keyword.'%')
                     ->groupBy(['make'])
                     ->orderBy('make', 'ASC');

        if( $result->count() > 0 ){
            $model = array_pluck($result->get()->toArray(), 'make');
        }

        return (object)[
            "status" => 200,
            "message" => __("messages.vehicle_info.list.200"),
            "make" => $model
        ];
    }

    /**
     * Get vehicle model of the model
     *
     * @param $keyword
     * @return object
     */
    public function getModel($keyword)
    {

        $model = [];

        $result = $this->model
                     ->select(['model'])
                     ->where('model', 'like', $keyword.'%')
                     ->groupBy(['model'])
                     ->orderBy('model', 'ASC');

        if( $result->count() > 0 ){
            $model = array_pluck($result->get()->toArray(), 'model');
        }

        return (object)[
            "status" => 200,
            "message" => __("messages.vehicle_info.list.200"),
            "model" => $model
        ];
    }
}