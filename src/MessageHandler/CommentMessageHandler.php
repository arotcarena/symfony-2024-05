<?php

namespace App\MessageHandler;

use App\Entity\Enum\CommentStateEnum;
use App\Message\CommentMessage;
use App\Repository\CommentRepository;
use App\Service\SpamChecker;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\WorkflowInterface;

#[AsMessageHandler]
final class CommentMessageHandler
{
    public function __construct(
        private CommentRepository $commentRepository,
        private EntityManagerInterface $em,
        private SpamChecker $spamChecker,
        private MessageBusInterface $bus,
        private WorkflowInterface $commentStateMachine,
        private ?LoggerInterface $logger = null,
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

        if($this->commentStateMachine->can($comment, 'accept')) 
        {
            // $score = $this->spamChecker->getSpamScore($comment, $message->context);
            // $transition = match ($score) {
            //     2 => 'reject_spam',
            //     1 => 'might_be_spam',
            //     default => 'accept',
            // };
            $transition = 'accept';
            
            $this->commentStateMachine->apply($comment, $transition);
            $this->em->flush();
            $this->bus->dispatch($message);
        } 
        elseif($this->commentStateMachine->can($comment, 'publish') || $this->commentStateMachine->can($comment, 'publish_ham')) 
        {
                    $this->commentStateMachine->apply($comment, $this->commentStateMachine->can($comment, 'publish') ? 'publish' : 'publish_ham');
                    $this->em->flush();
        } 
        elseif($this->logger) 
        {
            $this->logger->debug('Dropping comment message', ['comment' => $comment->getId(), 'state' => $comment->getState()]);
        }
     
        $this->em->flush();
    }
}
