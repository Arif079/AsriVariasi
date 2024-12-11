<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Pembelian</title>

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
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .wrapper table{
            float:left;
        }
        .body-td {
            border-top: none; /* No top border */
            border-bottom: none; /* No bottom border */
            padding: 0 px; /* Optional padding for content inside the element */
        }
        .under-td{
            border-top:none;
            padding: 50px;
        
        }
        .image {
            float: left;
            margin-right: 5px; /* Adjust margin as needed */
        }
        .title {
            float:right;
            border: 1px solid #000;
            padding: 1px 22px;
        }

        .currency:before {
            content: "Rp "; /* Add your currency symbol here */
            float: left; /* Align symbol to the left */
            margin-left: 1mm; /* Add space before the symbol */
        }

        

    </style>
</head>
<body>
<span class="title"><?=$fetch->ntrn;?></span>
<div class="image" >
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
            <img src="<?=$img_src;?>" style="max-width:150px;">
    </div>
    <div class="header">
            <h1>TRANSAKSI PEMBELIAN</h1>
    </div >
    
        <hr style="border:1px double #000">
      
      
    
    <div class="wrapper clearfix" >
        <div style="form-row" > 
            <table border="1px solid #000" width="40%" style="margin-top: 12px;margin-left: -152px;">
            <tr style="border:none">
                <td style="border:none">Tanggal</td>
                <td style="border:none">:</td>
                <td style="border:none" colspan="2"><?=date("d/m/Y",strtotime($fetch->date));?></td>
            <tr>
            <tr style="border:none">
                <td style="border:none">No Faktur</td>
                <td style="border:none">:</td>
                <td style="border:none"colspan="2"><?=$faktur?></td>
            <tr>
            <tr>
                <td style="border:none">Tgl Jatuh Tempo</td>
                <td style="border:none">:</td>
                <td style="border:none"colspan="2"><?= date("d/m/Y", strtotime($fetch->date . " +30 days")); ?></td>
            <tr>
            <tr>
                <td style="border:none">No PO</td>
                <td style="border:none">:</td>
                <td style="border:none" colspan="2"><?=$order->nop?></td>
            <tr>
            <tr>
                <td style="border:none">Tgl Transaksi</td>
                <td style="border:none">:</td>
                <td style="border:none"colspan="2"><?=date("d/m/Y",strtotime($fetch->date));?></td>
            <tr>
            </table>
            <table Border="1px solid #000" width="60%" style="margin-top: 12px;margin-left: 5px">
            <tr>
                <td style="border:none">Supplier</td>
                <td style="border:none">:</td>
                <td style="border:none"colspan="2"><?=$fetch->name?></td>
            <tr> 
            <tr>
                <td style="border:none">Alamat</td>
                <td style="border:none">:</td>
                <td style="border:none"colspan="2"><?=$fetch->address?></td>
            <tr> 
            <tr>
                <td style="border:none">Telp</td>
                <td style="border:none">:</td>
                <td style="border:none"colspan="2"><?=$fetch->telephone?></td>
            <tr> 
            <tr><td style="border:none" rowspan="5" colspan="4"></tr>
            </table>
        </div>
    </div>

    <div >
    <table class="table" width="100%" style="margin-top: 20px;">
        <thead>
            <tr>
                <th >No</th>
                <th >Kode Item</th>
                <th >Nama Barang</th>
                <th >Qty</th>
                <th >Harga</th>
                <th >Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach($order_detail as $detail) {
                $i++;
            ?>

                <tr>
                    <td style="text-align:center"><?=$i;?></td>
                    <td style="text-align:center"><?=$detail->kditem;?></td>
                    <td ><?=$detail->name;?></td>
                    <td style="text-align:center"><?=$detail->qty;?></td>
                    <td class="currency" style="text-align:center"><?=number_format($detail->price,0,",",".");?></td>
                    <td class="currency" style="text-align:right"><?=number_format($detail->qty * $detail->price);?></td>
                </tr>
                
            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="total-td" colspan="5" style="text-align:right">Sub-Total</td>
                <td class="currency" style="text-align:right;border:1px solid"><?=number_format($order->total,0,",",".");?></td>
            </tr>
            <tr>
                <td class="right-td" colspan="5" style="text-align:right">PPN</td>
                <td class="currency" style="text-align:right;border:1px solid"><?=number_format($order->total*($order->ppn/100),0,",",".");?></td>
            </tr>
            <tr>
                <td class="right-td" colspan="5" style="text-align:right">Total Faktur</td>
                <td class="currency" style="text-align:right;border:1px solid"><?=number_format($order->total*($order->ppn/100)+$order->total,0,",",".");?></td>
            </tr>
        </tfoot>
    </table>
        </div>
        <div class="wrapper clearfix">
            <div class="form-row col-md-6">
        <table class="table" width="50%" style="margin-top: -50px;float:left">
        <thead>
            <tr>
                <th>No BTB</th>
                <th>No Surat Jalan</th>
                <th>Tgl Terima</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach($details as $detail) {
                $i++;
            ?>

                <tr>
                    <td style="text-align:center;border:none"><?=$detail->nbtb?></td>
                    <td style="text-align:center;border-bottom:none;border-top:none"><?=$detail->surat?></td>
                    <td style="text-align:center;border:none"><?=date("d/m/Y",strtotime($detail->date));?></td>
                </tr>
                
            <?php
            }
            ?>
            <tr><td class="body-td"><br></td><td class="body-td"><br></td><td class="body-td"><br></td></tr>
            <tr><td class="under-td"><br></td><td class="under-td"><br></td><td class="under-td"><br></td></td>
        </tbody>
        </table>
        <table width="50%" style="margin-top: 50px;margin-left: 5px;border:none;">
            <tr>
                <td style="border:none;text-align:center">Mengetahui</td><td style="border:none;">&nbsp;</td><td style="border:none;text-align:center">Dibuat Oleh</td>
            <tr> 
            <tr>
                <td style="border:none;"><br><br><td style="border:none;">&nbsp;</td></td><td style="border:none"><br><br></td>
            <tr> 
            <tr>
                <td style="border:none;border-top:1px solid #000;text-align:center">Bagian Keuangan</td><td style="border:none;">&nbsp;</td><td style="border:none;border-top:1px solid #000;text-align:center"><?php echo $this->session->auth['username']; ?></td>
            <tr> 
            </table>
        </div>
        </div>
</body>
</html>