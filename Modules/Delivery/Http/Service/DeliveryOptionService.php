<?php

namespace Modules\Delivery\Http\Service;

use App\Http\Service\BaseService;
use App\Models\DeliveryOption;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Delivery\Contract\DeliveryOptionServiceInterface;
use Modules\Delivery\DTO\DeliveryOptionDto;
use Modules\Delivery\Http\Cache\DeliveryOptionCache;

class DeliveryOptionService extends BaseService implements DeliveryOptionServiceInterface
{
    public function get(string $id, string|array|null $relation = null)
    {
        try {
            $cacheKey = DeliveryOptionCache::GET . '_' . $id . ':' . md5(json_encode([
                'relation' => $relation
            ]));
            return Cache::tags([DeliveryOptionCache::GET, DeliveryOptionCache::GET . "_" . $id])->remember(
                $cacheKey,
                DeliveryOptionCache::GET_EXPIRY,
                fn() => DeliveryOption::query()
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
            $cacheKey = DeliveryOptionCache::GET_ALL . ':' . md5(json_encode([
                'relation' => $relation,
                'condsIn'   => $condsIn,
                'condsNotIn' => $condsNotIn,
                'queryOptions' => $queryOptions
            ]));

            return Cache::tags([DeliveryOptionCache::GET_ALL])->remember(
                $cacheKey,
                DeliveryOptionCache::GET_EXPIRY,
                fn() => $this->fetch(
                    DeliveryOption::when(
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

    public function create(DeliveryOptionDto $deliveryOptionDto)
    {
        try {
            return DeliveryOption::create($deliveryOptionDto->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(DeliveryOptionDto $deliveryOptionDto)
    {
        try {
            $deliveryOption = $this->get($deliveryOptionDto->id);
            $deliveryOption->fill($deliveryOptionDto->toArray());
            $deliveryOption->save();

            return $deliveryOption;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string|DeliveryOption $id)
    {
        try {
            $deliveryOption = $id instanceof DeliveryOption ? $id : $this->get($id);
            $title = $deliveryOption->{DeliveryOption::name};
            $deliveryOption->delete();

            return $title;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
