/**
 * SSH 部署脚本：将本地修改同步到服务器
 */
const { Client } = require('ssh2');
const fs = require('fs');
const path = require('path');

const CONFIG = {
    host: '154.219.120.254',
    port: 22,
    username: 'root',
    password: 'Dc0sZlar6k78',
    readyTimeout: 15000,
};

const LOCAL_BASE = path.resolve(__dirname, '..');

function exec(conn, cmd) {
    return new Promise((resolve, reject) => {
        conn.exec(cmd, (err, stream) => {
            if (err) return reject(err);
            let out = '', errOut = '';
            stream.on('data', d => out += d);
            stream.stderr.on('data', d => errOut += d);
            stream.on('close', (code) => {
                if (code !== 0 && errOut) console.error('[STDERR]', errOut.trim());
                resolve(out.trim());
            });
        });
    });
}

function uploadFile(conn, localPath, remotePath) {
    return new Promise((resolve, reject) => {
        conn.sftp((err, sftp) => {
            if (err) return reject(err);
            sftp.fastPut(localPath, remotePath, (err) => {
                sftp.end();
                if (err) return reject(err);
                resolve();
            });
        });
    });
}

async function main() {
    const conn = new Client();

    await new Promise((resolve, reject) => {
        conn.on('ready', resolve).on('error', reject).connect(CONFIG);
    });

    console.log('✓ SSH 连接成功');

    // 查找项目路径
    const findResult = await exec(conn, 'find /var/www /www /home /srv -name "task_notify_mch.php" 2>/dev/null | head -3');
    console.log('查找项目路径:', findResult);

    if (!findResult) {
        console.error('× 未找到项目路径，请手动指定');
        conn.end();
        return;
    }

    // 优先选择 /www/wwwroot/ 下的正式路径，排除回收站备份
    const lines = findResult.split('\n').map(l => l.trim()).filter(Boolean);
    const remoteFile = lines.find(l => l.includes('/www/wwwroot/')) || lines[0];
    const remoteBase = remoteFile.replace('/task_notify_mch.php', '');
    console.log('✓ 项目路径:', remoteBase);

    // 需要上传的文件列表 [本地相对路径, 远程相对路径]
    const files = [
        ['task_notify_mch.php',                          'task_notify_mch.php'],
        ['mxj/makelib.php',                              'mxj/makelib.php'],
        ['mxjs/makelib.php',                             'mxjs/makelib.php'],
        ['js/default/jsmxj/makevuser.js',                'js/default/jsmxj/makevuser.js'],
        ['js/default/jsmxj/ssuser.js',                   'js/default/jsmxj/ssuser.js'],
        ['js/default/jsmxj/lhcuser.js',                  'js/default/jsmxj/lhcuser.js'],
    ];

    for (const [localRel, remoteRel] of files) {
        const localFull  = path.join(LOCAL_BASE, localRel);
        const remoteFull = remoteBase + '/' + remoteRel;
        // 备份远端文件
        await exec(conn, `cp -f "${remoteFull}" "${remoteFull}.bak" 2>/dev/null || true`);
        await uploadFile(conn, localFull, remoteFull);
        console.log(`✓ 上传: ${remoteRel}`);
    }

    console.log('\n所有文件已同步到服务器。');
    conn.end();
}

main().catch(e => {
    console.error('× 错误:', e.message);
    process.exit(1);
});
