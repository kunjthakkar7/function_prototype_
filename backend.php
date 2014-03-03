<html>
<body>

<h1>Possible Prototypes</h1>

<?php 

$lang_struct=array();
// C -> struct
$C_struct=array();

//function begining & ending
$C_struct["begin"]="";
$C_struct["end"]="{<br>}";
//datatypes
$C_struct["integer"]="int";
$C_struct["character"]="char";

//dimension prefix & postfix to declaration
$C_struct["dim"]=array();

$dims=array();
$dims["pre"]="*";
$dims["pos"]="";
array_push($C_struct["dim"],$dims);

$dims=array();
$dims["pre"]="";
$dims["pos"]="[]";
array_push($C_struct["dim"],$dims);


//Java -> variables with same meaning as C
$java_struct=array();

$java_struct["begin"]="public class ".$_POST["class_name"]."{<br>		public ";
$java_struct["integer"]="int";
$java_struct["character"]="char";
$java_struct["common"]=array("ArrayList","Vector");
$java_struct["end"]="{<br>	}<br>}";

$java_struct["dim"]=array();

$dims=array();
$dims["pre"]="";
$dims["pos"]="[]";
array_push($java_struct["dim"],$dims);

$dims=array();
$dims["pre"]="[]";
$dims["pos"]="";
array_push($java_struct["dim"],$dims);

//adding to lang_struct
$lang_struct["C"]=$C_struct;
$lang_struct["JAVA"]=$java_struct;

//storing variables
$func_name=$_POST["func_name"];
$class_name=$_POST["class_name"];
$ret_type= $_POST["ret_type"];
$ret_dim= (int)$_POST["ret_dim"];
$lang=$_POST["lang"];

// Variable class name , datatype , dimension & possibilities
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
	}
}

$tot=(int)($_POST["num_var"]);
//create array of Variable class
$vars=array();
for ($x=0; $x<$tot; $x++)
{
	$num=((string)$x);
	array_push($vars,new Variable($_POST["name".$num],$_POST["type".$num],(int)$_POST["dim".$num],$lang));
} 
//Last Element of array is return type
array_push($vars,new Variable("",$ret_type,(int)$ret_dim,$lang));

//generate function prototype from vars
function generate_proto($index,$formed){
	global $tot,$vars,$lang_struct;
	if(($index)==($tot-1)){
		global $func_name,$ret_type,$lang;
		$cur_list=$vars[$index]->pos;
		//adding return type & function name
		for($x=0;$x<count($cur_list);$x++){
			$ret_list=$vars[$index+1]->pos;
			for($y=0;$y<count($ret_list);$y++){
				$ans=$formed." ".$cur_list[$x]." ";
				$ans=$lang_struct[$lang]["begin"].$ret_list[$y]." ".$func_name." ( ".$ans." ) ".$lang_struct[$lang]["end"];
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
<br><br><br><br>

<a href="index.html">go back</a>
</body>
</html>
