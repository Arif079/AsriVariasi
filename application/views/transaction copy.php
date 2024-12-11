<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Transaksi</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Transaksi</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div >

                    <div class="card" id="customerContainer"  style="display:Block;">
                        <div class="card-header">
                            <b>Data Pelanggan</b>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Pelanggan</label>
                                <input type="text" class="form-control select-search" placeholder="Search...">
                                <select class="form-control cust" id="cust-select" style="height:40px">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($customers as $cust) {
                                    {
                                        echo '<option value="' . $cust->name . '" data-alamat="' . $cust->address . '" data-telp="' . $cust->telephone.'" data-coad="' . $cust->coad . '">' . $cust->name . '</option>';
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            <input type="hidden" id="coad" name="coad">
                            <div class="form-group">
                                <label>Nama QQ</label>
                                <input type="text" id="namaqq" name="nama_qq" class="form-control form-control-sm" style="height:40px">
                            </div>
                            <div class="form-group">
                                <label>Nama Sales</label>
                                <input type="text" class="form-control select-search" placeholder="Search...">
                                <select class="form-control sales" style="height:40px" id="sales-select">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($sales as $sls) {
                                    if ($sls->type == 'SLS') {
                                        echo '<option value="' . $sls->name . '">' . $sls->name . '</option>';
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" name="alamat" id="alamat"style="height:40px" class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label>No Telp</label>
                                <input type="text" name="telp" id="telp" style="height:40px"class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label>Nomor polisi</label>
                                <input type="text" name="plat"style="height:40px" class="form-control form-control-sm">
                            </div>
                        </div>
                    <div class="col-md-6">
                            <div class="form-group">
                                <label>Type Mobil</label>
                                <input type="text" name="tipe"style="height:40px" class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label>Merk Mobil</label>
                                <input type="text" name="merk" style="height:40px"class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label>Nomor Rangka</label>
                                <input type="text" name="noka" style="height:40px"class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label>Nomor Mesin</label>
                                <input type="text" name="nosin"style="height:40px" class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label>Tahun</label>
                                <input type="text" name="tahun"style="height:40px" class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label>Warna</label>
                                <input type="text" name="warna" style="height:40px"class="form-control form-control-sm">
                            </div>
                            <div class="form-group">
                                <label>Diskon</label>
                                <input type="number" name="disc" style="height:40px"id="disc" value="0" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
              
            <div class="row">
                <div class="col-md-7 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-services-tab" data-toggle="tab" href="#nav-services" role="tab" aria-controls="nav-services" aria-selected="true">Job</a>
                                    <a class="nav-item nav-link" id="nav-sparepart-tab" data-toggle="tab" href="#nav-sparepart" role="tab" aria-controls="nav-sparepart" aria-selected="false" style="display:none">Item</a>
                                    <a class="nav-item nav-link" id="nav-paket-tab" data-toggle="tab" href="#nav-paket" role="tab" aria-controls="nav-paket" aria-selected="false">Paket</a>
                                </div>
                            </nav>
                            <div class="tab-content pt-3" id="nav-tabContent">
                                
                                <div class="tab-pane fade show active" id="nav-services" role="tabpanel" aria-labelledby="nav-services-tab">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>Kode item</th>
                                                    <th>Nama</th>
                                                    <th id="harga">Harga</th>
                                                    <th id="hargaqq">Harga QQ</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                    <div Class="col-md-5">
                    <div class="card" id="serviceCartContainer" style="display:none">
                        <div class="card-header">
                            <b>Job</b>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered" id="serviceCart">
                                <thead>
                                    <tr>
                                        <th>kode Job</th>
                                        <th>Nama Job</th>
                                        <th id="test" style="display:none;">Harga</th>
                                        <th id="test" style="display:none;">Harga QQ</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card" id="paketCartContainer" style="display:none">
                        <div class="card-header">
                            <b>Paket</b>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered" id="paketCart">
                                <thead>
                                    <tr>
                                        <th>Kode Item</th>
                                        <th>Nama</th>
                                        <th id="harga" style="display:none;" >Harga</th>
                                        <th id="hargaqq" style="display:none;">Harga QQ</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card" id="sparepartCartContainer" style="display:none">
                        <div class="card-header">
                            <b>Item</b>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered" id="sparepartCart">
                                <thead>
                                    <tr>
                                        <th>Kode item</th>
                                        <th>Nama</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Aksi</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                   
                    <div class="card">
                        <div class="card-header">
                            <b>Detail Pembayaran</b>
                        </div>
                        <div class="card-body">
                        <div style="border-bottom: 1px dashed #aaa" class="d-flex py-2">
                                <span>Diskon</span>
                                <span class="diskon ml-auto">Rp. 0</span>
                            </div>   
                        <div style="border-bottom: 1px dashed #aaa" class="d-flex py-2">
                                <span>Total</span>
                                <span class="total ml-auto">Rp. 0</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex mb-4" style="box-sizing:border-box">
                        <div class="col-6 p-0 pr-1">
                            <button type="button" class="btn btn-secondary btn-block" onclick="reset()">Batal</button>
                        </div>
                        <div class="col-6 p-0 pl-1">
                            <button type="button" class="btn btn-primary btn-block btn-save-confirm" >Buat SPK</button>
                        </div>
                    </div>
                </div>
            </div>
                            </div>
        </div>

        <script>

            $("#dataTable").DataTable({
                "processing": true,
                "serverSide": true,
                "autoWidth": false,
                "order": [],
                "info": false,
                "language": {
                    search: "<span style='margin-right: 26px'>Cari :</span>"
                },
                "lengthChange": false,
                "ajax": {"url": "<?=base_url("transaction/json_service");?>"}
            });

            $('#nav-services-tab').on("click",function(){
                jQuery("#dataTable").DataTable().ajax.url("<?=base_url("transaction/json_service");?>").load();
            });
            $('#nav-sparepart-tab').on("click", function () {
                var serviceKDitems = getServiceKDitems(); // Get KDitem values from the "Service" container
                var url = "<?=base_url("transaction/json_sparepart");?>";
                
                // Append the serviceKDitems as query parameters to the URL
                url += "?serviceKDitems=" + serviceKDitems.join(",");
                
                jQuery("#dataTable").DataTable().ajax.url(url).load();
            });

            $('#nav-paket-tab').on("click",function(){
                jQuery("#dataTable").DataTable().ajax.url("<?=base_url("transaction/json_paket");?>").load();
            });

            var ServiceCart = [];
            var SparepartCart = [];
            var PaketCart = [];
            var total = 0;
            //var discount = 0;
            var type = "";
            
            function addServiceCart(data){
                var before = ServiceCart;
                var qty = 1;

                if(before[data.id]) {
                    qty = before[data.id]["qty"] + 1;
                } else {
                    before[data.id] = data;
                }
                
                before[data.id]["qty"] = qty;
                ServiceCart = before;
                
                refreshServiceCart(ServiceCart,SparepartCart,PaketCart);
            }

            

            function addPaketCart(data){
                var before = PaketCart;
                var qty = 1;

                if(before[data.id]) {
                    qty = before[data.id]["qty"] + 1;
                } else {
                    before[data.id] = data;
                }
                
                before[data.id]["qty"] = qty;
                PaketCart = before;
                
                refreshServiceCart(ServiceCart,SparepartCart,PaketCart);
            }

            function addSparepartCart(data) {
                var before = SparepartCart;
                var qty = 1;

                if(before[data.id]) {
                    qty = before[data.id]["qty"] + 1;
                }

                if(qty <= data.stock) {
                    if(!before[data.id]) {
                        before[data.id] = data;
                    }
                    before[data.id]["qty"] = qty;
                } else {
                    Swal.fire(
                        'Gagal',
                        'Stok tidak cukup',
                        'error'
                    )
                }

                SparepartCart = before;

                refreshServiceCart(ServiceCart,SparepartCart,PaketCart);
            }

            function refreshServiceCart(data1,data2,data3) {
                var namaQQInput = document.getElementById('namaqq').value;
                var html1 = "";
                var html2 = "";
                var html3 = "";
                var countTotal = 0;
                data1 = data1.filter(function (el) {
                    return el != null;
                });
                data2 = data2.filter(function (el) {
                    return el != null;
                });
                data3 = data3.filter(function (el) {
                    return el != null;
                });

                data1.forEach(function(item,index){
                    html1 += '<tr><td>'+item.kditem+'</td><td>'+item.name+'</td><td class="text-center"><button type="button" class="btn btn-sm btn-danger" onclick="deleteServiceCart('+item.id+')"><i class="fa fa-times"></i></button></td></tr>';
                    if (namaQQInput) {
                        countTotal = (countTotal + (item.priceqq * item.qty));
                    } else {
                        countTotal = (countTotal + (item.price * item.qty));
                    }
                })
                data2.forEach(function(item,index){
                    html2 += '<tr><td>'+item.kditem+'</td><td>'+item.name+'</td><td style="display:none" >Rp '+item.price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')+'</td><td style="display:none" >Rp '+item.priceqq.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')+'</td><td class="text-center"><input type="number" style="width:52px" value="'+item.qty+'" class="change-qty" data-hpp="'+item.hpp+'" data-jual="'+item.jual+'" data-sedia="'+item.sedia+'" data-id="'+item.id+'" data-stock="'+item.stock+'"/></td><td><button type="button" class="btn btn-sm btn-danger" onclick="deleteSparepartCart('+item.id+')"><i class="fa fa-times"></i></button></td></tr>';
                    if (namaQQInput) {
                        countTotal = (countTotal + (item.priceqq * item.qty));
                    } else {
                        countTotal = (countTotal + (item.price * item.qty));
                    }
                    console.log(item.stock);
                })
                data3.forEach(function(item,index){
                    html3 += '<tr><td>'+item.kditem+'</td><td>'+item.name+'</td><td style="display:none" >Rp '+item.price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')+'</td><td style="display:none"value="Rp '+item.priceqq.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')+' data-hpp="'+item.hpp+'" data-jual="'+item.jual+'" data-persediaan="'+item.persediaan+'"></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger" onclick="deletePaketCart('+item.id+')"><i class="fa fa-times"></i></button></td></tr>';
                    if (namaQQInput) {
                        countTotal = (countTotal + (item.priceqq * item.qty));
                    } else {
                        countTotal = (countTotal + (item.price * item.qty));
                    }
                })
                var discount = parseInt(document.getElementById('disc').value);
                total = countTotal-discount;
               

                if(data1.length) {
                    jQuery("#serviceCartContainer").attr("style","display:block");
                    $("#nav-sparepart-tab").css("display", "block");
                    type = "service";
                } else {
                    jQuery("#serviceCartContainer").attr("style","display:none");
                    type = "sparepart";
                }
                if(data2.length) {
                    jQuery("#sparepartCartContainer").attr("style","display:block");
                } else {
                    jQuery("#sparepartCartContainer").attr("style","display:none");
                }
                if(data3.length) {
                    jQuery("#paketCartContainer").attr("style","display:block");
                } else {
                    jQuery("#paketCartContainer").attr("style","display:none");
                }

                jQuery("#serviceCart tbody").html(html1);
                jQuery("#sparepartCart tbody").html(html2);
                jQuery("#paketCart tbody").html(html3);
                jQuery('.diskon').html("Rp " + discount.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
                jQuery('.total').html("Rp "+total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
            }

            function deleteServiceCart(id) {
                delete ServiceCart[id];
                
                refreshServiceCart(ServiceCart,SparepartCart,PaketCart);
            }
            function deleteSparepartCart(id) {
                delete SparepartCart[id];
                
                refreshServiceCart(ServiceCart,SparepartCart,PaketCart);
            }

            function deletePaketCart(id) {
                delete PaketCart[id];
                
                refreshServiceCart(ServiceCart,SparepartCart,PaketCart);
            }

            $("body").on('change','.change-qty',function(){
                var before = SparepartCart;
                var qty = jQuery(this).val();
                var id = jQuery(this).attr("data-id");
                var stock = parseInt(jQuery(this).attr("data-stock"));
                
                if(qty <= stock) {
                    before[id]["qty"] = qty;
                } else {
                    Swal.fire(
                        'Gagal',
                        'Stok tidak cukup',
                        'error'
                    )
                }
                if(qty <= 0) {
                    delete before[id];
                }
                SparepartCart = before;

                refreshServiceCart(ServiceCart,SparepartCart,PaketCart);
            })

            function reset() {
                jQuery("#customerContainer input").val("");
                ServiceCart = [];
                SparepartCart = [];
                PaketCart = [];

                jQuery("#dataTable").DataTable().ajax.reload(null,true);

                refreshServiceCart(ServiceCart,SparepartCart,PaketCart);
            }

            function saveModal() {
                if(!total) {
                    Swal.fire(
                        'Gagal',
                        'Keranjang kosong',
                        'error'
                    )
                } else {
                    jQuery("#purchaseModal").modal("toggle");
                }
            }

            $("#money").on("keyup",function(){
                var value = jQuery(this);
                var change = parseInt(value.val()) - total;

                if(change < 0) {
                    change = "Belum cukup";
                    jQuery(".btn-save-confirm").prop("disabled",true);
                } else {
                    jQuery(".btn-save-confirm").prop("disabled",false);
                }

                jQuery("#change").val(change.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
            })

            $(".btn-save-confirm").on("click",function(){

                if(type == "sparepart") {
                    var url = "<?=base_url("transaction/insert/sparepart");?>";
                } else {
                    var url = "<?=base_url("transaction/insert/service");?>";
                }

                var itemSparepart = SparepartCart.filter(function (el) {
                    return el != null;
                });

                var itemPaket = PaketCart.filter(function (el) {
                    return el != null;
                });

                var form = {};
                form["total"] = total;
                form["sparepart"] = itemSparepart;
                form["paket"] = itemPaket;

                if(type == "service") {
                    form["customer"] = jQuery("#cust-select").val();
                    form["coad"] = jQuery("input[name=coad]").val();
                    form["sales"] = jQuery("#sales-select").val();
                    form["nama_qq"] = jQuery("input[name=nama_qq]").val();
                    form["alamat"] = jQuery("input[name=alamat]").val();
                    form["telp"] = jQuery("input[name=telp]").val();
                    form["plat"] = jQuery("input[name=plat]").val();
                    form["tipe"] = jQuery("input[name=tipe]").val();
                    form["merk"] = jQuery("input[name=merk]").val();
                    form["noka"] = jQuery("input[name=noka]").val();
                    form["nosin"] = jQuery("input[name=nosin]").val();
                    form["tahun"] = jQuery("input[name=tahun]").val();
                    form["warna"] = jQuery("input[name=warna]").val();
                    form["diskon"] = jQuery("input[name=disc]").val();
                    form["service"] = ServiceCart.filter(function (el) {
                        return el != null;
                    });
                }

                form = JSON.stringify(form);

                jQuery.ajax({
                    url: url,
                    method: "POST",
                    data: form,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function(data){
                        if(data.status) {
                            reset();
                            jQuery("#purchaseModal").modal("toggle");
                            jQuery("#change").val("");
                            jQuery("#money").val("");
                            if(data.type == "sparepart") {
                                Swal.fire(
                                    "Berhasil",
                                    data.msg,
                                    "success"
                                );
                            } else {
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: data.msg,
                                    icon: 'success',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'Lanjutkan',
                                    }).then((result) => {
                                        if (result.value) {
                                            window.location.href = "<?=base_url("wo");?>";
                                        } else {
                                            window.location.href = "<?=base_url("wo");?>";
                                        }
                                })
                            }
                        }
                    }
                });
            })

            document.addEventListener('DOMContentLoaded', function() {
                // Handle the change event of the customer select element
                $('#cust-select').change(function() {
                    // Get the selected customer's data attributes
                    var selectedCustomer = $('#cust-select option:selected');
                    var alamat = selectedCustomer.data('alamat');
                    var telp = selectedCustomer.data('telp');
                    var coad = selectedCustomer.data('coad');
                    
                    // Update the Alamat and No Telp input fields
                    $('#alamat').val(alamat);
                    $('#telp').val(telp);
                    $('#coad').val(coad);
                });

                const discinput= document.getElementById('disc');
                discinput.addEventListener('input', function () {
                    refreshServiceCart(ServiceCart,SparepartCart,PaketCart);
                });
                const namaQQInput = document.getElementById('namaqq');
                
                namaQQInput.addEventListener('input', function () {
                    refreshServiceCart(ServiceCart,SparepartCart,PaketCart);
                });
                $('input[name="tahun"]').datepicker({
                    format: "yyyy",
                    viewMode: "years", 
                    minViewMode: "years",
                    autoclose: true,
                    });
            });

            function getServiceKDitems() {
            var serviceKDitems = [];
            $('#serviceCart tbody tr').each(function () {
                var kditem = $(this).find('td:first').text(); // Assuming KDitem is in the first column
                serviceKDitems.push(kditem);
            });
            return serviceKDitems;
        }
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
        </script>