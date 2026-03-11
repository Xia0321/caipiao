<?php
include("../data/config.inc.php");
include("../data/db.php");
include("../global/db.inc.php");
include("../global/session.class.php");
include("../global/forms.php");
include('../func/func.php');
	$_SESSION["wid"] = 103; 
    $wid = $_SESSION["wid"];
	
if($_REQUEST['guest']=='guest'){
	   $msql->query("select guser from `$tb_web` where wid='$wid'");
	   $msql->next_record();
	   $guser = explode(',',$msql->f('guser'));
	   $cg = count($guser);
        $sql      = "SELECT * FROM `$tb_user` WHERE username='".$guser[rand(0,$cg-1)]."' ";
        $msql->query($sql);
        $msql->next_record(); 
		if($msql->f("userid")!=''){
	        include('../global/client.php');
            include("../global/Iplocation_Class.php");
            $sv              = rserver(); 
            $_SESSION['sv']  = $sv;
            $os              = getbrowser($_SERVER['HTTP_USER_AGENT']) . '  ' . getos($_SERVER['HTTP_USER_AGENT']);
            $_SESSION['gid'] = $msql->f('gid');
            $fsql->query("insert into `$tb_user_login` set xtype='2',ip='$ip',time=NOW(),ifok='1',username='$user',userpass='OK',server='$sv',os='$os'");
            $fsql->query("update `$tb_user` set logintimes=logintimes+1,lastloginip='$ip',lastlogintime=NOW(),online=1 where username='$user'");
            $passcode = (getmicrotime() * 100000000) . $time;
            $fsql->query("delete from `$tb_online` where xtype=2 and userid='" . $msql->f('userid') . "'");
            $fsql->query("insert into `$tb_online` set page='xy',passcode='$passcode',xtype='2',userid='" . $msql->f('userid') . "',logintime=NOW(),savetime=NOW(),ip='$ip',server='$sv',wid='$wid',layer='" . $msql->f('layer') . "',os='$os'");
            $_SESSION['upasscode'] = $passcode;
            $_SESSION['uuid']      = $msql->f('userid');
			$fsql->query("select allpass from `$tb_config`");
			$fsql->next_record();
            $_SESSION['ucheck']    = md5($fsql->f('allpass') . $msql->f('userid'));
            $_SESSION['sv']        = $sv;
		$fsql->query("select uskin from `$tb_web` where wid='$wid'");
		$fsql->next_record();
		$_SESSION['skin']        = $fsql->f('uskin');
            header('Location:../mxj/');
			exit;
		}
}

if($_POST['tj']==1){
    $agent = strtoupper($_POST["agent"]);
	$username = strtoupper($_POST["username"]);
	$password = $_POST["password"];
	$name = $_POST["name"];
	$tel = $_POST["tel"];
	$qq = $_POST["qq"];
	$code = $_POST["code"];
	$forms = new forms();
	if($code!=$_SESSION['login_check_number']){
	    echo "<script language='javascript'>alert('验证码不正确!');</script>";
	}else if(!$forms->isName($username)){
	    echo "<script language='javascript'>alert('您输入的用户名不正确!');</script>"; 
	}else if(!$forms->isEmpty($name)){
	    echo "<script language='javascript'>alert('姓名输入不正确!');</script>"; 
	}else if(!$forms->isNum($tel)){
	    echo "<script language='javascript'>alert('手机输入不正确!');</script>"; 
	}else if(!$forms->isNum($qq)){
	    echo "<script language='javascript'>alert('QQ输入不正确!');</script>"; 
	}else{
		$msql->query("select username from `$tb_user` where username='$username'");
		$msql->next_record();
		if($msql->f('username')==$username){
		       echo "<script language='javascript'>alert('用户名已存在!请重新输入!');</script>"; 
			   header("Location:reg.php");
			   exit; 
		}
		$trueagent = '';
		$ifa=1;
		if($forms->isName($agent)){
	       $msql->query("select username from `$tb_user` where username='$agent' and wid='$wid'");
		   //echo "select username from `$tb_user` where username='$agent' and wid='$wid'";exit; 
		   $msql->next_record();
	
		   if($msql->f('username')!=$agent){
		       echo "<script language='javascript'>alert('推荐人不存在!');window.location.href='".$_SERVER['HTTP_REFERER']."';</script>"; 
			   exit;
		   }
		   $trueagent=$msql->f('username');
		}
		if($trueagent==''){
		    $msql->query("select username from `$tb_user` where username=(select zcagent from `$tb_web` where wid='$wid')");
			$msql->next_record();
			if($msql->f('username')==''){
		       echo "<script language='javascript'>alert('推荐人不存在2!');window.location.href='".$_SERVER['HTTP_REFERER']."';</script>"; 
			   exit;
			}
			$trueagent=$msql->f('username');	
			$ifa=0;		
		}
		if($trueagent!=''){
            $userpass = md5(md5($password) . $config['upass']);
            $uid      = setupid("$tb_user", "userid");
            $msql->query("select * from `$tb_user` where username='$trueagent'");
			$msql->next_record();
            if ($msql->f('id') == ''){
                exit;
			}
            $layer = $msql->f('layer') + 1;
			$fid = $msql->f("userid");  
            $defaultpan = $msql->f('defaultpan');
			//$pan = json_encode(array($defaultpan));
			$pan  = $msql->f('pan');
            $wid        = $msql->f('wid');
            $gid        = $msql->f('gid');
			$sql        = "insert into `$tb_user` set username='$username',userid='$uid',userpass='$userpass',name='$name',tname='$name',qq='$qq',tel='$tel',status='1',ifagent='1',layer='$layer',maxren='0',plc='0',pan='$pan',defaultpan='$defaultpan',maxmoney='0',kmaxmoney='0',money='0',kmoney='0',fudong='1',fid='$fid',wid='$wid',fastje=0,gid='$gid',passtime=NOW(),regtime=NOW(),liushui=0";
            for ($j = ($layer - 2); $j >= 1; $j--) {
                $sql .= ",fid" . $j . "='" . $msql->f('fid' . $j) . "'";
            }
            $sql .= ",fid".($layer-1)."='".$fid."'";
			//echo $sql;exit;
            if ($msql->query($sql)) {
				$liushui=0;
				$modiip= getip();
				$sql = "insert into `$tb_notices` set userid='$uid',sendid='99999999',title='欢迎注册,有问题请联系客服!',content='欢迎注册,有问题请联系客服!',du=0,time=NOW()";
				$msql->query($sql);
                $sql      = "insert into `$tb_user_edit` set modiip='$modiip',moditime=NOW(),action='注册',userid='$uid',modiuser='$uid',modisonuser='0'";
				$msql->query($sql);
                $msql->query("insert into `$tb_fastje` select NULL,$uid,je,xsort from `$tb_fastje` where userid='99999999'");
                $msql->query("insert into `$tb_zpan` select NULL,gid,$uid,class,lowpeilv,0 from `$tb_zpan` where userid='$fid'");
				if($ifa==0){
                   $msql->query("insert into `$tb_points` select NULL,gid,$uid,class,ab,0,0,0,0,cmaxje,maxje,minje from `$tb_points` where userid='$fid'");
				}else{
                   $msql->query("insert into `$tb_points` select NULL,gid,$uid,class,ab,a,b,c,d,cmaxje,maxje,minje from `$tb_points` where userid='$fid'");
				}
                
				$gamecs = getgamecs($fid);
				$cg = count($gamecs);
				for($i=0;$i<$cg;$i++){
				    $gamecs[$i]['flyzc'] = 0;
					$gamecs[$i]['zc'] = 0;
					$gamecs[$i]['upzc'] = 0;
				}
                insertgame($gamecs, $uid);

		       echo "<script language='javascript'>alert('注册成功,请登陆!');window.location.href='index.php';</script>"; 
			   exit;
            }
		}
	}
}


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>手机端注册</title>
<script src="../js/jquery-1.8.3.js"></script>
<style type="text/css">
*{box-sizing:border-box;}
body{margin:0;padding:0;min-height:100vh;font-family:"Microsoft YaHei",sans-serif;background:linear-gradient(165deg,#2d1b4e 0%,#1a0f2e 50%,#0d0818 100%);color:#e8e4f0;-webkit-tap-highlight-color:transparent;}
.bg-m{position:fixed;inset:0;background-image:radial-gradient(rgba(180,140,255,.12) 1px,transparent 1px);background-size:20px 20px;pointer-events:none;z-index:0;}
.wrap{position:relative;z-index:1;min-height:100vh;padding:16px 18px 24px;}
.nav{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.nav a{color:rgba(232,228,240,.9);text-decoration:none;font-size:14px;}
.nav .back{display:flex;align-items:center;gap:6px;}
.nav .tit{font-size:17px;font-weight:600;color:#b48cff;}
.reg-card{background:rgba(255,255,255,.07);border:1px solid rgba(180,140,255,.25);border-radius:16px;padding:20px 18px;box-shadow:0 12px 40px rgba(0,0,0,.25);}
.fg{margin-bottom:14px;}
.fg label{display:block;font-size:12px;color:rgba(232,228,240,.85);margin-bottom:5px;}
.fg input{width:100%;height:42px;padding:0 12px;font-size:15px;color:#e8e4f0;background:rgba(0,0,0,.25);border:1px solid rgba(180,140,255,.3);border-radius:10px;outline:none;}
.fg input:focus{border-color:#b48cff;}
.fg .hint{font-size:11px;color:rgba(232,228,240,.5);margin-top:3px;}
.code-wrap{display:flex;gap:10px;align-items:flex-end;}
.code-wrap .fg{flex:1;}
.code-wrap img{height:42px;border-radius:10px;cursor:pointer;border:1px solid rgba(180,140,255,.3);}
.btn-reg{width:100%;height:46px;margin-top:20px;font-size:16px;font-weight:600;letter-spacing:4px;color:#1a0f2e;background:linear-gradient(180deg,#b48cff 0%,#8b5cf6 100%);border:none;border-radius:12px;cursor:pointer;}
.btn-reg:active{opacity:.9;}
.reg-foot{margin-top:18px;text-align:center;font-size:13px;color:rgba(232,228,240,.6);}
.reg-foot a{color:#b48cff;text-decoration:none;}
</style>
<script>
function changeimg(){ $("#imgcode").attr('src',"../imgcode.php?act=init&"+Math.random()); }
function checkform(){
	if(!$("#username").val()){ alert("请输入账号"); $("#username").focus(); return false; }
	if(!$("#password").val()){ alert("请输入密码"); $("#password").focus(); return false; }
	if($("#password").val()!=$("#password1").val()){ alert("两次密码不一致"); $("#password").focus(); return false; }
	if(!$("#name").val()){ alert("请输入姓名"); $("#name").focus(); return false; }
	if(!$("#tel").val()){ alert("请输入手机号码"); $("#tel").focus(); return false; }
	if(!$("#qq").val()){ alert("请输入QQ"); $("#qq").focus(); return false; }
	if(!$("#code").val()){ alert("请输入验证码"); $("#code").focus(); return false; }
	return true;
}
</script>
</head>
<body>
<div class="bg-m"></div>
<div class="wrap">
	<div class="nav">
		<a href="./" class="back">← 返回</a>
		<span class="tit">手机端注册</span>
		<span style="width:60px;"></span>
	</div>
	<div class="reg-card">
		<form method="post" onsubmit="return checkform();">
			<input type="hidden" name="tj" value="1" />
			<div class="fg">
				<label>推荐人账号</label>
				<input type="text" id="agent" name="agent" value="<?php echo isset($_GET['agent'])?htmlspecialchars($_GET['agent']):''; ?>" placeholder="选填">
				<div class="hint">选填</div>
			</div>
			<div class="fg">
				<label>登录账号</label>
				<input type="text" id="username" name="username" value="<?php echo isset($_POST['username'])?htmlspecialchars($_POST['username']):''; ?>" placeholder="4-10位字符">
			</div>
			<div class="fg">
				<label>登录密码</label>
				<input type="password" id="password" name="password" placeholder="6-20位字母数字组合">
			</div>
			<div class="fg">
				<label>确认密码</label>
				<input type="password" id="password1" name="password1" placeholder="再次输入密码">
			</div>
			<div class="fg">
				<label>真实姓名</label>
				<input type="text" id="name" name="name" value="<?php echo isset($_POST['name'])?htmlspecialchars($_POST['name']):''; ?>" placeholder="与提款户名一致">
			</div>
			<div class="fg">
				<label>手机号码</label>
				<input type="text" id="tel" name="tel" value="<?php echo isset($_POST['tel'])?htmlspecialchars($_POST['tel']):''; ?>" placeholder="手机号">
			</div>
			<div class="fg">
				<label>QQ</label>
				<input type="text" id="qq" name="qq" value="<?php echo isset($_POST['qq'])?htmlspecialchars($_POST['qq']):''; ?>" placeholder="QQ号">
			</div>
			<div class="fg code-wrap">
				<div class="fg" style="flex:1;">
					<label>验证码</label>
					<input type="text" id="code" name="code" placeholder="验证码">
				</div>
				<img id="imgcode" src="../imgcode.php?act=init" onclick="changeimg();" alt="" title="点击更换">
			</div>
			<button type="submit" class="btn-reg" id="btnRegister">创建账号</button>
			<div class="reg-foot">已有账号？<a href="./">去登录</a></div>
		</form>
	</div>
</div>
</body>
</html>
