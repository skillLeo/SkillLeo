<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $assignedTasks;
    public $createdTasks;
    public $customMessage;

    /**
     * @param \App\Models\User $user
     * @param \Illuminate\Support\Collection $assignedTasks
     * @param \Illuminate\Support\Collection $createdTasks
     * @param string|null $customMessage
     */
    public function __construct($user, $assignedTasks, $createdTasks, $customMessage = null)
    {
        $this->user = $user;
        $this->assignedTasks = $assignedTasks;
        $this->createdTasks = $createdTasks;
        $this->customMessage = $customMessage;
    }

    public function build()
    {
        return $this
            ->subject('Your Task Summary')
            ->view('emails.task-summary');
    }
}
