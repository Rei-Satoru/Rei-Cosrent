<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Get the asset URL for an image file
     * Handles various image path formats and ensures consistent output
     * 
     * @param string|null $imagePath The image path from database
     * @param string $defaultImage Default image path if imagePath is empty
     * @return string The complete asset URL for the image
     */
    public static function getImageUrl(?string $imagePath, string $defaultImage = 'assets/img/no-image.png'): string
    {
        // If empty, return default
        if (!$imagePath || trim($imagePath) === '') {
            return asset($defaultImage);
        }

        // If it's already a full URL (http or https), use it as-is
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        // If it already starts with 'storage/', use asset() directly
        if (str_starts_with($imagePath, 'storage/')) {
            return asset($imagePath);
        }

        // Otherwise, prepend 'storage/' prefix (for paths like 'profile_photos/...')
        return asset('storage/' . ltrim($imagePath, '/'));
    }

    /**
     * Alias for getImageUrl with shorter name
     */
    public static function url(?string $imagePath, string $defaultImage = 'assets/img/no-image.png'): string
    {
        return self::getImageUrl($imagePath, $defaultImage);
    }
}
