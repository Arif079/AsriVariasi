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

        h2 {
            text-align:left;
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
    <h1>BUKU BESAR</h1>
<?php
    function sortByDate($a, $b) {
    return strtotime($a->date) - strtotime($b->date);
}

usort($fetch, 'sortByDate');

// Grouping transactions by COA
$coa_transactions = [];
foreach ($fetch as $row) {
    $coa = $row->coa;
    $nama = $row->nama;
    if (!isset($coa_transactions[$coa])) {
        $coa_transactions[$coa] = [];
    }
    $coa_transactions[$coa][] = $row;
}

// Iterate through each COA
foreach ($coa_transactions as $coa => $transactions) {
    // Initialize total debit, credit, and current balance for each COA
    $total_credit = 0;
    $total_debit = 0;
    $current_balance = 0;
    ?>
    <!-- Table for each COA -->
    <table width="100%" class="table">
        <thead>
            <tr><td colspan="6"><h2><?= $coa ?> <?=$nama?></h1></td></tr>
            <tr>
                <th>Tanggal</th>
                <th>Referensi</th>
                <th>COA Lawan</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Iterate through transactions for each COA
        foreach ($transactions as $row) {
           
            // Update total debit, credit, and current balance
            $total_debit += $row->debet;
            $total_credit += $row->kredit;
            $current_balance += $row->debet - $row->kredit;
            ?>
            <!-- Table rows for each transaction -->
            <tr>
                <td style="text-align:center"><?= date("d-m-Y", strtotime($row->date)); ?></td>
                <td style="text-align:center"><?= $row->kdtransaksi ?></td>
                <td style="text-align:center"><?= $row->coalawan ?></td>
                <td style="text-align:center"><?= rupiah($row->debet); ?></td>
                <td style="text-align:center"><?= rupiah($row->kredit); ?></td>
                <td style="text-align:center"><?= rupiah($current_balance); ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <tfoot>
        <!-- Table footer with total values -->
        <tr>
            <td colspan="3">Total</td>
            <td style="text-align:center"><?= rupiah($total_debit); ?></td>
            <td style="text-align:center"><?= rupiah($total_credit); ?></td>
            <td style="text-align:center"><?= rupiah($current_balance); ?></td>
        </tr>
        </tfoot>
    </table>
    <?php
}
?>

</body>
</html>