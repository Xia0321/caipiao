$(function() {
	$("li.lib").click(function(){
	   window.location.href = mulu + "lib.php?xtype=lib";
		return false;
	});
	$("li.kj").click(function(){
	   window.location.href = mulu + "kj.php?xtype=kj";
		return false;
	});
	$("li.bao").click(function(){
	   window.location.href = mulu + "bao.php?xtype=bao";
		return false;
	});
	$(".back").click(function(){
	    window.location.href= "nav.php";
		return false;
	});
	$(".game").change(function(){
	    getkj();
	});
	getkj();
});


function getkj(){
	var gid = Number($("select.game").val());
	
   ngid = gid;
   if(gid==107){
	  $(".hename").html("冠亚和"); 
	  }else if(gid==151 | gid==152){
	  $(".hename").html("三军"); 
	  }else{
		$(".hename").html("总和");  
		 }
		
	$.ajax({
		type: 'POST',
		url: 'kj.php',
		dataType: 'json',
		cache: false,
		data: "xtype=getkj&tpage=" + tspage + "&gid=" + gid,
		success: function(m) {
			//alert(m);return;
			//alert(m['sql']);
			var rcount = m['rcount'];
			var mnum = m['mnum'];
			var psize = m['psize'];
			m = m['kj'];
			var ml = m.length;
			var str = '';
			$(".con").empty();
			for (i = 0; i < ml; i++) {
				// 无 m 时用 m1,m2,m3 组装（兼容 hide/kj getkj）
				if (!m[i]['m'] && mnum >= 1) {
					var arr = [];
					for (var jj = 1; jj <= mnum; jj++) arr.push(m[i]['m' + jj] != null ? m[i]['m' + jj] : '');
					m[i]['m'] = arr;
				}
				// 3D：根据三码和计算 和值/单双/大小（避免显示 undefined）
				if (mnum == 3 && (m[i]['dx'] === undefined || m[i]['dx'] === null)) {
					var a = m[i]['m'][0], b = m[i]['m'][1], c = m[i]['m'][2];
					var sum = (Number(a) || 0) + (Number(b) || 0) + (Number(c) || 0);
					m[i]['hs'] = sum;
					m[i]['ds'] = (sum % 2 == 0) ? '双' : '单';
					m[i]['dx'] = (sum >= 14) ? '大' : '小';
				}
				str += "<tr>";
				str += "<td>" + m[i]['qishu'] + "<BR />"+m[i]['kjtime']+"</td>";
				str += "<td>";
				for(j=1;j<=mnum;j++){
				   if(gid==100){
					 if(j==7) str += "T";
				     str += qiu6h(m[i]['m'][j-1]);
				   }else{
				     str += qiu(m[i]['m'][j-1]);
				   }
				   if(j==10 & (gid==161 | gid==162)) str+= "<BR />";
				   //alert(m[i]['m'][j-1]);
				   //str+= m[i]['m'][j-1];
				}
				
				str += "</td>";				
				str += "<td>";
				if (m[i]['hs'] !== undefined && m[i]['hs'] !== null && m[i]['hs'] !== '') {
				str  +=  m[i]['hs'] + "<BR />" + (m[i]['ds'] != null ? m[i]['ds'] : '') + "/" + (m[i]['dx'] != null ? m[i]['dx'] : '');
				}
				str += "</td>";
				str += "</tr>";
			}
	
			
			$(".con").append(str);

			var pcount = rcount % psize == 0 ? rcount / psize : (rcount - rcount % psize) / psize + 1;
			
			var pagestr='';
			for (i = 1; i <= pcount; i++) {
				pagestr += "<option value='"+i+"'>";
				pagestr += i + "</option>"
			}
			
			

			$(".page").html(pagestr);

			$(".page").val(tpage);
			$(".page").unbind("change");
			$(".page").change(function() {
				tpage = Number($(this).val());
				getkj();
				return false;
			})
		

			str = null;
			m = null;
			pagestr=null;
			$(".con tr").click(function() {
				$(this).toggleClass('byellow');
				return false
			})
		}
	})
}