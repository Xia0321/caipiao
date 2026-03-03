const { NodeSSH } = require('C:/Users/xiao/AppData/Roaming/npm/node_modules/node-ssh');
const fs = require('fs');
const crypto = require('crypto');
const path = require('path');

const ssh = new NodeSSH();
const local = 'C:/Users/xiao/Downloads/1/138_PNjGC';
const remote = '/www/wwwroot/154.219.120.254';

const files = [
  'hide/suser.php',
  'open_api.php',
  'hidedd/kj.php',
  'hide/kj.php',
  'hidedd/now.php',
  'hide/now.php',
  'task_notify_mch.php',
  'hidedd/user.php',
  'uxj/makelib.php',
  'mxj/makelib.php',
  'mxjs/makelib.php',
  'templates/default/hides/suseredit.html',
];

function md5file(p) {
  try { return crypto.createHash('md5').update(fs.readFileSync(p)).digest('hex'); }
  catch(e) { return 'LOCAL_MISSING'; }
}

ssh.connect({ host: '154.219.120.254', username: 'root', password: 'Dc0sZlar6k78', readyTimeout: 15000 }).then(async () => {
  for (const f of files) {
    const lmd5 = md5file(path.join(local, f));
    const r = await ssh.execCommand('md5sum ' + remote + '/' + f + ' 2>/dev/null | awk "{print $1}"');
    const rmd5 = r.stdout.trim() || 'REMOTE_MISSING';
    const match = lmd5 === rmd5;
    console.log((match ? 'OK' : 'DIFF') + '  ' + f);
    if (!match) {
      console.log('    local : ' + lmd5);
      console.log('    server: ' + rmd5);
    }
  }
  ssh.dispose();
}).catch(e => console.error(e.message));
