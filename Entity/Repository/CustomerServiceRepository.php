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

class CustomerServiceRepository extends PluginInstanceRepository
{
    public function getQueryBuilderForCustomerServiceManagementDataGrid()
    {
        return $this->createQueryBuilder('s');
    }
    
    public function getQueryBuilderForCustomerServiceFormDataGrid()
    {
        $qb = $this->createQueryBuilder('s');
        $qb
            ->where('s.enabled = ?1')
            ->setParameter(1, true)
        ;
        
        return $qb;
    }
}