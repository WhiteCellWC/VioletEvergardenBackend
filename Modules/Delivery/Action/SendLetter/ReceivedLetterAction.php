<?php

namespace Modules\Delivery\Action\SendLetter;

use Exception;
use Illuminate\Http\Request;
use Modules\Letter\Contract\LetterServiceInterface;
use Modules\Letter\DTO\SearchLetterDto;
use Modules\Shared\DTO\QueryOptionsDto;
use Modules\User\Contract\UserServiceInterface;

class ReceivedLetterAction
{
    public function __construct(
        protected LetterServiceInterface $letterService,
        protected UserServiceInterface $userService
    ) {}

    public function handle(Request $request, array|string|null $relation = null)
    {
        try {
            $email = $this->userService->get($request->get('login_user_id'))->email;

            $request->merge([
                'recipient_email' => $email
            ]);

            $condsIn = SearchLetterDto::fromRequest($request)->toArray();

            $queryOptions = QueryOptionsDto::fromRequest($request)->toArray();

            $letters = $this->letterService->getAll($relation, $condsIn, [], $queryOptions);

            return $letters;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
