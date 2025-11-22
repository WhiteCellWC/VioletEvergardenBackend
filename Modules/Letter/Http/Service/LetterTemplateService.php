<?php

namespace Modules\Letter\Http\Service;

use App\Http\Service\BaseService;
use App\Models\LetterTemplate;
use App\Models\LetterType;
use Exception;
use Illuminate\Support\Facades\Cache;
use Modules\Letter\Contract\LetterTemplateServiceInterface;
use Modules\Letter\DTO\LetterTemplateDto;
use Modules\Letter\Http\Cache\LetterTemplateCache;

class LetterTemplateService extends BaseService implements LetterTemplateServiceInterface
{
    public function get(string $id, string|array|null $relation = null)
    {
        try {
            $cacheKey = LetterTemplateCache::GET . '_' . $id . ':' . md5(json_encode([
                'relation' => $relation
            ]));
            return Cache::tags([LetterTemplateCache::GET, LetterTemplateCache::GET . "_" . $id])->remember(
                $cacheKey,
                LetterTemplateCache::GET_EXPIRY,
                fn() => LetterTemplate::query()
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
            $cacheKey = LetterTemplateCache::GET_ALL . ':' . md5(json_encode([
                'relation' => $relation,
                'condsIn'   => $condsIn,
                'condsNotIn' => $condsNotIn,
                'queryOptions' => $queryOptions
            ]));

            return Cache::tags([LetterTemplateCache::GET_ALL])->remember(
                $cacheKey,
                LetterTemplateCache::GET_EXPIRY,
                fn() => $this->fetch(
                    LetterTemplate::when(
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
                    )->when(isset($condsIn['letter_type_id']) && $condsIn['letter_type_id'], function ($query) use ($condsIn) {
                        $query->whereHas(LetterTemplate::letterTypes, function ($q) use ($condsIn) {
                            $q->where(LetterType::table . '.' . LetterType::id, $condsIn['letter_type_id']);
                        });
                    }),
                    $queryOptions
                )
            );
        } catch (Exception $e) {
            dd($e->getMessage());
            throw $e;
        }
    }

    public function create(LetterTemplateDto $letterTemplateDto)
    {
        try {
            return LetterTemplate::create($letterTemplateDto->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(LetterTemplateDto $letterTemplateDto)
    {
        try {
            $letterTemplate = $this->get($letterTemplateDto->id);
            $letterTemplate->fill($letterTemplateDto->toArray());
            $letterTemplate->save();

            return $letterTemplate;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string|LetterTemplate $id)
    {
        try {
            $letterTemplate = $id instanceof LetterTemplate ? $id : $this->get($id);
            $name = $letterTemplate->{LetterTemplate::name};
            $letterTemplate->delete();

            return $name;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
