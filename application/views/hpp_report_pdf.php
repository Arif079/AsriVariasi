<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-size: 16px;
        }
        h1 {
            text-align:center;
            text-decoration: underline;
            font-size: 26px;
        }

        .table {
            margin-top: 32px;
            border-collapse: collapse;
        }

        .table th,td {
            border:1px solid #000;
            padding: 7px 9px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <h1>LAPORAN HPP</h1>
    <div style="text-align:center"><?=$subtitle;?></div>

    <table width="100%" class="table">
        <thead>
            <tr>
            <th>Tanggal</th>
             <th>SPK</th>
             <th>Customer</th>
             <th>Qty</th>
             <th>HPP</th>
             <th>HARGA</th>
             <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            $grand_total = 0;
            $hargatotal = 0;
            $qtytotal = 0;
            foreach($fetch as $row) {
                $i++;
                $grand_total = $grand_total + $row->total;
                $hargatotal = $hargatotal + $row->total;
                $qtytotal = $qtytotal + $row->qty;
            ?>

                <tr>
                    <td style="text-align:center"><?=date("d-m-Y",strtotime($row->date));?></td>
                    <td style="text-align:center"><?=$row->spkid;?></td>
                    <td width="30%" style="text-align:center"><?=$row->customer."-".$row->nama_qq;?></td>
                    <td style="text-align:center"><?=$row->qty;?></td>
                  <td style="text-align:center"><?=rupiah($row->hppecer);?></td>
                    <td style="text-align:center"><?=rupiah($row->price);?></td>
                    <td style="text-align:center"><?=rupiah($row->total);?></td>
                    

                </tr>

            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td style="text-align:center"><?=rupiah($qtytotal);?></td>
                <td></td>
                <td style="text-align:center"><?=rupiah($hargatotal);?></td>
                <td style="text-align:center"><?=rupiah($grand_total);?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>