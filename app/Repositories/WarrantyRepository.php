<?php
/**
 * Design Repository
 */

namespace App\Repositories;

use App\Models\Warranty;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

/**
 * Class WarrantyRepository
 * @package App\Repositories
 */
class WarrantyRepository extends CrudRepository
{
    const APPENDS = [];

    const PERPAGE = 10;

    /**
     * @var $model Model Holds the model instance
     */
    protected $model;


    /**
     * WarrantyRepository constructor.
     * @param Design $model
     */
    public function __construct(
        Warranty $model
    )
    {
        parent::__construct("warranty", $model);
        $this->model = $model;
    }

    /**
     * Search warranty records
     *
     * @param $field
     * @param $id
     * @return mixed
     */
    public function searchWarranty($field, $value)
    {
        return $this->model->where($field, $value)->get();
    }
}