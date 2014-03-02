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

print_r($vars);
?>

</body>
</html>
