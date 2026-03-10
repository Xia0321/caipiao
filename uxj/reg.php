<?php
include("../data/config.inc.php");
include("../data/db.php");
include("../global/db.inc.php");
include("../global/session.class.php");
include("../global/forms.php");
include('../func/func.php');
if($_POST['tj']==1){
	$_SESSION["wid"] = 100; 
    $wid = $_SESSION["wid"];
    $agent = strtoupper($_POST["reg_agent"]);
	$username = strtoupper($_POST["reg_username"]);
	$password = $_POST["reg_password"];
	$name = $_POST["reg_name"];
	$tel = $_POST["reg_tel"];
	$qq = $_POST["reg_qq"];
	$code = $_POST["reg_code"];
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
		       echo "<script language='javascript'>alert('推荐人不存在!');window.location.href='".$_SERVER['HTTP_REFERER']."';</script>"; 
			   exit;
			}
			$trueagent=$msql->f('username');
			$ifa=0;			
		}
		if($trueagent!=''){
            $userpass = md5($password);
			$userpass= md5($userpass."puhh8kik");
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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>电脑端注册</title>
<link href="./css/validationEngine.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="./js/jquery1.js"></script>
<script type="text/javascript" src="./js/jquery_003.js"></script>
<script type="text/javascript" src="./js/jquery2.js"></script>
<script type="text/javascript" src="./js/reg.js"></script>
<script>function changeimg(){ $("#imgcode").attr('src',"../imgcode.php?act=init&"+Math.random()); }</script>
<style type="text/css">
*{box-sizing:border-box;}
body{margin:0;padding:0;min-height:100vh;font-family:"Microsoft YaHei",sans-serif;background:linear-gradient(155deg,#0f172a 0%,#1e3a5f 40%,#0c1929 100%);color:#e2e8f0;}
.bg-pc{position:fixed;inset:0;background:radial-gradient(ellipse 90% 50% at 50% -15%,rgba(59,130,246,.15) 0%,transparent 55%);pointer-events:none;z-index:0;}
.bg-line{position:fixed;inset:0;background-image:linear-gradient(rgba(59,130,246,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(59,130,246,.04) 1px,transparent 1px);background-size:48px 48px;pointer-events:none;z-index:0;}
.wrap{position:relative;z-index:1;min-height:100vh;padding:40px 20px;display:flex;flex-direction:column;align-items:center;}
.reg-head{text-align:center;margin-bottom:28px;}
.reg-head .tit{font-size:20px;font-weight:600;letter-spacing:4px;color:#60a5fa;}
.reg-head .desc{font-size:12px;color:rgba(226,232,240,.55);margin-top:6px;}
.reg-card{width:100%;max-width:460px;background:rgba(15,23,42,.88);border:1px solid rgba(59,130,246,.28);border-radius:16px;padding:32px 28px;box-shadow:0 20px 50px rgba(0,0,0,.4),0 0 0 1px rgba(59,130,246,.08) inset;}
.reg-row{margin-bottom:16px;}
.reg-row label{display:block;font-size:12px;color:rgba(226,232,240,.8);margin-bottom:5px;}
.reg-row input[type="text"],.reg-row input[type="password"]{width:100%;height:42px;padding:0 14px;font-size:14px;color:#e2e8f0;background:rgba(15,23,42,.7);border:1px solid rgba(59,130,246,.3);border-radius:8px;outline:none;}
.reg-row input:focus{border-color:#3b82f6;box-shadow:0 0 0 2px rgba(59,130,246,.2);}
.reg-row .hint{font-size:11px;color:rgba(226,232,240,.5);margin-top:4px;}
.reg-row.code-row{display:flex;gap:10px;align-items:flex-end;}
.reg-row.code-row .reg-row{flex:1;}
.reg-row.code-row img{height:42px;border-radius:8px;cursor:pointer;border:1px solid rgba(59,130,246,.3);}
.btn-reg{width:100%;height:44px;margin-top:24px;font-size:15px;font-weight:600;letter-spacing:4px;color:#fff;background:linear-gradient(180deg,#3b82f6 0%,#2563eb 100%);border:none;border-radius:10px;cursor:pointer;}
.btn-reg:hover{opacity:.95;box-shadow:0 6px 28px rgba(59,130,246,.4);}
.reg-foot{margin-top:20px;text-align:center;font-size:13px;color:rgba(226,232,240,.7);}
.reg-foot a{color:#60a5fa;text-decoration:none;}
</style>
</head>
<body>
<div class="bg-pc"></div>
<div class="bg-line"></div>
<div class="wrap">
	<div class="reg-head">
		<div class="tit">电脑端注册</div>
		<div class="desc">请填写以下信息完成注册</div>
	</div>
	<div class="reg-card">
		<script type="text/javascript">
		jQuery(document).ready(function(){
			var theForm=$("#main"); theForm.validationEngine();
			jQuery("input:text").each(function(){ jQuery(this).attr("name",jQuery(this).attr("id")); });
			jQuery("input:password").each(function(){ jQuery(this).attr("name",jQuery(this).attr("id")); });
		});
		</script>
		<form id="main" method="post" name="main">
			<input type="hidden" name="tj" value="1" />
			<div class="reg-row">
				<label>推荐人账号</label>
				<input type="text" id="reg_agent" maxlength="15" value="<?php echo isset($_GET['agent'])?htmlspecialchars($_GET['agent']):''; ?>" class="inp-txt">
				<div class="hint">选填</div>
			</div>
			<div class="reg-row">
				<label>会员账号</label>
				<input type="text" id="reg_username" maxlength="15" value="<?php echo isset($_POST['reg_username'])?htmlspecialchars($_POST['reg_username']):''; ?>" class="validate[required,custom[onlyLetterNumber],minSize[3],maxSize[8]] inp-txt">
				<div class="hint">3-8位数字与字母组合</div>
			</div>
			<div class="reg-row">
				<label>登录密码</label>
				<input type="password" id="reg_password" maxlength="15" class="validate[required,minSize[6],maxSize[15]] inp-txt">
				<div class="hint">6-15位，需含数字与字母</div>
			</div>
			<div class="reg-row">
				<label>确认密码</label>
				<input type="password" id="reg_password1" maxlength="15" class="validate[required,equals[reg_password]] inp-txt">
			</div>
			<div class="reg-row">
				<label>真实姓名</label>
				<input type="text" id="reg_name" maxlength="10" class="validate[required] inp-txt" value="<?php echo isset($_POST['reg_name'])?htmlspecialchars($_POST['reg_name']):''; ?>">
				<div class="hint">须与提款银行户名一致</div>
			</div>
			<div class="reg-row">
				<label>手机号码</label>
				<input type="text" id="reg_tel" maxlength="13" class="validate[required] inp-txt" value="<?php echo isset($_POST['reg_tel'])?htmlspecialchars($_POST['reg_tel']):''; ?>">
			</div>
			<div class="reg-row">
				<label>QQ</label>
				<input type="text" id="reg_qq" maxlength="30" class="validate[required] inp-txt" value="<?php echo isset($_POST['reg_qq'])?htmlspecialchars($_POST['reg_qq']):''; ?>">
			</div>
			<div class="reg-row code-row">
				<div class="reg-row" style="flex:1;">
					<label>验证码</label>
					<input type="text" id="reg_code" maxlength="10" class="validate[required] inp-txt">
				</div>
				<img id="imgcode" src="../imgcode.php?act=init" onclick="changeimg();" alt="" title="点击更换">
			</div>
			<button type="submit" name="regBtn" class="btn-reg">注 册</button>
			<div class="reg-foot">已有账号？<a href="index.php" class="click_to_login">登录</a></div>
		</form>
	</div>
</div>
</body>
</html>