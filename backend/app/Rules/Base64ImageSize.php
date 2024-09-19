<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64ImageSize implements Rule
{
    protected $maxSize;

    /**
     * Create a new rule instance.
     *
     * @param  int  $maxSize  Maximum size in kilobytes
     * @return void
     */
    public function __construct($maxSize)
    {
        $this->maxSize = $maxSize;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if the value is Base64
        if (!preg_match('/^data:image\/(?:png|jpg|jpeg);base64,/', $value)) {
            return false;
        }

        // Remove the Base64 metadata
        $imageData = preg_replace('/^data:image\/(?:png|jpg|jpeg);base64,/', '', $value);

        // Decode the Base64 data
        $decodedData = base64_decode($imageData);

        // Check the size of the decoded data
        $sizeInKB = strlen($decodedData) / 1024;

        return $sizeInKB <= $this->maxSize;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute exceeds the maximum allowed size of ' . $this->maxSize . ' KB.';
    }
}
