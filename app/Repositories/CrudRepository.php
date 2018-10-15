<?php
/**
 * Core Repository Class
 */

namespace App\Repositories;

use App\Models\Model;

/**
 * Class CrudRepository
 * @package App\Repositories
 */
abstract class CrudRepository
{
    const PERPAGE = 25;
    const APPENDS = [];
    const WITH = [];

    /**
     * @var $module String for the module name
     */
    protected $module;
    /**
     * @var $model Model Holds the model instance
     */
    protected $model;

    /**
     * CrudRepository constructor.
     * @param $module
     * @param Model $model
     */
    public function __construct($module, Model $model)
    {
        $this->module = $module;
        $this->model = $model;
    }

    /**
     * Get the list by PERPAGE SETUP
     *
     * @param $page
     * @return object
     */
    public function getList($page)
    {
        $count = $this->model->count();

        // IF EMPTY PAGE //
        if($page == 1 && $count == 0)
            return (object)[
                "message" => __("messages.{$this->module}.index.empty" ),
                "status" => 200,
                "list" => [],
                "max_page" => 1,
                "prev_page" => 0,
                "next_page" => 0,
            ];

        $max_page = ceil($count/static::PERPAGE);

        if( $page > $max_page )
            return (object)[
                "status" => 404,
                "message" => __("messages.{$this->module}.index.404")
            ];

        return (object)[
            "message" => null,
            "status" => 200,
            "list" => $this->model->byPage($page, static::PERPAGE),
            "max_page" => $max_page,
            "prev_page" => $page == 1 ? 0 : $page - 1,
            "next_page" => $page == $max_page ? 0 : $page + 1
        ];
    }

    /**
     * Create of the model function
     *
     * @param $data
     * @return object
     */
    public function create($data)
    {
        $this->model->fill($data)->save();
        $this->model->append(static::APPENDS);

        return (object)[
            "status" => 200,
            "message" => __("messages.{$this->module}.create.200"),
            "model" => $this->model
        ];
    }

    /**
     * Updating of the model
     *
     * @param Model $model
     * @param $data
     * @return object
     */
    public function update(Model $model, $data)
    {
        $model->fill($data)->save();
        $model->append(static::APPENDS);

        return (object)[
            "status" => 200,
            "message" => __("messages.{$this->module}.update.200"),
            "model" => $model,
        ];
    }

    /**
     * Deletion of the model
     *
     * @param Model $model
     * @return object
     */
    public function delete(Model $model)
    {
        if( $model->deletable ){
            $model->delete();
            return (object)[
                "status" => 200,
                "message" => __("messages.{$this->module}.delete.200")
            ];
        }

        return (object)[
            "status" => 400,
            "message" => __("messages.{$this->module}.delete.400")
        ];
    }

    /**
     * View Details
     *
     * @param Model $model
     * @return $this
     */
    public function view(Model $model)
    {
        return $model->append(static::APPENDS);
    }

    /**
     * Find by ID
     *
     * @param $field
     * @param $id
     * @return mixed
     */
    public function find($field, $id)
    {
        return $this->model->where($field, $id)->first();
    }
}