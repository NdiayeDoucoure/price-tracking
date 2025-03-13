<?php
namespace App\EventListener;

use App\Entity\Produit;
use App\Entity\HistoriquePrix;
use App\Service\NotificationService;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\EntityManagerInterface;

#[AsEntityListener(event: Events::preUpdate, entity: Produit::class)]
class ProduitListener
{
    private NotificationService $notification;
    private EntityManagerInterface $entityManager;
    public function __construct(NotificationService $notification, EntityManagerInterface $entityManager)
    {
        $this->notification = $notification;
        $this->entityManager = $entityManager;
    }

    public function preUpdate(Produit $produit, PreUpdateEventArgs $event): void
    {
        if ($event->hasChangedField('prix')) {
            $oldPrice = $event->getOldValue('prix');
            $newPrice = $event->getNewValue('prix');

            $variation = abs(($newPrice - $oldPrice) / $oldPrice * 100);

            if ($variation > 10) {
                $historique = new HistoriquePrix();
                $historique->setProduit($produit);
                $historique->setPrix($newPrice);
                $historique->setDate(new \DateTime());
                $historique->setMarche($produit->getMarche());
                $this->entityManager->persist($historique);
                $this->entityManager->flush();

                
                    $this->notification->sendPriceAlert(
                        "+221777854984",
                        $produit->getNom(),
                        $oldPrice,
                        $newPrice
                    );
            }
        }
    }
}
