<?php
namespace App\EventListener;

use App\Entity\Produit;
use App\Entity\HistoriquePrix;
use App\Service\NotificationService;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preUpdate, entity: Produit::class)]
#[AsEntityListener(event: Events::postUpdate, entity: Produit::class)]
class ProduitListener
{
    private NotificationService $notification;
    private EntityManagerInterface $entityManager;
    private array $historiqueBuffer = [];

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

            if ($oldPrice == $newPrice) {
                error_log("Le prix n'a pas changé. Sortie de l'événement.");
                return;
            }

            error_log("preUpdate - Ancien prix: $oldPrice | Nouveau prix: $newPrice | Produit: {$produit->getNom()}");

            $historique = new HistoriquePrix();
            $historique->setProduit($produit);
            $historique->setPrix($newPrice);
            $historique->setDate(new \DateTime());
            $historique->setMarche($produit->getMarche());

            // BufferSaveInMemory
            $this->historiqueBuffer[spl_object_id($produit)] = $historique;

            $variation = abs(($newPrice - $oldPrice) / $oldPrice * 100);
            if ($variation > 10) {
                error_log("Envoi du SMS pour {$produit->getNom()}");
                $this->notification->sendPriceAlert(
                    "+221777854984",
                    $produit->getNom(),
                    $oldPrice,
                    $newPrice
                );
            }
        }
    }

    public function postUpdate(Produit $produit, PostUpdateEventArgs $event): void
    {
        // BufferCheckIfExist
        $historique = $this->historiqueBuffer[spl_object_id($produit)] ?? null;
        if ($historique) {
            $this->entityManager->persist($historique);
            $this->entityManager->flush();
            error_log("Historique persisté pour le produit: {$produit->getNom()}");
            unset($this->historiqueBuffer[spl_object_id($produit)]); // bufferCleanup
        }
    }
}