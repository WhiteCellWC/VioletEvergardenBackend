<?php

namespace Modules\Letter\DTO;

use App\Enums\SendType;
use App\Models\Letter;
use Illuminate\Http\Request;

class LetterDto
{
    public function __construct(
        //Letter
        public ?int $id,
        public int $userId,
        public ?string $title,
        public ?string $body,
        public string $sendType,
        public ?int $paperTypeId,
        public ?int $fragranceTypeId,
        public ?int $envelopeTypeId,
        public ?int $waxSealTypeId,
        public ?bool $isDraft,
        public ?bool $isSent,
        public ?bool $isSealed,
        public ?bool $isPrinted,

        //Letter Type
        public ?array $letterTypeIds
    ) {}

    public function toArray()
    {
        return [
            Letter::id => $this->id,
            Letter::userId => $this->userId,
            Letter::title => $this->title,
            Letter::body => $this->body,
            Letter::sendType => $this->sendType,
            Letter::paperTypeId => $this->paperTypeId,
            Letter::fragranceTypeId => $this->fragranceTypeId,
            Letter::envelopeTypeId => $this->envelopeTypeId,
            Letter::waxSealTypeId => $this->waxSealTypeId,
            Letter::isDraft => $this->isDraft,
            Letter::isSent => $this->isSent,
            Letter::isSealed => $this->isSealed,
            Letter::isPrinted => $this->isPrinted
        ];
    }

    public static function fromRequest(Request $request, $id = null)
    {
        return new self(
            // Letter
            $id,
            $request->user_id,
            $request->title,
            $request->body,
            SendType::from($request->send_type)->value,
            $request->paper_type_id,
            $request->fragrance_type_id,
            $request->envelope_type_id,
            $request->wax_seal_type_id,
            $request->is_draft ? true : false,
            $request->is_sent ?? false,
            $request->is_sealed,
            $request->is_printed ?? false,

            // Letter Type
            $request->letter_type_ids
        );
    }
}
