<?php
//成份列表
//重複出現的東西，出現率會比較高。總長度建議不要超過255。
$elements=array(
'海之冰',
'海風',
'英文閱讀能力',
'火星文書寫能力',
'喵喵叫的可能',
'在路邊昏倒的可能',
'對著狗汪汪叫',
'遇到火星人的可能性',
'嘎嘎叫的能力',
'帳號無法登入的可能',
'填錯申請表的能力',
'在海大被風吹走的可能',
'滾來滾去',
'日月光華',
'唱歌走音的可能',
'在一餐吃到恐怖料理的可能',
'在二餐買不到雞排的可能',
'在祥豐校門遇到正妹的能力',
'機車可以停到濱海校門附近的可能',
'寫 Compiler 作業寫不出來',
'程式 compile 不過',
'好人卡',
'Runtime error',
'跳電',
'宿網爆流量',
'閃光室友',
'遇到閃光彈',
'三修',
'在夢泉前面把午餐弄翻的可能',
'好人電波'
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
