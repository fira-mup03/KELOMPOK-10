<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class AppointmentReminderNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'booking_id'   => $this->booking->id,
            'doctor_name'  => $this->booking->doctor->name,
            'booking_date' => $this->booking->booking_date->format('d M Y'),
            'booking_time' => $this->booking->booking_time,
            'message'      => "Pengingat: Jadwal pemeriksaan Anda dengan {$this->booking->doctor->name} besok pada {$this->booking->booking_date->format('d M Y')} pukul {$this->booking->booking_time}.",
        ];
    }
}
