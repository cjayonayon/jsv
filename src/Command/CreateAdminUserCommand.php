<?php

namespace App\Command;

use RuntimeException;
use App\Entity\Admin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminUserCommand extends Command
{
    protected static $defaultName = 'app:create-admin-user';
    
    private $io;

    private $entityManager;
    private $passwordEncoder;
    private $users;


    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, AdminRepository $users)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->passwordEncoder = $encoder;
        $this->users = $users;
    }

    protected function configure()
    {
        $this
            ->setDescription('This is a command for creating an Admin account')
            ->addArgument('password', InputArgument::REQUIRED, 'Password of the new user')
            ->addArgument('email',InputArgument::REQUIRED, 'Email account of the new user')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if(null !== $input->getArgument('password') && null !== $input->getArgument('email')){
            return;
        }
        $this->validateUser();

        $this->io->title('Add User Command Interactive Wizard');
        $this->io->text([
            'Let\'s gets started.',
            '',
            '',
            '---------------------------',
        ]);

        $email = $input->getArgument('email');
        if(null !== $email){
            $this->io->text(' > <info>email</info>: ', $email);
        }else{
            $email = $this->io->ask('Enter email');
            $input->setArgument('email', $email);
        }

        $password = $input->getArgument('password');
        if(null !== $password){
            $this->io>text(' > <info>Password</info>: '.str_repeat('*', mb_strlen($password)));
        }else{
            $password = $this->io->askHidden('Enter Password');
            $input->setArgument('password', $password);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $password = $input->getArgument('password');
        $email = $input->getArgument('email');

        $admin = new Admin();
        $admin->setUsername("admin");
        $encodedPassword = $this->passwordEncoder->encodePassword($admin, $password);
        $admin->setPassword($encodedPassword);
        $admin->setEmail($email);

        $this->entityManager->persist($admin);
        $this->entityManager->flush();
        
        $this->io->success('Admin Account Successfuly created.');
    }

    protected function validateUser()
    {
        $existingUser = $this->users->findOneBy(['username' => "admin"]);
        if (null !== $existingUser) {
            throw new RuntimeException('There is already a user registered account with the name admin');
        }
    }

}
