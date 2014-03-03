<html>
<body>
<?php 

$lang_struct=array();
// C
$C_struct=array();

$C_struct["integer"]="int";
$C_struct["character"]="char";

$C_struct["dim"]=array();

$dims=array();
$dims["pre"]="*";
$dims["pos"]="";
array_push($C_struct["dim"],$dims);

$dims=array();
$dims["pre"]="";
$dims["pos"]="[]";
array_push($C_struct["dim"],$dims);

//print_r($C_struct["dim"]);
//echo "<br>";

//Java
$java_struct=array();

$java_struct["integer"]="int";
$java_struct["character"]="char";
$java_struct["common"]=array("ArrayList","Vector");
$java_struct["dim"]=array();

$dims=array();
$dims["pre"]="";
$dims["pos"]="[]";
array_push($java_struct["dim"],$dims);

$dims=array();
$dims["pre"]="[]";
$dims["pos"]="";
array_push($java_struct["dim"],$dims);


$lang_struct["C"]=$C_struct;
$lang_struct["JAVA"]=$java_struct;

//print_r($C_struct);
//echo "<br>";

//print_r($java_struct);
//echo "<br>";

/*
print_r($lang_struct);
echo "<br>";
*/

$func_name=$_POST["func_name"];
$class_name=$_POST["class_name"];
$ret_type= $_POST["ret_type"];
$ret_dim= (int)$_POST["ret_dim"];
$cur_lang=$_POST["lang"];

class Variable{
	public $nam,$dtypes,$dims;
	function Variable($nam,$dtypes,$dims,$lang){
		global $lang_struct;
		$this->nam=$nam;
		$this->dtypes=$dtypes;
		$this->dims=$dims;
		$this->pos=array();
		if($dims==0){
			$temp=$lang_struct[$lang][$dtypes]." ".$nam." ";
			array_push($this->pos,$temp);
		}
		else{
			for($j=0;$j<count($lang_struct[$lang]["dim"]);$j++){
				$pre="";
				$pos="";
				for($i=0;$i<$dims;$i++){
					$pre=$pre.$lang_struct[$lang]["dim"][$j]["pre"];
					$pos=$pos.$lang_struct[$lang]["dim"][$j]["pos"];
				}
				$temp=$lang_struct[$lang][$dtypes].$pre." ".$nam.$pos." ";
				array_push($this->pos,$temp);
			}
			if($dims==1){
				for($j=0;$j<count($lang_struct[$lang]["common"]);$j++){
					$temp=$lang_struct[$lang]["common"][$j]." ".$nam." ";
					array_push($this->pos,$temp);
				}
			}
			else{
				for($j=0;$j<count($lang_struct[$lang]["common"]);$j++){
					for($k=0;$k<count($lang_struct[$lang]["dim"]);$k++){
						$pre="";
						$pos="";
						for($i=1;$i<$dims;$i++){
							$pre=$pre.$lang_struct[$lang]["dim"][$k]["pre"];
							$pos=$pos.$lang_struct[$lang]["dim"][$k]["pos"];
						}
						$temp=$lang_struct[$lang]["common"][$j].$pre." ".$nam.$pos." ";
						array_push($this->pos,$temp);
					}
				}
			}
		}
//		print_r($this->pos);
//		echo "<br>";
	}
}

$tot=(int)($_POST["num_var"]);
$vars=array();
for ($x=0; $x<$tot; $x++)
{
	$num=((string)$x);
	array_push($vars,new Variable($_POST["name".$num],$_POST["type".$num],(int)$_POST["dim".$num],$cur_lang));
} 
array_push($vars,new Variable("",$ret_type,(int)$ret_dim,$cur_lang));

/*
print_r($vars);
echo "<br>";
*/

function generate_proto($index,$formed){
	global $tot,$vars,$lang_struct;
	if(($index)==($tot-1)){
		global $func_name,$ret_type;
		$cur_list=$vars[$index]->pos;
		//adding return type & function name
		for($x=0;$x<count($cur_list);$x++){
			$ret_list=$vars[$index+1]->pos;
			for($y=0;$y<count($ret_list);$y++){
				$ans=$formed." ".$cur_list[$x]." ";
				$ans=$ret_list[$y]." ".$func_name." ( ".$ans." ) ";
				echo $ans."<br><br>";
			}
		}
		return;
	}
	$cur_list=$vars[$index]->pos;
	for($x=0;$x<count($cur_list);$x++){
		generate_proto($index+1,$formed." ".$cur_list[$x]." , ");
	}
};
generate_proto(0,"",$lang);

?>

</body>
</html>
