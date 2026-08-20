<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class CallFollowupNotification extends Notification implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public object $call,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $call = $this->call;

        $requirement = collect([
            $call->category ? ucfirst(str_replace('_', ' ', $call->category)) : null,
            $call->transaction_type ? ucfirst($call->transaction_type) : null,
            $call->city ?? null,
            ($call->budget_min || $call->budget_max)
                ? 'Budget ' . ($call->budget_min ? number_format($call->budget_min, 0) : '0') . ' - ' . ($call->budget_max ? number_format($call->budget_max, 0) : '∞')
                : null,
        ])->filter()->implode(', ');

        return (new MailMessage)
            ->subject('Due Call Follow-up — ' . $call->name)
            ->greeting('Hello,')
            ->line("A call follow-up is due for {$call->name} ({$call->phone}).")
            ->line('Requirement: ' . ($requirement ?: 'Not specified'))
            ->line('Follow-up date: ' . $call->follow_up_date->format('d M Y'))
            ->line('Please reach out to the client at your earliest convenience.');
    }

    public function toDatabase(object $notifiable): array
    {
        $call = $this->call;

        return [
            'title' => 'Due call follow-up',
            'message' => "Follow-up due for {$call->name} ({$call->phone}).",
            'call_log_id' => $call->id,
            'url' => route('call-logs.show', $call),
        ];
    }
}
