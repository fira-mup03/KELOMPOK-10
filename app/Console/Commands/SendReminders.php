<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AppointmentReminderNotification;

class SendReminders extends Command
{
    protected $signature   = 'reminder:send';
    protected $description = 'Kirim pengingat jadwal pemeriksaan yang sudah waktunya';

    public function handle(): void
    {
        $reminders = Reminder::pending()
            ->with(['user', 'booking.doctor'])
            ->get();

        $this->info("Ditemukan {$reminders->count()} pengingat yang perlu dikirim.");

        foreach ($reminders as $reminder) {
            try {
                $reminder->user->notify(new AppointmentReminderNotification($reminder->booking));
                $reminder->update(['is_sent' => true]);
                $this->line("✓ Pengingat dikirim ke: {$reminder->user->email}");
            } catch (\Exception $e) {
                $this->error("✗ Gagal kirim ke {$reminder->user->email}: {$e->getMessage()}");
            }
        }

        $this->info('Selesai!');
    }
}
