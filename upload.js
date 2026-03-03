const { NodeSSH } = require('C:/Users/xiao/AppData/Roaming/npm/node_modules/node-ssh');
const path = require('path');
const ssh = new NodeSSH();

const local = 'C:/Users/xiao/Downloads/1/138_PNjGC';
const remote = '/www/wwwroot/154.219.120.254';

const files = [
  'tools/autokjs.php',
];

ssh.connect({
  host: '154.219.120.254',
  username: 'root',
  password: 'Dc0sZlar6k78',
  keepaliveInterval: 3000,
  readyTimeout: 30000,
}).then(async () => {
  for (const f of files) {
    const lp = path.join(local, f);
    const rp = remote + '/' + f;
    try {
      await ssh.putFile(lp, rp);
      console.log('OK  ' + f);
    } catch (e) {
      console.error('ERR ' + f + ': ' + e.message);
    }
  }

  ssh.dispose();
}).catch(e => {
  console.error('connect error: ' + e.message);
});
