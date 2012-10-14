<?php
/*
 * This file is part of NeutronCustomerServiceBundle
 *
 * (c) Zender <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\Plugin\CustomerServiceBundle\Entity\Repository;

use Neutron\MvcBundle\Entity\Repository\PluginInstanceRepository;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

class CustomerServiceOverviewRepository extends PluginInstanceRepository
{
    public function getByCategoryQueryBuilder(CategoryInterface $category)
    {
        $qb = $this->createQueryBuilder('o');
        $qb
            ->select('o, r, s')
            ->join('o.references', 'r')
            ->join('r.inversed', 's')
            ->where('o.category = ?1 AND s.enabled = ?2')
            ->orderBy('r.position', 'ASC')
            ->setParameters(array(1 => $category, 2 => true))
        ;
        
        return $qb;
    }
    
    public function getByCategoryQuery(CategoryInterface $category)
    {
        $query = $this->getByCategoryQueryBuilder($category)->getQuery();
        
        return $query;
    }
    
    public function getByCategory(CategoryInterface $category)
    {
        return $this->getByCategoryQuery($category)->getOneOrNullResult();
    }
    
}