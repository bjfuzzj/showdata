| onlinedata | CREATE TABLE `onlinedata` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `orderid` varchar(20) NOT NULL COMMENT '订单号',
  `ordername` varchar(15) NOT NULL DEFAULT '' COMMENT '订单名称',
  `boxno` varchar(20) NOT NULL COMMENT '机顶盒编号',
  `totalprice` double NOT NULL COMMENT '订单金额(元)',
  `status` varchar(10) NOT NULL DEFAULT '',
  `productid` varchar(20) NOT NULL COMMENT '产品ID(编号)',
  `productname` varchar(20) NOT NULL COMMENT '产品包名称',
  `productdesc` varchar(20) NOT NULL COMMENT '产品包描述',
  `feeid` int(10) NOT NULL DEFAULT '0' COMMENT '资费ID',
  `priceper` varchar(20) NOT NULL COMMENT '产品单价',
  `ordernum` varchar(20) NOT NULL COMMENT '订购数量',
  `ordersrc` varchar(20) NOT NULL COMMENT '订单来源',
  `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=275599 DEFAULT CHARSET=utf8


//经营数据
CREATE TABLE `bussinessdata` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `areacode` varchar(6) NOT NULL DEFAULT '' COMMENT '地区码',
  `areaname` varchar(6) NOT NULL DEFAULT '' COMMENT '地区名',
  `networktype` enum('城网','农网')  NULL COMMENT '网络类型',
  `customertype` enum('非居民客户','居民客户','区公司客户')  NULL COMMENT '客户类型',
  `productname` varchar(25) NOT NULL COMMENT '产品名称',
  `channelname` varchar(25) NOT NULL COMMENT '频道名称',
  `suppliers` varchar(15) NOT NULL COMMENT '供应商',
  `coproducttype` varchar(15) NOT NULL COMMENT '组合产品类型',
  `iscoproduct` varchar(15) NOT NULL COMMENT '一级产品是否组合产品',
  `childproductypes` varchar(15) NOT NULL COMMENT '子产品类型',
  `isbasiccoproduct` varchar(15) NOT NULL COMMENT '基础产品是否组合产品',
  `finaltype` varchar(7) NOT NULL COMMENT '结算类型',
  `charge` varchar(16) NOT NULL COMMENT '资费',
  `shopemployee` varchar(7) NOT NULL COMMENT '营业员',
  `office` varchar(15) NOT NULL COMMENT '营业厅',
  `seller` varchar(7) NOT NULL COMMENT '销售员',
  `salesamount` double NOT NULL COMMENT '销售金额',
  `addednum` int(4) NOT NULL DEFAULT 0 COMMENT '频道新增笔数',
  `appendnum` int(4) NOT NULL DEFAULT 0 COMMENT '频道续订笔数',
  `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
  )