<?php
namespace Neutron\Plugin\CustomerServicesBundle\Doctrine;

use Neutron\Plugin\CustomerServicesBundle\Model\CustomerServiceManagerInterface;

use Neutron\ComponentBundle\Doctrine\AbstractManager;

class CustomerServiceManager extends AbstractManager implements CustomerServiceManagerInterface
{
    public function getQueryBuilderForCustomerServicesManagementDataGrid()
    {
        return $this->repository->getQueryBuilderForCustomerServicesManagementDataGrid();
    }
    
    public function getQueryBuilderForCustomerServiceListDataGrid()
    {
        return $this->repository->getQueryBuilderForCustomerServiceListDataGrid();
    }
}