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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ResumeDataService
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

        $data = [
            "\$schema" => "https://raw.githubusercontent.com/jsonresume/resume-schema/v1.0.0/schema.json",
            "basics" => [
                "name" => $user->getName(),
                "label" => $user->getLabel(),
                "email" => $user->getEmail(),
                "phone" => $user->getPhone(),
                "summary" => $user->getSummary(),
                "location" => $location ? [
                    "address" => $location->getAddress(),
                    "postalCode" => $location->getPostalCode(),
                    "city" => $location->getCity(),
                    "countryCode" => $location->getCountryCode(),
                    "region" => $location->getRegion()
                ] : [],
                "profiles" => array_map(fn($profile) => [
                    "network" => $profile->getNetwork(),
                    "username" => $profile->getUsername(),
                    "url" => $profile->getUrl()
                ], $profiles),
            ],
            "work" => array_map(fn($work) => [
                "name" => $work->getName(),
                "location" => $work->getLocation(),
                "position" => $work->getPosition(),
                "startDate" => $work->getStartDate()->format('Y-m-d'),
                "endDate" => $work->getEndDate() ? $work->getEndDate()->format('Y-m-d') : null,
                "summary" => $work->getSummary(),
                "highlights" => $work->getHighlights()
            ], $works),
            "education" => array_map(fn($edu) => [
                "institution" => $edu->getInstitution(),
                "url" => $edu->getUrl(),
                "area" => $edu->getArea(),
                "studyType" => $edu->getStudyType(),
                "startDate" => $edu->getStartDate()->format('Y-m-d'),
                "endDate" => $edu->getEndDate() ? $edu->getEndDate()->format('Y-m-d') : null,
                "score" => $edu->getScore(),
                "courses" => $edu->getCourses()
            ], $education),
            "project" => array_map(fn($project) => [
                "name" => $project->getName(),
                "description" => $project->getDescription(),
                "highlights" => $project->getHighlights(),
                "keywords" => $project->getTechnology()->map(fn($technology) => $technology->getName())->toArray(),
                "startDate" => $project->getStartDate() ? $project->getStartDate()->format('Y-m-d') : null,
                "endDate" => $project->getEndDate() ? $project->getEndDate()->format('Y-m-d') : null,
                "url" => $project->getUrl(),
                "roles" => $project->getRoles(),
                "entity" => $project->getEntity(),
                "type" => $project->getType()
            ], $projects),
            "skills" => array_map(fn($skill) => [
                "name" => $skill->getName(),
                "level" => $skill->getLevel(),
                "keywords" => $skill->getKeywords()
            ], $skills),
            "languages" => array_map(fn($language) => [
                "language" => $language->getLanguage(),
                "fluency" => $language->getFluency()
            ], $languages),
            "interests" => array_map(fn($interest) => [
                "name" => $interest->getName(),
                "keywords" => $interest->getKeywords()
            ], $interests),
            "references" => array_map(fn($portfolioReference) => [
                "name" => $portfolioReference->getName(),
                "reference" => $portfolioReference->getRef()
            ], $portfolioReferences),
            "meta" => [
                "version" => "v1.0.0",
                "lastModified" => (new \DateTime())->format('c')
            ]
        ];

        $filesystem = new Filesystem();
        $jsonPath = $this->params->get('kernel.project_dir') . '/src/resume.json';
        $filesystem->dumpFile($jsonPath, json_encode($data, JSON_PRETTY_PRINT));

        return $jsonPath;
    }
}
