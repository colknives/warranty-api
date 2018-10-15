<?php

namespace App\Jobs;

use App\Services\WarrantyService;

class AttachmentJob extends Job
{

    /**
     * Attachment instance.
     */
    protected $attachment;

    /**
     * Zoho Service Instance
     */
    protected $warranty;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($attachment)
    {
        $this->attachment = $attachment;
        $this->warranty = new WarrantyService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $upload = $this->warranty->upload($this->attachment['id'], $this->attachment['filename']);

        // $upload = $this->warranty->upload('3548539000000330025', '/var/www/tfgroup/api/zoho/storage/attachment/fx1Vvr5a3AEBy6rQTe1539530002.png');
    }
}
