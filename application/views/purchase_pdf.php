<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Pembelian</title>

    <style>
        body {
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
        }

        .header h1 {
            text-decoration: underline;
            font-size: 22px;
        }

        .table {
            margin-top: 18px;
            border-collapse: collapse;
        }

        .table > tbody,th,td {
            border: 1px solid #000;
            padding: 3px 9px;
            font-family: Arial, Helvetica, sans-serif;
        }
        
        .heading h2 {
            font-size: 12px;
            margin: 9px 0;
        }
        .col-wrap:after {
            clear:both;
            display:block;
            content: "";
        }

        .right-td{
            border-left:none;
            border-top:none;
            border-bottom:none;
        }
        .total-td{
            border-left:none;
            border-bottom:none;
        }
        .new-td{
            border-top:none;
            border-right:none;
            border-left:none;
            border-bottom:none;
        }
        .title {
            float:right;
            border: 1px solid #000;
            padding: 1px 22px;
        }

    .currency:before {
        content: "Rp "; /* Add your currency symbol here */
        float: left; /* Align symbol to the left */
        margin-left: 2mm; /* Add space before the symbol */
    }
    </style>
</head>
<body>
<div class="header">
        <div class="heading">
        <span class="title"><?=$fetch->nop;?></span>
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
            <img src="<?=$img_src;?>" style="max-width:169px;float:left">
        <h1>ORDER PEMBELIAN</h1>
        
    </div >
        <hr style="border:1px double #000">
    <div>
    <table style="table-layout:fixed;width: 100%; border:none; border-collapse:collapse;">
    <tbody>
        <tr>
            <td style="font-size: 12px; border:none;">No PO</td>
            <td style="font-size: 12px; border:none;"colspan="2">: <?=$fetch->nop;?></td>
            <td style="font-size: 12px; border:none;">Nama Supplier</td>
            <td style="font-size: 12px; border:none;" colspan="2">: <?=$fetch->name;?></td>
        </tr>
        <tr>
            <td style="font-size: 12px; border:none;">Tanggal PO</td>
            <td style="font-size: 12px; border:none;" colspan="2">: <?=date("d/m/Y",strtotime($fetch->date));?></td>
            <td style="font-size: 12px; border:none;">Alamat Supplier</td>
            <td style="height:auto;font-size: 9px; border:none;word-wrap:break-word;" colspan="2" >: <?=$fetch->address;?></td>
        </tr>
        <tr>
            <td style="font-size: 12px; border:none;">Nama Perusahaan</td>
            <td style="font-size: 12px; border:none;" colspan="2">: <?=$this->shop_info->get_shop_name();?></td>
            <td style="font-size: 12px; border:none;">Telepon</td>
            <td style="font-size: 12px; border:none;" colspan="2">: <?=$fetch->telephone;?></td>
        </tr>
        <tr>
            <td style="font-size: 12px; border:none;">Alamat Perusahaan</td>
            <td style="width:auto;font-size: 12px; border:none" colspan="5">: Jl Bendungan Hilir Blok H9-10  
        </td>
        </tr>
        <tr>
            <td style="font-size: 12px; border:none;">Telephone</td>
            <td style="font-size: 12px; border:none;" colspan="5">: 0318968266</td>
        </tr>
    </tbody>
</table>





    <div>
    <table class="table" width="100%" style="margin-top: 20px;">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 10%;">Kode Item</th>
                <th>Nama Barang</th>
                <th style="width: 3%;">Qty</th>
                <th>Harga</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach($details as $detail) {
                $i++;
            ?>

                <tr>
                    <td style="text-align:center"><?=$i;?></td>
                    <td style="text-align:center"><?=$detail->kditem;?></td>
                    <td><?=$detail->name;?></td>
                    <td style="text-align:center"><?=$detail->qty;?></td>
                    <td class="currency" style="text-align:right"><?=number_format($detail->price,0,",",".");?></td>
                    <td class="currency" style="text-align:right"><?=number_format($detail->qty * $detail->price,0,",",".");?></td>
                </tr>

            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="new-td" colspan="2" style="text-align:left">Tgl Pengiriman :</td>
                <td class="new-td" colspan="2" style="text-align:left"><?=$fetch->tgl_kirim;?></td>
                <td class="total-td"  style="text-align:right">Sub-Total</td>
                <td class="currency"  style="text-align:right;border:1px solid"><?=number_format($fetch->total,0,",",".");?></td>
            </tr>
            <tr>
                <td class="new-td" colspan="2" tyle="text-align:left">Cara Pembayaran :</td>
                <td class="new-td" colspan="2" style="text-align:left">: <?=$fetch->bayar;?></td>
                <td class="right-td"  style="text-align:right">PPN</td>
                <td class="currency" style="text-align:right;border:1px solid"><?=number_format($fetch->total*($fetch->ppn/100),0,",",".");?></td>
            </tr>
            <tr>
                <td class="new-td" colspan="2" tyle="text-align:left">Keterangan :</td>
                <td class="new-td" colspan="2" style="text-align:left"> <?=$fetch->ket;?></td>
                <td class="right-td"  style="text-align:right">Total</td>
                <td class="currency" style="text-align:right;border:1px solid"><?=number_format($fetch->total*($fetch->ppn/100)+$fetch->total,0,",",".");?></td>
            </tr>
        </tfoot>
    </table>
        </div>
        <div style="padding-top:40px">
            <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Supplier &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Disetujui Oleh &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Dibuat Oleh</p>
<p><br></p>
<p>&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; ______________ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ______________ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;______________&nbsp;</p>

        </div>
</body>
</html>