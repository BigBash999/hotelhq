<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\LeaveRequest;

class LeaveStatusUpdated extends Notification
{
    use Queueable;

    public $leaveRequest;

    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Leave Request ' . ucfirst($this->leaveRequest->status),
            'message' => 'Your ' . $this->leaveRequest->leave_type . ' leave from ' . $this->leaveRequest->start_date . ' to ' . $this->leaveRequest->end_date . ' has been ' . $this->leaveRequest->status . '.',
            'url' => route('employee.dashboard'),
            'icon' => $this->leaveRequest->status == 'approved' ? 'check' : 'x',
        ];
    }
}