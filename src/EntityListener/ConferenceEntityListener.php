<?php
namespace App\EntityListener;

use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(Events::prePersist, entity: Conference::class)]
#[AsEntityListener(Events::preUpdate, entity: Conference::class)]
class ConferenceEntityListener
{
    public function __construct(
        private SluggerInterface $slugger
    )
    {
        
    }

    public function __invoke(Conference $conference, LifecycleEventArgs $args)
    {
        $conference->computeSlug($this->slugger);
    }
}