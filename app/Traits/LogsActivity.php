<?php

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        $events = ['created', 'updated', 'deleted'];
        foreach ($events as $event) {
            static::$event(function (Model $model) use ($event) {
                $model->logEvent($event);
            });
        }
    }

    protected function logEvent(string $event): void
    {
        $user = auth()->user();
        if (! $user && ! request()->hasSession()) {
            return;
        }

        $description = $this->getActivityDescription($event);
        $properties = $this->getActivityProperties($event);

        Activity::create([
            'causer_type' => $user ? get_class($user) : null,
            'causer_id' => $user?->id,
            'subject_type' => static::class,
            'subject_id' => $this->getKey(),
            'event' => $event,
            'description' => $description,
            'properties' => $properties,
        ]);
    }

    protected function getActivityDescription(string $event): ?string
    {
        $name = method_exists($this, 'activityIdentifier') ? $this->activityIdentifier() : $this->getKey();
        $class = class_basename(static::class);

        return match ($event) {
            'created' => "{$class} #{$name} was created",
            'updated' => "{$class} #{$name} was updated",
            'deleted' => "{$class} #{$name} was deleted",
            default => "{$class} #{$name} {$event}",
        };
    }

    protected function getActivityProperties(string $event): ?array
    {
        if ($event === 'updated' && $this->isDirty()) {
            return [
                'old' => $this->getOriginal(),
                'new' => $this->getChanges(),
            ];
        }

        return null;
    }
}
