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
    <h1>LAPORAN KAS</h1>
    <h1>COA : 1-1-101 KAS KECIL VARIASI</h1>
    <div style="text-align:center"><?=$subtitle;?></div>

    <table width="100%" class="table">
        <thead>
            <tr>
            <th>Tanggal</th>
            <th>No Bukti</th>
            <th>Keterangan</th>
            <th>COA Lawan</th>
            <th>Debet</th>
            <th>Kredit</th>
            <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            $grand_total = 0;
            foreach($fetch as $row) {
                $i++;
                $grand_total = $grand_total + $row->saldo;
            ?>

                <tr>
                    <td style="text-align:center"><?=date("d-m-Y",strtotime($row->date));?></td>
                    <td style="text-align:center"><?=$row->kdtransaksi;?></td>
                    <td style="text-align:center"><?=$row->uraian;?></td>
                    <td style="text-align:center"><?=$row->coa?></td>
                    <td style="text-align:center"><?=$row->debet?></td>
                    <td style="text-align:center"><?=$row->kredit;?></td>
                    <td style="text-align:center"><?=$row->saldo; ?></td>
                    

                </tr>

            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total</td>
                <td style="text-align:center"><?=rupiah($grand_total);?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>