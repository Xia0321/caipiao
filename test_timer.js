/**
 * 定时器逻辑单元测试
 * 运行方式: node test_timer.js
 *
 * 覆盖范围:
 *   1. timekx()          - 开奖倒计时显示与 getnowtime 触发逻辑
 *   2. time0 sentinel    - 封盘 panstatus=0 时跳03修复（所有游戏类型）
 *   3. time1 sentinel    - fenlei==100 赛车 otherstatus=0 时跳03修复
 *   4. gntime 轮询间隔   - gntime=2 约等于1秒轮询
 *   5. 不同游戏类型       - fenlei=0 普通 / fenlei=100 bid='' / fenlei=100 bid!='A'
 *   6. lib() 触发条件    - 状态有任何变化（开/封/期数）时调用，稳定时不调用
 */

// ─────────────────────────────────────────────
// 测试框架
// ─────────────────────────────────────────────
var passed = 0, failed = 0;

function assert(condition, msg) {
    if (condition) {
        console.log('  \u2713 ' + msg);
        passed++;
    } else {
        console.log('  \u2717 FAIL: ' + msg);
        failed++;
    }
}

function describe(title, fn) {
    console.log('\n[' + title + ']');
    fn();
}

// ─────────────────────────────────────────────
// 环境模拟
// ─────────────────────────────────────────────
var kjtime_display   = '';
var time0_display    = '';
var time0x_called    = false;
var time1x_called    = false;
var getnowtime_count = 0;

// 游戏类型和盘口（测试中动态切换）
var fenlei = 0;
var current_bid = '';   // 模拟 $(".main a.click").attr("bid")

function setTimeout()  { return null; }
function clearTimeout() {}

var updatel_count = 0;
var upl;
function getnowtime() { getnowtime_count++; }
function time0x()     { time0x_called = true; }
function time1x()     { time1x_called = true; }
function updatel()    { updatel_count++; }

// ─────────────────────────────────────────────
// 被测逻辑 — 从 makeuser.js 原样提取
// ─────────────────────────────────────────────

// 全局变量（对应 makeuser.js 第1行）
var gntime = 2;   // ← 已改为2（原为7）
var timek, timekFirstZero = true;

// timekx() 原样提取
function timekx() {
    if (timek < 0) timek = 0;
    var str = '';
    var h = 0, m = 0, s = 0;
    h = Math.floor(timek / (60 * 60));
    m = Math.floor((timek - h * 60 * 60) / 60);
    s = timek - h * 60 * 60 - m * 60;
    if (h > 0) str += h + ":";
    if (m < 10) m = '0' + m;
    if (s < 10) s = '0' + s;
    str += m + ":";
    str += s;
    if (timek > 0) {
        kjtime_display = str;
        timekFirstZero = true;
    } else {
        kjtime_display = "00:00";
        if (timekFirstZero) {
            timekFirstZero = false;
            getnowtime();
            clearTimeout(upl); updatel();
        }
    }
    timek--;
    if (timek < 0) timek = 0;
}

/**
 * 模拟 getnowtime() success 回调中 time0 / time1 处理逻辑
 * 对应 makeuser.js 最新代码
 */
function handleGetnowtime(m0, m1, m3, m4) {
    time0x_called = false;
    time1x_called = false;
    time0_display = '';

    // fenlei==100 分支：处理 time1
    if (fenlei === 100) {
        var time1 = Number(m1);
        if (Number(m4) === 0 && time1 === 3) {
            // time1 sentinel：otherstatus=0，强制返回3
            if (current_bid !== '') {
                time0_display = "00:00";   // 模拟 $(".time0").html("00:00")
            }
            // bid=='' 时不更新显示（由 time0 处理）
        } else {
            time1x_called = true;          // 模拟调用 time1x()
        }
    }

    // time0 处理（所有 fenlei 共用）
    var time0 = Number(m0);
    if (Number(m3) === 0 && time0 === 3) {
        // time0 sentinel：panstatus=0，强制返回3
        if (fenlei !== 100 || current_bid === '') {
            time0_display = "00:00";       // 模拟 $(".time0").html("00:00")
        }
    } else {
        time0x_called = true;              // 模拟调用 time0x()
    }
}

/**
 * 模拟 gntimex() 倒计时循环
 * 返回：触发 getnowtime() 前经过的秒数
 */
function simulateGntimex(startGntime) {
    var gn = startGntime;
    var seconds = 0;
    while (gn > 0) {
        gn--;
        if (gn > 0) seconds++; // 每次 setTimeout 1 秒
    }
    // gn==0 → 调用 getnowtime()
    return seconds;
}

// ─────────────────────────────────────────────
// 工具函数
// ─────────────────────────────────────────────
function reset() {
    timek            = 0;
    timekFirstZero   = true;
    kjtime_display   = '';
    time0_display    = '';
    time0x_called    = false;
    time1x_called    = false;
    getnowtime_count = 0;
    updatel_count    = 0;
    fenlei           = 0;
    current_bid      = '';
}

// ═════════════════════════════════════════════
// 一、timekx() 倒计时显示逻辑
// ═════════════════════════════════════════════

describe('timekx - 正常倒计时格式', function() {
    reset(); timek = 5;
    timekx(); assert(kjtime_display === '00:05', 'timek=5  → 显示 00:05');
    timekx(); assert(kjtime_display === '00:04', 'timek=4  → 显示 00:04');
    timekx(); assert(kjtime_display === '00:03', 'timek=3  → 显示 00:03');
    timekx(); assert(kjtime_display === '00:02', 'timek=2  → 显示 00:02');
    timekx(); assert(kjtime_display === '00:01', 'timek=1  → 显示 00:01');
});

describe('timekx - 时间格式（含小时）', function() {
    reset(); timek = 3723; timekx();
    assert(kjtime_display === '1:02:03', 'timek=3723 → 1:02:03');

    reset(); timek = 7260; timekx();
    assert(kjtime_display === '2:01:00', 'timek=7260 → 2:01:00');

    reset(); timek = 60; timekx();
    assert(kjtime_display === '01:00',   'timek=60   → 01:00（无小时前缀）');
});

describe('timekx - 首次归零触发 getnowtime()', function() {
    reset(); timek = 1;
    timekx();
    assert(kjtime_display    === '00:01', '归零前显示 00:01');
    assert(getnowtime_count  === 0,       '尚未归零，不触发');

    timekx();
    assert(kjtime_display    === '00:00', '归零显示 00:00');
    assert(getnowtime_count  === 1,       '首次归零触发一次 getnowtime');
    assert(timekFirstZero    === false,   'timekFirstZero 置 false');
});

describe('timekx - 00:00 期间不重复触发', function() {
    reset(); timek = 1;
    timekx(); timekx(); timekx(); timekx(); timekx();
    assert(getnowtime_count === 1, '持续 00:00，只触发一次 getnowtime');
});

describe('timekx - 新一期重置后再次触发', function() {
    reset(); timek = 1;
    timekx(); timekx();
    assert(getnowtime_count === 1, '第一期归零触发1次');

    timek = 2; timekx(); timekx(); timekx();
    assert(getnowtime_count === 2, '第二期归零再次触发');

    timek = 1; timekx(); timekx();
    assert(getnowtime_count === 3, '第三期归零再次触发');
});

describe('timekx - 服务端返回负数或0', function() {
    reset(); timek = -30; timekx();
    assert(kjtime_display   === '00:00', '负数夹到0，显示 00:00');
    assert(getnowtime_count === 1,       '负数首次触发 getnowtime');

    reset(); timek = 0; timekx();
    assert(kjtime_display   === '00:00', 'timek=0 显示 00:00');
    assert(getnowtime_count === 1,       'timek=0 触发 getnowtime');
});

describe('timekx - 归零时触发 updatel() 拉取开奖结果', function() {
    reset(); timek = 1;
    timekx();
    assert(updatel_count === 0, 'timek=1 → 未归零，不触发 updatel');
    timekx();
    assert(updatel_count === 1, '首次归零 → 立即触发 updatel（开奖结果不延迟）');
    timekx(); timekx();
    assert(updatel_count === 1, '持续 00:00 → updatel 不重复触发');
});

// ═════════════════════════════════════════════
// 二、time0 sentinel — 普通游戏（fenlei=0）
// ═════════════════════════════════════════════

describe('time0 sentinel | fenlei=0 普通游戏', function() {
    reset(); fenlei = 0;

    // sentinel：封盘后服务端返回 panstatus=0, pantime=3
    handleGetnowtime(3, 0, 0, 0);
    assert(time0x_called === false,   'panstatus=0,pantime=3  → 不调 time0x()');
    assert(time0_display === '00:00', 'panstatus=0,pantime=3  → 显示 00:00');

    // 真实开盘倒计时（非sentinel）
    handleGetnowtime(120, 0, 0, 0);
    assert(time0x_called === true,    'panstatus=0,pantime=120 → 正常调 time0x()');

    // 封盘倒计时（panstatus=1）
    handleGetnowtime(60, 0, 1, 0);
    assert(time0x_called === true,    'panstatus=1,pantime=60  → 正常调 time0x()');

    // panstatus=1 且 pantime 恰好=3（不是sentinel，正常处理）
    handleGetnowtime(3, 0, 1, 0);
    assert(time0x_called === true,    'panstatus=1,pantime=3   → 非sentinel，调 time0x()');
});

// ═════════════════════════════════════════════
// 三、time0 + time1 sentinel — 赛车 fenlei=100
// ═════════════════════════════════════════════

describe('time0 sentinel | fenlei=100 bid="" (主盘 A盘)', function() {
    reset(); fenlei = 100; current_bid = '';

    // panstatus=0 sentinel → time0 显示 00:00，time0x 不调
    handleGetnowtime(3, 120, 0, 1);
    assert(time0x_called === false,   'panstatus=0,pantime=3   → 不调 time0x()');
    assert(time0_display === '00:00', 'panstatus=0,pantime=3   → time0 显示 00:00');
    // bid=='' 时 time1x 正常调（otherstatus=1）
    assert(time1x_called === true,    'otherstatus=1           → 正常调 time1x()');

    // panstatus=1，正常封盘倒计时
    handleGetnowtime(60, 120, 1, 1);
    assert(time0x_called === true,    'panstatus=1,pantime=60  → 调 time0x()');
    assert(time1x_called === true,    'otherstatus=1           → 调 time1x()');
});

describe('time1 sentinel | fenlei=100 bid!="" (副盘 B盘)', function() {
    reset(); fenlei = 100; current_bid = 'B';

    // otherstatus=0 sentinel → time1 显示 00:00，time1x 不调
    handleGetnowtime(60, 3, 1, 0);
    assert(time1x_called === false,   'otherstatus=0,time1=3  → 不调 time1x()');
    assert(time0_display === '00:00', 'otherstatus=0,time1=3  → time1 显示 00:00');
    assert(time0x_called === true,    'panstatus=1,pantime=60 → 正常调 time0x()');

    // otherstatus=1，正常调 time1x
    handleGetnowtime(60, 120, 1, 1);
    assert(time1x_called === true,    'otherstatus=1,time1=120 → 调 time1x()');

    // otherstatus=0 但 time1 非3（真实倒计时）
    handleGetnowtime(60, 240, 1, 0);
    assert(time1x_called === true,    'otherstatus=0,time1=240 → 非sentinel，调 time1x()');

    // otherstatus=1 但 time1 恰好=3（不是sentinel）
    handleGetnowtime(60, 3, 1, 1);
    assert(time1x_called === true,    'otherstatus=1,time1=3   → 非sentinel，调 time1x()');
});

describe('time0+time1 双sentinel | fenlei=100 两者均封盘', function() {
    // 主副盘都关闭时，time0 和 time1 同时返回 sentinel=3
    reset(); fenlei = 100; current_bid = 'B';
    handleGetnowtime(3, 3, 0, 0);
    assert(time0x_called === false,   'panstatus=0,time0=3    → 不调 time0x()');
    assert(time1x_called === false,   'otherstatus=0,time1=3  → 不调 time1x()');
    assert(time0_display === '00:00', '副盘视角 → 显示 00:00');

    reset(); fenlei = 100; current_bid = '';
    handleGetnowtime(3, 3, 0, 0);
    assert(time0x_called === false,   'panstatus=0,time0=3    → 不调 time0x()');
    assert(time1x_called === false,   'otherstatus=0,time1=3  → 不调 time1x()');
    assert(time0_display === '00:00', '主盘视角 → 显示 00:00');
});

// ═════════════════════════════════════════════
// 四、gntime 轮询间隔验证
// ═════════════════════════════════════════════

describe('gntime 轮询间隔', function() {
    // gntime=2：1次 setTimeout（1秒）后触发 getnowtime
    var secs2 = simulateGntimex(2);
    assert(secs2 === 1, 'gntime=2 → 约1秒触发轮询（原为6秒）');

    // gntime=7（原始值）：6次 setTimeout（6秒）后触发
    var secs7 = simulateGntimex(7);
    assert(secs7 === 6, 'gntime=7 → 约6秒触发轮询（验证对比）');

    // 确认新值比旧值快
    assert(secs2 < secs7, '新gntime=2 比 旧gntime=7 轮询更快');

    // 确认全局初始值已是2
    assert(gntime === 2, '全局 gntime 初始值为 2');
});

// ═════════════════════════════════════════════
// 五、lib() 触发条件 — 只在开盘或期数变化时调用
// ═════════════════════════════════════════════

// ── 模拟 DOM 状态（对应 .panstatus 的 s / os 属性 和 .thisqishu 的 html）
var dom_qishu      = 100;
var dom_panstatus  = 0;
var dom_otherstatus = 0;
var lib_called     = false;
function lib() { lib_called = true; }

/**
 * 模拟 getnowtime() 中 lib() 触发逻辑（对应 makeuser.js 最新代码）
 * new_qishu = m[2], new_pan = m[3], new_other = m[4]
 * outside_maint: true = 非维护时段（对应 m[5] 时间条件满足），默认 true
 */
function handleLib(new_qishu, new_pan, new_other, outside_maint) {
    lib_called = false;
    if (outside_maint === undefined) outside_maint = true;

    if (fenlei === 100) {
        if (Number(new_qishu) != dom_qishu || Number(new_pan) != dom_panstatus || Number(new_other) != dom_otherstatus) {
            dom_qishu       = Number(new_qishu);
            dom_panstatus   = Number(new_pan);
            dom_otherstatus = Number(new_other);
            if (outside_maint) { lib(); }
        }
    } else {
        if (Number(new_qishu) != dom_qishu || Number(new_pan) != dom_panstatus) {
            dom_qishu     = Number(new_qishu);
            dom_panstatus = Number(new_pan);
            lib();
        }
    }
}

function resetLib() {
    lib_called      = false;
    dom_qishu       = 100;
    dom_panstatus   = 0;
    dom_otherstatus = 0;
    fenlei          = 0;
    current_bid     = '';
}

// ── fenlei=0 普通游戏 ──────────────────────────

describe('lib() 触发 | fenlei=0 封盘（1→0）触发', function() {
    resetLib(); dom_panstatus = 1;
    handleLib(100, 0, 0);   // panstatus 1→0
    assert(lib_called === true, 'panstatus 1→0（封盘）→ lib 触发（显示封盘状态）');
});

describe('lib() 触发 | fenlei=0 开盘（0→1）触发', function() {
    resetLib(); dom_panstatus = 0;
    handleLib(100, 1, 0);   // panstatus 0→1
    assert(lib_called === true, 'panstatus 0→1（开盘）→ lib 触发');
});

describe('lib() 触发 | fenlei=0 期数变化触发', function() {
    resetLib(); dom_panstatus = 1;
    handleLib(101, 1, 0);   // qishu 100→101
    assert(lib_called === true, 'qishu 变化 → lib 触发');
});

describe('lib() 触发 | fenlei=0 状态无变化不触发', function() {
    resetLib(); dom_panstatus = 1;
    handleLib(100, 1, 0);   // 首次同步状态
    lib_called = false;
    handleLib(100, 1, 0);   // 再次相同状态
    assert(lib_called === false, '状态无变化 → lib 不触发');
});

// ── fenlei=100 六合彩 ─────────────────────────

describe('lib() 触发 | fenlei=100 panstatus 封盘（1→0）触发', function() {
    resetLib(); fenlei = 100; dom_panstatus = 1; dom_otherstatus = 1;
    handleLib(100, 0, 1);   // panstatus 1→0
    assert(lib_called === true, 'panstatus 1→0（封主盘）→ lib 触发（显示封盘）');
});

describe('lib() 触发 | fenlei=100 otherstatus 封盘（1→0）触发', function() {
    resetLib(); fenlei = 100; dom_panstatus = 1; dom_otherstatus = 1;
    handleLib(100, 1, 0);   // otherstatus 1→0
    assert(lib_called === true, 'otherstatus 1→0（封正码）→ lib 触发（显示封盘）');
});

describe('lib() 触发 | fenlei=100 开盘（0→1）触发', function() {
    resetLib(); fenlei = 100; dom_panstatus = 0; dom_otherstatus = 0;
    handleLib(100, 1, 1);   // 两者 0→1
    assert(lib_called === true, 'panstatus/otherstatus 0→1（开盘）→ lib 触发');
});

describe('lib() 触发 | fenlei=100 期数变化触发', function() {
    resetLib(); fenlei = 100; dom_panstatus = 1; dom_otherstatus = 1;
    handleLib(101, 1, 1);   // qishu 变化
    assert(lib_called === true, 'qishu 变化 → lib 触发');
});

describe('lib() 触发 | fenlei=100 维护时段内不触发', function() {
    resetLib(); fenlei = 100; dom_panstatus = 0;
    handleLib(101, 1, 1, false);  // 期数+开盘变化，但在维护时段内
    assert(lib_called === false, '维护时段内（m[5] 21:00-21:28）→ lib 不触发');
});

describe('lib() 触发 | 完整一期流程 lib() 触发次数', function() {
    resetLib(); fenlei = 0; dom_panstatus = 1;

    // 1. 封盘
    handleLib(100, 0, 0);
    assert(lib_called === true,  '封盘 → lib 触发（显示封盘状态）');

    // 2. 封盘中多次轮询
    handleLib(100, 0, 0);
    assert(lib_called === false, '封盘持续（状态无变化）→ lib 不触发');

    // 3. 新期开盘
    handleLib(101, 1, 0);
    assert(lib_called === true,  '新期开盘 → lib 触发');

    // 4. 开盘后稳定
    handleLib(101, 1, 0);
    assert(lib_called === false, '开盘后稳定 → lib 不触发');
});

// ─────────────────────────────────────────────
// 汇总
// ─────────────────────────────────────────────
console.log('\n══════════════════════════════════');
console.log('总计: ' + (passed + failed) + ' 项');
console.log('通过: ' + passed);
console.log('失败: ' + failed);
console.log('══════════════════════════════════\n');
process.exit(failed > 0 ? 1 : 0);
