<?php
//成份列表
//重複出現的東西，出現率會比較高。總長度建議不要超過255。
$elements=array(
'屍毒',
'御宅氣',
'高手高手高高手',
'雜魚',
'高頻雜訊',
'黑暗',
'死靈怨影',
'光',
'性慾',
'心中的翡翠森林',
'心中的斷背山',
'大宇宙的意志',
'燃燒的小宇宙',
'反物質',
'三鋰水晶',
'空間扭曲',
'時空斷層',
'微型黑洞',
'微波雷射',
'化屍水',
'王水',
'海水',
'一江春水',
'花痴',
'夢',
'烈日之心',
'友愛',
'愛心光束',
'命運的相逢',
'巨大蘿蔔',
'高張力鋼',
'米諾夫斯基粒子',
'G3毒氣',
'三倍速',
'彈幕',
'沙林毒氣',
'新人類',
'恨',
'鬼東西',
'歌聲',
'腦殘',
'墮落',
'飢渴',
'戀童癖',
'自戀',
'戀父情結',
'戀母情結',
'戀兄情結',
'戀妹情結',
'愛','愛','愛','愛',
'沒創意',
'髒空氣',
'不良思想',
'反動思想',
'細肩帶小女孩不加辣',
'細肩帶小男孩不加辣',
'渣渣',
'成為豆腐的覺悟',
'撞豆腐自殺的勇氣',
'被受害人折斷的決心',
'義理巧克力',
'星之雨',
'腦麻',
'變態',
'嘴砲',
'信念',
'微妙',
'莫名奇妙',
'巨大怪獸',
'人體暖爐',
'智慧',
'天然呆',
'生命之水',
'天邊一朵雲',
'糟糕',
'心機',
'超合金',
'乙醯膽鹼',
'氫氟酸',
'絨毛',
'碎碎念',
'怨念',
'宿便','宿便',
'毒電波','毒電波','毒電波','毒電波',
'正義之心',
'腦漿',
'膿','膿','膿',
'海之冰',
'狗血',
'核子反應原料',
'反應爐冷卻水',
'高性能炸藥',
'對艦大型雷爆彈',
'國造六六火箭彈',
'超音波',
'觀世音',
'天下第一舉世無雙絕對無敵真正非常超越超級震古鑠今空前絕後刀槍不入無堅不摧無所不能好厲害',
'謎'
);

//方便使用的str_split，撿來的
//str_split在PHP5有，但是PHP4沒有 T__T
if(!function_exists('str_split')){
   function str_split($string,$split_length=1){
       $count = strlen($string); 
       if($split_length < 1){
           return false; 
       } elseif($split_length > $count){
           return array($string);
       } else {
           $num = (int)ceil($count/$split_length); 
           $ret = array(); 
           for($i=0;$i<$num;$i++){ 
               $ret[] = substr($string,$i*$split_length,$split_length); 
           } 
           return $ret;
       }     
   } 
}

//傳入整數(或整數字串)X，pointer會向後移動X，然後傳回該位置的元素名。
//到陣列結尾就重頭開始
$pointer=0;
function get_element($id){
	if(!is_numeric($id)) return 'Error!';
	else{
		global $elements;
		global $pointer;
		eval("\$pointer+=$id;");
		$pointer%=count($elements);
		return $elements[$pointer];
	}
}


$result_string='';
if( isset($_POST['name']) && $_POST['name']!=''){//結果模式
	if(strlen($_POST['name']) > 256){//對名字長度作出限制，以免Hash跑太久
		$result_string='您輸入的名字太長了。';
	}
	else{//計算結果
		$input_string = strtoupper($_POST['name']);
		$input_hash = md5($input_string);

		$hase_length=strlen($input_hash);
		$chunks=str_split($input_hash,2);

		//蠢台詞時間。
		include('stupid_stories.php');


		// $elist[成份名稱]=成份含量
		$total_quantity=0;//成份總量
		for($i=0; $i < count($chunks); $i+=2){
			$current_component=get_element("0x{$chunks[$i]}");
			eval("\$current_quantity=0x{$chunks[$i+1]};");
			$current_quantity*=$current_quantity*$current_quantity;//三次方，將大量成份跟少量成分的距離拉大
			$total_quantity+=$current_quantity;
			
			if(isset( $elist[$current_component] ) ){
				$elist[$current_component]+=$current_quantity;
			}
			else{
				$elist[$current_component]=$current_quantity;
			}
		}
		//sort
		arsort($elist);
		$result_string.="<p>{$_POST['name']}的成分：</p>\n<ul>\n";
		foreach($elist as $k => $v){
			$percent=number_format( 100*$elist[$k]/$total_quantity, 2);
			if( ereg('0.0[0-9]',$percent) ) continue;//太少的東西就不顯示了
			else{
				$result_string.="<li>{$k}：{$percent}%</li>\n";
			}
		}
		$result_string.="</ul>\n<hr />\n";



	}
}//結果輸出模式結束

else{//輸入模式
$result_string='<p><a href="http://w3.nctu.edu.tw/~u8912009/mis/component_check.rar">Code下載</a>，<a href="http://w3.nctu.edu.tw/~u8912009/mis/component_check_4.2.rar">4/2特別版</a></p>';

}

?>
<html><head><title>分析你的成分</title><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body>
<?php echo($result_string); ?>
<form name="form1" id="form1" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>">
<p>請輸入你的名字：<input name="name" type="text" id="name" maxlength="256" /></p>
<p><input type="submit" name="Submit" value="分析結果" /></p>
</form>
<?php include("footer.php"); ?>
</body></html>
