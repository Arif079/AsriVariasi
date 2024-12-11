
<html>
<head>
    <title>BUKU BESAR</title>
    
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    
</head>
<body>
 <div class="container box">
  <h3 align="center">ASRI VARIASI</h3>
  <br />
  <div class="table-responsive">
   <table class="table table-bordered">
   <tr><td colspan="6"><h2><?= $coa ?> <?=$nama?></h1></td></tr> 
   <tr>
                <th>Tanggal</th>
                <th>Referensi</th>
                <th>COA Lawan</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Saldo</th>
    </tr>
    <?php
    foreach($data_entri as $row)
    {
     echo '
     <tr>
      <td>'.$row->date.'</td>
      <td>'.$row->kdtransaksi.'</td>
      <td>'.$row->coalawan.'</td>
      <td>'.$row->rupiah($row->debet).'</td>
      <td>'.$row->rupiah($row->kredit).'</td>
      <td>'.$row->rupiah($balance).'</td>
     </tr>
     ';
    }
    ?>
   </table>
   <div align="center">
    <form method="post" action="<?php echo base_url(); ?>excel_export/action">
     <input type="submit" name="export" class="btn btn-success" value="Export" />
    </form>
   </div>
   <br />
   <br />
  </div>
 </div>
</body>
</html>