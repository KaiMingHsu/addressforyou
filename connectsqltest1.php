<?php
$filename ="sql/addressf_project.sql"

$servername = "localhost";

$username = "root";

$password = "";
$db_name="addressf_project";
// Create connection
$conn = mysqli_connect($servername, $username, $password , $db_name  ) or die("fail to connection");
// Check connection
mysqli_set_charset( $conn, 'utf8');

$address = $_GET['address'];
$lat =$_GET['lat'];
$lng = $_GET['lng'];

//假資料
// $address='福營路2670號';  
//  $lat =25.022688;
//  $lng =121.4232879;



// ---------part1 -------------------------
//echo "select address is $address";
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









//echo json_encode( $datas );

// ---------part3  -------------------------
// show sql 2 test 
$no= $datas[0];
$sql2= "SELECT * from addressf_project.realdata where no ='".$no."'";
$re2=mysqli_query($conn,$sql2);
$data2=mysqli_fetch_array($re2,MYSQLI_NUM);
$job=array("","F201010","F203010","F203020","F204110","F205040","F206010","F206020","F206030","F206040","F207030","F208040","F208050","F209060","F211010","F213010","F213030","F213060","F213080","F213990","F214020","F214030","F214050","F215010","F218010","F219010","F399010","F501030","F501060","JE01010","JZ99080");
$topjob=array();
$i=0;
for($j=0;$j<31;$j++)
{
    if($j>0){
        $topjob[$j-1] =(int)$data2[$j];
        /*
        if((int)$data2[$j]==1){
                $topjob[$i]=$job[$j];
                $i++;
        }
        */
    }
}
//echo json_encode( $topjob); 

// -----part 4 -------------------------------
//  show sql 3  test 
$sql3 = "SELECT  `NO` ,  `F201010` ,  `F203010` ,  `F203020` ,  `F204110` ,  `F205040` ,  `F206010` ,  `F206020` , `F206030` ,  `F206040` ,  `F207030` ,  `F208040` ,  `F208050` ,  `F209060` ,  `F211010` ,  `F213010` , `F213030` ,  `F213060` ,  `F213080` ,  `F213990` ,  `F214020` ,  `F214030` ,  `F214050` ,  `F215010` , `F218010` ,  `F219010` ,  `F399010` ,  `F501030` ,  `F501060` ,  `JE01010` ,  `JZ99080` ,  `地址` ,  `資本額` , x, y
FROM rowdata HAVING (POW( POW( ( ".(int)$datas[3]." - x ) , 2 ) + POW( ".(int)$datas[4]." - y, 2 ) , 0.5 )) <500 ";
$re3=mysqli_query($conn,$sql3);
$jobsum = array();
while ($data3=mysqli_fetch_array($re3,MYSQLI_NUM)) 
{
    for ($i=0 ; $i<30 ; $i++)
    {
        $jobsum[$i]=$jobsum[$i]+$data3[$i+1];
    }
}

//echo json_encode($jobsum);
//-------part5  --------------------------
//return  result  to  client side 

//$job30 =array("F201010","F203010","F203020","F204110","F205040","F206010","F206020","F206030","F206040","F207030","F208040","F208050","F209060","F211010","F213010","F213030","F213060","F213080","F213990","F214020","F214030","F214050","F215010","F218010","F219010","F399010","F501030","F501060","JE01010","JZ99080");
$sql5 = "SELECT  * FROM addressf_project.realrent HAVING (POW( POW( ( ".(int)$datas[3]." - x ) , 2 ) + POW( ".(int)$datas[4]." - y, 2 ) , 0.5 )) <500 limit 6";
$re5= mysqli_query($conn , $sql5);
$j=0;


$result=array();
while ($row = mysqli_fetch_assoc($re5)) {
    $result['area'][$j]=$row['area'];
    $result['price'][$j]=$row['price'];
    $result['man'][$j]=$row['man'];
//  $result['title'][$j]=$row['title'];
//  $result['nea'][$j]=$row['nea'];
    $result['lat'][$j]=$row['lat'];
    $result['lng'][$j]=$row['lng'];
    $result['address'][$j]=$row['address'];
    $j++;
//  $result[$j][0]=$j ; 
//  $result[$j][1]=$data2["address"]  ; 
//  $result[$j][2]=$data2["vill"]  ; 
//  $result[$j][3]=$data2["per"]  ; 
//  $result[$j][4]=$data2["size"]  ; 
// $result[$j][5]=$data2["total"]  ; 
// $result[$j][6]=$data2["NO"];
// $j++;
    // $result[$j] =  $data2["NO"];
    //echo $data2["x"];
}






$result['v1']=$topjob;
$result['v2']=$jobsum;
// $result = array_merge($topjob,$jobsum);
echo json_encode($result,JSON_UNESCAPED_UNICODE);






//$address = $_GET['address'];
//$result = mysqli_query($conn ,$sql);
//$data= mysqli_fetch_array( $re , MYSQLI_NUM );
//echo $data[0].$data[1];
//$row = $re->fetch_array(MYSQLI_NUM);
//printf ("%d (%d)\n", $row[0],$row[1]);
//
/*
if (!$re) {     
    die('Invalid query: ' . mysql_error());
} else {

    if ($re->num_rows > 0) {            
        while($row = $result->fetch_assoc()) {              
             printf ("123 %s (%s)\n", $row["no"], $row["no"]);
        }
        //utf-8 accents unescaping to be shown correctly
    } else {
        echo "0";
    }
}
*/
?>