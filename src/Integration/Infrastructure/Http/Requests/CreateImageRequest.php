<?php

namespace TeacherAi\Integration\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TeacherAi\Integration\Application\DTO\InputCreateImage;

class CreateImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function toDTO(): InputCreateImage
    {
        $imageFile = $this->file('image');

        $imageContent = file_get_contents($imageFile->getRealPath());
        $base64Image = base64_encode($imageContent);
        $mimeType = $imageFile->getMimeType();
        $base64ImageWithPrefix = 'data:' . $mimeType . ';base64,' . $base64Image;

        return InputCreateImage::fromArray([
            'image' => $base64ImageWithPrefix
        ]);
    }
}
