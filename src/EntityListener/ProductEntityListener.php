<?php

namespace App\EntityListener;

use App\Entity\Product;
use doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Product $product, LifecycleEventArgs $event)
    {
        $this->computeSlug($product);
    }

    public function preUpdate(Product $product, LifecycleEventArgs $event)
    {
        $this->computeSlug($product);
    }

    public function computeSlug(Product $product)
    {
        $product->setSlug((string) $this->slugger->slug((string) $product->getName())->lower());
    }
}
