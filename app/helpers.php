<?php

use App\Helpers\Toastr;
use App\Models\Company;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

if (! function_exists('current_company_id')) {
    function current_company_id(): ?int
    {
        if (app()->runningInConsole()) {
            if (session()->has('company_id')) {
                return (int) session('company_id');
            }

            return Company::orderBy('id')->value('id');
        }

        if ($user = auth()->user()) {
            if ($user->isSuperAdmin() && session()->has('company_id')) {
                return (int) session('company_id');
            }

            if ($user->company_id) {
                return (int) $user->company_id;
            }

            return session()->has('company_id') ? (int) session('company_id') : null;
        }

        if (session()->has('client_id')) {
            return session()->has('company_id') ? (int) session('company_id') : null;
        }

        if (session()->has('company_id')) {
            return (int) session('company_id');
        }

        return Company::orderBy('id')->value('id');
    }
}

if (! function_exists('current_company')) {
    function current_company(): ?Company
    {
        $id = current_company_id();

        return $id ? Company::find($id) : null;
    }
}

if (! function_exists('company_settings')) {
    function company_settings(): array
    {
        return Setting::pluck('value', 'key')->toArray();
    }
}

if (! function_exists('is_super_admin')) {
    function is_super_admin(): bool
    {
        return auth()->check() && auth()->user()->role === 'super_admin';
    }
}

if (! function_exists('current_subscription')) {
    function current_subscription(): ?\App\Models\Subscription
    {
        $company = current_company();

        return $company ? $company->activeSubscription() : null;
    }
}

if (! function_exists('has_active_subscription')) {
    function has_active_subscription(): bool
    {
        return current_subscription() !== null;
    }
}

if (! function_exists('subscription_limit')) {
    function subscription_limit(string $key): int
    {
        return current_subscription()?->package?->{$key} ?? 0;
    }
}

if (! function_exists('within_subscription_limit')) {
    function within_subscription_limit(string $key): bool
    {
        $limit = subscription_limit($key);
        if ($limit <= 0) {
            return true;
        }

        $table = $key === 'max_employees' ? 'users'
            : ($key === 'max_clients' ? 'clients'
            : ($key === 'max_properties' ? 'properties' : null));

        if (! $table) {
            return true;
        }

        return DB::table($table)->where('company_id', current_company_id())->count() < $limit;
    }
}

if (! function_exists('toastr')) {
    function toastr(?string $message = null, string $type = 'success'): Toastr
    {
        $instance = app(Toastr::class);
        if ($message) {
            return $instance->$type($message);
        }

        return $instance;
    }
}

if (! function_exists('crop_and_save')) {
    function crop_and_save(UploadedFile $file, string $path, int $width = 1920, int $height = 800): string
    {
        $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
        if ($image === false) {
            throw new RuntimeException('Unable to process image');
        }

        $origW = imagesx($image);
        $origH = imagesy($image);

        $srcRatio = $origW / $origH;
        $dstRatio = $width / $height;

        if ($srcRatio > $dstRatio) {
            $newH = $origH;
            $newW = (int) round($origH * $dstRatio);
        } else {
            $newW = $origW;
            $newH = (int) round($origW / $dstRatio);
        }

        $srcX = (int) round(($origW - $newW) / 2);
        $srcY = (int) round(($origH - $newH) / 2);

        $thumb = imagecreatetruecolor($width, $height);
        imagecopyresampled($thumb, $image, 0, 0, $srcX, $srcY, $width, $height, $newW, $newH);
        imagedestroy($image);

        $filename = pathinfo($file->hashName(), PATHINFO_FILENAME).'.webp';
        $savePath = $path.'/'.$filename;

        ob_start();
        imagewebp($thumb, null, 85);
        $contents = ob_get_clean();
        imagedestroy($thumb);

        Storage::disk('public')->put($savePath, $contents);

        return $savePath;
    }
}
