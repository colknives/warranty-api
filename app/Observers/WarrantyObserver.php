<?php
/**
 * Brand Observer
 */
namespace App\Observers;

use App\Models\Warranty;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Repositories\WarrantyRepository;

/**
 * Class WarrantyObserver
 * @package App\Observers
 */
class WarrantyObserver
{

    /**
     * Request Instance
     *
     * @var
     */
    protected $request;

    /**
     * Warranty Repository Instance
     *
     * @var
     */
    protected $warrantyRepository;

    /**
     * WarrantyObserver constructor.
     * @param Request $request
     * @param WarrantyRepository $warrantyRepository
     */
    public function __construct(
        Request $request,
        WarrantyRepository $warrantyRepository)
    {
        $this->request = $request;
        $this->warrantyRepository = $warrantyRepository;
    }

    /**
     * Created Event
     *
     * @param Warranty $warranty
     */
    public function created(Warranty $warranty)
    {
        $warranty->uuid = Uuid::uuid4()->toString();
        $warranty->save();
    }
}