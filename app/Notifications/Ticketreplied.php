<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketReplied extends Notification
{
    use Queueable;

    protected $ticket;
    protected $reply;

    public function __construct($ticket, $reply)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Ticket Has Been Replied')
            ->line('Your ticket (#' . $this->ticket->id . ') has been replied to.')
            ->line('Reply: ' . $this->reply->message)
            ->action('View Ticket', url('/support'))
            ->line('Thank you for using our service!');
    }
}