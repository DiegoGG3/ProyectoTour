<?php
// PARA USARLO php bin/console app:user:reset

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetUserAccountCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:user:reset')
            ->setDescription('Restablece una cuenta de usuario')
            ->setHelp('Este comando te permite restablecer una cuenta de usuario.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $io->ask('Ingresa el correo electrónico del usuario a restablecer:');

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $io->error('Usuario no encontrado.');
            return Command::FAILURE;
        }

        $newPassword = $io->ask('Ingresa tu nueva contraseña:');
        $user->setPassword(
            $this->userPasswordHasher->hashPassword($user, $newPassword)
        );

        $this->entityManager->flush();

        $io->success('La cuenta de usuario se ha restablecido exitosamente.');

        return Command::SUCCESS;
    }
}
