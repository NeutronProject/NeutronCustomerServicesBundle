<?php
/*
 * This file is part of NeutronCustomerServiceBundle
 *
 * (c) Zender <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\Plugin\CustomerServicesBundle\Entity\Repository;

use Neutron\MvcBundle\Model\Category\CategoryInterface;

use Neutron\MvcBundle\Entity\Repository\PluginInstanceRepository;

class CustomerServicesPluginRepository extends PluginInstanceRepository
{
    public function getOverviewByCategoryQueryBuilder(CategoryInterface $category)
    {
        $qb = $this->createQueryBuilder('o');
        $qb
            ->select('o, r, s')
            ->join('o.customerServiceReferences', 'r')
            ->join('r.inversed', 's')
            ->where('o.category = ?1 AND s.enabled = ?2')
            ->orderBy('r.position', 'ASC')
            ->setParameters(array(1 => $category, 2 => true))
        ;
        
        return $qb;
    }
    
    public function getOverviewByCategoryQuery(CategoryInterface $category)
    {
        $query = $this->getOverviewByCategoryQueryBuilder($category)->getQuery();
        
        return $query;
    }
    
    public function getOverviewByCategory(CategoryInterface $category)
    {
        return $this->getOverviewByCategoryQuery($category)->getOneOrNullResult();
    }
    
}