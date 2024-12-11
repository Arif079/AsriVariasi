<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-size: 9px;
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
    <h1><div style="text-align:center"><?=$subtitle;?></div></h1>

    <table width="100%" class="table">
        <thead>
            <tr>
            <th>Tanggal</th>
            <th>No Buku </th>
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
            function sortByDate($a, $b) {
                return strtotime($a->date) - strtotime($b->date);
            }

            usort($fetch, 'sortByDate');
            
            $i = 0;
            $total_credit=0;
            $total_debit=0;
            $current_balance = 0;
            foreach($fetch as $row) {
                $i++;
                $total_debit += $row->debet;
                $total_credit += $row->kredit;
                $current_balance += $row->debet - $row->kredit;
            ?>

                <tr>
                    <td style="text-align:center"><?=date("d-m-Y",strtotime($row->date));?></td>
                    <td style="text-align:center"><?=$row->buku?></td>
                    <td style="text-align:center"><?=$row->kdtransaksi?></td>
                    <td style="text-align:center"><?=$row->uraian?></td>
                    <td style="text-align:center"><?=$row->coalawan?></td>
                    <td style="text-align:center"><?=rupiah($row->debet);?></td>
                    <td style="text-align:center"><?=rupiah($row->kredit);?></td>
                    <td style="text-align:center"><?=rupiah($current_balance);?></td>
                    

                </tr>

            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
            <td colspan="5">Total</td>
                <td style="text-align:center"><?=rupiah($total_debit);?></td>
                <td style="text-align:center"><?=rupiah($total_credit);?></td>
                <td style="text-align:center"><?=rupiah($current_balance);?></td>
            </tr>
        </tfoot>
    </table>
    </table>
    <table class="table" align="right" width="100%">
            <tr>
                <td width="20%">Pembukuan</td>
                <td width="20%">Mengetahui</td>
                <td width="20%">Menyetujui</td>
                <td width="20%">Kasir</td>
                <td width="20%">Penerima</td>
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