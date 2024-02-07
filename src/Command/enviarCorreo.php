<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:send-email',
    description: 'Send an email.',
    hidden: false,
    aliases: ['app:send-email']
)]
class enviarCorreo extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('correo', InputArgument::OPTIONAL, 'Your correo')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('correo');
        if ($arg1 && $io->confirm('¿Quieres enviar el correo?')) {
            //Ejecutar email enviando correo a dirección $arg1
            $io->note(sprintf('Correo electrónico: %s', $arg1));
        }
        $io->success('Comando ejecutado con éxito!!');
        return Command::SUCCESS;
    }
}