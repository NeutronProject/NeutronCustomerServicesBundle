<?php
namespace Neutron\Plugin\CustomerServicesBundle\Doctrine;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Neutron\MvcBundle\Doctrine\AbstractPluginManager;

class CustomerServicesPluginManager extends AbstractPluginManager 
{
    public function getOverviewByCategory(CategoryInterface $category)
    {
        return $this->repository->getOverviewByCategory($category);
    }
}