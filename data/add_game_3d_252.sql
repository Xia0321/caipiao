-- ============================================================
-- 新增游戏「3D」(gid=252)，玩法参考福彩3D，与体彩排列3(gid=251)一致
-- 执行前请确认：若库中已有体彩排列3(gid=251)，则玩法可从251克隆；否则需先导入251数据再执行克隆部分
--
-- 后台「没有相关数据」说明：开奖/结果页依赖 x_kj 期号数据。请务必执行下面第 4、5 步（或后台开奖管理里为 252 增加期号并保存），
-- 并将当前期号写入 x_game.thisqishu，否则切换 3D 时列表为空。
-- ============================================================

-- 1) 新增 x_game 一条：游戏 252 名为「3D」，fenlei=163，mnum=3
-- 方式A：若已有 gid=251，从 251 复制一条并改为 252
INSERT INTO `x_game` (
  `gid`, `panstatus`, `otherstatus`, `gname`, `fast`, `ifopen`, `mnum`, `havetm`, `thisqishu`, `thisbml`, `autoopenpan`, `otherclosetime`, `userclosetime`, `baostatus`, `autokj`, `cs`, `ftype`, `mtype`, `ztype`, `patt`, `patt1`, `patt2`, `patt3`, `patt4`, `patt5`, `pan`, `xsort`, `class`, `url`, `kjurl`, `fenlei`, `flname`, `sgname`, `dftype`, `ptype`, `guanfang`, `kjtime`, `upqishu`
)
SELECT
  252, `panstatus`, `otherstatus`, '3D', `fast`, `ifopen`, 3, `havetm`, '', `thisbml`, `autoopenpan`, `otherclosetime`, `userclosetime`, `baostatus`, `autokj`, `cs`, `ftype`, `mtype`, `ztype`, `patt`, `patt1`, `patt2`, `patt3`, `patt4`, `patt5`, `pan`, `xsort`, `class`, `url`, `kjurl`, '163', `flname`, '3D', `dftype`, `ptype`, `guanfang`, `kjtime`, 0
FROM `x_game` WHERE `gid` = 251 LIMIT 1;

-- 若上面因无 251 而报错，可改用方式B（最小配置，无 251 时用）：
-- INSERT INTO `x_game` (`gid`,`panstatus`,`otherstatus`,`gname`,`fast`,`ifopen`,`mnum`,`havetm`,`thisqishu`,`thisbml`,`autoopenpan`,`otherclosetime`,`userclosetime`,`baostatus`,`autokj`,`cs`,`ftype`,`mtype`,`ztype`,`patt`,`patt1`,`patt2`,`patt3`,`patt4`,`patt5`,`pan`,`xsort`,`class`,`url`,`kjurl`,`fenlei`,`flname`,`sgname`,`dftype`,`ptype`,`guanfang`,`kjtime`,`upqishu`) VALUES
-- (252,0,0,'3D',1,0,3,0,'','',1,0,0,1,1,'{}','{}','{}','{}',NULL,'[]','[]','[]','[]','[]','[]','{}',0,'c163','','','163','3D系列','3D','{}','[]',0,0,0);

-- 2) 从 251 克隆玩法到 252（bid/sid/cid/pid 统一 +1000 变为 252xxx）
-- 必须先有 gid=251 的数据，否则以下 4 条会无插入结果

INSERT INTO `x_bclass` (`gid`, `bid`, `name`, `ifok`, `xsort`)
SELECT 252, `bid` + 1000, `name`, `ifok`, `xsort` FROM `x_bclass` WHERE `gid` = 251;

INSERT INTO `x_sclass` (`gid`, `bid`, `sid`, `name`, `ifok`, `xsort`)
SELECT 252, `bid` + 1000, `sid` + 1000, `name`, `ifok`, `xsort` FROM `x_sclass` WHERE `gid` = 251;

INSERT INTO `x_class` (`gid`, `bid`, `sid`, `cid`, `name`, `xsort`, `ifok`, `mtype`, `ftype`, `xshow`, `one`, `dftype`)
SELECT 252, `bid` + 1000, `sid` + 1000, `cid` + 1000, `name`, `xsort`, `ifok`, `mtype`, `ftype`, `xshow`, `one`, `dftype` FROM `x_class` WHERE `gid` = 251;

INSERT INTO `x_play` (`gid`, `bid`, `sid`, `cid`, `pid`, `name`, `ifok`, `peilv1`, `peilv2`, `mp1`, `mp2`, `ztype`, `znum1`, `znum2`, `xsort`, `start`, `autocs`, `zstart`, `zautocs`, `zqishu`, `buzqishu`, `pl`, `mpl`, `ystart`, `yautocs`, `ptype`)
SELECT 252, `bid` + 1000, `sid` + 1000, `cid` + 1000, `pid` + 1000, `name`, `ifok`, `peilv1`, `peilv2`, `mp1`, `mp2`, `ztype`, `znum1`, `znum2`, `xsort`, `start`, `autocs`, `zstart`, `zautocs`, `zqishu`, `buzqishu`, `pl`, `mpl`, `ystart`, `yautocs`, `ptype` FROM `x_play` WHERE `gid` = 251;

-- 3) 为默认用户开放 252（userid 按你站实际改，常见为 99999999；若已存在该用户+252 可跳过或改 id）
--    重要：后台「开奖结果」页的下拉彩种来自 getgamecs(当前登录用户)。要让 3D 出现在下拉里，当前登录的代理/会员 userid 必须在 x_gamecs 中有 gid=252，请对实际登录用的 userid 执行相同 INSERT 或复制 99999999 的 252 记录。
INSERT INTO `x_gamecs` (`userid`, `gid`, `ifok`, `flytype`, `flyzc`, `zc`, `upzc`, `zcmin`, `xsort`)
VALUES (99999999, 252, 1, 3, 100, 100, 0, 0, 50);

-- 4) 可选：为 252 插入一期 x_kj（期号、时间请按你站规则改）
INSERT INTO `x_kj` (`gid`, `dates`, `qishu`, `bml`, `opentime`, `closetime`, `kjtime`, `baostatus`, `m1`, `m2`, `m3`, `m4`, `m5`, `m6`, `m7`, `m8`, `m9`, `m10`, `m11`, `m12`, `m13`, `m14`, `m15`, `m16`, `m17`, `m18`, `m19`, `m20`, `js`)
VALUES (252, CURDATE(), 20250302001, '', NOW(), NOW(), NOW(), 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0);

-- 5) 更新 x_game 的 thisqishu（有 x_kj 后，把当前期号写进游戏表）
-- UPDATE `x_game` SET `thisqishu` = '20250302001' WHERE `gid` = 252;
