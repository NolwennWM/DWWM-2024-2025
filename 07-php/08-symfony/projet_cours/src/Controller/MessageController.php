<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/message")]
final class MessageController extends AbstractController
{
    #[Route('/add', name: 'app_message_create')]
    public function createMessage(ManagerRegistry $doc): Response
    {
        $em = $doc->getManager();
        $message = new Message();
        $message->setContent("Ceci est un message de test")
                ->setCreatedAt(new \DateTimeImmutable());
        $em->persist($message);
        $em->flush();

        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    #[Route("/{page<^\d+$>?1}/{nb<^\d+$>?5}", name: "app_message_read")]
    // public function readMessage(ManagerRegistry $doc): Response
    public function readMessage(MessageRepository $repo, int $page, int $nb): Response
    {
        // $repo = $doc->getRepository(Message::class);
        // $messages = $repo->findAll();
        // $messages = $repo->find(/*un id*/);
        // $messages = $repo->findOneBy([]);
        // $messages = $repo->findBy([], ['createdAt' => 'DESC']);
        // $messages = $repo->findByDateInterval("2020-01-01","2025-02-03");
        $messages = $repo->findBy([], ['createdAt' => 'DESC'], $nb, ($page-1)*$nb);
        $total = $repo->count();
        $nbPage = ceil($total/$nb);
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
            'nbPage' => $nbPage,
            'currentPage' => $page,
            'nombre'=>$nb
        ]);
    }
    #[Route("/delete/{id<^\d+$>}", name:"app_message_delete")]
    // public function deleteMessage(ManagerRegistry $doc, int $id): Response
    public function deleteMessage(ManagerRegistry $doc, MessageRepository $repo, int $id): Response
    {
        // $repo = $doc->getRepository(Message::class);
        $message = $repo->find($id);
        if(!$message)
        {
            $this->addFlash("error", "Aucun message correspondant");
        }else
        {
            $em = $doc->getManager();
            $em->remove($message);
            $em->flush();
            $this->addFlash("info", "Message supprimé avec succès");
        }
        return $this->redirectToRoute("app_message_read");
    }
    #[Route("/update/{id<^\d+$>}", name: "app_message_update")]
    public function updateMessage(Message $message=null, ManagerRegistry $doc):Response
    {
        // dd($message);
        if(!$message)
        {
            $this->addFlash("error", "Aucun message correspondant");
        }else
        {
            $message->setContent("Message Modifié")
                    /* ->setEditedAt(new \DateTime()) */;
            $em = $doc->getManager();
            $em->persist($message);
            $em->flush();
            $this->addFlash("info", "Message modifié avec succès");
        }
        return $this->redirectToRoute("app_message_read");
    }
}
