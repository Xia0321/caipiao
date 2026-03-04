# 手机端 makev 投注数据流与 DOM 结构分析

## 1. HTML 结构（makev.html）

### 1.1 整体层级
```
body
├── .tz (投注栏/确认区)           ← 2682 行，与 main-content 同级
│   ├── .tzzs (已选N注)
│   ├── .je (金额输入)
│   └── .jeqr (确认按钮)
└── .main-content                ← 2750 行
    ├── .clmake (长龙/色波)      ← 3153，默认 display:none
    │   └── .cls (色波选项通过 JS 填 .pg)
    ├── .ylmake (遗漏)           ← 3441，默认 display:none
    │   └── .yls (遗漏选项通过 JS 填 .pg)
    └── .tzbody (冠军/两面等)    ← 3520
        ├── .menuplay (玩法菜单：长龙/遗漏/两面/冠军…)
        └── .make                 ← 3815，选项容器
            └── [.bcn_center2.items / .rough_lines.items]  ← 由 JS libs() 动态 append
                └── .play / .plays / .dds (具体选项)
```

### 1.2 选项来源与 class
- **普通玩法（冠军/两面等）**：`lib()` → `libs()` → AJAX make.php?xtype=lib → `$(".make").append(str)`  
  生成的节点带 class：`play`、`qiua`/`qiub`/`qiuc`、`p{pid}`，以及子节点 `.peilv1`。  
  选中态：在对应 `<a class="... play ...">` 上增加 class `qiuselect`。
- **字组合玩法**：同一 `str` 里会有 `plays`、`dds`（d1/d2/d3），选中态为 `qiuselect`。
- **色波/长龙**：`getcl()` 填 `.cls`，节点带 class `pg`、`loWSJd` 等，选中态 `isSelected`。
- **遗漏**：`getyl()` 填 `.yls`，节点带 class `pg`、`emmWlu` 等，选中态 `isSelected`。

### 1.3 关键点
- 只有一个 `.make`，且只在 `.tzbody` 下；所有 `.play`/`.plays`/`.dds` 都在 `.make` 内。
- `.tz` 与 `.main-content` 同级，不在 `.tzbody` 内；投注栏与选项区是同一 document。
- `ngid`、`fenlei`、`mulu` 等在 makev.html 内通过模板变量输出（如 `var ngid= {+$gid+};`）。

---

## 2. JS 数据流（makevuser.js）

### 2.1 选注与展示
1. 用户点击玩法 → `lib()` → `libs(stype)` → 请求 `make.php?xtype=lib` → 成功回调里 `$(".make").append(str)`，并 `addfunc(duo)`。
2. **普通玩法**：`addfunc(0)` 里用 `$(document).on("click.play touchend.play", ".play", ...)` 委托，点击时对当前 `.play` 做 `toggleClass("qiuselect")`，并 `addtouzhupaly()` → `totalje()`，更新「已选N注」和「确认」按钮。
3. **色波/遗漏**：`clylfunc()` 里对 `.pg` 做 `touchend.pg` / `click.pg` 委托，选中态为 `isSelected`，同样会更新注数。
4. **字组合**：`addfunc(1)` 里绑定 `.plays`、`.dds`，选中态为 `qiuselect`。

### 2.2 提交（exe）
1. 用户点「确认」→ `$(".tz .jeqr").click` → `exe()`。
2. **查找根容器**：  
   `$root = $(".make").length ? $(".make").first().closest("body") : $(document.body)`  
   保证在存在 `.make` 时，所有查找都在「包含 .make 的 body」内（兼容同一页多 document/iframe 时只取当前页）。
3. **三类选注**（均在 $root 下）：
   - 字组合：`$root.find(".plays.qiuselect").length > 0` → 用 `$root.find(".dds.d1/.d2/.d3")` 等组装 `play`。
   - 色波/遗漏：`$pgSel = $root.find(".pg.isSelected")`，用 `$pgSel.each(...)` 组装 `play`。
   - 普通玩法：`$playSel = $root.find(".make .play.qiuselect")`（若无则回退 `$root.find(".play.qiuselect")`），用 `$playSel.each(...)` 组装 `play`。
4. 组装出的 `play[i]` 包含：`gid/pid/bid/name/je/peilv1/con/bz/wf` 等，然后 `pstr = JSON.stringify(play)`，POST 到 `makelib.php?xtype=make`，带 `pstr`、`abcd`、`ab`。

### 2.3 为何会“组装数据全为空”
- **play 数组为空**：说明上面三步里，`$root.find(".plays.qiuselect")`、`$pgSel`、`$playSel` 都为空。
  - 可能原因：选中的节点不在当前 `$root`（例如在 iframe 或另一 document）；或选中态 class 没加上/被移除了（例如委托或 touchend 未生效）；或 `.make` 尚未渲染/被清空。
- **play 有长度但字段全空**：说明走到了某条分支并执行了 `.each`，但 `$(this).attr("pid")`、`$(this).find(".peilv1").html()` 等取不到。
  - 可能原因：实际被选中的不是由 `rhtmla/rhtmlb/rhtmlc` 生成的那批节点（例如 DOM 被其他脚本改过，或选择器匹配到了错误节点）；或生成的 HTML 里没有 `pid`/`mname` 或 `.peilv1`。

---

## 3. 本次修改要点

1. **不再依赖 :visible**  
   以前用 `$(".tzbody").is(":visible")` 等决定从哪个面板收集，移动端可能误判。现在统一用 `$(document.body)` 或「包含 .make 的 body」为根，在 `$root` 下用 `$root.find(...)` 收集，避免因可见性导致收集不到。

2. **统一用 $root 做选择**  
   `exe()` 内字组合、.pg、.play 的查找都改为在 `$root` 下进行（含 `.plays.qiuselect`、`.dds.d1/.d2/.d3`、`.pg.isSelected`、`.make .play.qiuselect`），minje/maxje 等也从 `$root.find(...)` 取，保证和当前页选项一致。

3. **.play 优先在 .make 内**  
   `$playSel` 优先用 `$root.find(".make .play.qiuselect")`，没有再退化为 `$root.find(".play.qiuselect")`，避免误采到其他区域的 .play。

4. **根容器兼容 iframe**  
   若存在 `.make`，则 `$root = $(".make").first().closest("body")`，这样若 makev 在 iframe 中打开，也会在 iframe 的 body 内收集，不会采到父页的节点。

---

## 4. 建议排查步骤（仍为空时）

1. **控制台**：在 `exe()` 开头临时加  
   `console.log("$root", $root.length, "$pgSel", $pgSel.length, "$playSel", $playSel.length, "plays", $root.find(".plays.qiuselect").length);`  
   确认当前页下三类选中数量是否符合预期。

2. **确认选中态**：在点击选项后、再点确认前，在控制台执行  
   `document.querySelectorAll(".play.qiuselect, .pg.isSelected, .plays.qiuselect")`  
   看是否包含你刚选中的节点。

3. **确认 .make 与 .tz 同文档**：控制台执行  
   `document.querySelector(".make")?.closest("body") === document.body`  
   若为 false，说明 .make 在 iframe 内，此时 `$root` 已按上面逻辑取 iframe 的 body，需再确认 makevuser.js 是否在该 iframe 内执行。

4. **后端**：若前端 `pstr` 已非空，可看 makelib.php 收到的 `$_POST['pstr']` 和解析后的 `$play`，确认是前端没组好还是后端校验/处理导致“为空”的提示。
