<html>
<body>
<?php 

class Variable{
	public $nam,$dtypes,$dims;
	function Variable($nam,$dtypes,$dims){
		$this->nam=$nam;
		$this->dtypes=$dtypes;
		$this->dims=$dims;
	}
}

$tot=(int)($_POST["num_var"]);
$vars=array();
for ($x=0; $x<$tot; $x++)
{
	$num=((string)$x);
	array_push($vars,new Variable($_POST["name".$num],$_POST["type".$num],(int)$_POST["dim".$num]));
} 

/*
print_r($vars);
echo "<br>";
*/

$func_name=$_POST["func_name"];
$class_name=$_POST["class_name"];
$ret_type= $_POST["ret_type"];
$ret_dim= (int)$_POST["ret_dim"];

$lang_struct=array();
// C
$C_struct=array();

$C_struct["integer"]="int";
$C_struct["character"]="char";

//Java
$java_struct=array();

$java_struct["integer"]="int";
$java_struct["character"]="char";

$lang_struct["C"]=$C_struct;
$lang_struct["JAVA"]=$java_struct;

/*
print_r($lang_struct);
echo "<br>";
*/

function get_datatype($type,$lang){
	global $lang_struct;
	$temp=$lang_struct[$lang];
	return $temp[$type];
}


function generate_proto($index,$formed,$lang){
	global $tot,$vars,$lang_struct;
	if(($index)==($tot-1)){
		global $func_name,$ret_type;
		$dtype=get_datatype($vars[$index]->dtypes,$lang);
		$ans=$formed.$dtype." ".($vars[$index]->nam);
		$dtype=get_datatype($ret_type,$lang);
		$ans=$dtype." ".$func_name." ( ".$ans." ) <br>";
		echo $ans;
		return;
	}
	$dtype=get_datatype($vars[$index]->dtypes,$lang);
	generate_proto($index+1,$formed.$dtype." ".$vars[$index]->nam." , ",$lang);
};

generate_proto(0,"","C");
generate_proto(0,"","JAVA");

?>

</body>
</html>
