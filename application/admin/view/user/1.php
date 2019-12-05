<?php


$res ="<request>
<userId>280451254</userId>
<contentId>638716044686</contentId>
<consumeCode>006088155004</consumeCode>
<cpid>710387</cpid>
<hRet>0</hRet>
<status>1800</status>
<versionId>20154</versionId>
<cpparam>0000000013264455</cpparam>
<packageID />
<provinceId>571</provinceId>
<channelId>40388001</channelId>
<guid>bfcc39507ea311e69ee952540058cbe3</guid>
<imei>86432302029587</imei>
<imsi>460011765516744</imsi>
<price></price>
</request>";


$dd = simplexml_load_string($res);
$json = json_encode($dd);
$jj = json_decode($json,true);

var_dump($jj['userId']);