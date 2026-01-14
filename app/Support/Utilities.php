<?php

namespace App\Support;

class Utilities {
    // public function isPhoneFormat(string $identifier): bool {
    //     return preg_match('/^(09[3-9]\d{7}|095\d{7}|944\d{7})$/', $identifier);
    // }

    public static function photoUrl(?string $path): ?string {
        if (!$path) return null;

        if (str_contains($path, '://')) return $path;

        $path = preg_replace('#^public/#', '', $path);
        $path = ltrim($path, '/');

        $relative = str_starts_with($path, 'storage/')
            ? '/' . $path
            : '/storage/' . $path;

        return preg_replace('#/+#', '/', $relative);
    }
}
