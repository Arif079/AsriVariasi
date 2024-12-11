<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-size: 12px;
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
            padding: 2px 2px;
        }

         .currency:before {
        content: "Rp "; /* Add your currency symbol here */
        float: left; /* Align symbol to the left */
        margin-left: 2mm;
        margin-top: 2mm; /* Add space before the symbol */
    }
    </style>
</head>
<body>
    <h1>LAPORAN SPK</h1>
    <div style="text-align:center"><?=$subtitle;?></div>

    <table width="100%" class="table">
        <thead>
            <tr>
                <th width="5%">Tanggal</t>
                <th width="5%">No SPK</th>
                <th width="5%">No WO</th>
                <th width="5%">No Invoice</th>
                <th>Nama Customer</th>
                <th>Sales</th>
                <th>Tipe Kendaraan</th>
                <th>Total</th>
                <th>Lunas/Tidak Lunas</th>
                <th>Sisa</th>
               
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            $grand_total = 0;
            $grand_sisa = 0;
            usort($fetch, function($a, $b) {
                return strtotime($a->date) - strtotime($b->date);
            });
            foreach($fetch as $row) {
                $i++;
                $grand_total = $grand_total + $row->total;
                $grand_sisa = $grand_sisa + $row->sisa;
            ?>

                <tr>
                    <td style="text-align:center"><?=date("d-m-Y",strtotime($row->date));?></td>
                    <td style="text-align:center"><?=$row->spkid;?></td>
                    <td style="text-align:center"><?=$row->nowo;?></td>
                    <td style="text-align:center"><?=$row->noinvoice;?></td>
                    <td style="text-align:center"><?=$row->customer."-".$row->nama_qq;?></td>
                    <td style="text-align:center"><?=$row->sales;?></td>
                    <td style="text-align:center"><?=$row->tipe; ?>/<?= substr($row->tahun, -2); ?>/<?= $row->warna; ?>/<?= substr($row->noka, -5); ?></td>
                    <td style="text-align:center"><?=rupiah($row->total);?></td>
                    <td style="text-align:center">
                                                                        <?php
                                                                        if ($row->lunas == 0) {
                                                                            echo "BELUM LUNAS";
                                                                        } elseif ($row->lunas == 1) {
                                                                            echo "LUNAS";
                                                                        } else {
                                                                            // Handle other cases if needed
                                                                        }
                                                                        ?>
                                                                    </td>
                    <td style="text-align:center"><?=rupiah($row->sisa);?></td>
                    

                </tr>

            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">Total SPK : <?=$i?></td>
                <td >Grand Total</td>
                <td style="text-align:center"><?=rupiah($grand_total);?></td>
                <td style="text-align:center">Total Sisa</td>
                <td style="text-align:center"><?=rupiah($grand_sisa);?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>