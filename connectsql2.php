


<?php
$datas2 = array();

$servername = "localhost";
$username = "addressf";
$password = "doingning2016";
$db_name="addressf_project";
$conn = mysqli_connect($servername, $username, $password , $db_name  ) or die("fail to connection");
// Check connection
mysqli_set_charset( $conn, 'utf8');
$Sjob =  $_GET['Sjob'];
 $address = $_GET['address'];
// $address='八德街153巷17號';
$lat =$_GET['lat'];
$lng = $_GET['lng'];

 //假資料
 /*
 $address = '八德街154巷17號';
 $lat =25.034301;
 $lng =121.45598;
$Sjob ="菸酒";
 */

//-----------------------------------
$job30 = array("農產品","食品什貨","菸酒","服飾","家具","五金","日常用品","模具","水電行","清潔用品","化粧品","成藥","樂器","建材","電器","電腦","電信","機械器具","其他機械","機車","汽機車零件","車胎","首飾","資訊軟體","電子材料","便利商店","飲料店","餐館","租賃","美容美髮");
$i = 29;
//確定職業
$Occupations = 0;
for($i = 0 ; $i< 30 ;$i++ )
{
	if($job30[$i]==$Sjob){
		 $Occupations=$i;
		 // $Occupations=29;
		break ;
	}
}
//確定地址 取得座標和KEY
$sql = "SELECT * FROM addressf_project.add_vill_twd97 where address='".$address."' ";
$re=mysqli_query($conn,$sql);
$row =mysqli_num_rows($re);

if($row == 0 )
{

	/*
	SELECT id, ( 3959 * acos( cos( radians(37) ) * cos( radians( lat ) ) 
* cos( radians( lng ) - radians(-122) ) + sin( radians(37) ) * sin(radians(lat)) ) ) AS distance
FROM markers
HAVING distance < 25 
ORDER BY distance 
LIMIT 0 , 20;
	 */

 $sql1 = "SELECT * ,  ( 3959 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin(radians(lat)) ) ) as distance FROM addressf_project.add_vill_twd97 having distance<2000  order by distance limit 1 ";
 $re1 = mysqli_query($conn,$sql1);
 $data=mysqli_fetch_array($re1,MYSQLI_NUM);
	for ($i = 0 ; $i<count($data) ; $i++) 
	{
		$datas[$i] = $data[$i];

	}



}else{

  $data=mysqli_fetch_array($re,MYSQLI_NUM);
	for ($i = 0 ; $i<count($data) ; $i++) 
	{
		$datas[$i] = $data[$i];
	}


}








//500 M  店家
// sql3 500m  store summary ---------------
$sql3 = "SELECT  `NO` ,  `F201010` ,  `F203010` ,  `F203020` ,  `F204110` ,  `F205040` ,  `F206010` ,  `F206020` , `F206030` ,  `F206040` ,  `F207030` ,  `F208040` ,  `F208050` ,  `F209060` ,  `F211010` ,  `F213010` , `F213030` ,  `F213060` ,  `F213080` ,  `F213990` ,  `F214020` ,  `F214030` ,  `F214050` ,  `F215010` , `F218010` ,  `F219010` ,  `F399010` ,  `F501030` ,  `F501060` ,  `JE01010` ,  `JZ99080` ,  `地址` ,  `資本額` , x, y
FROM rowdata HAVING (POW( POW( ( ".(int)$datas[3]." - x ) , 2 ) + POW( ".(int)$datas[4]." - y, 2 ) , 0.5 )) <500 ";
$re3=mysqli_query($conn,$sql3);
//$jobsum = array();

while ($data3=mysqli_fetch_array($re3,MYSQLI_NUM)) 
{
    for ($q=0 ; $q<30 ; $q++)
    {
        $jobsum[$q]=$jobsum[$q]+$data3[$q+1];
       // echo $jobsum[$i];
    }
}

//echo json_encode($jobsum);

//500m store summary end --------------------
$j=0;

while ($j<30) {
	$datas2['jobsum'][$j] = $jobsum[$j];
	$j++;
}


// echo json_encode($jobsum);


$job30 =array("F201010","F203010","F203020","F204110","F205040","F206010","F206020","F206030","F206040","F207030","F208040","F208050","F209060","F211010","F213010","F213030","F213060","F213080","F213990","F214020","F214030","F214050","F215010","F218010","F219010","F399010","F501030","F501060","JE01010","JZ99080");
$sql22 = "SELECT * from addressf_project.add_vill_twd97 WHERE ".$job30[$Occupations]."=1 HAVING (POW( POW( ( ".(int)$datas[3]." - x ) , 2 ) + POW( ".(int)$datas[4]." - y, 2 ) , 0.5 )) <480 ORDER BY RAND() LIMIT 5";
$re22 = mysqli_query( $conn , $sql22 ) ;
$j=0;
while ($data22 = mysqli_fetch_assoc($re22)) {
	 $datas2['address'][$j] =  $data22["address"];
  	$j++;
}

$job30 =array("F201010","F203010","F203020","F204110","F205040","F206010","F206020","F206030","F206040","F207030","F208040","F208050","F209060","F211010","F213010","F213030","F213060","F213080","F213990","F214020","F214030","F214050","F215010","F218010","F219010","F399010","F501030","F501060","JE01010","JZ99080");
$sql2 = "SELECT  * FROM addressf_project.realrent   HAVING (POW( POW( ( ".(int)$datas[3]." - x ) , 2 ) + POW( ".(int)$datas[4]." - y, 2 ) , 0.5 )) <500 limit 3";
 $re2= mysqli_query($conn , $sql2);
// echo $sql2;
$j=0;

while ($row = mysqli_fetch_assoc($re2)) {
	// echo $j;
	$datas2['area'][$j]=$row['area'];
	$datas2['price'][$j]=$row['price'];
	$datas2['man'][$j]=$row['man'];
//	$datas2['title'][$j]=$row['title'];
//	$datas2['nea'][$j]=$row['nea'];
	$datas2['lat'][$j]=$row['lat'];
	$datas2['lng'][$j]=$row['lng'];
	$datas2['address'][$j]=$row['address'];

	$j++;
// 	$datas2[$j][0]=$j ; 
// 	$datas2[$j][1]=$data2["address"]  ; 
// 	$datas2[$j][2]=$data2["vill"]  ; 
// 	$datas2[$j][3]=$data2["per"]  ; 
// 	$datas2[$j][4]=$data2["size"]  ; 
// $datas2[$j][5]=$data2["total"]  ; 
// $datas2[$j][6]=$data2["NO"];
// $j++;
	// $datas2[$j] =  $data2["NO"];
  	//echo $data2["x"];
}

echo json_encode($datas2,JSON_UNESCAPED_UNICODE);

// //得到所以同職業的key

// $total_data2 = array_intersect($datas2, $datas22);
// $j=0;
// $number = array();
// foreach ($total_data2 as  $value) {
// 	$number[$j] =  $value;
// 	$j++;
// }
// $rand_keys = array_rand($number, 10);
//print_r($datas22);

// foreach ($datas2 as $value) {
// 	echo $value[6]."<br>";
// 	echo $i;
// 	$i++;

// 	code...
// }
//echo print_r($datas2);



?>