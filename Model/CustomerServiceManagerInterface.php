<?php
namespace Neutron\Plugin\CustomerServiceBundle\Model;

interface CustomerServiceManagerInterface
{
    public function getQueryBuilderForCustomerServiceManagementDataGrid();
    
    public function getQueryBuilderForCustomerServiceFormDataGrid();
}