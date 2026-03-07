-- ============================================================
-- 为 3D (gid=252) 两面玩法增加「奇」「偶」选项（与单双规则一致：奇=单、偶=双）
-- 执行前请确认：x_play 中已有 gid=252 且 name 为「单」「双」的玩法行
-- ============================================================

SET @pid = (SELECT COALESCE(MAX(pid), 252000) FROM x_play WHERE gid = 252);

INSERT INTO `x_play` (`gid`, `bid`, `sid`, `cid`, `pid`, `name`, `ifok`, `peilv1`, `peilv2`, `mp1`, `mp2`, `ztype`, `znum1`, `znum2`, `xsort`, `start`, `autocs`, `zstart`, `zautocs`, `zqishu`, `buzqishu`, `pl`, `mpl`, `ystart`, `yautocs`, `ptype`)
SELECT gid, bid, sid, cid, @pid:=@pid+1, '奇', ifok, peilv1, peilv2, mp1, mp2, ztype, znum1, znum2, xsort, start, autocs, zstart, zautocs, zqishu, buzqishu, pl, mpl, ystart, yautocs, ptype
FROM x_play WHERE gid = 252 AND name = '单';

INSERT INTO `x_play` (`gid`, `bid`, `sid`, `cid`, `pid`, `name`, `ifok`, `peilv1`, `peilv2`, `mp1`, `mp2`, `ztype`, `znum1`, `znum2`, `xsort`, `start`, `autocs`, `zstart`, `zautocs`, `zqishu`, `buzqishu`, `pl`, `mpl`, `ystart`, `yautocs`, `ptype`)
SELECT gid, bid, sid, cid, @pid:=@pid+1, '偶', ifok, peilv1, peilv2, mp1, mp2, ztype, znum1, znum2, xsort, start, autocs, zstart, zautocs, zqishu, buzqishu, pl, mpl, ystart, yautocs, ptype
FROM x_play WHERE gid = 252 AND name = '双';
