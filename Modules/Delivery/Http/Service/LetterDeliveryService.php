<?php

namespace Modules\Delivery\Http\Service;

use App\Http\Service\BaseService;
use App\Models\LetterDelivery;
use Exception;
use Illuminate\Support\Facades\Cache;
use Modules\Delivery\Contract\LetterDeliveryServiceInterface;
use Modules\Delivery\DTO\LetterDeliveryDto;
use Modules\Delivery\Http\Cache\LetterDeliveryCache;

class LetterDeliveryService extends BaseService implements LetterDeliveryServiceInterface
{
    public function get(string $id, string|array|null $relation = null)
    {
        try {
            $cacheKey = LetterDeliveryCache::GET . '_' . $id . ':' . md5(json_encode([
                'relation' => $relation
            ]));
            return Cache::tags([LetterDeliveryCache::GET, LetterDeliveryCache::GET . "_" . $id])->remember(
                $cacheKey,
                LetterDeliveryCache::GET_EXPIRY,
                fn() => LetterDelivery::query()
                    ->when(
                        $relation,
                        fn($query, $relation) => $query->with($relation)
                    )->findOrFail($id)
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAll(string|array|null $relation = null, ?array $condsIn = null, ?array $condsNotIn = null, ?array $queryOptions = null)
    {
        try {
            $cacheKey = LetterDeliveryCache::GET_ALL . ':' . md5(json_encode([
                'relation' => $relation,
                'condsIn'   => $condsIn,
                'condsNotIn' => $condsNotIn,
                'queryOptions' => $queryOptions
            ]));

            return Cache::tags([LetterDeliveryCache::GET_ALL])->remember(
                $cacheKey,
                LetterDeliveryCache::GET_EXPIRY,
                fn() => $this->fetch(
                    LetterDelivery::when(
                        $relation,
                        fn($query, $relation) => $query->with($relation)
                    )->when(
                        $condsIn,
                        fn($query, $condsIn) => $query->condsInByColumns($condsIn)
                    )->when(
                        $condsNotIn,
                        fn($query, $condsNotIn) => $query->condsNotInByColumns($condsNotIn)
                    )->when(
                        $queryOptions,
                        fn($query, $queryOptions) => $query->queryOptions($queryOptions)
                    ),
                    $queryOptions
                )
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function create(LetterDeliveryDto $letterDeliveryDto)
    {
        try {
            return LetterDelivery::create($letterDeliveryDto->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(LetterDeliveryDto $letterDeliveryDto)
    {
        try {
            $letterDelivery = $this->get($letterDeliveryDto->id);
            $letterDelivery->fill($letterDeliveryDto->toArray());
            $letterDelivery->save();

            return $letterDelivery;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string|LetterDelivery $id)
    {
        try {
            $letterDelivery = $id instanceof LetterDelivery ? $id : $this->get($id);
            $title = $letterDelivery->{LetterDelivery::id};
            $letterDelivery->delete();

            return $title;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
