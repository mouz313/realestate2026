<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'key', 'value'];

    /**
     * Read a setting value, transparently decrypting values stored encrypted
     * while remaining backward-compatible with legacy plaintext values.
     */
    public static function decryptedValue(string $key, $default = null)
    {
        $value = static::where('key', $key)->value('value');

        if ($value === null || $value === '') {
            return $default;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }

    /**
     * Return all settings as a key => value map with decrypted values.
     */
    public static function decryptedMap(): array
    {
        return static::pluck('value', 'key')
            ->map(function ($value) {
                try {
                    return Crypt::decryptString($value);
                } catch (\Throwable $e) {
                    return $value;
                }
            })
            ->toArray();
    }
}
