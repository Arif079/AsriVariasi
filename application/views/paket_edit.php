<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            <form id="editPaketForm">
                <input type="hidden" name="id" value="<?= $paket_id; ?>">
                <input type="hidden" name="prod" value="<?= $fetch->prod_id; ?>">
                <div class="form-group">
                    <label>Supplier :</label>
                    <select class="form-control supplier" name="supplier_id" required>
                        <option value="">-- Pilih --</option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?= $supplier->id; ?>" <?= ($supplier->id == $fetch->supplier_id) ? 'selected' : ''; ?>><?= $supplier->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Kode Paket :</label>
                    <input type="text" class="form-control" name="kdpaket" value="<?= $fetch->kdpaket; ?>" required>
                    <label>Nama Paket :</label>
                    <input type="text" class="form-control" name="nama" value="<?= $fetch->nama; ?>" required>
                    <label>Harga Jual Paket :</label>
                    <input type="number" class="form-control" name="harga" value="<?= $fetch->harga; ?>" required>
                    <label>Harga Jual Paket QQ :</label>
                    <input type="number" class="form-control" name="hargaqq" value="<?= $fetch->hargaqq; ?>" required>
                    <label>HPP Paket:</label>
                    <input type="number" class="form-control" name="hpp" value="<?= $fetch->hpp; ?>" required>
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
                                        <?= $item->name; ?>
                                    </div>
                                </td>
                                <td><input type="number" class="form-control form-control-sm product-price" name="price" value="<?= $item->price; ?>" required></td>
                                <td><input type="number" class="form-control form-control-sm" name="qty" value="<?= $item->qty; ?>" required></td>
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
            <div class="modal-header <h5 class="modal-title" id="largeModalLabel">Pilih Barang</h5>
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
            autoWidth: false,
            "processing": true,
            "serverSide": true,
            "ajax": {"url": "<?= base_url('Paket/json_product'); ?>"}
        });

        // Add row functionality
        $(".btn-add-row").on("click", function() {
            $("#PaketForm tbody").append('<tr><td><div class="product-name form-control form-control-sm" data-id="" data-stock="">--Pilih--</div></td><td><input type="number" class="form-control form-control-sm product-price" name="price" required></td><td><input type="number" class="form-control form-control-sm" name="qty" required></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');
        });

        // Product selection functionality
        $('body').on("click", ".product-name", function() {
            var index = $(this).closest('tr').index();
            $("#selectProduct").attr("data-index", index);
            $("#selectProduct").modal("toggle");
        });

        $('body').on("click", ".btn-choose", function() {
            var indexAffected = parseInt($("#selectProduct").attr("data-index")) + 1;
            var id = $(this).data("id");
            var name = $(this).data("name");
            var stock = $(this).data("stock");
            var price = $(this).data("price");

            $("#PaketForm tbody tr:nth-child(" + indexAffected + ") .product-name").attr("data-id", id).text(name);
            $("#PaketForm tbody tr:nth-child(" + indexAffected + ") .product-name").attr("data-stock", stock);
            $("#PaketForm tbody tr:nth-child(" + indexAffected + ") .product-price").attr("data-price", price).val(price);

            $("#selectProduct").modal("toggle");
        });

        // Delete row functionality
        $("body").on("click", ".btn-del", function() {
            $(this).closest('tr').remove();
        });

        // Form submission functionality
        $(".btn-save").on("click", function() {
            var items = [];
            var total = 0;
            var countItem = $("#PaketForm tbody tr").length;

            for (var i = 1; i <= countItem; i++) {
                var item = "#PaketForm tbody tr:nth-child(" + i + ")";
                var tmp = {};
                tmp["product_id"] = $(item + " .product-name").data("id");
                tmp["product_stock"] = $(item + " .product-name").data("stock");
                tmp["price"] = $(item + " .product-price").val();
                tmp["qty"] = $(item + " input[name=qty]").val();
                total += parseFloat(tmp["price"]) * parseInt(tmp["qty"]) || 0; // Ensure qty is an integer

                items.push(tmp);
            }

            var form = {
                "id": <?= $paket_id; ?>,
                "supplier_id": $(".supplier").val(),
                "kdpaket": $("input[name='kdpaket']").val(),
                "nama": $("input[name='nama']").val(),
                "harga": $("input[name='harga']").val(),
                "hargaqq": $("input[name='hargaqq']").val(),
                "hpp": $("input[name='hpp']").val(),
                "total": total,
                "details": items,
                "prod_id": $("input[name='prod']").val(),
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
                        Swal .fire("Berhasil", response.msg, "success");
                        window.location.href = "<?= base_url('Paket'); ?>";
                    } else {
                        Swal.fire("Gagal", response.msg, "error");
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire("Error", "Terjadi kesalahan saat menyimpan data. Silakan coba lagi.", "error");
                }
            });
        });
    });
</script>