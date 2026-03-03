/**
 * 从服务器拉取所有文件到本地
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
const REMOTE_BASE = '/www/wwwroot/154.219.120.254';
const TAR_REMOTE = '/tmp/project_pull.tar.gz';
const TAR_LOCAL  = path.join(LOCAL_BASE, 'tools', '_project_pull.tar.gz');

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

function downloadFile(conn, remotePath, localPath) {
    return new Promise((resolve, reject) => {
        conn.sftp((err, sftp) => {
            if (err) return reject(err);
            sftp.fastGet(remotePath, localPath, (err) => {
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

    // 在服务器打包（排除 .git / logs / node_modules 等）
    console.log('正在服务器端打包...');
    await exec(conn,
        `tar czf ${TAR_REMOTE} -C ${REMOTE_BASE} . ` +
        `--exclude='./.git' ` +
        `--exclude='./logs' ` +
        `--exclude='./node_modules' ` +
        `--exclude='./.idea' ` +
        `--exclude='./tools/_project_pull.tar.gz' ` +
        `2>/dev/null`
    );
    console.log('✓ 打包完成');

    // 下载 tar
    console.log('正在下载压缩包...');
    await downloadFile(conn, TAR_REMOTE, TAR_LOCAL);
    console.log('✓ 下载完成:', TAR_LOCAL);

    // 清理服务器临时文件
    await exec(conn, `rm -f ${TAR_REMOTE}`);
    conn.end();
    console.log('✓ SSH 已断开');
}

main().catch(e => {
    console.error('× 错误:', e.message);
    process.exit(1);
});
