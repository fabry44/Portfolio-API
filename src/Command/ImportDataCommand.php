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
    name: 'app:import-data',
    description: 'Importe les données initiales du portfolio dans la base de données.',
)]
class ImportDataCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setHelp('Cette commande insère les données de base du portfolio dans la base de données.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title("📥 Importation des données du portfolio...");

        try {
            // **Données JSON de référence**
            $dataPortfolio = [
                "basics" => [
                    "name" => "Roy Fabien",
                    "birthdate" => "1984-09-16",
                    "label" => "Développeur Web et Web Mobile",
                    "status" => "Open to work",
                    "image" => "/developpeur.webp",
                    "email" => "fabienroy2@gmail.com",
                    "phone" => "0650262098",
                    "password" => "12345678",
                    "website" => "",
                    "url" => "",
                    "summary" => "Après 15 ans d’expérience dans les réseaux fibre optique, dont 6 ans en tant que conducteur de travaux, j’ai décidé de relever un nouveau défi en me reconvertissant dans le développement web et mobile. Passionné par la programmation depuis toujours, je me spécialise aujourd’hui en Symfony tout en explorant Node.js et Python.",
                    "location" => [
                        "address" => "22 rue des Renardières 44100 Nantes",
                        "postalCode" => "44100",
                        "city" => "Nantes",
                        "countryCode" => "FR",
                        "region" => "Pays de la Loire"
                    ],
                    "profiles" => []
                ],

                "education" => [
                    [
                        "institution" => "École Studi",
                        "url" => "",
                        "area" => "Développement Web et Web Mobile",
                        "studyType" => "Certificat d'État niveau 5",
                        "startDate" => "2023-07-20",
                        "endDate" => "2025-01-01",
                        "score" => "",
                        "courses" => []
                    ],
                    [
                        "institution" => "Lycée Félix le Dantec à Lannion",
                        "url" => "",
                        "area" => "Optique physique, géométrique et photonique",
                        "studyType" => "BTS Génie Optique option Photonique",
                        "startDate" => "2010-09-01",
                        "endDate" => "2010-06-01",
                        "score" => "",
                        "courses" => []
                    ],
                    [
                        "institution" => "Lycée Chateaubriand à Combourg",
                        "url" => "",
                        "area" => "Mathématiques",
                        "studyType" => "Bac Scientifique",
                        "startDate" => "",
                        "endDate" => "2008-06-01",
                        "score" => "",
                        "courses" => []
                    ]
                ],
                "skills" => [
                    [
                        "name" => "Languages",
                        "level" => "",
                        "keywords" => [
                            "HTML",
                            "CSS",
                            "JavaScript",
                            "PHP",
                            "Rust",
                            "Python",
                        ]

                    ],
                    [
                        "name" => "Frameworks et Librairies",
                        "level" => "",
                        "keywords" => [
                            "Symfony",
                            "Astro",
                            "React",
                            "Symfony",
                            "Bootstrap",
                            "Tailwind CSS",
                            "Node.js",
                        ]

                    ],
                    [
                        "name" => "Bases de Données",
                        "level" => "",
                        "keywords" => [
                            "MongoDB",
                            "MySQL",
                        ]

                    ],
                    [
                        "name" => "Outils Additionnels",
                        "level" => "",
                        "keywords" => [
                            "Figma",
                            "Git",
                            "GitHub",
                            "Netlify",
                        ]

                    ]
                ],
                "projects" => [
                    [
                        "name" => "Site web pour un zoo (Arcadia)",
                        "description" => "Projet d'étude Studi (ECF) : conception d'un site web Zoo-Arcadia",
                        "keywords" => ["HTML", "CSS", "JavaScript", "PHP", "Symfony", "Bootstrap"],
                        "url" => "#",
                        "type" => "application"
                    ],
                    [
                        "name" => "SOS PRO, Site vitrine pour une auto-entreprise",
                        "description" => "Site vitrine pour une auto-entreprise de services à la personne avec Symfony 7.2, Bootstrap 5, JQuery et MariaDB",
                        "keywords" => ["HTML", "CSS", "JavaScript", "PHP", "Symfony", "Bootstrap", "MariaDB"],
                        "url" => "#",
                        "type" => "application"
                    ]
                ]
            ];

            // **1️⃣ Création de l'utilisateur**
            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $dataPortfolio['basics']['email']]);
            if ($existingUser) {
                $io->warning("⚠️ Utilisateur avec l'email " . $dataPortfolio['basics']['email'] . " existe déjà.");
                $user = $existingUser;
                $user->setName($dataPortfolio['basics']['name'])
                    ->setBirth(new \DateTimeImmutable($dataPortfolio['basics']['birthdate']))
                    ->setLabel($dataPortfolio['basics']['label'])
                    ->setEmail($dataPortfolio['basics']['email'])
                    ->setPhone($dataPortfolio['basics']['phone'])
                    ->setStatus($dataPortfolio['basics']['status'])
                    ->setPassword(password_hash($dataPortfolio['basics']['password'], PASSWORD_BCRYPT))
                    ->setSummary($dataPortfolio['basics']['summary']);
                $this->entityManager->persist($user);
                $io->success("✅ Utilisateur à été mis à jours.");
            } else {
                $user = new User();
                $user->setName($dataPortfolio['basics']['name'])
                    ->setBirth(new \DateTimeImmutable($dataPortfolio['basics']['birthdate']))
                    ->setLabel($dataPortfolio['basics']['label'])
                    ->setEmail($dataPortfolio['basics']['email'])
                    ->setPhone($dataPortfolio['basics']['phone'])
                    ->setStatus($dataPortfolio['basics']['status'])
                    ->setPassword(password_hash($dataPortfolio['basics']['password'], PASSWORD_BCRYPT))
                    ->setSummary($dataPortfolio['basics']['summary']);
                $this->entityManager->persist($user);
                $io->success("✅ Utilisateur ajouté.");
            

                // **2️⃣ Création de la localisation**
                $location = new Location();
                $location->setUser($user)
                    ->setAddress($dataPortfolio['basics']['location']['address'])
                    ->setPostalCode($dataPortfolio['basics']['location']['postalCode'])
                    ->setCity($dataPortfolio['basics']['location']['city'])
                    ->setCountryCode($dataPortfolio['basics']['location']['countryCode'])
                    ->setRegion($dataPortfolio['basics']['location']['region']);
                $this->entityManager->persist($location);
                $io->success("✅ Localisation ajoutée.");
            }

            // **3️⃣ Insertion des formations**
            foreach ($dataPortfolio['education'] as $edu) {
                $education = new Education();
                $education->setInstitution($edu['institution'])
                    ->setUrl($edu['url'])
                    ->setArea($edu['area'])
                    ->setStudyType($edu['studyType'])
                    ->setStartDate(new \DateTime($edu['startDate']))
                    ->setEndDate(new \DateTime($edu['endDate']));
                $this->entityManager->persist($education);
            }
            $io->success("✅ Formations ajoutées.");

            // **4️⃣ Insertion des compétences**
            foreach ($dataPortfolio['skills'] as $skillData) {
                $skill = new Skill();
                $skill->setName($skillData['name'])
                    ->setKeywords($skillData['keywords']);
                $this->entityManager->persist($skill);
            }
            $io->success("✅ Compétences ajoutées.");

            $technology = [];
            // **5️⃣ Insertion des projets**
            foreach ($dataPortfolio['projects'] as $projectData) {
                
                $project = new Project();
                foreach ($projectData['keywords'] as $keyword) {
                    $technology = $this->entityManager->getRepository(Technology::class)->findOneBy(['name' => $keyword]);
                    $project->addTechnology($technology);
                }
                $project->setName($projectData['name'])
                    ->setDescription($projectData['description']);
                $this->entityManager->persist($project);
            }
            $io->success("✅ Projets ajoutés.");

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
