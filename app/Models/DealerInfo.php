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
 * Class DealerInfo
 * @package App\Models
 */
class DealerInfo extends Model
{
    const SORT = 'name';
    const FIELDS = [
        "id",
        "name",
        "type"
    ];

    /**
     * List of fillable fields
     * @var array
     */
    protected $fillable = [
        "name",
        "type"
    ];

    /**
     * List of hidden fields
     * @var array
     */
    protected $hidden = [
        "created_at",
        "updated_at"
    ];
}