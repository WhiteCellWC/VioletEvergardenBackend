<?php

namespace Modules\LetterComponent\Http\Service;

use App\Http\Service\BaseService;
use App\Models\EnvelopeType;
use Exception;
use Illuminate\Support\Facades\Cache;
use Modules\LetterComponent\Contract\EnvelopeTypeServiceInterface;
use Modules\LetterComponent\DTO\EnvelopeTypeDto;
use Modules\LetterComponent\Http\Cache\EnvelopeTypeCache;

class EnvelopeTypeService extends BaseService implements EnvelopeTypeServiceInterface
{
    public function get(string $id, string|array|null $relation = null)
    {
        try {
            $cacheKey = EnvelopeTypeCache::GET . '_' . $id . ':' . md5(json_encode([
                'relation' => $relation
            ]));
            return Cache::tags([EnvelopeTypeCache::GET, EnvelopeTypeCache::GET . "_" . $id])->remember(
                $cacheKey,
                EnvelopeTypeCache::GET_EXPIRY,
                fn() => EnvelopeType::query()
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
            $cacheKey = EnvelopeTypeCache::GET_ALL . ':' . md5(json_encode([
                'relation' => $relation,
                'condsIn'   => $condsIn,
                'condsNotIn' => $condsNotIn,
                'queryOptions' => $queryOptions
            ]));

            return Cache::tags([EnvelopeTypeCache::GET_ALL])->remember(
                $cacheKey,
                EnvelopeTypeCache::GET_EXPIRY,
                fn() => $this->fetch(
                    EnvelopeType::when(
                        $condsIn,
                        fn($query, $condsIn) => $query->condsInByColumns($condsIn)
                    )->when(
                        $condsNotIn,
                        fn($query, $condsNotIn) => $query->condsNotInByColumns($condsNotIn)
                    )->when(
                        $queryOptions,
                        fn($query, $queryOptions) => $query->queryOptions($queryOptions)
                    )->when(
                        $relation,
                        fn($query, $relation) => $query->with($relation)
                    ),
                    $queryOptions
                )
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function create(EnvelopeTypeDto $envelopeTypeDto)
    {
        try {
            return EnvelopeType::create($envelopeTypeDto->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(EnvelopeTypeDto $envelopeTypeDto)
    {
        try {
            $envelopeType = $this->get($envelopeTypeDto->id);
            $envelopeType->fill($envelopeTypeDto->toArray());
            $envelopeType->save();

            return $envelopeType;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string|EnvelopeType $id)
    {
        try {
            $envelopeType = $id instanceof EnvelopeType ? $id : $this->get($id);
            $name = $envelopeType->{EnvelopeType::name};
            $envelopeType->delete();

            return $name;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
