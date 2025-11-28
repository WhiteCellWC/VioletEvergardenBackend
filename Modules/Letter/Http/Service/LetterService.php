<?php

namespace Modules\Letter\Http\Service;

use App\Http\Service\BaseService;
use App\Models\Letter;
use App\Models\Recipient;
use Exception;
use Illuminate\Support\Facades\Cache;
use Modules\Letter\Contract\LetterServiceInterface;
use Modules\Letter\DTO\LetterDto;
use Modules\Letter\Http\Cache\LetterCache;

class LetterService extends BaseService implements LetterServiceInterface
{
    public function get(string $id, string|array|null $relation = null)
    {
        try {
            $cacheKey = LetterCache::GET . '_' . $id . ':' . md5(json_encode([
                'relation' => $relation
            ]));
            return Cache::tags([LetterCache::GET, LetterCache::GET . "_" . $id])->remember(
                $cacheKey,
                LetterCache::GET_EXPIRY,
                fn() => Letter::query()
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
            $cacheKey = LetterCache::GET_ALL . ':' . md5(json_encode([
                'relation' => $relation,
                'condsIn'   => $condsIn,
                'condsNotIn' => $condsNotIn,
                'queryOptions' => $queryOptions
            ]));

            return Cache::tags([LetterCache::GET_ALL])->remember(
                $cacheKey,
                LetterCache::GET_EXPIRY,
                fn() => $this->fetch(
                    Letter::when(
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
                    )->when(
                        !empty($condsIn['recipient_email']),
                        function ($query) use ($condsIn) {
                            $query->whereHas(Letter::recipients, function ($q) use ($condsIn) {
                                $q->where(Recipient::email, $condsIn['recipient_email']);
                            });
                        }
                    ),
                    $queryOptions
                )
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function create(LetterDto $letterDto)
    {
        try {
            return Letter::create($letterDto->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(LetterDto $letterDto)
    {
        try {
            $letter = $this->get($letterDto->id);
            $letter->fill($letterDto->toArray());
            $letter->save();

            return $letter;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string|Letter $id)
    {
        try {
            $letter = $id instanceof Letter ? $id : $this->get($id);
            $title = $letter->{Letter::title};
            $letter->delete();

            return $title;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
