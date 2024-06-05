<?php

namespace App\MessageHandler;

use App\Entity\Enum\CommentStateEnum;
use App\Message\CommentMessage;
use App\Repository\CommentRepository;
use App\Service\SpamChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CommentMessageHandler
{
    public function __construct(
        private CommentRepository $commentRepository,
        private EntityManagerInterface $em
    )
    {
        
    }

    public function __invoke(CommentMessage $message)
    {
        $comment = $this->commentRepository->find($message->id);

        if(!$comment)
        {
            return;
        }

        $comment->setState(CommentStateEnum::Published);
     
        $this->em->flush();
    }
}
