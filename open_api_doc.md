# 开放接口接入文档（open_api.php）

## 1. 概述

`open_api.php` 提供第三方接入能力，包含：**快速注册**、**快速登录**、**游戏入口（免登跳转）**。  
所有接口除 `entry` 外均返回 JSON；`entry` 为页面重定向。

| 项目 | 说明 |
|------|------|
| 接口地址 | `https://您的域名/138/open_api.php`（以实际部署路径为准） |
| 验签密钥 | 与服务端约定，当前在代码中为 `OPEN_API_SECRET`，**上线前务必修改** |
| 字符编码 | UTF-8 |

---

## 2. 快速注册（quick_register）

为商户创建会员账号（写入 `x_user`，并标记 `is_api=1`、`mch_code`，其中 `mch_code` 继承自对应**代理账号**的配置）。

### 请求

- **方法**：`POST`
- **参数**：`application/x-www-form-urlencoded` 或 `multipart/form-data`

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| action   | string | 是 | 固定值：`quick_register` |
| username | string | 是 | 登录名，1~11 位，首字符为字母或数字，其余可为字母、数字、`.`、`_`（提交后会转大写） |
| password | string | 是 | 登录密码（明文，服务端会加密存储） |
| mch_code | string | 是 | **商户号**：对应后台代理的登录名（`x_user.username`，且该用户需为代理、`is_api=1`） |
| name     | string | 否 | 昵称/姓名，缺省时用 username |
| tel      | string | 否 | 手机号 |
| qq       | string | 否 | QQ |
| sign     | string | 否 | （推荐）请求签名，用于服务端验签，算法见下文 |

### 响应（JSON）

**成功：**

```json
{
  "code": 0,
  "msg": "ok",
  "userid": "会员ID",
  "username": "登录名"
}
```

**失败：**

| code | msg | 说明 |
|------|-----|------|
| 1 | 缺少 username / password / mch_code(商户号) | 必填参数缺失 |
| 2 | 用户名格式不正确 | 不符合规则（首字符 + 后续 1~10 位） |
| 3 | 用户名已存在 | 该 username 已被注册 |
| 4 | 系统未配置默认推荐人 | 后台网站配置未设置默认推荐人 |
| 5 | 默认推荐人不存在 | 推荐人账号不存在 |
| 6 | 注册写入失败 | 数据库写入异常 |
| 7 | 商户不存在或未开启 API | 未在后台找到 `username=mch_code` 且 `ifagent=1`、`is_api=1` 的代理账号 |
| 8 | 商户未配置 mch_code / mch_secret | 代理账号未设置签名编码或密钥 |
| 9 | 商户验签失败 | 提供了 sign，但与服务端按约定算法计算结果不一致 |

#### 2.1.1 商户验签（可选但推荐）

当接入方希望对快速注册请求做来源校验时，可在请求中携带 `sign` 字段：

1. 平台根据 `mch_code`（商户号）在 `x_user` 中查找对应代理：  
   `username = mch_code` 且 `ifagent=1` 且 `is_api=1` 且 `status=1`  
   取出该代理配置的 `mch_secret`。
2. 将除 `sign` 外的所有参数组成一个数组 `payload`，建议包含至少：
   - `action`
   - `username`
   - `password`
   - `mch_code`（商户号）
   - 其他业务字段（如 `name`、`tel`、`qq` 等）
3. 对 `payload` 按 key 做 **升序排序**，再整体做 JSON 序列化（UTF-8，`JSON_UNESCAPED_UNICODE`），记为 `json`。
4. 计算：

```text
sign = md5(mch_secret + json)
```

5. 将该 `sign` 一并随请求提交。  
   服务端会用相同算法计算期望值，若不一致则返回 `code=9`。

---

## 3. 快速登录（quick_login）

校验账号密码后，返回带**签名参数**的游戏入口 URL，用于 H5/客户端跳转并免登进入游戏列表。

### 请求

- **方法**：`POST`
- **参数**：同上（form-urlencoded 或 form-data）

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| action | string | 是 | 固定值：`quick_login` |
| username | string | 是 | 登录名（与注册时一致，大小写不敏感） |
| password | string | 是 | 登录密码（明文） |
| gid | string | 否 | 游戏ID。传入后用户点击免登链接将**直接进入该游戏的投注页面**，不传则进入用户上次使用的游戏或系统默认游戏 |

### 响应（JSON）

**成功：**

```json
{
  "code": 0,
  "msg": "ok",
  "game_url": "https://域名/open_api.php?action=entry&token=xxxx",
  "device": "mobile 或 pc",
  "expire_at": 1773367286
}
```

- **game_url**：带 token 的免登链接，有效期 5 分钟。将此链接给用户在浏览器中打开即可自动登录。
- **device**：根据当前请求的 User-Agent 判断，`mobile` 表示移动端，`pc` 表示 PC。
- **expire_at**：链接过期时间戳（秒），超过此时间需重新调用获取。

**失败：**

| code | msg | 说明 |
|------|-----|------|
| 1 | 缺少 username / password | 必填参数缺失 |
| 2 | 账号或密码错误 | 用户名或密码不正确（仅针对普通会员账号） |
| 3 | 账号已禁用 | 该账号 status=0 |
| 4 | 密码错误次数过多 | 连续错误 5 次被锁定，需联系代理重置 |

---

## 4. 游戏入口（entry）

用于**免登进入游戏列表**：使用 `quick_login` 返回的 `game_url` 在浏览器中打开，服务端校验 `userid`、`ts`、`sign` 后写 session 并 302 跳转到对应游戏列表页。

### 请求

- **方法**：`GET`
- **参数**：全部放在 URL 查询串

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| action | string | 是 | 固定值：`entry` |
| userid | string | 是 | 会员 userid |
| ts | string | 是 | 时间戳（秒），与当前服务器时间差不能超过 **300 秒（5 分钟）** |
| sign | string | 是 | 签名，见下方算法 |

### 签名算法

- **拼接串**：`密钥 + userid + ts`（字符串直接拼接，无分隔符）
- **密钥**：与服务端约定的 `OPEN_API_SECRET`（代码中 `open_api.php` 内定义）
- **签名**：对拼接串做 **MD5**（32 位小写）

示例（密钥为 `open_api_sign_key_please_change`，userid=`123`，ts=`1700000000`）：

```
拼接：open_api_sign_key_please_change1231700000000
sign = md5(拼接)
```

客户端生成 sign 后，将 `action=entry&userid=xxx&ts=xxx&sign=xxx` 拼到 `open_api.php` 的 URL 上即可。

### 服务端校验逻辑

1. 缺少 `userid` / `ts` / `sign` → 重定向到首页 `/`
2. `ts` 非数字或与服务器时间差 > 300 秒 → 重定向到首页
3. `sign` 与按上述算法计算出的值不一致 → 重定向到首页
4. 根据 `userid` 查不到对应用户或用户已禁用 → 重定向到首页  
通过校验后：写入在线表、写 session，再 302 跳转。

### 跳转规则

| 设备类型 | 跳转地址 |
|----------|----------|
| 移动端（根据 User-Agent 判断） | `/uxj/xy.php`（手机版游戏列表） |
| PC | `/mxj/xy.php`（PC 版游戏列表） |

**注意**：`entry` 不返回 JSON，只做 302 重定向；失败时统一重定向到站点首页 `/`。

---

## 5. 接入流程示例

### 5.1 注册

1. 调用 `quick_register`，传入 `username`、`password`、`mch_code`（商户号，对应后台代理登录名）等，可选携带 `sign` 做验签。
2. 若 `code=0`，保存返回的 `userid`、`username`，用于后续登录。

### 5.2 登录并进入游戏

1. 调用 `quick_login`，传入 `username`、`password`，可选传入 `gid`（游戏ID）。
2. 若 `code=0`，使用返回的 `game_url`：
   - **H5**：直接 `window.location.href = game_url` 或 `<a href="game_url">进入游戏</a>`。
   - **App**：用 WebView 打开 `game_url`。
3. 用户被带到 `entry`，验签通过后自动跳转到 mxj（手机）或 uxj（PC）游戏页面，无需再输账号密码。
4. 如果传了 `gid`，用户进入后将**直接显示对应游戏的投注界面**；不传则进入默认游戏。

### 5.3 自行拼接 entry 链接（可选）

若您已有合法 `userid` 和密钥，可自行生成 entry 链接：

1. 取当前时间戳 `ts`（秒）。
2. 计算 `sign = md5(OPEN_API_SECRET + userid + ts)`。
3. 请求：  
   `GET https://域名/138/open_api.php?action=entry&userid=xxx&ts=xxx&sign=xxx`  
   注意链接有效期 5 分钟。

---

## 6. 注意事项

1. **密钥**：`OPEN_API_SECRET` 需与接入方约定，并**不要**暴露在前端或公开仓库；上线前务必修改默认值。
2. **时间**：entry 的 `ts` 与服务器时间差不得超过 5 分钟，请保证接入方服务器或设备时间同步。
3. **商户**：快速注册依赖 `mch_code`，请在后台「商户管理」中先配置对应商户；任务回调等会使用该商户配置。
4. **账号类型**：quick_login 仅针对普通会员（`ifagent=0` 且 `ifson=0`），运营商/子账号需走其他登录逻辑。
5. **编码**：请求与响应均使用 UTF-8。

---

## 7. 错误码汇总

| code | 接口 | 说明 |
|------|------|------|
| -1 | 任意 | action 无效或未传 |
| 0 | 全部 | 成功（entry 无 JSON，直接跳转） |
| 1 | quick_register | 缺少必填参数 |
| 1 | quick_login | 缺少 username/password |
| 2 | quick_register | 用户名格式错误 |
| 2 | quick_login | 账号或密码错误 |
| 3 | quick_register | 用户名已存在 |
| 3 | quick_login | 账号已禁用 |
| 4 | quick_register | 系统未配置默认推荐人 |
| 5 | quick_register | 默认推荐人不存在 |
| 6 | quick_register | 注册写入失败 |

entry 校验失败时不返回 JSON，直接 302 到首页。

---

## 8. 请求示例

### 8.1 cURL

**快速注册：**

```bash
curl -X POST "https://您的域名/138/open_api.php" \
  -d "action=quick_register" \
  -d "username=testuser01" \
  -d "password=123456" \
  -d "mch_code=MCH001" \
  -d "name=测试用户"
```

**快速登录（进入默认游戏）：**

```bash
curl -X POST "https://您的域名/open_api.php" \
  -d "action=quick_login" \
  -d "username=testuser01" \
  -d "password=123456"
```

**快速登录（直接进入指定游戏）：**

```bash
curl -X POST "https://您的域名/open_api.php" \
  -d "action=quick_login" \
  -d "username=testuser01" \
  -d "password=123456" \
  -d "gid=172"
```

### 8.2 PHP

```php
// 快速注册
$url = 'https://您的域名/138/open_api.php';
$post = [
    'action'   => 'quick_register',
    'username' => 'testuser01',
    'password' => '123456',
    'mch_code' => 'MCH001',
];
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = json_decode(curl_exec($ch), true);

// 快速登录（可选传 gid 直接进入指定游戏）
$post = ['action' => 'quick_login', 'username' => 'testuser01', 'password' => '123456', 'gid' => '172'];
// ... 同上 POST，若 code=0 则 $res['game_url'] 即为免登链接，用户点击直接进入 gid=172 的游戏
```

### 8.3 JavaScript（前端跳转）

```javascript
// 登录成功后跳转游戏（使用 quick_login 返回的 game_url）
fetch('https://您的域名/138/open_api.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
  body: 'action=quick_login&username=testuser01&password=123456&gid=172'
})
  .then(r => r.json())
  .then(data => {
    if (data.code === 0) {
      window.location.href = data.game_url;  // 跳转即免登
    } else {
      alert(data.msg);
    }
  });
```

### 8.4 自建 entry 签名（PHP）

```php
$secret = '与服务端约定的OPEN_API_SECRET';
$userid = '会员userid';
$ts = time();
$sign = md5($secret . $userid . $ts);
$entry_url = "https://您的域名/138/open_api.php?action=entry&userid=" . urlencode($userid) . "&ts=$ts&sign=$sign";
// 将该 URL 给用户打开即可免登
```

---

## 9. 快速参考

| action | 方法 | 用途 |
|--------|------|------|
| quick_register | POST | 商户代注册会员 |
| quick_login | POST | 登录并获取免登链接 |
| entry | GET | 免登跳转（userid+ts+sign） |

**entry 签名**：`sign = md5(密钥 + userid + ts)`，ts 为当前秒级时间戳，有效期 5 分钟。

---

# 第二部分：商户回调任务（task_notify_mch.php）

## 10. 概述

`task_notify_mch.php` 用于平台向**商户**推送或拉取数据：检索所有 `is_api=1` 的用户，直接从 `x_user` 表读取该用户的 `mch_code`、`callback_url`、`mch_secret` 等字段，再按「回调根地址 + 方法名」向商户发起 POST 请求（不再使用 `x_mchs` 表）。

| 项目 | 说明 |
|------|------|
| 脚本地址 | `https://您的域名/138/task_notify_mch.php`（以实际部署为准） |
| 请求方式 | 平台 → 商户：POST，Body 为 JSON，可选 sign |
| 商户配置 | 后台「商户管理」中配置 callback_url、mch_secret（回调密钥） |
| 字符编码 | UTF-8 |

**URL 规则**：请求地址 = `{callback_url}/{方法名}`。  
例如 callback_url 为 `https://www.example.com/cb`，则：
- 获取余额：`https://www.example.com/cb/getBalance`
- 下注扣款：`https://www.example.com/cb/changeBalance`
- 注单结算：`https://www.example.com/cb/settleOrder`
- 取消注单：`https://www.example.com/cb/cancelOrder`

**签名规则**（平台发往商户时，若配置了 mch_secret）：  
对**除 sign 外**的整包 payload 做 JSON 序列化（UTF-8、不转义 Unicode），再计算 `sign = md5(mch_secret + json字符串)`，将 `sign` 放入同一 JSON 中一起 POST。

---

## 11. 平台侧 HTTP 触发（task_notify_mch.php?action=xxx）

以下为直接访问 `task_notify_mch.php` 时通过 `action` 触发的逻辑；不传 `action` 或 `action=notify` 时执行默认的「按用户汇总游戏数据推送到 callback_url」（见第 15 节）。

| action | 方法 | 说明 |
|--------|------|------|
| getBalance | GET/POST | 拉取所有 is_api=1 用户余额：向各商户 getBalance 发请求，若商户返回 balance/money/kmoney 则回写本地 |
| changeBalance | POST | 单用户扣款/加款通知：传 userid, amount, type(deduct\|add)，可选 orders（JSON 数组） |
| settleNotify | GET/POST | 按期号、彩种推送已结算注单：传 qishu, gid，平台查 kk=1 的注单按用户分组推送到各商户 settleOrder |
| cancelOrder | GET/POST | 取消注单通知：传 ids=1,2,3 或 codes=code1,code2，平台查注单后按用户分组推送到各商户 cancelOrder |

### 11.1 action=getBalance

- **参数**：无（或仅 action=getBalance）
- **行为**：对每个 is_api=1 且 mch_code 有效的用户，向 `{callback_url}/getBalance` POST 请求，body 含 userid, username, mch_code, notify_time（及可选 sign）。若商户返回 JSON 中含 balance 或 money/kmoney，则回写该用户在本平台的 money/kmoney。
- **响应**：`{"code":0,"msg":"getBalance done"}`

### 11.2 action=changeBalance

- **参数**：userid（必填）, amount（必填，正数）, type（可选，deduct\|add，默认 deduct）, orders（可选，JSON 数组，本次下注详情）
- **行为**：向该用户所属商户的 `{callback_url}/changeBalance` 发起 POST，body 含 userid, username, mch_code, amount, type, notify_time，若有 orders 则一并传入（及可选 sign）。
- **响应**：`{"code":0,"msg":"ok"}` 或 `{"code":1,"msg":"fail"}`

### 11.3 action=settleNotify

- **参数**：qishu（必填）, gid（必填）
- **行为**：查询 x_lib 中 qishu、gid、kk=1 的注单，按 userid 分组；对其中 is_api=1 且 mch_code 有效的用户，向该商户的 `{callback_url}/settleOrder` POST 该用户的本期已结算注单列表（含 valid_je、win_loss 等）。
- **响应**：`{"code":0,"msg":"settleNotify done"}`；缺参数时 `{"code":1,"msg":"missing qishu or gid"}`

### 11.4 action=cancelOrder

- **参数**：ids 或 codes 二选一。ids=注单id逗号分隔（如 1,2,3）；codes=注单code逗号分隔。
- **行为**：按 id 或 code 查询 x_lib，按 userid 分组；对 is_api=1 用户向该商户的 `{callback_url}/cancelOrder` POST 被取消的注单列表。
- **响应**：`{"code":0,"msg":"cancelOrder done"}`；缺或无效参数时 `{"code":1,"msg":"missing ids or codes"}` 等。

---

## 12. 商户需实现的接口（接收端）

商户在后台配置的 callback_url 对应的服务需按「根地址 + 方法名」提供以下接口，接收平台 POST 的 JSON Body（Content-Type: application/json; charset=utf-8），并可根据 mch_secret 校验 sign。

### 12.1 getBalance（平台拉取用户余额）

- **URL**：`{callback_url}/getBalance`
- **方法**：POST，Body 为 JSON
- **请求体示例**：

```json
{
  "userid": "会员ID",
  "username": "登录名",
  "mch_code": "商户编码",
  "notify_time": "2025-02-09 12:00:00",
  "sign": "可选，md5(mch_secret+除sign外的json)"
}
```

- **商户响应**：建议返回 JSON，包含以下之一以便平台回写本地余额：
  - `balance`：总余额（或与 money 二选一）
  - `money`：低频彩余额
  - `kmoney`：快开彩余额  
  平台若收到这些字段会更新对应用户的 x_user 表。

### 12.2 changeBalance（下注扣款 / 加款通知）

- **URL**：`{callback_url}/changeBalance`
- **方法**：POST，Body 为 JSON
- **请求体示例**：

```json
{
  "userid": "会员ID",
  "username": "登录名",
  "mch_code": "商户编码",
  "amount": 100.5,
  "type": "deduct",
  "notify_time": "2025-02-09 12:00:00",
  "orders": [
    {
      "tid": "注单tid",
      "gid": "彩种id",
      "qishu": "期号",
      "pid": "玩法id",
      "content": "内容",
      "je": 10.5,
      "gname": "游戏名"
    }
  ],
  "sign": "可选"
}
```

- **说明**：type 为 `deduct` 表示下注扣款，`add` 表示加款（如退款、派奖）。orders 为本笔变动对应的注单列表（下注时由平台带上本次下注详情），商户可按此记账或对账。

### 12.3 settleOrder（注单结算通知）

- **URL**：`{callback_url}/settleOrder`
- **方法**：POST，Body 为 JSON
- **请求体示例**：

```json
{
  "userid": "会员ID",
  "username": "登录名",
  "mch_code": "商户编码",
  "orders": [
    {
      "id": "注单主键",
      "tid": "注单tid",
      "code": "注单code",
      "userid": "会员ID",
      "qishu": "期号",
      "dates": "日期",
      "gid": "彩种id",
      "bid": "大分类",
      "sid": "小分类",
      "cid": "玩法类id",
      "pid": "玩法id",
      "content": "内容",
      "je": 10.5,
      "prize": 0,
      "z": 0,
      "valid_je": 10.5,
      "win_loss": -10.5,
      "time": "下注时间"
    }
  ],
  "notify_time": "2025-02-09 12:00:00",
  "sign": "可选"
}
```

- **说明**：valid_je 为有效投注金额（无效注单 z=7 时为 0）；win_loss = prize - je，表示该注输赢金额。商户可根据 orders 更新己方余额或报表。

### 12.4 cancelOrder（取消注单通知）

- **URL**：`{callback_url}/cancelOrder`
- **方法**：POST，Body 为 JSON
- **请求体示例**：

```json
{
  "userid": "会员ID",
  "username": "登录名",
  "mch_code": "商户编码",
  "orders": [
    {
      "id": "注单主键",
      "tid": "注单tid",
      "code": "注单code",
      "userid": "会员ID",
      "qishu": "期号",
      "dates": "日期",
      "gid": "彩种id",
      "bid": "大分类",
      "sid": "小分类",
      "cid": "玩法类id",
      "pid": "玩法id",
      "content": "内容",
      "je": 10.5,
      "time": "下注时间"
    }
  ],
  "notify_time": "2025-02-09 12:00:00",
  "sign": "可选"
}
```

- **说明**：平台在取消/删除注单后会按用户分组推送至此接口，商户可根据 orders 中的注单信息（如 je）将对应金额加回用户余额。

---

## 13. 回调触发时机（平台侧）

| 回调 | 触发时机 |
|------|----------|
| changeBalance | 用户在下注成功扣款后，由 mxj/makelib.php、uxj/makelib.php 内部调用 mch_notify_change_balance，并传入本次下注的 orders |
| settleOrder | 开奖/结算脚本在完成某期某彩种结算后，调用 task_notify_mch.php?action=settleNotify&qishu=期号&gid=彩种 |
| cancelOrder | 后台或接口在取消/删除注单后，调用 task_notify_mch.php?action=cancelOrder&ids=1,2,3 或 &codes=code1,code2 |
| getBalance | 定时任务或手动访问 task_notify_mch.php?action=getBalance，拉取 is_api=1 用户在各商户的余额并回写本地 |

---

## 14. 签名校验（商户端）

商户接收 POST 后，可从 Body 取出除 `sign` 外的全部字段，按与平台相同的顺序序列化为 JSON（UTF-8、不转义 Unicode），再计算 `expect_sign = md5(mch_secret + json)`，与请求体中的 `sign` 比较（建议使用恒定时间比较，如 PHP 的 hash_equals），一致则验签通过。

---

## 15. 默认通知（无 action 或 action=notify）

不传 `action` 或 `action=notify` 时，平台对每个 is_api=1 且 mch_code 有效的用户，向**根地址** callback_url（不拼方法名）POST 一条汇总数据，Body 示例：

```json
{
  "userid": "会员ID",
  "username": "登录名",
  "mch_code": "商户编码",
  "money": 0,
  "kmoney": 0,
  "maxmoney": 0,
  "kmaxmoney": 0,
  "today_count": 5,
  "today_je": 100.5,
  "notify_time": "2025-02-09 12:00:00",
  "sign": "可选，md5(mch_secret+userid+notify_time)"
}
```

用于商户侧汇总展示或对账，与 getBalance/changeBalance/settleOrder/cancelOrder 相互独立。
