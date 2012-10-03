<?php
namespace Neutron\Plugin\CustomerServicesBundle\Model;

interface CustomerServiceManagerInterface
{
    public function getQueryBuilderForCustomerServicesManagementDataGrid();
    
    public function getQueryBuilderForCustomerServiceListDataGrid();
}