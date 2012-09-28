<?php

require_once 'Jquerypager.php';

class SampleGrid
{

    function gridExample()
    {
        $jPager = new Jquerypager();
        
        $header = array(
                            'merchantId' => "Id",
                            'bName' => "Merchant Name",
                            'bAddress' => "Address",
                            'bPhone' => 'Phone',
                    );
        
        #Define Link
        $link = array(
                        array(
                            'title' => 'View',
                            'text' => 'view',
                            'target' => 'overlay',
                            'link' => '/merchant/view',
                            'qstring' => array('merchantId')
                        ),
                        array(
                            'title' => 'Delete',
                            'text' => 'delete',
                            'target' => 'confirm',
                            'link' => '/datagrid_sample.php?action=delete&mId=',
                            'refresh-url' => '/',
                            'qstring' => array('merchantId')
                        )
                        
                    );
        
        $gridData = $this->getData();
        
        #Set header and users data
        $jPager->setPager("idUsersContacts",10,$header,$gridData);
        
        #set Sort Order and some other gui param
        $jPager->setJqPagerParameter(true,array(0=>'desc'),array(),true,true);
        
        
        #set Link data
        $jPager->addLink($link);
        $data_grid = $jPager->getHtml();
        
        include('grid_sample.html.php');
                
    }
    
    
    function getData()
    {
        return $businesses = array(
                            array(
                                    "groupId" => "1",
                                    "merchantId" => "000001",  
                                    "bName" => "CVS",
                                    "bAddress" => "Location 1 address test address",
                                    "bPhone" => "123-412-3451",
                                ),
                            array( 
                                    "groupId" => "1",
                                    "merchantId" => "000002",
                                    "bName" => "CVS",
                                    "bAddress" => "Location 2 address test address",
                                    "bPhone" => "345-567-8561",
                                ),
                            array( 
                                    "groupId" => "1",
                                    "merchantId" => "000003",
                                    "bName" => "CVS",
                                    "bAddress" => "277 W State Road 436, Altamonte Springs, FL",
                                    "bPhone" => "407-389-6025,,4,,9",
                                ),
                            array( 
                                    "groupId" => "1",
                                    "merchantId" => "000004",
                                    "bName" => "CVS",
                                    "bAddress" => "Location 4 address test address",
                                    "bPhone" => "345-567-8342",
                                ),
                            array( 
                                    "groupId" => "2",
                                    "merchantId" => "000005",
                                    "bName" => "Rite Aid",
                                    "bAddress" => "Location 1 address test address",
                                    "bPhone" => "345-567-856",
                                ),
                            array( 
                                    "groupId" => "2",
                                    "merchantId" => "000006",
                                    "bName" => "Rite Aid",
                                    "bAddress" => "Location 2 address test address",
                                    "bPhone" => "345-567-856",
                                ),
                            array( 
                                    "groupId" => "2",
                                    "merchantId" => "000007",
                                    "bName" => "Rite Aid",
                                    "bAddress" => "Location 3 address test address",
                                    "bPhone" => "345-567-856",
                                ),
                            array( 
                                    "groupId" => "3",
                                    "merchantId" => "000008",
                                    "bName" => "Whole Green",
                                    "bAddress" => "Location 1 address test address",
                                    "bPhone" => "345-567-1234",
                                ),
                            array( 
                                    "groupId" => "3",
                                    "merchantId" => "000009",
                                    "bName" => "Whole Green",
                                    "bAddress" => "Location 2 address test address",
                                    "bPhone" => "345-567-2345",
                                ),
                            array( 
                                    "groupId" => "3",
                                    "merchantId" => "000010",
                                    "bName" => "Whole Green",
                                    "bAddress" => "Location 3 address test address",
                                    "bPhone" => "345-567-3456",
                                ),
                            array( 
                                    "groupId" => "3",
                                    "merchantId" => "000011",
                                    "bName" => "Whole Green",
                                    "bAddress" => "Location 4 address test address",
                                    "bPhone" => "345-567-4567",
                                ),
                            array( 
                                    "groupId" => "3",
                                    "merchantId" => "000012",
                                    "bName" => "Whole Green",
                                    "bAddress" => "Location 5 address test address",
                                    "bPhone" => "345-567-5678",
                                ),
                            array( 
                                    "groupId" => "0",
                                    "merchantId" => "000013",
                                    "bName" => "Standup",
                                    "bAddress" => "Location 5 address test address",
                                    "bPhone" => "212-909-8288,1,2124180928#,,1",
                                ),
                            array(
                                    "groupId" => "1",
                                    "merchantId" => "000014",  
                                    "bName" => "CVS",
                                    "bAddress" => "Location 1 address test address",
                                    "bPhone" => "123-412-3451",
                                ),
                            array( 
                                    "groupId" => "1",
                                    "merchantId" => "000015",
                                    "bName" => "CVS",
                                    "bAddress" => "Location 2 address test address",
                                    "bPhone" => "345-567-8561",
                                ),
                            array( 
                                    "groupId" => "1",
                                    "merchantId" => "000016",
                                    "bName" => "CVS",
                                    "bAddress" => "277 W State Road 436, Altamonte Springs, FL",
                                    "bPhone" => "407-389-6025,,4,,9",
                                ),
                            array( 
                                    "groupId" => "1",
                                    "merchantId" => "000016",
                                    "bName" => "CVS",
                                    "bAddress" => "Location 4 address test address",
                                    "bPhone" => "345-567-8342",
                                ),
                            array( 
                                    "groupId" => "2",
                                    "merchantId" => "000017",
                                    "bName" => "Rite Aid",
                                    "bAddress" => "Location 1 address test address",
                                    "bPhone" => "345-567-856",
                                ),
                            array( 
                                    "groupId" => "2",
                                    "merchantId" => "000018",
                                    "bName" => "Rite Aid",
                                    "bAddress" => "Location 2 address test address",
                                    "bPhone" => "345-567-856",
                                ),
                            array( 
                                    "groupId" => "2",
                                    "merchantId" => "000019",
                                    "bName" => "Rite Aid",
                                    "bAddress" => "Location 3 address test address",
                                    "bPhone" => "345-567-856",
                                ),
                            array( 
                                    "groupId" => "3",
                                    "merchantId" => "000020",
                                    "bName" => "Whole Green",
                                    "bAddress" => "Location 1 address test address",
                                    "bPhone" => "345-567-1234",
                                ),
                            array( 
                                    "groupId" => "3",
                                    "merchantId" => "000021",
                                    "bName" => "Whole Green",
                                    "bAddress" => "Location 2 address test address",
                                    "bPhone" => "345-567-2345",
                                ),
                            array( 
                                    "groupId" => "3",
                                    "merchantId" => "000022",
                                    "bName" => "Whole Green",
                                    "bAddress" => "Location 3 address test address",
                                    "bPhone" => "345-567-3456",
                                ),
                            array( 
                                    "groupId" => "3",
                                    "merchantId" => "000023",
                                    "bName" => "Whole Green",
                                    "bAddress" => "Location 4 address test address",
                                    "bPhone" => "345-567-4567",
                                ),
                            array( 
                                    "groupId" => "3",
                                    "merchantId" => "000024",
                                    "bName" => "Whole Green",
                                    "bAddress" => "Location 5 address test address",
                                    "bPhone" => "345-567-5678",
                                ),
                            array( 
                                    "groupId" => "0",
                                    "merchantId" => "000025",
                                    "bName" => "Standup",
                                    "bAddress" => "Location 5 address test address",
                                    "bPhone" => "212-909-8288,1,2124180928#,,1",
                                )
                    );
    }
}


$oGrid = new SampleGrid();
$oGrid->gridExample();