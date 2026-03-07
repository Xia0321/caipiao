-- ============================================================
-- 修复 排列3(251)、3D(252)、极速快三(151/152/153/155/157) 组选3、组选6 没有赔率
-- 原因：1) 号码选择页每个 0-9 的赔率来自 x_play.pl（JSON），不是 peilv1  2) pl 为空或全 0 会显示 0
-- 赔率：组选3=320，组选6=160（可改）
-- ============================================================

SET @pl3 = 320;
SET @pl6 = 160;
-- pl 为 10 个号码(0-9)的赔率数组，前端用 pl[0][i] 显示
SET @pl3_json = '[[320,320,320,320,320,320,320,320,320,320]]';
SET @pl6_json = '[[160,160,160,160,160,160,160,160,160,160]]';

-- 1) x_play：组选3 的 peilv + pl（号码 0-9 各 320）
UPDATE `x_play`
SET `peilv1` = @pl3, `peilv2` = @pl3, `mp1` = @pl3, `mp2` = @pl3,
    `pl` = @pl3_json
WHERE `gid` IN (251, 252, 151, 152, 153, 155, 157) AND `name` IN ('组选3', '组选三');

-- 2) x_play：组选6 的 peilv + pl（号码 0-9 各 160）
UPDATE `x_play`
SET `peilv1` = @pl6, `peilv2` = @pl6, `mp1` = @pl6, `mp2` = @pl6,
    `pl` = @pl6_json
WHERE `gid` IN (251, 252, 151, 152, 153, 155, 157) AND `name` IN ('组选6', '组选六');

-- 3) x_play_user：已有行的赔率 + pl 同步（组选3）
UPDATE `x_play_user` u
JOIN `x_play` p ON u.gid = p.gid AND u.pid = p.pid
SET u.peilv1 = @pl3, u.peilv2 = @pl3, u.mp1 = @pl3, u.mp2 = @pl3,
    u.pl = @pl3_json
WHERE u.gid IN (251, 252, 151, 152, 153, 155, 157) AND p.name IN ('组选3', '组选三');

-- 4) x_play_user：已有行的赔率 + pl 同步（组选6）
UPDATE `x_play_user` u
JOIN `x_play` p ON u.gid = p.gid AND u.pid = p.pid
SET u.peilv1 = @pl6, u.peilv2 = @pl6, u.mp1 = @pl6, u.mp2 = @pl6,
    u.pl = @pl6_json
WHERE u.gid IN (251, 252, 151, 152, 153, 155, 157) AND p.name IN ('组选6', '组选六');

-- 5) 补全 x_play_user 缺失行（独立赔率用户用 pl 显示每个号码赔率，缺行会显示 0）
-- 若报错列数不匹配，可注释本段，只保留 1–4 步
INSERT INTO `x_play_user` (
  `gid`, `userid`, `bid`, `sid`, `cid`, `pid`,
  `peilv1`, `peilv2`, `mp1`, `mp2`, `pl`, `mpl`, `xsort`
)
SELECT
  p.gid, gc.userid, p.bid, p.sid, p.cid, p.pid,
  IF(p.name IN ('组选3', '组选三'), @pl3, @pl6),
  IF(p.name IN ('组选3', '组选三'), @pl3, @pl6),
  IF(p.name IN ('组选3', '组选三'), @pl3, @pl6),
  IF(p.name IN ('组选3', '组选三'), @pl3, @pl6),
  IF(p.name IN ('组选3', '组选三'), @pl3_json, @pl6_json),
  IF(p.name IN ('组选3', '组选三'), @pl3_json, @pl6_json),
  IFNULL(p.xsort, 0)
FROM `x_play` p
INNER JOIN `x_gamecs` gc ON gc.gid = p.gid AND gc.ifok = 1
LEFT JOIN `x_play_user` pu ON pu.gid = p.gid AND pu.pid = p.pid AND pu.userid = gc.userid
WHERE p.name IN ('组选3', '组选三', '组选6', '组选六')
  AND p.gid IN (251, 252, 151, 152, 153, 155, 157)
  AND pu.pid IS NULL;
