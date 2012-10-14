<?php
namespace Neutron\Plugin\CustomerServiceBundle\Model;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

interface CustomerServiceOverviewManagerInterface
{
    public function getByCategory(CategoryInterface $category);
}