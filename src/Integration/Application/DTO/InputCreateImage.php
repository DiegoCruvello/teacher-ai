<?php

namespace TeacherAi\Integration\Application\DTO;

readonly class InputCreateImage
{
    public function __construct(
        public string $image,
    ) {
    }

    public function toArray(): array
    {
        return [
            'image' => $this->image,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['image'],
        );
    }
}
