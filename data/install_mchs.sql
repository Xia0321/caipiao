-- x_mchs 商户回调配置表
-- 如果表不存在则执行此 SQL 创建，已存在则跳过
CREATE TABLE IF NOT EXISTS `x_mchs` (
  `id`           int(11)      NOT NULL AUTO_INCREMENT,
  `mch_code`     varchar(64)  NOT NULL DEFAULT '' COMMENT '商户号，与 x_user.mch_code 对应',
  `callback_url` varchar(500) NOT NULL DEFAULT '' COMMENT '商户回调根地址，如 https://example.com/api/xycp',
  `mch_secret`   varchar(128) NOT NULL DEFAULT '' COMMENT '验签密钥，需与商户 back_key 一致',
  `status`       tinyint(1)   NOT NULL DEFAULT 1   COMMENT '1=启用 0=禁用',
  `created_at`   datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_mch_code` (`mch_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商户回调配置';
