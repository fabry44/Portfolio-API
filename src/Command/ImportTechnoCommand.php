<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Work;
use App\Entity\Skill;
use App\Entity\Language;
use App\Entity\Interest;
use App\Entity\Reference;
use App\Entity\Project;
use App\Entity\Profile;
use App\Entity\Location;
use App\Entity\Education;
use App\Entity\Technology;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-techno',
    description: 'Importe les données initiales du portfolio dans la base de données.',
)]
class ImportTechnoCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setHelp('Cette commande insère les données de Technologie du portfolio dans la base de données.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title("📥 Importation des Techno du portfolio...");

        try {
            // **Données JSON de référence**
            $dataPortfolio = [
                "technology" => [
                    "langages" => [
                        ["name" => "HTML", "icon" => "Html", "class" => "bg-[#963419] text-white", "style" => "background-color: #963419; color: #fff;"],
                        ["name" => "CSS", "icon" => "Css", "class" => "bg-[#07436b] text-white", "style" => "background-color: #07436b; color: #fff;"],
                        ["name" => "JavaScript", "icon" => "Js", "class" => "bg-[#a39535] text-white", "style" => "background-color: #a39535; color: #fff;"],
                        ["name" => "Python", "icon" => "Python", "class" => "bg-[#306998] text-white", "style" => "background-color: #306998; color: #fff;"],
                        ["name" => "PHP", "icon" => "Php", "class" => "bg-[#4F5D95] text-white", "style" => "background-color: #4F5D95; color: #fff;"]
                    ],
                    "frameworks_libraries" => [
                        ["name" => "Astro", "icon" => "AstroIcon", "class" => "bg-[#FF5A1F] text-white", "style" => "background-color: #FF5A1F; color: #fff;"],
                        ["name" => "Tailwind CSS", "icon" => "Tailwind", "class" => "bg-[#06B6D4] text-white", "style" => "background-color: #06B6D4; color: #fff;"],
                        ["name" => "Symfony", "icon" => "Symfony", "class" => "bg-[#000000] text-white", "style" => "background-color: #000000; color: #fff;"],
                        ["name" => "Bootstrap", "icon" => "Bootstrap", "class" => "bg-[#563D7C] text-white", "style" => "background-color: #563D7C; color: #fff;"],
                        ["name" => "React", "icon" => "React", "class" => "bg-[#1c7777] text-white", "style" => "background-color: #1c7777; color: #fff;"],
                        ["name" => "Django", "icon" => "Django", "class" => "bg-[#092E20] text-white", "style" => "background-color: #092E20; color: #fff;"],
                        ["name" => "jQuery", "icon" => "Jquery", "class" => "bg-[#0769AD] text-white", "style" => "background-color: #0769AD; color: #fff;"]
                    ],
                    "bases_de_donnees" => [
                        ["name" => "MongoDB", "icon" => "Mongo", "class" => "bg-[#47A248] text-white", "style" => "background-color: #47A248; color: #fff;"],
                        ["name" => "MariaDB", "icon" => "Maria", "class" => "bg-[#003545] text-white", "style" => "background-color: #003545; color: #fff;"],
                        ["name" => "SQLite", "icon" => "Sqlite", "class" => "bg-[#003B57] text-white", "style" => "background-color: #003B57; color: #fff;"],
                        ["name" => "MySQL", "icon" => "Mysql", "class" => "bg-[#00758F] text-white", "style" => "background-color: #00758F; color: #fff;"]
                    ]
                ]
            ];

            // **1️⃣ Insertion des technologies**
            foreach ($dataPortfolio['technology'] as $category => $techs) {
                foreach ($techs as $techData) {
                    $technology = new Technology();
                    $technology->setName($techData['name'])
                        ->setIcon($techData['icon'])
                        ->setClass($techData['class'])
                        ->setStyle($techData['style']);
                    $this->entityManager->persist($technology);
                }
            }
            $io->success("✅ Technologies ajoutées.");

            // **Flush final**
            $this->entityManager->flush();
            $io->success("🚀 Données du portfolio importées avec succès.");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error("❌ Erreur: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
