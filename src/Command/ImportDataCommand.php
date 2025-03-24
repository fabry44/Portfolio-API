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
            // **1️⃣ Récupération de l'utilisateur existant ou création**
            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'fabienroy2@gmail.com']);
            if ($existingUser) {
                $user = $existingUser;
                $user->setName('Roy Fabien')
                    ->setBirth(new \DateTimeImmutable('1984-09-16'))
                    ->setLabel('Développeur Web et Web Mobile')
                    ->setEmail('fabienroy2@gmail.com')
                    ->setPhone('0650262098')
                    ->setStatus('Open to work')
                    ->setSummary('<div>Je m\'appelle <strong>Fabien Roy</strong>. Après 15 ans d\'expérience dans les réseaux fibre optique, dont 6 années en tant que conducteur de travaux spécialisé dans le déploiement de la fibre optique, j’ai décidé d\'entamer une reconversion vers le <strong>développement web et mobile</strong>.<br><br></div><div>J\'ai obtenu un <strong>certificat d\'État niveau 5 en Développement Web et Web Mobile (DWWM)</strong> à l\'école Studi en candidat libre, tout en travaillant à plein temps. Cette expérience a renforcé ma persévérance et ma capacité à m’adapter à des défis exigeants.<br><br></div><div>Avant même cette reconversion, ma passion pour la programmation était déjà présente. Dans mon ancien métier, j’utilisais des scripts en <strong>VBA</strong> pour automatiser des tâches répétitives, optimisant ainsi mon temps et la qualité des résultats. Aujourd’hui, cette aptitude à rechercher des solutions innovantes s’applique pleinement à mes projets de développement.<br><br></div><div>Je me spécialise en <strong>développement backend avec Symfony</strong> et continue d\'explorer d\'autres technologies comme Node.js, Rust et Python. Chaque jour, je cherche à améliorer mes compétences, à travailler sur des projets concrets et à approfondir mes connaissances.<br><br></div>');
            } else {
                $user = new User();
                $user->setName('Roy Fabien')
                    ->setBirth(new \DateTimeImmutable('1984-09-16'))
                    ->setLabel('Développeur Web et Web Mobile')
                    ->setEmail('fabienroy2@gmail.com')
                    ->setPhone('0650262098')
                    ->setStatus('Open to work')
                    ->setPassword(password_hash('12345678', PASSWORD_BCRYPT))
                    ->setSummary('<div>Je m\'appelle <strong>Fabien Roy</strong>. Après 15 ans d\'expérience dans les réseaux fibre optique, dont 6 années en tant que conducteur de travaux spécialisé dans le déploiement de la fibre optique, j’ai décidé d\'entamer une reconversion vers le <strong>développement web et mobile</strong>.<br><br></div><div>J\'ai obtenu un <strong>certificat d\'État niveau 5 en Développement Web et Web Mobile (DWWM)</strong> à l\'école Studi en candidat libre, tout en travaillant à plein temps. Cette expérience a renforcé ma persévérance et ma capacité à m’adapter à des défis exigeants.<br><br></div><div>Avant même cette reconversion, ma passion pour la programmation était déjà présente. Dans mon ancien métier, j’utilisais des scripts en <strong>VBA</strong> pour automatiser des tâches répétitives, optimisant ainsi mon temps et la qualité des résultats. Aujourd’hui, cette aptitude à rechercher des solutions innovantes s’applique pleinement à mes projets de développement.<br><br></div><div>Je me spécialise en <strong>développement backend avec Symfony</strong> et continue d\'explorer d\'autres technologies comme Node.js, Rust et Python. Chaque jour, je cherche à améliorer mes compétences, à travailler sur des projets concrets et à approfondir mes connaissances.<br><br></div>');
                $this->entityManager->persist($user);
            }

            // **2️⃣ Localisation**
            $location = new Location();
            $location->setUser($user)
                ->setAddress('22 rue des Renardières')
                ->setPostalCode('44100')
                ->setCity('Nantes')
                ->setCountryCode('France')
                ->setRegion('Loire Atlantique');
            $this->entityManager->persist($location);

            // **3️⃣ Insertion des formations**
            $educations = [
                ['École Studi', 'Développement Web et Web Mobile', 'Certificat d\'État niveau 5', '2023-07-20', '2025-01-01'],
                ['Lycée Félix le Dantec à Lannion', 'Optique physique, géométrique et photonique', 'BTS Génie Optique option Photonique', '2010-09-01', '2010-06-01'],
                ['Lycée Chateaubriand à Combourg', 'Mathématiques', 'Bac Scientifique', '2025-03-11', '2008-06-01']
            ];
            foreach ($educations as $edu) {
                $education = new Education();
                $education->setInstitution($edu[0])
                    ->setArea($edu[1])
                    ->setStudyType($edu[2])
                    ->setStartDate(new \DateTime($edu[3]))
                    ->setEndDate(new \DateTime($edu[4]));
                $this->entityManager->persist($education);
            }

            // **4️⃣ Insertion des compétences**
            $skills = [
                ['Languages', ['HTML', 'CSS', 'JavaScript', 'PHP', 'Rust', 'Python']],
                ['Frameworks et Librairies', ['Symfony', 'Astro', 'React', 'Bootstrap', 'Tailwind CSS', 'Node.js']],
                ['Bases de Données', ['MongoDB', 'MySQL']],
                ['Outils Additionnels', ['Figma', 'Git', 'GitHub', 'Netlify']]
            ];
            foreach ($skills as $skillData) {
                $skill = new Skill();
                $skill->setName($skillData[0])
                    ->setKeywords($skillData[1]);
                $this->entityManager->persist($skill);
            }

            // **Ajout des profils**
            $profiles = [
                ['network' => 'Linkedin', 'url' => 'www.linkedin.com/in/roy-fabien-13982a157'],
                ['network' => 'Github', 'url' => 'https://github.com/fabry44']
            ];
            foreach ($profiles as $profileData) {
                $profile = new Profile();
                $profile->setUser($user)
                    ->setNetwork($profileData['network'])
                    ->setUrl($profileData['url']);
                $this->entityManager->persist($profile);
            }

            // **Ajout des expériences professionnelles**
            $workExperiences = [
                ['company' => 'Société ERT Technologies', 'location' => '44 – Nantes', 'position' => 'Monteur Câbleur FIBRE OPTIQUE', 'startDate' => '2007-08-01', 'endDate' => '2011-04-01'],
                ['company' => 'La Signalisation de Bretagne', 'location' => 'Anetz - 44', 'position' => 'TECHNICIEN FIBRE OPTIQUE FTTH', 'startDate' => '2011-04-01', 'endDate' => '2012-09-01'],
                ['company' => 'Groupe Circet', 'location' => '44-St Herblain', 'position' => 'Technicien fibre optique', 'startDate' => '2012-09-01', 'endDate' => '2013-02-01'],
                ['company' => 'Free infrastructure', 'location' => 'Paris, 8ème', 'position' => 'Technicien Telecom', 'startDate' => '2013-03-01', 'endDate' => '2013-11-01'],
                ['company' => 'Groupe Circet', 'location' => '44-carquefou', 'position' => 'Chef de chantier puis Conducteur de travaux', 'startDate' => '2013-11-01', 'endDate' => '2019-04-01'],
                ['company' => 'CDH', 'location' => '44-Guéméné-Penfao', 'position' => 'Conducteur de travaux Tirage et raccordement Fibre Optique', 'startDate' => '2019-04-01', 'endDate' => null]
            ];
            foreach ($workExperiences as $workData) {
                $work = new Work();
                $work->setCompany($workData['company'])
                    ->setLocation($workData['location'])
                    ->setPosition($workData['position'])
                    ->setStartDate(new \DateTime($workData['startDate']))
                    ->setEndDate($workData['endDate'] ? new \DateTime($workData['endDate']) : null);
                $this->entityManager->persist($work);
            }

            // **Ajout des langues**
            $languages = [
                ['language' => 'Français', 'fluency' => 'Langue maternelle'],
                ['language' => 'Anglais', 'fluency' => 'Intermédiaire'],
                ['language' => 'Espagnole', 'fluency' => 'Débutant']
            ];
            foreach ($languages as $languageData) {
                $language = new Language();
                $language->setLanguage($languageData['language'])
                    ->setFluency($languageData['fluency']);
                $this->entityManager->persist($language);
            }

            // **5️⃣ Insertion des projets avec technologies**
            $projects = [
                [
                    'Site web pour un zoo (Arcadia)',
                    "<div><strong>Projet d'étude Studi (ECF) : conception d'un site web Zoo-Arcadia:<br></strong><br>Ce projet a été réalisé dans le cadre de l’évaluation de certification et visait à mettre en place un système de gestion pour un zoo.<br><br></div>",
                    'https://project-zoo-arcadia-c0cc1a69ef90.herokuapp.com/',
                    'https://github.com/fabry44/Studi_project_Zoo_Arcadia.git',
                    '2024-07-22',
                    ['Symfony', 'Bootstrap', 'PostgreSQL', 'MongoDB', 'Stimulus JS'],
                    "{\"11\":\"Symfony Security + Voters pour restreindre l\u2019acc\u00e8s aux administrateurs, v\u00e9t\u00e9rinaires et employ\u00e9s.\",\"12\":\"Acc\u00e8s s\u00e9curis\u00e9 aux donn\u00e9es sensibles avec EasyAdmin v4.\"}"
                ],
                [
                    'SOS PRO, Site vitrine de service aux particuliers et aux entreprises',
                    "<div>Le projet <strong>SOSPRO</strong> a été conçu pour gérer une entreprise de services à domicile et en entreprise.</div>",
                    'sospro-jalila.fr',
                    'https://github.com/fabry44/SOSPRO.git',
                    '2024-11-17',
                    ['Symfony', 'FullCalendar', 'MariaDB', 'UX Turbo', 'JWT'],
                    "{\"0\":\"Int\u00e9gration de FullCalendar avec gestion des \u00e9v\u00e9nements en drag-and-drop.\"}"
                ],
                [
                    'Portfolio-API',
                    "<div><strong>Portfolio Backend</strong> est une API développée avec <strong>Symfony 7</strong>.</div>",
                    null,
                    'https://github.com/fabry44/Portfolio-API.git',
                    '2025-03-12',
                    ['Symfony', 'OAuth2', 'JWT', 'Docker', 'MariaDB'],
                    "{\"0\":\"API REST s\u00e9curis\u00e9e avec OAuth2 et JWT.\"}"
                ],
                [
                    'Portfolio-Astro',
                    "<div><strong>Portfolio-Astro</strong> est l’interface frontend de mon portfolio.</div>",
                    'https://fabien-roy.netlify.app/',
                    'https://github.com/fabry44/portfolio-astro.git',
                    '2025-03-12',
                    ['Astro.js', 'Tailwind CSS', 'React', 'Netlify'],
                    "[\"Astro.js pour un site statique ultra-rapide.\"]"
                ]
            ];

            foreach ($projects as $projectData) {
                $project = new Project();
                $project->setName($projectData[0])
                    ->setDescription($projectData[1])
                    ->setLink($projectData[2])
                    ->setGithub($projectData[3])
                    ->setDate(new \DateTime($projectData[4]))
                    ->setHighlights($projectData[6]);

                foreach ($projectData[5] as $tech) {
                    $technology = $this->entityManager->getRepository(Technology::class)->findOneBy(['name' => $tech]);
                    if ($technology) {
                        $project->addTechnology($technology);
                    }
                }
                $this->entityManager->persist($project);
            }

            // **Finalisation de l'import**
            $this->entityManager->flush();
            $io->success("🚀 Données du portfolio importées avec succès.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error("❌ Erreur: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
