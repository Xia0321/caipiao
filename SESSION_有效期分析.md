# 138项目 $_SESSION 存储有效期分析报告

## 一、当前 Session 处理方式

### 1.1 实际使用的 Session 机制
- **入口文件**: `index.php` → 引入 `global/session.class.php`
- **关键发现**: `session.class.php` 中虽然定义了自定义的 `MySessionHandler` 类，但 `session_set_save_handler()` 被**注释掉了**（第95行）
- **结论**: 项目**实际使用的是 PHP 默认的 Session 处理机制**（文件系统存储），而不是自定义的数据库存储

```php
// global/session.class.php 第95行
//session_set_save_handler($handler, true);  // 被注释，未启用
session_start();
```

### 1.2 自定义 SessionHandler 状态
- 自定义的 `MySessionHandler` 类已定义但**未启用**
- 如果启用，会将 Session 数据存储到数据库表 `x_session` 中
- `gc()` 函数中的清理逻辑：`($maxlifetime + lastvisit) < $timeNow`

---

## 二、Session 有效期配置分析

### 2.1 配置文件中的设置

#### `data/config.inc.php` (第11行)
```php
$SESS_LIFE = 14400;  // 14400秒 = 4小时
```
- **状态**: 已定义但**未被使用**
- **说明**: 这个变量在 `session.class.php` 中没有被引用
- **用途**: 原本设计用于自定义 SessionHandler 的有效期设置

#### `data/session.php` (第3行)
```php
//ini_set('session.gc_maxlifetime', 28800);  // 28800秒 = 8小时（被注释）
```
- **状态**: **被注释，未生效**
- **说明**: 如果启用，会将 Session 垃圾回收时间设置为 8 小时

### 2.2 PHP 默认配置

由于项目没有显式设置 Session 相关参数，实际使用的是 **PHP 默认配置**：

| 配置项 | 默认值 | 说明 |
|--------|--------|------|
| `session.gc_maxlifetime` | **1440秒** (24分钟) | Session 数据在服务器上的最大存活时间 |
| `session.cookie_lifetime` | **0** (浏览器关闭时过期) | Session Cookie 的生命周期 |
| `session.gc_probability` | **1** | 垃圾回收执行概率的分子 |
| `session.gc_divisor` | **100** | 垃圾回收执行概率的分母（实际概率 = 1/100 = 1%） |

---

## 三、实际 Session 有效期

### 3.1 服务器端有效期
- **实际值**: **1440秒（24分钟）** - PHP 默认的 `session.gc_maxlifetime`
- **触发机制**: 
  - Session 数据在服务器上（文件系统）保存 24 分钟后会被垃圾回收机制清理
  - 垃圾回收不是每次请求都执行，而是按概率执行（默认 1% 的概率）

### 3.2 客户端 Cookie 有效期
- **实际值**: **0** - 浏览器关闭时 Cookie 失效
- **说明**: Session Cookie（PHPSESSID）在浏览器关闭后会被删除

### 3.3 业务层面的检查
项目中有额外的业务逻辑检查：

#### `uxj/checklogin.php` (第48行)
```php
if($time-$msql->f('savetime')>90 & ...) {
    // 更新在线状态时间戳
}
```
- **说明**: 检查用户最后活动时间，如果超过 90 秒且满足条件，会更新在线状态

---

## 四、关键代码位置

### 4.1 Session 初始化
- **文件**: `index.php` → `global/session.class.php`
- **行号**: 96行 - `session_start()`

### 4.2 Session 配置定义（未使用）
- **文件**: `data/config.inc.php`
- **行号**: 11行 - `$SESS_LIFE = 14400;`

### 4.3 Session 配置（被注释）
- **文件**: `data/session.php`
- **行号**: 3行 - `//ini_set('session.gc_maxlifetime', 28800);`

### 4.4 自定义 SessionHandler（未启用）
- **文件**: `global/session.class.php`
- **行号**: 95行 - `//session_set_save_handler($handler, true);`

---

## 五、总结与建议

### 5.1 当前状态总结

| 项目 | 配置值 | 实际生效值 | 状态 |
|------|--------|-----------|------|
| 服务器端 Session 有效期 | 未设置 | **1440秒（24分钟）** | PHP 默认值 |
| 客户端 Cookie 有效期 | 未设置 | **0（浏览器关闭失效）** | PHP 默认值 |
| 自定义 SessionHandler | 已定义 | **未启用** | 被注释 |
| `$SESS_LIFE` 变量 | 14400秒 | **未使用** | 定义了但未引用 |

### 5.2 实际有效期
- **Session 数据在服务器上的最大存活时间**: **24分钟**（1440秒）
- **Session Cookie 有效期**: **浏览器会话期间**（关闭浏览器即失效）

### 5.3 建议

如果需要修改 Session 有效期，有以下几种方式：

#### 方式1: 启用被注释的配置（推荐）
在 `data/session.php` 中取消注释：
```php
ini_set('session.gc_maxlifetime', 28800);  // 8小时
```

#### 方式2: 在 session_start() 之前设置
在 `global/session.class.php` 的 `session_start()` 之前添加：
```php
ini_set('session.gc_maxlifetime', 14400);  // 4小时（使用 $SESS_LIFE 的值）
session_start();
```

#### 方式3: 启用自定义 SessionHandler
如果需要使用数据库存储 Session，可以：
1. 取消注释 `session_set_save_handler($handler, true);`
2. 在 `gc()` 函数中使用 `$SESS_LIFE` 变量替代 `$maxlifetime`

---

## 六、注意事项

1. **垃圾回收机制**: Session 的清理不是实时的，而是按概率执行（默认 1%），所以过期 Session 可能不会立即被删除
2. **Cookie 安全性**: 当前使用默认的 Session Cookie 设置，建议根据安全需求配置 `session.cookie_httponly`、`session.cookie_secure` 等参数
3. **数据库存储**: 如果启用自定义 SessionHandler，需要确保数据库表 `x_session` 结构正确

---

**分析日期**: 2026-02-10  
**分析文件**: `138` 项目目录
