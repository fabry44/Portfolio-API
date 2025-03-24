<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Repository\WorkRepository;
use App\Repository\SkillRepository;
use App\Repository\LanguageRepository;
use App\Repository\InterestRepository;
use App\Repository\PortfolioReferenceRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProfileRepository;
use App\Repository\LocationRepository;
use App\Repository\EducationRepository;
use App\Repository\TechnologyRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PortfolioDataService
{
    private UserRepository $userRepository;
    private WorkRepository $workRepository;
    private SkillRepository $skillRepository;
    private LanguageRepository $languageRepository;
    private InterestRepository $interestRepository;
    private PortfolioReferenceRepository $portfolioReferenceRepository;
    private ProjectRepository $projectRepository;
    private ProfileRepository $profileRepository;
    private LocationRepository $locationRepository;
    private EducationRepository $educationRepository;
    private TechnologyRepository $technologyRepository;
    private ParameterBagInterface $params;
    private SerializerInterface $serializer;

    public function __construct(
        UserRepository $userRepository,
        WorkRepository $workRepository,
        SkillRepository $skillRepository,
        LanguageRepository $languageRepository,
        InterestRepository $interestRepository,
        PortfolioReferenceRepository $portfolioReferenceRepository,
        ProjectRepository $projectRepository,
        ProfileRepository $profileRepository,
        LocationRepository $locationRepository,
        EducationRepository $educationRepository,
        TechnologyRepository $technologyRepository,
        ParameterBagInterface $params,
        SerializerInterface $serializer
    ) {
        $this->userRepository = $userRepository;
        $this->workRepository = $workRepository;
        $this->skillRepository = $skillRepository;
        $this->languageRepository = $languageRepository;
        $this->interestRepository = $interestRepository;
        $this->portfolioReferenceRepository = $portfolioReferenceRepository;
        $this->projectRepository = $projectRepository;
        $this->profileRepository = $profileRepository;
        $this->locationRepository = $locationRepository;
        $this->educationRepository = $educationRepository;
        $this->technologyRepository = $technologyRepository;
        $this->params = $params;
        $this->serializer = $serializer;
    }

    public function generateJsonFile(): string
    {
        $user = $this->userRepository->findOneBy([]);
        $location = $this->locationRepository->findOneBy(["user" => $user]);
        $profiles = $this->profileRepository->findBy(["user" => $user]);
        $works = $this->workRepository->findAll();
        $skills = $this->skillRepository->findAll();
        $languages = $this->languageRepository->findAll();
        $interests = $this->interestRepository->findAll();
        $portfolioReferences = $this->portfolioReferenceRepository->findAll();
        $projects = $this->projectRepository->findAll();
        $education = $this->educationRepository->findAll();
        $technologies = $this->technologyRepository->findAll();

        // Calcul de l'âge
        $birthdate = $user->getBirth();
        $age = $birthdate ? (new \DateTime())->diff($birthdate)->y : null;
        $linkedin = $profiles ? array_values(array_filter($profiles, fn ($profile) => $profile->getNetwork() === 'Linkedin'))[0]->getUrl() ?? null : null;
        $github = $profiles ? array_values(array_filter($profiles, fn ($profile) => $profile->getNetwork() === 'Github'))[0]->getUrl() ?? null : null;

        $data = [
            "user" => [
                "email" => $user->getEmail(),
                "name" => $user->getName(),
                "phone" => $user->getPhone(),
                "birth" => $birthdate ? $birthdate->format(\DateTime::ATOM) : null,
                "address" => $location ? $location->getAddress()." ".$location->getPostalCode()." ".$location->getCity() : null,
                "linkedin" => $linkedin,
                "github" => $github,
                "status" => $user->getStatus(),
                "about" => $user->getSummary(),
                "function" => $user->getLabel(),
                "age" => $age,
                "languages" => array_map(fn ($lang) => $lang->getLanguage(), $languages),
                "interests" => array_map(fn ($interest) => $interest->getName(), $interests),
                "img" => $user->getPhoto()
            ],
            "formations" => array_map(fn ($edu) => [
                "degree" => $edu->getStudyType(),
                "institution" => $edu->getInstitution(),
                "date" => $edu->getEndDate()?->format(\DateTime::ATOM),
                "description" => $edu->getArea()
            ], $education),
            "projects" => array_map(fn ($project) => [
                "title" => $project->getName(),
                "img" => $project->getPhotos()->first()?->getImageName(), // Récupère la première photo du projet
                "Allimg" => array_map(fn ($photo) => $photo->getImageName(), $project->getPhotos()->toArray()), // Récupère toutes les photos du projet
                "description" => $project->getDescription(),
                "highlights" => $project->getHighlights(),
                "link" => $project->getLink(),
                "github" => $project->getGithub(),
                "technology" => array_map(fn ($tech) => [
                    "name" => $tech->getName(),
                    "icon" => $tech->getIcon(),
                    "class" => $tech->getClass(),
                    "style" => $tech->getStyle()
                ], $project->getTechnology()->toArray())
            ], $projects),
            "technologies" => array_map(fn ($tech) => [
                "name" => $tech->getName(),
                "icon" => $tech->getIcon(),
                "class" => $tech->getClass(),
                "style" => $tech->getStyle()
            ], $technologies),
            "updated_at" => (new \DateTime())->format('Y-m-d H:i:s')
        ];

        // Génération du fichier JSON
        $filesystem = new Filesystem();
        $jsonPath = $this->params->get('kernel.project_dir') . '/src/Data/data.json';
        $filesystem->dumpFile($jsonPath, json_encode($data, JSON_PRETTY_PRINT));

        return $jsonPath;
    }
}
