<?php

namespace Modules\Delivery\Http\Controller\Api\V1;

use App\Enums\SendType;
use App\Models\Letter;
use App\Models\Recipient;
use Exception;
use Illuminate\Http\Request;
use Modules\Delivery\Action\SendLetter\ReceivedLetterAction;
use Modules\Delivery\Action\SendLetter\SendDigitalLetterAction;
use Modules\Delivery\Action\SendLetter\SendPhysicalLetterAction;
use Modules\Delivery\Http\Request\Api\V1\SendLetterApiRequest;
use Modules\Letter\Contract\LetterServiceInterface;
use Modules\Letter\Http\Resource\Api\V1\LetterApiResource;

class SendLetterApiController
{
    protected $backendRelation = [Letter::recipients, Letter::recipients . '.' . Recipient::letterDeliveries, Letter::user];

    public function __construct(
        protected SendDigitalLetterAction $sendDigitalLetterAction,
        protected SendPhysicalLetterAction $sendPhysicalLetterAction,
        protected LetterServiceInterface $letterService,
        protected ReceivedLetterAction $receivedLetterAction,
    ) {}

    public function sendLetter(SendLetterApiRequest $request)
    {
        try {
            if ($request->get('send_type') == SendType::PHYSICAL->value) {
                $dataArr = $this->sendPhysicalLetterAction->handle($request);
            } else if ($request->get('send_type') == SendType::DIGITAL->value) {
            }

            return response()->json($dataArr);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function receivedLetter(Request $request)
    {
        try {
            $recievedLetters = $this->receivedLetterAction->handle($request, $this->backendRelation);

            return LetterApiResource::collection($recievedLetters);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
