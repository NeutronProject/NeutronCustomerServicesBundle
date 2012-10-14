<?php
namespace Neutron\Plugin\CustomerServiceBundle\Doctrine;

use Neutron\Plugin\CustomerServiceBundle\Model\CustomerServiceManagerInterface;

use Neutron\ComponentBundle\Doctrine\AbstractManager;

class CustomerServiceManager extends AbstractManager implements CustomerServiceManagerInterface 
{
    public function getQueryBuilderForCustomerServiceManagementDataGrid()
    {
        return $this->repository->getQueryBuilderForCustomerServiceManagementDataGrid();
    }
    
    public function getQueryBuilderForCustomerServiceFormDataGrid()
    {
        return $this->repository->getQueryBuilderForCustomerServiceFormDataGrid();
    }
}