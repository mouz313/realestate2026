<?php

use App\Helpers\Toastr;
use App\Models\Company;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
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

if (! function_exists('dashboard_route')) {
    function dashboard_route(): string
    {
        return 'admin.dashboard';
    }
}

if (! function_exists('dashboard_url')) {
    function dashboard_url(): string
    {
        return route(dashboard_route());
    }
}

if (! function_exists('company_users')) {
    function company_users(?int $companyId = null): Collection
    {
        $companyId = $companyId ?? current_company_id();

        return User::where('company_id', $companyId)
            ->whereIn('role', ['admin', 'staff'])
            ->get();
    }
}

if (! function_exists('notify_company')) {
    /**
     * @param  object|int  $company  Company model, model with company_id, or raw id.
     * @param  string  $class  Fully-qualified notification class name.
     * @param  array  $args  Constructor arguments for the notification.
     * @param  array  $extra  Additional notifiables (recipients) to notify.
     */
    function notify_company($company, string $class, array $args = [], array $extra = []): void
    {
        $companyId = $company instanceof Company
            ? $company->id
            : (is_object($company) ? ($company->company_id ?? null) : (int) $company);

        $recipients = collect();

        if ($companyId) {
            $recipients = company_users((int) $companyId);
        }

        foreach ($extra as $notifiable) {
            if ($notifiable instanceof Notifiable || in_array(Notifiable::class, class_uses_recursive($notifiable), true)) {
                $recipients->push($notifiable);
            }
        }

        $recipients = $recipients->unique(function ($r) {
            return get_class($r).':'.$r->getKey();
        });

        $instance = $args ? new $class(...$args) : new $class;

        foreach ($recipients as $notifiable) {
            $notifiable->notify($instance);
        }
    }
}
