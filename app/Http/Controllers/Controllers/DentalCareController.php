<?php

namespace App\Http\Controllers\Controllers;

use App\Http\Controllers\Controller;
use App\Contracts\BookingServiceInterface;
use App\Contracts\QueueServiceInterface;
use App\Contracts\EducationServiceInterface;
use App\Contracts\TreatmentHistoryServiceInterface;
use App\Contracts\ReminderServiceInterface;
use Illuminate\View\View;

class DentalCareController extends Controller
{
    public function __construct(
        protected BookingServiceInterface $bookingService,
        protected QueueServiceInterface $queueService,
        protected EducationServiceInterface $educationService,
        protected TreatmentHistoryServiceInterface $treatmentHistoryService,
        protected ReminderServiceInterface $reminderService
    ) {
    }

    public function consultation(): View
    {
        return view('consultation');
    }

    public function bookingSchedule(): View
    {
        $bookings = $this->bookingService->getBookingSchedule(auth()->id());
        return view('booking', compact('bookings'));
    }

    public function treatmentHistory(): View
    {
        $history = $this->treatmentHistoryService->getTreatmentHistory(auth()->id());
        return view('treatment-history', compact('history'));
    }

    public function queue(): View
    {
        $queue = $this->queueService->getQueueStatus(auth()->id());
        return view('queue', compact('queue'));
    }

    public function education(): View
    {
        $articles = $this->educationService->getEducationArticles();
        return view('education', compact('articles'));
    }
}
