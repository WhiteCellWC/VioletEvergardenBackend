<?php

namespace Modules\Delivery\Http\Service;

use App\Http\Service\BaseService;
use App\Models\DeliveryTier;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Delivery\Contract\DeliveryTierServiceInterface;
use Modules\Delivery\DTO\DeliveryTierDto;
use Modules\Delivery\Http\Cache\DeliveryTierCache;

class DeliveryTierService extends BaseService implements DeliveryTierServiceInterface
{
    public function get(string $id, string|array|null $relation = null)
    {
        try {
            $cacheKey = DeliveryTierCache::GET . '_' . $id . ':' . md5(json_encode([
                'relation' => $relation
            ]));
            return Cache::tags([DeliveryTierCache::GET, DeliveryTierCache::GET . "_" . $id])->remember(
                $cacheKey,
                DeliveryTierCache::GET_EXPIRY,
                fn() => DeliveryTier::query()
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
            $cacheKey = DeliveryTierCache::GET_ALL . ':' . md5(json_encode([
                'relation' => $relation,
                'condsIn'   => $condsIn,
                'condsNotIn' => $condsNotIn,
                'queryOptions' => $queryOptions
            ]));

            return Cache::tags([DeliveryTierCache::GET_ALL])->remember(
                $cacheKey,
                DeliveryTierCache::GET_EXPIRY,
                fn() => $this->fetch(
                    DeliveryTier::when(
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

    public function create(DeliveryTierDto $deliveryTierDto)
    {
        try {
            return DeliveryTier::create($deliveryTierDto->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(DeliveryTierDto $deliveryTierDto)
    {
        try {
            $deliveryTier = $this->get($deliveryTierDto->id);
            $deliveryTier->fill($deliveryTierDto->toArray());
            $deliveryTier->save();

            return $deliveryTier;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string|DeliveryTier $id)
    {
        try {
            $deliveryTier = $id instanceof DeliveryTier ? $id : $this->get($id);
            $title = $deliveryTier->{DeliveryTier::maxWeight};
            $deliveryTier->delete();

            return $title;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
