<?php

namespace Modules\Delivery\Http\Controller\Api\V1;

use App\Enums\SendType;
use Exception;
use Modules\Delivery\Action\SendLetter\SendDigitalLetterAction;
use Modules\Delivery\Action\SendLetter\SendPhysicalLetterAction;
use Modules\Delivery\Http\Request\Api\V1\SendLetterApiRequest;

class SendLetterApiController
{
    public function __construct(
        protected SendDigitalLetterAction $sendDigitalLetterAction,
        protected SendPhysicalLetterAction $sendPhysicalLetterAction
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
}
