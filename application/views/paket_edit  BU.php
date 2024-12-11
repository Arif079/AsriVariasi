<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            <form id="editPaketForm">
                <input type="hidden" name="id" value="<?= $paket_id; ?>">
                <div class="form-group">
                    <label>Supplier :</label>
                    <select class="form-control supplier" name="supplier_id">
                        <option value="">-- Pilih --</option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?= $supplier->id; ?>" <?= ($supplier->id == $fetch->supplier_id) ? 'selected' : ''; ?>><?= $supplier->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Kode Paket :</label>
                    <input type="text" class="form-control" name="kdpaket" value="<?= $fetch->kdpaket; ?>">
                    <label>Nama Paket :</label>
                    <input type="text" class="form-control" name="nama" value="<?= $fetch->nama; ?>">
                    <label>Harga Jual Paket :</label>
                    <input type="number" class="form-control" name="harga" value="<?= $fetch->harga; ?>">
                    <label>Harga Jual Paket QQ :</label>
                    <input type="number" class="form-control" name="hargaqq" value="<?= $fetch->hargaqq; ?>">
                    <label>HPP Paket:</label>
                    <input type="number" class="form-control" name="hpp" value="<?= $fetch->hpp; ?>">
                </div>

                <label>Barang :</label>
                <table class="table table-bordered" id="PaketForm">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th style="width:25%">Harga</th>
                            <th style="width:25%">Qty</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($detail as $item) { ?>
                            <tr>
                                <td>
                                    <div class="product-name form-control form-control-sm" data-id="<?= $item->product_id; ?>" data-stock="<?= $item->stock; ?>">
                                        <?= $item->name; ?> <!-- Display the product name here -->
                                    </div>
                                </td>
                                <td><input type="number" class="form-control form-control-sm" name="price" value="<?= $item->price; ?>"></td>
                                <td><input type="number" class="form-control form-control-sm" name="qty" value="<?= $item->qty; ?>"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
            <button class="btn btn-success btn-sm mb-2 btn-add-row"><i class="fa fa-plus"></i> Tambah</button>
        </div>
    </div>
    <div class="text-right mb-4">
        <button class="btn btn-secondary">Cancel</button>
        <button class="btn btn-primary btn-save"><i class="fa fa-save"></i> Simpan</button>
    </div>
</div>

<div class="modal" id="selectProduct" data-index="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largeModalLabel">Pilih Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="listProduct">
                    <thead>
                        <tr>
                            <th>Kode Item</th>
                            <th>Nama</th>
                            <th style="width:20%">Opsi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable for products
        $("#listProduct").DataTable({
            info: false,
            lengthChange: false,
            autoWidth: false ,
            "processing": true,
            "serverSide": true,
            "ajax": {"url": "<?= base_url("Paket/json_product"); ?>"}
        });

        // Add row functionality
        $(".btn-add-row").on("click", function() {
            jQuery("#PaketForm tbody").append('<tr><td><div class="product-name form-control form-control-sm" data-id="" data-stock="">--Pilih--</div></td><td><span class="product-price form-control form-control-sm" data-price=""></td><td><input type="number" class="form-control form-control-sm" name="qty"></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');
        });

        // Product selection functionality
        $('body').on("click", ".product-name", function() {
            var index = jQuery(this).parent().parent().index();

            jQuery("#selectProduct").attr("data-index", index);
            jQuery("#selectProduct").modal("toggle");
        });

        $('body').on("click", ".btn-choose", function() {
            var indexAffected = parseInt(jQuery("#selectProduct").attr("data-index")) + 1;

            var id = jQuery(this).attr("data-id");
            var name = jQuery(this).attr("data-name");
            var stock = jQuery(this).attr("data-stock");
            var price = jQuery(this).attr("data-price");

            jQuery("#PaketForm tbody tr:nth-child(" + indexAffected + ") .product-name").attr("data-id", id);
            jQuery("#PaketForm tbody tr:nth-child(" + indexAffected + ") .product-name").attr("data-stock", stock);
            jQuery("#PaketForm tbody tr:nth-child(" + indexAffected + ") .product-name").html(name);
            jQuery("#PaketForm tbody tr:nth-child(" + indexAffected + ") .product-price").attr("data-price", price);
            jQuery("#PaketForm tbody tr:nth-child(" + indexAffected + ") .product-price").text(price);

            jQuery("#selectProduct").modal("toggle");
        });

        // Delete row functionality
        $("body").on("click", ".btn-del", function() {
            jQuery(this).parent().parent().remove();
        });

        // Form submission functionality
        $(".btn-save").on("click", function() {
    var items = [];
    var total = 0;
    var countItem = jQuery("#PaketForm tbody tr").length;

    for (var i = 1; i <= countItem; i++) {
        var item = "#PaketForm tbody tr:nth-child(" + i + ")";
        var tmp = {};
        tmp["product_id"] = jQuery(item + " .product-name").attr("data-id");
        tmp["product_stock"] = jQuery(item + " .product-name").attr("data-stock");
        tmp["price"] = jQuery(item + " .product-price").text();
        tmp["qty"] = jQuery(item + " input[name=qty]").val();
        total += parseFloat(tmp["price"]) * parseInt(tmp["qty"]); // Ensure qty is an integer

        items.push(tmp);
    }

    var form = {
        "id": <?= $paket_id; ?>,
        "supplier_id": jQuery(".supplier").val(),
        "kdpaket": jQuery("input[name='kdpaket']").val(), // Correctly access the value
        "nama": jQuery("input[name='nama']").val(),
        "harga": jQuery("input[name='harga']").val(),
        "hargaqq": jQuery("input[name='hargaqq']").val(),
        "hpp": jQuery("input[name='hpp']").val(),
        "total": total,
        "details": items
    };

    $.ajax({
        url: "<?= base_url('paket/update'); ?>",
        method: "POST",
        data: JSON.stringify(form),
        dataType: "json",
        processData: false,
        contentType: "application/json",
        success: function(response) {
            if (response.status) {
                Swal.fire("Berhasil", response.msg, "success");
                window.location.href = "<?= base_url('Paket'); ?>";
            } else {
                Swal.fire("Gagal", response.msg, "error");
            }
        }
    });
});
    });
</script>