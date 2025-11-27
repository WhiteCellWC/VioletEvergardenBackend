<?php

namespace Modules\Delivery\Http\Service;

use App\Http\Service\BaseService;
use App\Models\Recipient;
use Exception;
use Illuminate\Support\Facades\Cache;
use Modules\Delivery\Contract\RecipientServiceInterface;
use Modules\Delivery\DTO\RecipientDto;
use Modules\Delivery\Http\Cache\RecipientCache;

class RecipientService extends BaseService implements RecipientServiceInterface
{
    public function get(string $id, string|array|null $relation = null)
    {
        try {
            $cacheKey = RecipientCache::GET . '_' . $id . ':' . md5(json_encode([
                'relation' => $relation
            ]));
            return Cache::tags([RecipientCache::GET, RecipientCache::GET . "_" . $id])->remember(
                $cacheKey,
                RecipientCache::GET_EXPIRY,
                fn() => Recipient::query()
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
            $cacheKey = RecipientCache::GET_ALL . ':' . md5(json_encode([
                'relation' => $relation,
                'condsIn'   => $condsIn,
                'condsNotIn' => $condsNotIn,
                'queryOptions' => $queryOptions
            ]));

            return Cache::tags([RecipientCache::GET_ALL])->remember(
                $cacheKey,
                RecipientCache::GET_EXPIRY,
                fn() => $this->fetch(
                    Recipient::when(
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

    public function create(RecipientDto $recipientDto)
    {
        try {
            return Recipient::create($recipientDto->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(RecipientDto $recipientDto)
    {
        try {
            $recipient = $this->get($recipientDto->id);
            $recipient->fill($recipientDto->toArray());
            $recipient->save();

            return $recipient;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string|Recipient $id)
    {
        try {
            $recipient = $id instanceof Recipient ? $id : $this->get($id);
            $title = $recipient->{Recipient::name};
            $recipient->delete();

            return $title;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
