<?php

namespace Modules\User\DTO;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

readonly class SearchUserDto
{
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $dateOfBirth,
        public ?string $gender,
        public ?string $bio
    ) {}

    public function toArray(): array
    {
        return [
            User::name => $this->name,
            User::email => $this->email,
            User::dateOfBirth => $this->dateOfBirth,
            User::gender => $this->gender,
            User::bio => $this->bio
        ];
    }

    public static function fromRequest(Request $request): SearchUserDto
    {
        return new self(
            $request->name,
            $request->email,
            $request->date_of_birth,
            $request->gender,
            $request->bio
        );
    }
}
