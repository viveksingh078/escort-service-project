<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;


class TicketSubmitted extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Support Ticket Submitted')
            ->greeting('Hello!')
            ->line('A new support ticket has been submitted with the following details:')
            ->line('Name: ' . $this->ticket->name)
            ->line('Email: ' . $this->ticket->email)
            ->line('Category: ' . $this->ticket->category)
            ->line('Message: ' . $this->ticket->message)
            ->line('Thank you for using our application!')
            ->salutation('Regards, Ruby Sirens Support Team');
    }
}