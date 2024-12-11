<!-- application/views/field_list_view.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Field List</title>
</head>
<body>
    <h1>Field List for Table: <?php echo $table_name; ?></h1>
    <ul>
        <?php foreach ($columns as $column): ?>
            <li><?php echo $column['Field']; ?></li>
        <?php endforeach; ?>
    </ul>
    <!-- Modal pembayaran
        <div class="modal" id="purchaseModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="largeModalLabel">Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Bayar</label>
                            <input type="text" id="money" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Kembalian</label>
                            <input type="text" id="change" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-save-confirm" disabled>Lanjutkan</button>
                    </div>
                </div>
            </div>
        </div>
                            -->
</body>
</html>
