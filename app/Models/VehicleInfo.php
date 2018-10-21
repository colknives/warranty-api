<?php
/**
  * Vehicle Info model
  */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

/**
 * Class VehicleInfo
 * @package App\Models
 */
class VehicleInfo extends Model
{
    const SORT = 'year';
    const FIELDS = [
        "year",
        "make",
        "model"
    ];

    /**
     * List of fillable fields
     * @var array
     */
    protected $fillable = [
        "year",
        "make",
        "model"
    ];

    /**
     * List of hidden fields
     * @var array
     */
    protected $hidden = [
        "created_at",
        "id",
        "updated_at"
    ];
}