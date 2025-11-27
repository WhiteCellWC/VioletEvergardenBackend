<?php

namespace Modules\Delivery\Action\SendLetter;

use Exception;
use Illuminate\Http\Request;
use Modules\Delivery\Contract\DeliveryTierServiceInterface;
use Modules\Delivery\Contract\LetterDeliveryServiceInterface;
use Modules\Delivery\Contract\RecipientServiceInterface;
use Modules\Delivery\DTO\LetterDeliveryDto;
use Modules\Delivery\DTO\RecipientDto;
use Modules\Letter\Contract\LetterServiceInterface;
use Modules\Letter\DTO\LetterDto;
use Modules\LetterComponent\Contract\EnvelopeTypeServiceInterface;
use Modules\LetterComponent\Contract\FragranceTypeServiceInterface;
use Modules\LetterComponent\Contract\PaperTypeServiceInterface;
use Modules\LetterComponent\Contract\WaxSealTypeServiceInterface;

class SendPhysicalLetterAction
{
    public function __construct(
        protected LetterServiceInterface $letterService,
        protected RecipientServiceInterface $recipientService,
        protected EnvelopeTypeServiceInterface $envelopeTypeService,
        protected PaperTypeServiceInterface $paperTypeService,
        protected WaxSealTypeServiceInterface $waxSealTypeService,
        protected FragranceTypeServiceInterface $fragranceTypeService,
        protected LetterDeliveryServiceInterface $letterDeliveryService,
        protected DeliveryTierServiceInterface $deliveryTierService
    ) {}

    public function handle(Request $request)
    {
        try {

            if ($request->letter_id) {
                $letterDto = LetterDto::fromRequest($request, $request->letter_id);
                $letter = $this->letterService->update($letterDto);
            } else {
                $letterDto = LetterDto::fromRequest($request);
                $letter = $this->letterService->create($letterDto);
            }

            $letterPrice = 0;

            if ($letter->envelope_type_id) {
                $envelopeType = $this->envelopeTypeService->get($letter->envelope_type_id);
                $letterPrice += $envelopeType->price;
            }

            if ($letter->paper_type_id) {
                $paperType = $this->paperTypeService->get($letter->paper_type_id);
                $letterPrice += $paperType->price_per_page;
            }

            if ($letter->wax_seal_type_id) {
                $waxSealType = $this->waxSealTypeService->get($letter->wax_seal_type_id);
                $letterPrice += $waxSealType->price;
            }

            if ($letter->fragrance_type_id) {
                $fragranceType = $this->fragranceTypeService->get($letter->fragrance_type_id);
                $letterPrice += $fragranceType->price;
            }

            if ($request->delivery_tier_id) {
                $deliveryTier = $this->deliveryTierService->get($request->delivery_tier_id);
                $letterPrice += $deliveryTier->price;
            }

            foreach ($request->recipients as $recipient) {
                $recipientDto = new RecipientDto(
                    null,
                    $request->user_id,
                    $letter->id,
                    $recipient['name'],
                    $recipient['email'],
                    $recipient['phone'],
                    $recipient['address_line1'],
                    $recipient['address_line2'],
                    json_encode($recipient['variables']),
                    $recipient['country_id'],
                    $recipient['state_id'],
                    $recipient['postal_code']
                );

                $recipient = $this->recipientService->create($recipientDto);

                $scheduledAt = null;

                if ($request->scheduled_date) {
                    $scheduledAt = \Carbon\Carbon::parse($request->scheduled_date)->format('Y-m-d H:i:s');
                }
                $letterDeliveryDto = new LetterDeliveryDto(
                    null,
                    $recipient->id,
                    $request->delivery_option_id,
                    $request->delivery_tier_id,
                    $letterPrice,
                    null,
                    'pending',
                    $scheduledAt,
                    null,
                    null
                );

                $letterDelivery = $this->letterDeliveryService->create($letterDeliveryDto);
            }

            return [];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
