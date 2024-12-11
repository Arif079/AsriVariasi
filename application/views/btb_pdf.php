<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bukti Terima Barang</title>

    <style>
        body {
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
        }

        .h1 {
            text-decoration: underline;
            font-size: 7px;
            text-align: center;
            position: absolute;
            top: 50px; /* Adjust this value to control the vertical placement */
            left: 50%;
            transform: translateX(-50%);
        }

        .table {
            margin-top: 12px;
            border-collapse: collapse;
        }

        .table tr,td,th {
            border: 1px solid #000;
            padding: 3px 9px;
            font-size: 12px;
        }

        .title {
            float:right;
            border: 1px solid #000;
            padding: 1px 22px;
        }
    </style>
</head>
<body>
<div class="header">
<span class="title"><?=$fetch->nbtb;?></span>
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
            <img src="<?=$img_src;?>" style="max-width:169px;float:left;margin: right 12px;">
            </div>
        <h1>BUKTI TERIMA BARANG</h1>
        <hr style="border:1px double #000">

        <div>
        <table style="table-layout:fixed;width: 100%; border:none; border-collapse:collapse;">
    <tbody>
        <tr>
            <td style="font-size: 12px; border:none;">No PO</td>
            <td style="font-size: 12px; border:none;" colspan="2">: <?=$fetch->nop;?></td>
            <td style="font-size: 12px; border:none;">Nama Supplier</td>
            <td style="font-size: 12px; border:none;" colspan="2">: <?=$fetch->name;?></td>
        </tr>
        <tr>
            <td style="font-size: 12px; border:none;">Tanggal PO</td>
            <td style="font-size: 12px; border:none;" colspan="2">: <?=date("d/m/Y",strtotime($fetch->date));?></td>
            <td style="font-size: 12px; border:none;">Alamat Supplier</td>
            <td style="text-align:left; font-size: 10px; border:none;word-wrap: break-word" colspan="2" >: <?=$fetch->address;?></td>
        </tr>
        <tr>
            <td style="font-size: 12px; border:none;">Nama Perusahaan</td>
            <td style="font-size: 12px; border:none;" colspan="2">: <?=$this->shop_info->get_shop_name();?></td>
            <td style="font-size: 12px; border:none;">Telepon</td>
            <td style="font-size: 12px; border:none;" colspan="2">: <?=$fetch->telephone;?></td>
        </tr>
        <tr>
            <td style="font-size: 12px; border:none;">Alamat Perusahaan</td>
            <td style="font-size: 12px; border:none;word-wrap: break-word;" colspan="5">: Jl Bendungan Hilir Blok H9-10  
        </td>
        </tr>
        <tr>
            <td style="font-size: 12px; border:none;">Telephone</td>
            <td style="font-size: 12px; border:none;" colspan="5">: 0318968266</td>
        </tr>
    </tbody>
</table>


    <table class="table" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Item</th>
                <th>Nama Barang</th>
                <th>QTY</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            $totalQty = 0;
            foreach($details as $detail) {
                $i++;
                $totalQty += $detail->qty;
            ?>

                <tr>
                    <td style="text-align:center"><?=$i;?></td>
                    <td style="text-align:center"><?=$detail->kditem;?></td>
                    <td><?=$detail->name;?></td>
                    <td style="text-align:center"><?=$detail->qty;?></td>
                </tr>

            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right">Jumlah Qty</td>
                <td style="text-align:center"><?=$totalQty?></td>
            </tr>
        </tfoot>
    </table>
    <div style="padding-top:40px">
            <p> &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Diperiksa Oleh  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Disetujui Oleh &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Dibuat Oleh</p>
<p><br></p>
<p>&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; ______________ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ______________ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;______________&nbsp;</p>

        </div>
</body>
</html>