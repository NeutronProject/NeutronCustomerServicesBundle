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

use Gedmo\Translatable\Entity\Repository\TranslationRepository;

class CustomerServiceRepository extends TranslationRepository
{
    public function getQueryBuilderForCustomerServicesManagementDataGrid()
    {
        return $this->createQueryBuilder('s');
    }
    
    public function getQueryBuilderForCustomerServiceListDataGrid()
    {
        $qb = $this->createQueryBuilder('s');
        $qb
            ->where('s.enabled = ?1')
            ->setParameter(1, true)
        ;
        
        return $qb;
    }
}