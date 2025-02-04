<?php

namespace App\Command;

use PDO;
use PDOException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-data',
    description: 'Importe les données du portfolio (formations, expériences, projets et technologies) dans la base de données.',
)]
class ImportDataCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setHelp('Cette commande crée les tables nécessaires (si elles n\'existent pas) et insère les données du portfolio définies dans le tableau $dataPortfolio.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Connexion à la base de données via PDO
        $dbUrl = $_ENV['DATABASE_URL'] ?? null;
        if (!$dbUrl) {
            $io->error('La variable d\'environnement DATABASE_URL n\'est pas définie.');
            return Command::FAILURE;
        }
        $db = parse_url($dbUrl);

        $host = $db["host"] ?? 'localhost';
        $port = $db["port"] ?? 3306;
        $user = $db["user"] ?? null;
        $password = $db["pass"] ?? null;
        $database = ltrim($db["path"], "/");

        $dsn = sprintf("mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4", $host, $port, $database);

        try {
            $io->writeln('Connexion à la base de données...');
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $io->writeln('Connexion réussie.');

            /*
             * Définition des données du portfolio
             */
            $dataPortfolio = [
                "formations" => [
                    [
                        "date" => "2025-01-01",
                        "degree" => "Certificat d'État niveau 5 - Développement Web et Web Mobile",
                        "institution" => "École Studi",
                        "description" => "Formation suivie en candidat libre à l'école Studi, spécialisée en développement backend avec Symfony."
                    ],
                    [
                        "date" => "2010-06-01",
                        "degree" => "BTS Génie Optique option Photonique",
                        "institution" => "Lycée Félix le Dantec à Lannion",
                        "description" => "Spécialisation en optique physique, géométrique et photonique."
                    ],
                    [
                        "date" => "2008-06-01",
                        "degree" => "Bac Scientifique",
                        "institution" => "Lycée Chateaubriand à Combourg",
                        "description" => "Spécialité Mathématiques."
                    ]
                ],
                "experiences" => [
                    [
                        "title" => "Tech Startup",
                        "beginAt" => "2024",
                        "endAt" => null,
                        "position" => "Développeur Web Junior",
                        "summary" => "Développement et maintenance d’applications web en JavaScript, HTML et CSS.",
                        "responsibilities" => [
                            "Collaboration avec les développeurs seniors pour concevoir et implémenter des applications web modernes.",
                            "Débogage et optimisation du code frontend pour garantir une expérience utilisateur fluide.",
                            "Participation aux revues de code et amélioration des standards de codage de l’équipe."
                        ],
                        "achievements" => [
                            "Développement et maintenance d’applications web en JavaScript, HTML et CSS.",
                            "Implémentation de nouvelles fonctionnalités en collaboration avec l’équipe."
                        ],
                        "skills" => ["React", "Tailwind", "GitHub"],
                        "location" => "Auckland, Nouvelle-Zélande",
                        "location_type" => "Sur site"
                    ],
                    [
                        "title" => "Innovative Solutions",
                        "beginAt" => "2022",
                        "endAt" => "2023",
                        "position" => "Développeur Logiciel Junior",
                        "summary" => "Développement et maintenance d’applications logicielles en Python et Django.",
                        "responsibilities" => [
                            "Développement et maintenance des services backend en Python et Django.",
                            "Travail en étroite collaboration avec les développeurs frontend pour intégrer les éléments orientés utilisateur.",
                            "Participation aux réunions quotidiennes et aux sprints bihebdomadaires pour aligner les objectifs du projet."
                        ],
                        "achievements" => [
                            "Mise en œuvre d’une fonctionnalité améliorant l’authentification utilisateur et la sécurité."
                        ],
                        "skills" => ["Python", "Django", "SQL", "Git"],
                        "location" => "Wellington, Nouvelle-Zélande",
                        "location_type" => "Hybride"
                    ]
                ],
                "projects" => [
                    [
                        "title" => "Site web pour un zoo (Arcadia)",
                        "description" => "Projet d'étude Studi (ECF) : conception d'un site web Zoo-Arcadia",
                        "link" => "#",
                        // "github" => "https://github.com/fabry44/Studi_project_Zoo_Arcadia.git",
                        // "image" => "/projects/proyecto1.webp",
                        "technology" => ["Html", "Css", "Js", "React"]
                    ],
                    [
                        "title" => "SOS PRO, Site vitrine pour une auto-entreprise",
                        "description" => "Site vitrine pour une auto-entreprise de services à la personne avec Symfony 7.2, Bootstrap 5, JQuery et MariaDB",
                        "link" => "#",
                        // "github" => "https://github.com/fabry44/SOSPRO.git",
                        // "image" => "/projects/proyecto2.webp",
                        "technology" => ["Html", "Css", "Js"]
                    ]
                ],
                "technology" => [
                    "langages" => [
                        ["name" => "HTML", "icon" => "Html", "class" => "bg-[#963419] text-white"],
                        ["name" => "CSS", "icon" => "Css", "class" => "bg-[#07436b] text-white"],
                        ["name" => "JavaScript", "icon" => "Js", "class" => "bg-[#a39535] text-white"],
                        ["name" => "PHP", "icon" => "Php", "class" => "bg-[#4F5D95] text-white"]
                    ],
                    "frameworks_libraries" => [
                        ["name" => "Astro", "icon" => "AstroIcon", "class" => "bg-[#FF5A1F] text-white"],
                        ["name" => "Bootstrap", "icon" => "Bootstrap", "class" => "bg-[#563D7C] text-white"],
                        ["name" => "React", "icon" => "React", "class" => "bg-[#1c7777] text-white"]
                    ],
                    "bases_de_donnees" => [
                        ["name" => "MongoDB", "icon" => "Mongo", "class" => "bg-[#47A248] text-white"],
                        ["name" => "MySQL", "icon" => "Mysql", "class" => "bg-[#00758F] text-white"]
                    ]
                ]
            ];

            /*
             * Création de la table `formation`
             */
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `formation` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `date` DATE NOT NULL,
                    `degree` VARCHAR(255) NOT NULL,
                    `institution` VARCHAR(255) NOT NULL,
                    `description` TEXT NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");

            $sqlFormation = "REPLACE INTO `formation` (`date`, `degree`, `institution`, `description`) VALUES (:date, :degree, :institution, :description)";
            $stmtFormation = $pdo->prepare($sqlFormation);

            foreach ($dataPortfolio['formations'] as $formation) {
                $stmtFormation->execute([
                    ':date' => (new \DateTimeImmutable($formation['date']))->format('Y-m-d'),
                    ':degree' => $formation['degree'],
                    ':institution' => $formation['institution'],
                    ':description' => $formation['description'],
                ]);
            }
            $io->writeln('Les formations ont été insérées.');

            /*
             * Création de la table `experience`
             * On stocke les tableaux (responsibilities, achievements, skills) en JSON
             */
            // $pdo->exec("
            //     CREATE TABLE IF NOT EXISTS `experience` (
            //         `id` INT(11) NOT NULL AUTO_INCREMENT,
            //         `title` VARCHAR(255) NOT NULL,
            //         `beginAt` VARCHAR(20) NOT NULL,
            //         `endAt` VARCHAR(20) DEFAULT NULL,
            //         `position` VARCHAR(255) NOT NULL,
            //         `summary` TEXT NOT NULL,
            //         `responsibilities` TEXT,
            //         `achievements` TEXT,
            //         `skills` TEXT,
            //         `location` VARCHAR(255) NOT NULL,
            //         `location_type` VARCHAR(50) NOT NULL,
            //         PRIMARY KEY (`id`)
            //     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            // ");

            // $sqlExperience = "REPLACE INTO `experience` 
            //     (`title`, `beginAt`, `endAt`, `position`, `summary`, `responsibilities`, `achievements`, `skills`, `location`, `location_type`) 
            //     VALUES 
            //     (:title, :beginAt, :endAt, :position, :summary, :responsibilities, :achievements, :skills, :location, :location_type)";
            // $stmtExperience = $pdo->prepare($sqlExperience);

            // foreach ($dataPortfolio['experiences'] as $experience) {
            //     $stmtExperience->execute([
            //         ':title' => $experience['title'],
            //         ':beginAt' => $experience['beginAt'],
            //         ':endAt' => $experience['endAt'],
            //         ':position' => $experience['position'],
            //         ':summary' => $experience['summary'],
            //         ':responsibilities' => json_encode($experience['responsibilities']),
            //         ':achievements' => json_encode($experience['achievements']),
            //         ':skills' => json_encode($experience['skills']),
            //         ':location' => $experience['location'],
            //         ':location_type' => $experience['location_type'],
            //     ]);
            // }
            // $io->writeln('Les expériences ont été insérées.');

            /*
             * Création de la table `project`
             * On stocke le tableau "technology" en JSON
             */
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `project` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `title` VARCHAR(255) NOT NULL,
                    `description` TEXT NOT NULL,
                    `link` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");

            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `project_technology` (
                    `project_id` INT(11) NOT NULL,
                    `technology_id` INT(11) NOT NULL,
                    PRIMARY KEY (`project_id`, `technology_id`),
                    FOREIGN KEY (`project_id`) REFERENCES `project`(`id`) ON DELETE CASCADE,
                    FOREIGN KEY (`technology_id`) REFERENCES `technology`(`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");

            $sqlProject = "REPLACE INTO `project` (`title`, `description`, `link`) VALUES (:title, :description, :link)";
            $stmtProject = $pdo->prepare($sqlProject);

            foreach ($dataPortfolio['projects'] as $project) {
                $stmtProject->execute([
                    ':title' => $project['title'],
                    ':description' => $project['description'],
                    ':link' => $project['link'],
                    // ':technology' => json_encode($project['technology']),
                ]);
            }
            $io->writeln('Les projets ont été insérés.');

            /*
             * Création de la table `technology`
             * Chaque technologie sera insérée avec sa catégorie (langages, frameworks_libraries, bases_de_donnees)
             */
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `technology` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `category` VARCHAR(50) NOT NULL,
                    `title` VARCHAR(255) NOT NULL,
                    `icon` VARCHAR(255) NOT NULL,
                    `class` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");

            $sqlTechnology = "REPLACE INTO `technology` (`name`, `icon`, `class`) VALUES (:name, :icon, :class)";
            $stmtTechnology = $pdo->prepare($sqlTechnology);

            foreach ($dataPortfolio['technology'] as $category => $techs) {
                foreach ($techs as $techData) {
                    $stmtTechnology->execute([
                        ':name' => $techData['name'],
                        ':icon' => $techData['icon'],
                        ':class' => $techData['class'],
                    ]);
                }
            }
            $io->writeln('Les technologies ont été insérées.');

            $io->success('Données du portfolio importées avec succès.');
            return Command::SUCCESS;
        } catch (PDOException $e) {
            $io->error('Erreur lors de la connexion ou de l\'exécution SQL: ' . $e->getMessage());
            return Command::FAILURE;
        } catch (\Exception $e) {
            $io->error('Une erreur inattendue est survenue: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

