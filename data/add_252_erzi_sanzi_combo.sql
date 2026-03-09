-- ============================================================
-- 252(3D) 二字组合(00-99)、三字组合(000-999) 玩法与赔率
-- pl 结构：二字 [[同号0-9],[二不同号0-9],[同上]]；三字 [[三同号0-9],[二同号0-9],[三不同0-9]]
-- 执行前请确认 x_play 中已有 gid=252 的任意一行（用于复制 bid/sid/cid）
-- ============================================================

-- 二字组合：00,11..99 用 pl[0][i]，01,02..98 用 pl[1] 取两号最小（同号赔率约100，二不同约50）
SET @pl2z = '[[100,100,100,100,100,100,100,100,100,100],[50,50,50,50,50,50,50,50,50,50],[50,50,50,50,50,50,50,50,50,50]]';

-- 三字组合：000-999 分三同/二同/三不同，赔率三同>二同>三不同
SET @pl3z = '[[1000,1000,1000,1000,1000,1000,1000,1000,1000,1000],[150,150,150,150,150,150,150,150,150,150],[80,80,80,80,80,80,80,80,80,80]]';

-- 1) 若已存在 二字组合/三字组合，只更新 pl
UPDATE `x_play` SET `pl` = @pl2z, `mpl` = @pl2z, `peilv1` = 100, `mp1` = 100
WHERE `gid` = 252 AND `name` IN ('二字组合', '2字组合');

UPDATE `x_play` SET `pl` = @pl3z, `mpl` = @pl3z, `peilv1` = 1000, `mp1` = 1000
WHERE `gid` = 252 AND `name` IN ('三字组合', '3字组合');

-- 2) 若不存在则插入（从 252 的 252005 玩法行复制 bid/sid/cid 等，pid 每次取当前 max+1）
INSERT INTO `x_play` (`gid`, `bid`, `sid`, `cid`, `pid`, `name`, `ifok`, `peilv1`, `peilv2`, `mp1`, `mp2`, `ztype`, `znum1`, `znum2`, `xsort`, `start`, `autocs`, `zstart`, `zautocs`, `zqishu`, `buzqishu`, `pl`, `mpl`, `ystart`, `yautocs`, `ptype`)
SELECT 252, t.bid, t.sid, t.cid, (SELECT COALESCE(MAX(pid),252000) FROM x_play WHERE gid=252) + 1, '二字组合', 1, 100, 0, 100, 0, t.ztype, 2, 0, (SELECT COALESCE(MAX(xsort),0)+10 FROM x_play WHERE gid=252 AND bid=t.bid), 0, 0, 0, 0, t.zqishu, t.buzqishu, @pl2z, @pl2z, 0, 0, t.ptype
FROM (SELECT bid, sid, cid, ztype, zqishu, buzqishu, ptype FROM `x_play` WHERE `gid` = 252 AND `bid` = 252005 LIMIT 1) t
WHERE NOT EXISTS (SELECT 1 FROM `x_play` WHERE `gid` = 252 AND `name` IN ('二字组合', '2字组合'));

INSERT INTO `x_play` (`gid`, `bid`, `sid`, `cid`, `pid`, `name`, `ifok`, `peilv1`, `peilv2`, `mp1`, `mp2`, `ztype`, `znum1`, `znum2`, `xsort`, `start`, `autocs`, `zstart`, `zautocs`, `zqishu`, `buzqishu`, `pl`, `mpl`, `ystart`, `yautocs`, `ptype`)
SELECT 252, t.bid, t.sid, t.cid, (SELECT COALESCE(MAX(pid),252000) FROM x_play WHERE gid=252) + 1, '三字组合', 1, 1000, 0, 1000, 0, t.ztype, 3, 0, (SELECT COALESCE(MAX(xsort),0)+10 FROM x_play WHERE gid=252 AND bid=t.bid), 0, 0, 0, 0, t.zqishu, t.buzqishu, @pl3z, @pl3z, 0, 0, t.ptype
FROM (SELECT bid, sid, cid, ztype, zqishu, buzqishu, ptype FROM `x_play` WHERE `gid` = 252 AND `bid` = 252005 LIMIT 1) t
WHERE NOT EXISTS (SELECT 1 FROM `x_play` WHERE `gid` = 252 AND `name` IN ('三字组合', '3字组合'));

-- 3) 同步 x_sclass：确保左侧菜单有「二字组合」「三字组合」入口（252005 下若有多个 sid 则菜单来自 x_sclass.sid）
-- 若 252 从 251 克隆，251 的 x_sclass 已含对应 sid；此处仅当 252 无 252005 时补 sid（一般不需执行）
-- INSERT INTO `x_sclass` (`gid`,`bid`,`sid`,`name`,`ifok`,`xsort`) SELECT 252,252005,252005,'1字定位',1,5 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM x_sclass WHERE gid=252 AND sid=252005);
