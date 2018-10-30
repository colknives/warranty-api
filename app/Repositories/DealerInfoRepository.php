<?php
/**
 * Dealer Info Repository
 */

namespace App\Repositories;

use App\Models\DealerInfo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use DB;

/**
 * Class DealerInfoRepository
 * @package App\Repositories
 */
class DealerInfoRepository extends CrudRepository
{
    const APPENDS = [];

    const PERPAGE = 10;

    /**
     * @var $model Model Holds the model instance
     */
    protected $model;


    /**
     * DealerInfoRepository constructor.
     * @param Design $model
     */
    public function __construct(
        DealerInfo $model
    )
    {
        parent::__construct("dealer_infos", $model);
        $this->model = $model;
    }

    /**
     * Get dealer full list of the model
     *
     * @param $keyword
     * @return object
     */
    public function dealerList($type)
    {

        $model = [];

        $result = $this->model
                     ->select('name')
                     ->where('type', $type)
                     ->groupBy('name')
                     ->orderBy('name', 'ASC');

        if( $result->count() > 0 ){
            $model = array_pluck($result->get()->toArray(), 'name');
        }

        return (object)[
            "status" => 200,
            "message" => __("messages.dealer_info.list.200"),
            "model" => $model
        ];
    }
}