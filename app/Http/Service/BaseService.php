<?php

namespace App\Http\Service;

use Exception;
use Modules\Shared\DTO\QueryOptionsDto;

class BaseService
{
    protected function fetch($query, ?array $queryOptions)
    {
        try {
            $noPagination = $queryOptions[QueryOptionsDto::noPagination] ?? false;
            $perPage      = $queryOptions[QueryOptionsDto::pagPerPage] ?? 10;

            if ($noPagination) {
                return $query->get();
            }

            return $query->paginate($perPage)->withQueryString();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
