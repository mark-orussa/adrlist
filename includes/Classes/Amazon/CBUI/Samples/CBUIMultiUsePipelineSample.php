<?php
/** 
 *  PHP Version 5
 *
 *  @category    Amazon
 *  @package     Amazon_CBUI
 *  @copyright   Copyright 2008-2011 Amazon Technologies, Inc.
 *  @link        http://aws.amazon.com
 *  @license     http://aws.amazon.com/apache2.0  Apache License, Version 2.0
 *  @version     2010-08-28
 */
/******************************************************************************* 
 *    __  _    _  ___ 
 *   (  )( \/\/ )/ __)
 *   /__\ \    / \__ \
 *  (_)(_) \/\/  (___/
 * 
 *  Amazon FPS PHP5 Library
 * 
 */

//require_once('.config.inc.php');

//require_once('Amazon/CBUI/CBUIMultiUsePipeline.php');

class CBUIMultiUsePipelineSample {

    function test() {
        $pipeline = new Amazon_CBUI_CBUIMultiUsePipeline(AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY);

        $pipeline->setMandatoryParameters("callerReferenceMultiUse",  
                "http://www.mysite.com/call_back.jsp", "50");
        
        //optional parameters
        $pipeline->setUsageLimit1("Amount", "10", "6 Months");
        $pipeline->addParameter("paymentMethod", "CC");
        
        //MultiUse url
        print "Sample CBUI url for MultiUse pipeline : " . $pipeline->getUrl() . "\n";
    }
}

CBUIMultiUsePipelineSample::test();
