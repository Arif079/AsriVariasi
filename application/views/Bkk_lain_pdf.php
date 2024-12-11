<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bukti Kas Keluar</title>

    <style>
        body {
            font-size: 16px;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
        }

        .header h1 {
            text-decoration: underline;
            font-size: 22px;
        }
        body {
            font-size: 16px;
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
            float: right;
            margin-right: 5px; /* Adjust margin as needed */
        }
        .table-mid {
            margin-top:18px;
            border-collapse:collapse;
        }
        .currency:before {
        content: "Rp "; /* Add your currency symbol here */
        float: left; /* Align symbol to the left */
        margin-left: 2mm; /* Add space before the symbol */
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
        <span class="title"><?=$bkkno->bkkno;?></span>
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
            <img src="<?=$img_src;?>" style="max-width:150px;float:left">
        <h1>BUKTI KAS KELUAR</h1>
    </div>

  
    
    <div class="wrapper clearfix" >
        <div style="form-row" > 
            <table border="1px solid #000" width="50%" style="margin-top: 12px;margin-left: -152px;">
            <tr style="border:none">
                <td style="border:none;font-size:12px;"colspan="4"><?=$this->shop_info->get_shop_name();?></td>
            <tr>
            <tr style="border:none">
                <td style="border:none;font-size:12px;"colspan="4"><?=$this->shop_info->get_shop_address();?></td>

            <tr>
            <tr>
                <td style="width:30%;border:none;font-size:12px;"></td>
                <td style="width:10%;border:none;font-size:12px;"></td>
                <td style="border:none;font-size:12px;"colspan="2"></td>
            <tr>
            <tr>
                <td style="width:30%;border:none;font-size:12px;">No Referensi</td>
                <td style="width:10%;border:none;font-size:12px;">:</td>
                <td style="border:none;font-size:12px;" colspan="2"><?=$fetch->nbl?></td>
            <tr>
            <tr>
                <td style="border:none;font-size:12px;">Tgl Pembayaran</td>
                <td style="width:10%;border:none;font-size:12px;">:</td>
                <td style="border:none;font-size:12px;"colspan="2"><?=date("d/m/Y",strtotime($fetch->date));?></td>
            <tr>
            </table>
            <table Border="1px solid #000" width="50%" style="margin-top: 12px;margin-left: 5px">
            <tr>
                <td style="border:none;font-size:12px;"colspan="4">Dibayarkan Kepada</td>
            <tr> 
            <tr>
                <td style="border:none;font-size:12px">Supplier</td>
                <td style="border:none;font-size:12px">:</td>
                <td style="border:none;font-size:12px"colspan="2"><?=$fetch->customer?></td>
            <tr> 
            <tr>
                <td style="border:none;font-size:12px">Alamat</td>
                <td style="border:none;font-size:12px">:</td>
                <td style="border:none;font-size:12px"colspan="2"><?=$fetch->alamat?></td>
            <tr> 
            
            <tr>
                <td style="border:none;font-size:12px">Akun</td>
                <td style="border:none;font-size:12px">:</td>
                <td style="border:none;font-size:12px"colspan="2"><?=$fetch->coak?></td>
            <tr> 
            <tr>
                <td style="border:none;font-size:12px"></td>
                <td style="border:none;font-size:12px"></td>
                <td style="border:none;font-size:12px"colspan="2"><?=$fetch->uraian?></td>
            <tr> 
            
            </table>
        </div>
    </div>
    <table class="table-mid" width="100%">
        <thead>
            
            <tr>
                <th >Perkiraan</th>
                <th width="60%" >Uraian</th>
                <th >Jumlah</th>
             <!--   <th>Sub-Total</th> -->
            </tr>
        </thead>
        <tbody>
        

                <tr>
                    <td style="border-bottom:none;text-align:center" width="30%"><?=$fetch->coad;?></td>
                    <td style="border-bottom:none;" width="50%"><?=($fetch->uraian);?></td>
                    <td class="currency" style="border-bottom:none;text-align:right" width="20%"><?=number_format($fetch->jumlah,0,",",".");?></td>
                </tr>

                <tr>
                    <td style="border-top:none;padding: 10px;"></td>
                    <td style="border-top:none;padding: 10px;"></td>
                    <td style="border-top:none;padding: 10px;"></td>
                </tr>
        </tbody>
    </table>
    <table class="table" align="right" width="80%">
            <tr>
                <td>Pembukuan</td>
                <td>Mengetahui</td>
                <td>Menyetujui</td>
                <td>Kasir</td>
                <td>Penerima</td>
            </tr>
            <tr >
                <td ><br><br><br></td>
                <td ><br><br><br></td>
                <td ><br><br><br></td>
                <td ><br><br><br></td>
                <td ><br><br><br></td>
            </tr>
        </table>
</body>
</html>