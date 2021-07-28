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
        $product->computeSlug($this->slugger);
    }

    public function preUpdate(Product $product, LifecycleEventArgs $event)
    {
        $product->computeSlug($this->slugger);
    }
}
