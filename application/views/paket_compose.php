        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Tambah Paket Produk</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li><a href="<?=base_url("Paket");?>">Paket Produk</a></li>
                            <li class="active">Tambah Paket Produk</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label>Supplier :</label>
                            <input type="text" class="form-control select-search" placeholder="Search...">
                            <select class="form-control supplier">
                            
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($suppliers as $supllier) {
                                    echo '<option value="'.$supllier->id.'">'.$supllier->name.'</option>
                                    ';
                                }
                                ?>
                            </select>
                            <label>Kode Job :</label>
                            <input type="text" class="form-control kdjob" >
                            <label>Nama Job :</label>
                            <input type="text" class="form-control namajob" >
                            <label>Kode Paket :</label>
                            <input type="text" class="form-control kdpaket" >
                            <label>Nama Paket :</label>
                            <input type="text" class="form-control nama" >
                            <label>Harga Jual Paket :</label>
                            <input type="number" class="form-control harga" >
                            <label>Harga Jual Paket QQ :</label>
                            <input type="number" class="form-control hargaqq" >
                            <label>HPP Paket:</label>
                            <input type="number" class="form-control hpp" >
                        </div>
                        <div class="form-group">
                                <label>Kode Akun HPP</label>
                                <input type="text" class="form-control select-search" placeholder="Search...">
                                <select name="coahpp" id="coahpp"class="form-control coa">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($coa as $akun) {
                                    if ($akun->isdetail == '1') {
                                    echo '<option value="'.$akun->coa.'">'.$akun->coa.' - '.$akun->nama.'</option>
                                    ';
                                    }
                                }
                                ?>
                        </select>
                        </div>
                        <div class="form-group">
                                <label>Kode Akun Persediaan</label>
                                <input type="text" class="form-control select-search" placeholder="Search...">
                                <select name="coapersediaan" id="coapersediaan" class="form-control coa">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($coa as $akun) {
                                    if ($akun->isdetail == '1') {
                                    echo '<option value="'.$akun->coa.'">'.$akun->coa.' - '.$akun->nama.'</option>
                                    ';
                                    }
                                }
                                ?>
                        </select>
                        </div>
                        <div class="form-group">
                                <label>Kode Akun Penjualan</label>
                                <input type="text" class="form-control select-search" placeholder="Search...">
                                <select name="coapenjualan" id="coapenjualan" class="form-control coa">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($coa as $akun) {
                                    if ($akun->isdetail == '1') {
                                    echo '<option value="'.$akun->coa.'">'.$akun->coa.' - '.$akun->nama.'</option>
                                    ';
                                    }
                                }
                                ?>
                            </select>
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
                                <tr>
                                    <td><div class="product-name form-control form-control-sm" data-id="" data-stock="">--Pilih--</div></td>
                                    <td><span class="product-price orm-control form-control-sm" data-price=""></td>
                                    <td><input type="number" class="form-control form-control-sm" name="qty"></td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td>
                                </tr>
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
            $("#listProduct").DataTable({
                info: false,
                lengthChange: false,
                autoWidth: false,
                "processing": true,
                "serverSide": true,
                "ajax": {"url": "<?=base_url("Paket/json_product");?>"}
            });

            $(".btn-add-row").on("click",function(){

                jQuery("#PaketForm tbody").append('<tr><td><div class="product-name form-control form-control-sm" data-id="" data-stock="">--Pilih--</div></td><td><span class="product-price form-control form-control-sm" data-price=""></td><td><input type="number" class="form-control form-control-sm" name="qty"></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');                

            })

            $('body').on("click",".product-name",function(){
                var index = jQuery(this).parent().parent().index();

                jQuery("#selectProduct").attr("data-index",index);
                jQuery("#selectProduct").modal("toggle");
            })

            $('body').on("click",".btn-choose",function(){
                var indexAffected = parseInt(jQuery("#selectProduct").attr("data-index")) + 1;

                var id = jQuery(this).attr("data-id");
                var name = jQuery(this).attr("data-name");
                var stock = jQuery(this).attr("data-stock");
                var price = jQuery(this).attr("data-price");

                jQuery("#PaketForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-id",id);
                jQuery("#PaketForm tbody tr:nth-child("+indexAffected+") .product-name").attr("data-stock",stock);
                jQuery("#PaketForm tbody tr:nth-child("+indexAffected+") .product-name").html(name);
                jQuery("#PaketForm tbody tr:nth-child("+indexAffected+") .product-price").attr("data-price",price);
                jQuery("#PaketForm tbody tr:nth-child("+indexAffected+") .product-price").text(price);

                jQuery("#selectProduct").modal("toggle");
            })

            $(".btn-save").on("click",function(){
                var items = [];
                var total = 0;
                var countItem = jQuery("#PaketForm tbody tr").length;
                
                for(var i = 1;i<=countItem;i++) {
                    var item = "#PaketForm tbody tr:nth-child("+i+")";
                    var tmp = {};
                    tmp["product_id"] = jQuery(item+" .product-name").attr("data-id");
                    tmp["product_stock"] = jQuery(item+" .product-name").attr("data-stock");
                    tmp["price"] = jQuery(item+" .product-price").text();
                    tmp["qty"] = jQuery(item+" input[name=qty]").val();
                    total = total + parseFloat(tmp["price"]); //* tmp["qty"]);

                    items.push(tmp);
                }

                var form = {};
                form["supplier_id"] = jQuery(".supplier").val();
                form["kdjob"] = jQuery(".kdjob").val();
                form["namajob"] = jQuery(".namajob").val();
                form["kdpaket"] = jQuery(".kdpaket").val();
                form["nama"] = jQuery(".nama").val();
                form["harga"] = jQuery(".harga").val();
                form["hargaqq"] = jQuery(".hargaqq").val();
                form["hpp"] = jQuery(".hpp").val();
                form["total"] = total;
                form["details"] = items;
                form["coapenjualan"] = jQuery("select[name=coapenjualan]").val();
                form["coahpp"] = jQuery("select[name=coahpp]").val();
                form["coapersediaan"] = jQuery("select[name=coapersediaan]").val();

                postJson(JSON.stringify(form));

            })

            function postJson(json) {  
                jQuery.ajax({
                    url: "<?=base_url('Paket/create');?>",
                    method: "POST",
                    data: json,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function(data){
                        if(data.status) {
                            jQuery(".supplier").val("");
                            jQuery("#PaketForm tbody").html('<tr><td><div class="product-name form-control form-control-sm" data-id="" data-stock="">--Pilih--</div></td><td><input type="number" class="form-control form-control-sm" name="price"></td><td><input type="number" class="form-control form-control-sm" name="qty"></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fa fa-times"></i></button></td></tr>');

                            Swal.fire(
                                "Berhasil",
                                data.msg,
                                "success"
                            );
                            window.history.back();
                        } else {
                            Swal.fire(
                                "Gagal",
                                data.msg,
                                "error"
                            );
                        }
                    }
                })
            }

            $("body").on("click",".btn-del",function(){
                jQuery(this).parent().parent().remove();
            })
            $(document).ready(function () {
        // Add an input event listener to the search inputs
        $('.select-search').on('input', function () {
            var filterValue = $(this).val().toLowerCase();
            filterSelect($(this).siblings('select'), filterValue);
        });

        // Function to filter the options in the select element
        function filterSelect(select, filterValue) {
            var options = select.find('option');

            // Loop through each option and show/hide based on the filter
            options.each(function () {
                var option = $(this);
                var text = option.text().toLowerCase();
                if (text.includes(filterValue) || filterValue === '') {
                    option.show();
                } else {
                    option.hide();
                }
            });
         }
        });
        </script>1