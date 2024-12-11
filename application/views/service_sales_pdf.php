<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SPK</title>

    <style>
        body {
            font-size: 12px;
        }
        .header {
            background: "#CCC";
        }
        .heading {
            float:left;
            width: 50%;
        }

        .heading h1 {
            font-size: 24px;
            margin:0;
            padding:0;
        }
        
        .heading h2 {
            font-size: 9px;
            margin: 9px 0;
        }

        .title {
            float:right;
            border: 1px solid #000;
            padding: 9px 22px;
        }
        .header:after {
            display:block;
            content: "";
            clear: both;
        }

        .no-td {
            border:none;
            padding:2px;
            font-size:12px;
        
        }
        .body-td {
            height: 30px;
            border-top: none; /* No top border */
            border-bottom: none; /* No bottom border */
            padding: 0 px; /* Optional padding for content inside the element */
            font-size:12px;
        }
        .under-td{
            height:30px;
            border-top:none;
        }
        .invisible {
            display: none; /* This CSS rule hides the column */
        }
        .label {
            padding: 0 9px 0 16px;
        }

        .col-wrap:after {
            clear:both;
            display:block;
            content: "";
        }
        .m-td{
            font-size:9px;
            height:5px;

        }
        .col-1 {
            float:left;
        }

        .col-2 {
            float:right;
            margin-right:20px;
        }
        .center{
            margin-right:20px;
        }
        .centered {
        float: left; /* or float: right; */
        margin-left: auto;
        margin-right: auto;
        }

        table {
            border-collapse: collapse;
            font-size: 12px;
        }

        table th,td {
            border:1px solid #000;
            padding: 7px 9px;
        }
         .currency:before {
        content: "Rp "; /* Add your currency symbol here */
        float: left; /* Align symbol to the left */
        margin-left: 2mm;
        margin-top: 2mm; /* Add space before the symbol */
    }

    .currency-total:before {
        content: "Rp "; /* Add your currency symbol here */
        float: left; /* Align symbol to the left */
        margin-left: 2mm; /* Add space before the symbol */
    }
    </style>
</head>
<body>
    <div class="header">
        <div class="heading">
            <?php
            //convert image into Binary data
            $img_type = "png";
            $img_data = fopen ( "././img/asrivariasi.png", 'rb' );
            $img_size = filesize ( "././img/asrivariasi.png" );
            $binary_image = fread ( $img_data, $img_size );
            fclose ( $img_data );

            //Build the src string to place inside your img tag
            $img_src = "data:image/".$img_type.";base64,".str_replace ("\n", "", base64_encode ( $binary_image ) );
            ?>
            <img src="<?=$img_src;?>" style="max-width:180px;float:left">
            <div style="padding: 70px 0 0 0">
                <!-- <h3><?=$this->shop_info->get_shop_name();?></h3> -->
                <h2><?=$this->shop_info->get_shop_address();?></h2>
            </div>
        </div>
        <span class="title"><?=$fetch->noinvoice?></span>
    </div>

    <div class="col-wrap" style="margin-top:-70px;border-style: solid;" >
    <table style="width: 100%; margin-right: calc(2%);">
    <tbody>
        <tr>
            <td class="no-td" style="text-align: left;">Nama</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td class="no-td" style="text-align: left; "><?=$spk->customer?></td>
            <td class="no-td" style="text-align: left;">No Rangka</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td colspan="2"  class="no-td" style="text-align: left; "><?=$fetch->noka?></td>
            <td class="no-td" style="text-align: center;"></td>
            <td class="no-td" style="text-align: center;" colspan="2">Pelanggan : Prioritas/Reguler</td>
        </tr>
        <tr>
            <td class="no-td" style="text-align: left;">Alamat</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td style="white-space: wrap;" class="no-td" style="text-align: left; "><?=$spk->alamat?></td>
            <td class="no-td" style="text-align: left;">No Mesin</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td colspan="2" class="no-td" style="text-align: left; "><?=$fetch->nosin?></td>
            <td class="no-td" style="text-align: center;"></td>
            <td class="no-td" style="text-align: center;">Diterima</td>
            <td class="no-td" style="text-align: center;">Selesai</td>
        </tr>
        <tr>
            <td class="no-td" style="text-align: left;">NPWP/NIK</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td class="no-td" style="text-align: left; "><?=$spk->npwp?></td>
            <td class="no-td" style="text-align: left;">Merk</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td class="no-td" style="text-align: left; "><?=$fetch->merk?></td>
            <td class="no-td" style="text-align: right;">Tgl</td>
            <td class="no-td" style="text-align: center;">:</td>
            <td class="no-td" style="text-align: center;"><?=date('d-m-y', strtotime($fetch->tglmasuk))?></td>
            <td class="no-td" style="text-align: center;"><?=date('d-m-y', strtotime($fetch->tglkeluar))?></td>

        </tr>
        <tr>
            <td class="no-td" style="text-align: left;">NAMA QQ</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td class="no-td" style="text-align: left;" ><?=$spk->nama_qq?></td>
            <td class="no-td" style="text-align: left;">Tipe</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td class="no-td" style="text-align: left; "><?=$fetch->tipe?></td>
            <td class="no-td" style="text-align: right;">Jam</td>
            <td class="no-td" style="text-align: center;">:</td>
            <td class="no-td" style="text-align: center;"><?=$fetch->jammasuk?></td>
            <td class="no-td" style="text-align: center;"><?=$fetch->jamkeluar?></td>
        </tr>
        <tr>
            <td class="no-td" style="text-align: left;">Telephone</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td class="no-td" style="text-align: left; "><?=$spk->notelp?></td>
            <td class="no-td" style="text-align: left;">Tahun</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td class="no-td" style="text-align: left; "><?=$fetch->tahun?></td>
            <td class="no-td" style="text-align: right;">KM</td>
            <td class="no-td" style="text-align: center;">:</td>
            <td class="no-td" style="text-align: center;"><?=$fetch->kmmasuk?></td>
            <td class="no-td" style="text-align: center;"><?=$fetch->kmkeluar?></td>
        </tr>
        <tr>
            <td class="no-td" style="text-align: left;">No Polisi</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td class="no-td" style="text-align: left; "><?=$spk->plat?></td>
            <td class="no-td" style="text-align: left;">Warna</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td class="no-td" style="text-align: left; "><?=$fetch->warna?></td>
            <td class="no-td" style="text-align: Right;">SPK</td>
            <td class="no-td" style="text-align: center;">:</td>
            <td class="no-td" colspan="2" style="text-align: center;"><?=$spk->spkid;?></td>
        </tr>
        <tr>
            <td class="no-td" style="text-align: left;">Nama Sales</td>
            <td class="no-td" style="text-align: left;">:</td>
            <td class="no-td" style="text-align: left; "><?=$spk->sales?></td>
            <td class="no-td" style="text-align: left;"></td>
            <td class="no-td" style="text-align: left;"></td>
            <td class="no-td" style="text-align: left; "></td>
            <td class="no-td" style="text-align: Right;"></td>
            <td class="no-td" style="text-align: center;"></td>
            <td class="no-td" colspan="2" style="text-align: center;"></td>
        </tr>
    </tbody>
</table>

    </div>
    <table width="100%" style="margin-top:12px;" >
        <thead style="font-size:12px;">
            <th width="3%">No.</th>
            <th width="50%">Uraian Pekerjaan dan Variasi</th>
            <th width="3%">Jumlah</th>
            <th >Harga</th>
            <th >Total</th>
            </tr>
        </thead>
        <tbody style="height:900px;">
        <?php
           $counter = 1; // Initialize a counter variable
           $lastNamajob = null; // Initialize a variable to store the last namajob value
           
           foreach ($details as $detail) {
               ?>
               <tr>
                   <?php
                   if ($lastNamajob !== $detail->namajob) {
                       // Display the namajob only if it has changed
                       ?>
                       <td class="body-td" style="text-align:center"><?= $counter; ?></td>
                       <td class="body-td"><?=$detail->namajob;?><br><?=$detail->name;?></td>
                       <?php
                       $lastNamajob = $detail->namajob;
                   } else {
                       // If it's the same as the previous row, rowspan to merge cells
                       ?>
                       <td class="body-td" style="text-align:center"><?= $counter; ?></td>
                       <td class="body-td"><?=$detail->name;?></td>
                       <?php
                   }
                   ?>
                   
                   <td class="body-td" style="text-align:center"><?= $detail->qty; ?></td>
                   <td class="body-td currency" style="text-align:right"><?= number_format($detail->price,0,",","."); ?></td>
                   <td class="body-td currency" style="text-align:right"><?= number_format($detail->price * $detail->qty,0,",","."); ?></td>
               </tr>
               <?php
               $counter++; // Increment the counter for the next row
           }           
            // Generate blank rows to reach a total of 15 rows
            $remainingRows = 12 - count($details);

            for ($i = 0; $i < $remainingRows; $i++) {
                ?>
                <tr>
                    <td class="body-td"></td>
                    <td class="body-td"></td>
                    <td class="body-td" style="text-align:center"></td>
                    <td class="body-td" style="text-align:right"></td>
                    <td class="body-td" style="text-align:right"></td>
                </tr>
                <?php
            }
            ?>

        <tr><td class="under-td"><br></td><td class="under-td"><br></td><td class="under-td"><br></td><td class="under-td"><br></td><td class="under-td"><br></td></tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right; padding: 5px;">Total : </td>
                <td class="currency-total" style="text-align:right;font-size: 11px;"><?=number_format($spk->total,0,",",".");?></td>
            </tr>
        </tfoot>
    </table>
    <div class="wrapper">
        <div class="form-row col-md-6">
        <table Border="1" width="50%" style="margin-top: 12px; float: left; font-size:12px;">
    <thead>
        <tr>
            <th>Nama Teknisi</th>
            <th>On</th>
            <th>Pause</th>
            <th>Off</th>
            <th>TTD Teknisi</th>
            <th>Final Inspeksi</th>
        </tr>
    </thead>
    <tbody>
                <?php
            $counter = 1; // Initialize a counter variable
            $rowspan = 10 ; // Calculate rowspan value

            foreach ($teknisi as $detail) {
                ?>
                <tr>
                    <td class="m-td" style="font-size:10px"><?= $detail->nama; ?></td> <!-- Add the counter here -->
                    <td class="m-td"><?= $detail->masuk; ?></td>
                    <td class="m-td"><?= $detail->pause; ?></td>
                    <td class="m-td"><?= $detail->keluar ?></td>
                    <td class="m-td"></td>
                    <?php if ($counter === 1) { // Check if it's the last iteration ?>
                        <td rowspan="<?= $rowspan; ?>"></td>
                    <?php } ?>
                </tr>
                <?php
                $counter++; // Increment the counter for the next row
            }
            // Generate blank rows to reach a total of 15 rows
            $remainingRows = 10 - count($teknisi);

            for ($i = 0; $i < $remainingRows; $i++) {
                ?>
                <tr>
                    <td class="m-td"></td>
                    <td class="m-td"></td>
                    <td class="m-td"></td>
                    <td class="m-td"></td>
                    <td class="m-td"></td>
                </tr>
                <?php
            }
            ?>

    </tbody>
    </table>
        </div>
    <table Border="1" width="49.5%" style="margin-top: 12px;margin-left: 5px; float: left;">
    <tr>
        <td style="font-size: 12px">
            <u>Keterangan</u>: <br> Customer telah setuju dengan keterangan harga yang diberikan pihak variasi
            <br> <u>Garansi berlaku sebagai berikut</u>: <br> Sesuai dengan kartu garansi yang tertera terpisah dari Surat perintah kerja (SPK) ini
            <br> 1 Bulan untuk : Electrical, Ornamen, Bungkus Jok, Karpet Dasar. <br> 1 Minggu Untuk : Poles <br> Klaim diluar ketentuan di atas 1x24 Jam diluar tanggungan Asri Variasi
            <br> <u>Syarat & Ketentuan Berlaku</u>: <br> Dipasang di bengkel Asri Variasi <br> Bukti Pembayaran Lunas Asri Variasi <br>
        </td>
    </tr>
            </table>
        
        </div>
        <div style="clear: both;"></div>
       <div style="form-row col-md-6" > 
        <table Border="1" width="50%" style="margin-top: 12px;margin-left: 0px; float: left;">
            <tr>
                <td colspan="2" style="font-size: 10px;">Keterangan :<br>Kendaraan telah diterima dengan kondisi sesuai dengan cek fisik</td>
            </tr>
            <tr><td style="text-align:center;border-right:none;border-bottom:none;">Pelanggan</td><td style="text-align:center;border-left:none;border-bottom:none;">Penerima</td></tr>
            <tr><td style="border-right:none;border-top:none;border-bottom:none;"><br></td><td style="border-left:none;border-top:none;border-bottom:none;"><br></td></tr>
            <tr><td style="text-align:center;border-right:none;border-top:none;border-bottom:none;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td><td style="text-align:center;border-left:none;border-top:none;border-bottom:none;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td></tr>
        </table>
        <table Border="1" width="49%" style="margin-top: 12px;margin-left: 10px; float: left;">
        <tr>
                <td colspan="2" style="font-size: 12px;"><br>Sesudah kendaraan selesai di check dan di terima kembali</td>
            </tr>
        <tr><td style="text-align:center;border-right:none;border-bottom:none;">Yang menyerahkan</td><td style="text-align:center;border-left:none;border-bottom:none;">Pelanggan</td></tr>
            <tr><td style="border-right:none;border-top:none;border-bottom:none;"><br></td><td style="border-left:none;border-top:none;border-bottom:none;"><br></td></tr>
            <tr><td style="text-align:center;border-right:none;border-top:none;border-bottom:none;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td><td style="text-align:center;border-left:none;border-top:none;border-bottom:none;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td></tr>
        </table>
    </div>
    </div>
</body>
</html>