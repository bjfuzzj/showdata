<?php
$insertArray = array
(
    'ctime', 
    'companyno',
    'companyname',
    'department',
    'customtype',
    'boxno',
    'cardno',
    'terminaltype',
    'interviewtype',
    'definition',
    'productname',
    'producttype',
    'feetype',
    'chargeunit',
    'priceper',
    'business',
    'businessno',
    'price',
    'expiredate',
    'paytype',
);

$changeArray = array
(
    'customtype' => array('居民客户', '非居民客户'),
    'terminaltype' => array('主终端', '副终端'),
    'interviewtype' => array('双向', ''),//其他待选
    'definition' => array('普清', '标清', '高清', '超清', 'BD', '原画')
);





$onlineInsertArray = array
(
    'orderid',
    'ordername',
    'ctime',
    'boxno',
    'totalprice',
    'status',
    'productid',
    'productname',
    'productdesc',
    'feeid',
    'priceper',
    'ordernum',
    'ordersrc',
);


$bussinessInsertArray = array
(

//  'ordernumber', // '序号',
  'areacode', // '地区码',
  'areaname', //'地区名',
  'networktype', //'网络类型',
  'customertype', // '客户类型',
  'productname', //'产品名称',
  'channelname', //'频道名称',
  'suppliers', //'供应商',
  'coproducttype', //'组合产品类型',
  'iscoproduct', //'一级产品是否组合产品',
  'childproductypes',//'子产品类型',
  'isbasiccoproduct',//'基础产品是否组合产品',
  'finaltype',//'结算类型',
  'charge', //'资费',
  'shopemployee',//'营业员',
  'office',//'营业厅',
  'seller',//'销售员',
  'salesamount',//'销售金额',
  'addednum',//'频道新增笔数',
  'appendnum',//'频道续订笔数',
  'ctime',//'创建时间',
);


$dayArray = array
(
    '00:00'=>'01:00',
    '01:00'=>'02:00',
    '02:00'=>'03:00',
    '03:00'=>'04:00',
    '04:00'=>'05:00',
    '05:00'=>'06:00',
    '06:00'=>'07:00',
    '07:00'=>'08:00',
    '08:00'=>'09:00',
    '09:00'=>'10:00',
    '10:00'=>'11:00',
    '11:00'=>'12:00',
    '12:00'=>'13:00',
    '13:00'=>'14:00',
    '14:00'=>'15:00',
    '15:00'=>'16:00',
    '16:00'=>'17:00',
    '17:00'=>'18:00',
    '18:00'=>'19:00',
    '19:00'=>'20:00',
    '20:00'=>'21:00',
    '21:00'=>'22:00',
    '22:00'=>'23:00',
    '23:00'=>'24:00',
);
