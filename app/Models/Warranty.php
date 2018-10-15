<?php
/**
  * Customer model
  */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

/**
 * Class Customer
 * @package App\Models
 */
class Warranty extends Model
{
    const SORT = 'uuid';
    const FIELDS = [
        "uuid",
        "claim_no",
        "firstname",
        "lastname",
        "contact_number",
        "email",
        "address",
        "suburb",
        "postcode",
        "country",
        "serial_number",
        "purchase_date",
        "product_type",
        "product_applied",
        "proof_purchase",
        "dealer_name",
        "dealer_location"
    ];

    /**
     * List of fillable fields
     * @var array
     */
    protected $fillable = [
        "claim_no",
        "firstname",
        "lastname",
        "contact_number",
        "email",
        "address",
        "suburb",
        "postcode",
        "country",
        "serial_number",
        "purchase_date",
        "product_type",
        "product_applied",
        "proof_purchase",
        "dealer_name",
        "dealer_location"
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