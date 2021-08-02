<?php


namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    private MailerInterface $mailer;

    /**
     * @param \Symfony\Component\Mailer\MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendMail(string $to, string $from, string $subject, string $htmlTemplate, $entity = null)
    {
        $entityName = explode('\\', get_class($entity));

        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($htmlTemplate)
            ->context([
                strtolower(end($entityName)) => $entity,
            ]);

        $this->mailer->send($email);
    }
}
