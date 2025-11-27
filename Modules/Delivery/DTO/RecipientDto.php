<?php

namespace Modules\Delivery\DTO;

use App\Models\Recipient;
use Illuminate\Http\Request;

class RecipientDto
{
    public function __construct(
        public ?int $id,
        public ?int $userId,
        public ?int $letterId,
        public string $name,
        public string $email,
        public ?string $phone,
        public string $addressLine1,
        public ?string $addressLine2,
        public ?string $variables,
        public int $countryId,
        public int $stateId,
        public string $postalCode
    ) {}

    public function toArray()
    {
        return [
            Recipient::id => $this->id,
            Recipient::userId => $this->userId,
            Recipient::letterId => $this->letterId,
            Recipient::name => $this->name,
            Recipient::email => $this->email,
            Recipient::phone => $this->phone,
            Recipient::addressLine1 => $this->addressLine1,
            Recipient::addressLine2 => $this->addressLine2,
            Recipient::variables => $this->variables,
            Recipient::countryId => $this->countryId,
            Recipient::stateId => $this->stateId,
            Recipient::postalCode => $this->postalCode
        ];
    }

    public static function fromRequest(Request $request, $id = null)
    {
        return new self(
            $id,
            $request->user_id ?? null,
            $request->letter_id ?? null,
            $request->name,
            $request->email,
            $request->phone ?? null,
            $request->address_line1,
            $request->address_line2 ?? null,
            $request->variables ?? null,
            $request->country_id,
            $request->state_id,
            $request->postal_code
        );
    }
}
