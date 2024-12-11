<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MEMORIAL</title>

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

        .table {
            margin-top: 18px;
            border-collapse: collapse;
        }

        .table tr,td,th {
            border: 1px solid #000;
            height: 20px;
            width: 100px;
            text-align:center;
        }
        

    </style>
</head>
<body>
    <div class="header">
        <h1>MEMORIAL</h1>
        <hr></hr>
        <p style="text-align:left"><?=$this->shop_info->get_shop_name();?><br>
         <?=$this->shop_info->get_shop_address();?></p>

    </div>
    
    <table style="margin-top 18px; border-collapse:collapse;" width="100%">
    <thead>
        <tr>
            <th style="width:20%" > No Memo </th>
            <th style="width:20%" > Tgl Memo </th>
            <th style="width:60%" > Keterangan </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width:20% ;font-size:12px;text-align:center"><?=$fetch->nmm?></td>
            <td style="width:20% ;font-size:12px;"><?=date("d/m/Y",strtotime($fetch->date));?></td>
            <td style="width:60%; font-size:12px; text-align:left"><?=$fetch->uraian?></td>
        </tr>
    </tbody>
    </table>    
    <table class="table" width="80%">
        <thead>
            
            <tr>
                <th style="width:40%">Akun</th>
                <th style="width:20%">Debet</th>
                <th style="width:20%">Kredit</th>
             <!--   <th>Sub-Total</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            ?>

               
                <tr>
                    <td style="border-bottom:none;text-align:left;width:40%"><?=$fetch->coad?>&nbsp;<?=$fetch->nama_coad?></td>
                    <td style="text-align:center;border-bottom:none;widthL20%"><?=$fetch->jumlah?></td>
                    <td style="border-bottom:none;text-align:center;width:20%">0.00</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;text-align:left;width:40%"><?=$fetch->coak?>&nbsp;<?=$fetch->nama_coak?></td>
                    <td style="text-align:center;border-bottom:none;widthL20%">0.00</td>
                    <td style="border-bottom:none;text-align:center;width:20%"><?=$fetch->jumlah?></td>
                </tr>

            <?php
            ?>
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

</body>
</html>