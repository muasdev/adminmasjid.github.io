<?php
$koneksi=mysqli_connect("localhost","root","","taman_jompie");
$query = "select * from berita";
$hasil  =mysqli_query($koneksi, $query);

if(mysqli_num_rows($hasil) > 0 ){
  $response = array();
  while($x = mysqli_fetch_array($hasil)){
    $h['id'] = $x["id"];
    $h['foto_berita'] = $x["foto_berita"];
    $h['judul_berita'] = $x["judul_berita"];
    $h['deskripsi'] = $x["deskripsi"];
    array_push($response, $h);
  }
  echo json_encode($response);
}else {
  $response="tidak ada data";
  echo json_encode($response);
}
?>