<?php

namespace Modules\Letter\DTO;

use App\Enums\SendType;
use App\Models\LetterTemplate;
use Illuminate\Http\Request;

class SearchLetterTemplateDto
{
    public function __construct(
        public ?string $name,
        public ?string $description,
        public ?string $sendType,
        public ?int $paperTypeId,
        public ?int $fragranceTypeId,
        public ?int $envelopeTypeId,
        public ?int $waxSealTypeId,
        public ?bool $status,
        public ?int $letterTypeId,
    ) {}

    public function toArray()
    {
        return [
            LetterTemplate::name => $this->name,
            LetterTemplate::description => $this->description,
            LetterTemplate::sendType => $this->sendType,
            LetterTemplate::paperTypeId => $this->paperTypeId,
            LetterTemplate::fragranceTypeId => $this->fragranceTypeId,
            LetterTemplate::envelopeTypeId => $this->envelopeTypeId,
            LetterTemplate::waxSealTypeId => $this->waxSealTypeId,
            LetterTemplate::status => $this->status,
            'letter_type_id' => $this->letterTypeId
        ];
    }

    public static function fromRequest(Request $request)
    {
        return new self(
            $request->name,
            $request->description,
            $request->send_type ? SendType::from($request->send_type)->value : null,
            $request->paper_type_id,
            $request->fragrance_type_id,
            $request->envelope_type_id,
            $request->wax_seal_type_id,
            $request->status,
            $request->letter_type_id,
        );
    }
}
