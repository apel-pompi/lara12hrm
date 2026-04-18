<?php

namespace App\Services\Agency\Setting;

use App\Filters\Agency\Setting\DocumentTypeFilter;

use App\Models\AgencySetting\WDocumentType;

class DocumentTypeService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = WDocumentType::with(['user'])->withCount('docusage')->orderBy('id', 'desc');

        $workflow = resolve(DocumentTypeFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $workflow;
    }
}
