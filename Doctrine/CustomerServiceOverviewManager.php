<?php
namespace Neutron\Plugin\CustomerServiceBundle\Doctrine;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Neutron\ComponentBundle\Doctrine\AbstractManager;

use Neutron\Plugin\CustomerServiceBundle\Model\CustomerServiceOverviewManagerInterface;

class CustomerServiceOverviewManager extends AbstractManager 
    implements CustomerServiceOverviewManagerInterface
{
    public function getByCategory(CategoryInterface $category)
    {
        return $this->repository->getByCategory($category);
    }
}