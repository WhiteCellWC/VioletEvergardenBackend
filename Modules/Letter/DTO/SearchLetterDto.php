<?php

namespace Modules\Letter\DTO;

use App\Enums\SendType;
use App\Models\Letter;
use Illuminate\Http\Request;

class SearchLetterDto
{
    public function __construct(
        public ?int $userId,
        public ?string $title,
        public ?string $body,
        public ?string $sendType,
        public ?int $paperTypeId,
        public ?int $fragranceTypeId,
        public ?int $envelopeTypeId,
        public ?int $waxSealTypeId,
        public ?bool $isDraft,
        public ?bool $isSent,
        public ?bool $isSealed,
        public ?bool $isPrinted,
        public ?string $recipientEmail
    ) {}

    public function toArray()
    {
        return [
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
            Letter::isPrinted => $this->isPrinted,
            'recipient_email' => $this->recipientEmail
        ];
    }

    public static function fromRequest(Request $request)
    {
        return new self(
            $request->user_id,
            $request->title,
            $request->body,
            $request->send_type ? SendType::from($request->send_type)->value : null,
            $request->paper_type_id,
            $request->fragrance_type_id,
            $request->envelope_type_id,
            $request->wax_seal_type_id,
            $request->is_draft,
            $request->is_sent,
            $request->is_sealed,
            $request->is_printed,
            $request->recipient_email
        );
    }
}
