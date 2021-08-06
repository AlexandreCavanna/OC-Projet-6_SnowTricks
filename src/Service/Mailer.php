<?php


namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    private MailerInterface $mailer;

    private const FROM = 'contact@snowtricks.fr';

    private const SUBJECT = 'Mot de passe oublié.';

    private const HTML_TEMPLATE = 'emails/forgot-password.html.twig';

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
    public function sendMail(User $user)
    {
        $entityName = explode('\\', get_class($user));

        $email = (new TemplatedEmail())
            ->from(self::FROM)
            ->to($user->getEmail())
            ->subject(self::SUBJECT)
            ->htmlTemplate(self::HTML_TEMPLATE)
            ->context([
                strtolower(end($entityName)) => $user,
            ]);

        $this->mailer->send($email);
    }
}
