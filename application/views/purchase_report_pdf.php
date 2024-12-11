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
        }
    </style>
</head>
<body>
    <h1>LAPORAN PEMBELIAN</h1>
    <div style="text-align:center"><?=$subtitle;?></div>

    <table width="100%" class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>NOP</th>
                <th>BTB</th>
                <th>NTP</th>
                <th>Supplier</th>
                <th>Item</th>
                <th>Tanggal</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Ppn</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            $grand_total = 0;
            foreach($fetch as $row) {
                $i++;
                $grand_total = $grand_total + $row->grntotal;
            ?>

                <tr>
                    <td style="text-align:center"><?=$i;?></td>
                    <td style="text-align:center"><?=$row->nop;?></td>
                    <td style="text-align:center"><?=$row->nbtb;?></td>
                    <td style="text-align:center"><?=$row->ntrn;?></td>
                    <td style="text-align:center"><?=$row->supp;?></td>
                    <td style="text-align:center"><?=$row->prod;?></td>
                    <td style="text-align:center"><?=date("d-m-Y",strtotime($row->date));?></td>
                    <td style="text-align:center"><?=$row->qty;?></td>
                    <td style="text-align:center"><?=rupiah($row->price);?></td>
                    <td style="text-align:center"><?=rupiah($row->ppn);?></td>
                    <td style="text-align:center"><?=rupiah($row->grntotal);?></td>
                </tr>

            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="10">Total</td>
                <td style="text-align:center"><?=rupiah($grand_total);?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>