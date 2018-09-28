<?php
$koneksi=mysqli_connect("localhost","root","","taman_jompie");
$query = "select * from tanaman";
$hasil  =mysqli_query($koneksi, $query);

if(mysqli_num_rows($hasil) > 0 ){
  $response = array();
  while($x = mysqli_fetch_array($hasil)){
    $h['id'] = $x["id"];
    $h['foto_tanaman'] = $x["foto_tanaman"];
    $h['nama_ilmiah'] = $x["nama_ilmiah"];
    $h['nama_lokal'] = $x["nama_lokal"];
    $h['famili_tanaman'] = $x["famili_tanaman"];
    array_push($response, $h);
  }
  echo json_encode($response);
}else {
  $response="tidak ada data";
  echo json_encode($response);
}
?>