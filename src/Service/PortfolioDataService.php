<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use App\Repository\FormationRepository;
use App\Repository\TechnologyRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PortfolioDataService
{
    private UserRepository $userRepository;
    private FormationRepository $formationRepository;
    private TechnologyRepository $technologyRepository;
    private ProjectRepository $projectRepository;
    private ParameterBagInterface $params;
    private SerializerInterface $serializer;

    public function __construct(
        UserRepository $userRepository,
        FormationRepository $formationRepository,
        TechnologyRepository $technologyRepository,
        ProjectRepository $projectRepository,
        ParameterBagInterface $params,
        SerializerInterface $serializer
    ) {
        $this->userRepository = $userRepository;
        $this->formationRepository = $formationRepository;
        $this->technologyRepository = $technologyRepository;
        $this->projectRepository = $projectRepository;
        $this->params = $params;
        $this->serializer = $serializer;
    }

    public function generateJsonFile(): string
    {
        $user = $this->userRepository->findOneBy([]); // On suppose un seul utilisateur
        $formations = $this->formationRepository->findAll();
        $projects = $this->projectRepository->findAll();
        $technologies = $this->technologyRepository->findAll();

        $data = [
            'user' => $user ? json_decode($this->serializer->serialize($user, 'json', ['groups' => 'api.portfolio']), true) : null,
            'formations' => json_decode($this->serializer->serialize($formations, 'json', ['groups' => 'api.portfolio']), true),
            'projects' => json_decode($this->serializer->serialize($projects, 'json', ['groups' => 'api.portfolio']), true),
            'technologies' => json_decode($this->serializer->serialize($technologies, 'json', ['groups' => 'api.portfolio']), true),
            'updated_at' => (new \DateTime('now', new \DateTimeZone('Europe/Paris')))->format('Y-m-d H:i:s')
        ];

        $birthdate = $data['user']['birth'] ?? null;
        $age = $birthdate ? (new \DateTime())->diff(new \DateTime($birthdate))->y : null;
        $data['user']['age'] = $age;

        $filesystem = new Filesystem();
        $jsonPath = $this->params->get('kernel.project_dir') . '/src/Data/data.json';
        $filesystem->dumpFile($jsonPath, json_encode($data, JSON_PRETTY_PRINT));

        return $jsonPath;
    }

    public function getPortfolioData()
    {
        $user = $this->userRepository->findOneBy([]); // On suppose un seul utilisateur
        $formations = $this->formationRepository->findAll();
        $projects = $this->projectRepository->findAll();
        $technologies = $this->technologyRepository->findAll();

        return [
            'user' => $user ? json_decode($this->serializer->serialize($user, 'json', ['groups' => 'api.portfolio']), true) : null,
            'formations' => json_decode($this->serializer->serialize($formations, 'json', ['groups' => 'api.portfolio']), true),
            'projects' => json_decode($this->serializer->serialize($projects, 'json', ['groups' => 'api.portfolio']), true),
            'technologies' => json_decode($this->serializer->serialize($technologies, 'json', ['groups' => 'api.portfolio']), true)
        ];
    }
}
