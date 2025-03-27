<?php
namespace App\Service;

use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService
{
    private $mailer;
    private $userRepository;

    public function __construct(MailerInterface $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    /**
     * Envoie un email en utilisant les paramètres fournis.
     *
     * @param string $to L'adresse email du destinataire.
     * @param string $subject Le sujet de l'email.
     * @param string $message Le contenu du message de l'email.
     * @param array $headers Les en-têtes supplémentaires pour l'email.
     * @return bool Retourne true si l'email a été envoyé avec succès, sinon false.
     */
    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ): void
    {
        //On crée le mail
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("mail/$template.html.twig", [
                'context' => $context
            ]);

        // On envoie le mail
        try {
            $this->mailer->send($email);
        } catch (\Throwable $e) {
            file_put_contents(__DIR__ . '/../../var/log/mailer_error.log', $e->getMessage() . "\n", FILE_APPEND);
        }
    }
}