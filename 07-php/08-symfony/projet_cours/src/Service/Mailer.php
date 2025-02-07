<?php 

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    public function __construct(private MailerInterface $mailer)
    {}

    public function sendEmail(string $from, string $to, string $subject, string $content)
    {
        $email = (new Email()) 
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($content);
        $this->mailer->send($email);
    }
}