<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProjectType;
use App\Service\ProjectManager;
use Symfony\Component\Serializer\SerializerInterface;

class ProjectController extends AbstractController
{

    private $projectManager;
    private $serializer;

    public function __construct(ProjectManager $projectManager, SerializerInterface $serializer)
    {
        $this->projectManager = $projectManager;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/projects", methods={"POST"})
     */
    public function postProject(Request $request): JsonResponse
    {
        $project = $this->projectManager->createProject();
        $form = $this->createForm(ProjectType::class, $project);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            return JsonResponse::fromJsonString($this->serializer->serialize($project, 'json'));
        }

        return $this->json(["error" => "An error occured"]);
    }
}
