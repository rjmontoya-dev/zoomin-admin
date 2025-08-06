<?php

namespace App\Http\Service\Inquiry;

use App\Http\Service\Base\BaseService;

class InquiryService extends BaseService
{
    /**
     * @return void
     */
    protected function initialize(): void
    {
        $this->setSearchableFields([
            'name',
            'email',
            'company',
        ]);
    }
}
