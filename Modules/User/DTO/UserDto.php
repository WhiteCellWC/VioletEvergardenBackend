<?php

namespace Modules\User\DTO;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

readonly class UserDto
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public string $dateOfBirth,
        public string $gender,
        public string|UploadedFile $profileImage,
        public string $bio
    ) {}

    public function toArray(): array
    {
        return [
            User::id => $this->id,
            User::name => $this->name,
            User::email => $this->email,
            User::dateOfBirth => $this->dateOfBirth,
            User::gender => $this->gender,
            User::profileImage => $this->profileImage,
            User::bio => $this->bio
        ];
    }

    public static function fromRequest(Request $request, ?int $id = null): UserDto
    {
        return new self(
            $id,
            $request->name,
            $request->email,
            $request->date_of_birth,
            $request->gender,
            $request->profile_image,
            $request->bio
        );
    }
}
