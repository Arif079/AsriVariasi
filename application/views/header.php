<?php
if(!isset($authPage)) {
  $authPage = FALSE;
}
?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$pageTitle;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?=base_url();?>assets/sufee/vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/sufee/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/sufee/vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/daterangepicker/css/datepicker.min.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/daterangepicker/css/datepicker-bs4.min.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/pace-style.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/dropify/css/dropify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.3.5/jquery.timepicker.min.css">    
    <!-- Add Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="<?=base_url();?>assets/sufee/assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <style>
      #dataTable_filter input {
        margin-left: -17px;
      }
    </style>

</head>


<body<?php if($authPage) { echo " class='bg-dark'"; } ?>>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
  <script src="<?=base_url();?>assets/jquery.js"></script>
  <script src="<?=base_url();?>assets/sufee/vendors/popper.js/dist/umd/popper.min.js"></script>
  <script src="<?=base_url();?>assets/sufee/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="<?=base_url();?>assets/sufee/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="<?=base_url();?>assets/sufee/vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?=base_url();?>assets/sweetalert2/sweetalert2.all.min.js"></script>
  <script src="<?=base_url();?>assets/daterangepicker/js/datepicker-full.min.js"></script>
  <script src="<?=base_url();?>assets/sufee/vendors/chart.js/dist/Chart.min.js"></script>
  <script src="<?=base_url();?>assets/dropify/js/dropify.min.js"></script>
  <!-- JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.3.5/jquery.timepicker.min.js"></script>
  <script>
      paceOptions = {
      restartOnRequestAfter: 5,
      ajax: {
        trackMethods: ['GET', 'POST', 'PUT', 'DELETE', 'REMOVE']
      }
    }
  </script>
  <script src="<?=base_url();?>assets/pace.min.js"></script>

<?php

if (!isset($authPage)) {
    $authPage = FALSE;
}
    // Check if the user is logged in based on the roleID
    if (isset($this->session->auth['roleid'])) {
        $roleID = $this->session->auth['roleid'];
?>
  
            
    <!-- Left Panel -->
   
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="<?=base_url();?>"><?=$this->shop_info->get_shop_name();?></a>
                <a class="navbar-brand hidden" href="<?=base_url();?>">B</a>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                <li class="menu-item-has-children dropdown"> <!-- Added the dropdown class 
                <li><a href="<?= base_url("sparepart_sales"); ?>">Riwayat Penjualan</a></li>
                        
            -->
                <?php if ($roleID == 1 || $roleID == 2): ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <!-- Added dropdown-toggle and data-toggle attributes -->
                        <i class="menu-icon fa fa-plus-square"></i>Transaksi
                    </a>
               
                    <ul class="sub-menu dropdown-menu"> <!-- Added sub-menu and dropdown-menu classes -->
                        <li><a href="<?= base_url("transaction"); ?>">Surat Perbaikan Kendaraan (SPK)</a></li>
                        <li><a href="<?= base_url("wo"); ?>">Isi Working Order</a></li>
                        <li><a href="<?= base_url("service_sales"); ?>">Monitoring SPK</a></li>
                       
                    </ul>
                </li>
                <?php endif; ?>
                <li class="menu-item-has-children dropdown">
                <?php if ($roleID == 1): ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-database"></i>Data Master
                    </a>
                
                    <ul class="sub-menu dropdown-menu">
                        <li><a href="<?= base_url("marketing"); ?>">Master Sales</a></li>
                        <li><a href="<?= base_url("mekanik"); ?>">Master Mekanik</a></li>
                        <li><a href="<?= base_url("Ppn"); ?>">Master PPN</a></li>
                        <li><a href="<?= base_url("Paket"); ?>">Master Paket</a></li>
                        <li><a href="<?= base_url("customers"); ?>">Master Customer</a></li>
                        <li><a href="<?= base_url("supplier"); ?>">Master Supplier</a></li>
                        <li><a href="<?= base_url("sparepart"); ?>">Master Item</a></li>
                        <li><a href="<?= base_url("services"); ?>">Master Job</a></li>
                        <li><a href="<?= base_url("satuan"); ?>">Master Satuan</a></li>
                        <li><a href="<?= base_url("kacafilm"); ?>">Master Kacafilm</a></li>
                        <li><a href="<?= base_url("jasa"); ?>">Master Jasa</a></li>                       
                    </ul>
                </li>
                <?php endif; ?>
                <?php if ($roleID == 3): ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-database"></i>Data Master
                    </a>
                
                    <ul class="sub-menu dropdown-menu">
                        <li><a href="<?= base_url("Ppn"); ?>">Master PPN</a></li>
                        <li><a href="<?= base_url("Paket"); ?>">Master Paket</a></li>
                        <li><a href="<?= base_url("supplier"); ?>">Master Supplier</a></li>
                        <li><a href="<?= base_url("sparepart"); ?>">Master Item</a></li>
                        <li><a href="<?= base_url("services"); ?>">Master Job</a></li>
                        <li><a href="<?= base_url("satuan"); ?>">Master Satuan</a></li>
                        <li><a href="<?= base_url("kacafilm"); ?>">Master Kacafilm</a></li>                       
                    </ul>
                </li>
                <?php endif; ?>
                <?php if ($roleID == 2): ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-database"></i>Data Master
                    </a>
                
                    <ul class="sub-menu dropdown-menu">
                        <li><a href="<?= base_url("marketing"); ?>">Master Sales</a></li>
                        <li><a href="<?= base_url("customers"); ?>">Master Customer</a></li>  
                        <li><a href="<?= base_url("mekanik"); ?>">Master Mekanik</a></li>                  
                    </ul>
                </li>
                <?php endif; ?>
                <li class="menu-item-has-children dropdown">
                <?php if ($roleID == 1 || $roleID == 2): ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-shopping-cart"></i>Pembelian
                    </a>
                
                    <ul class="sub-menu dropdown-menu">
                        <li><a href="<?= base_url("purchase"); ?>">Purchase Order</a></li>
                        <li><a href="<?= base_url("btb"); ?>">Bukti Terima Barang</a></li>
                        <li><a href="<?= base_url("retur"); ?>">Retur Barang</a></li>
                        <li><a href="<?= base_url("transaksi"); ?>">Transaksi Pembelian</a></li>
                        <li><a href="<?= base_url("stockopname"); ?>">Stockopname</a></li> 
                    </ul>
                </li>
                <?php endif; ?>
                <?php if ($roleID == 3): ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-shopping-cart"></i>Pembelian
                    </a>
                
                    <ul class="sub-menu dropdown-menu">
                        <li><a href="<?= base_url("transaksi"); ?>">Transaksi Pembelian</a></li>
                        <li><a href="<?= base_url("stockopname"); ?>">Stockopname</a></li> 
                    </ul>
                </li>
                <?php endif; ?>
                <li class="menu-item-has-children dropdown">
                <?php if ($roleID == 1 || $roleID == 3 || $roleID == 5): ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-book"></i>Akuntansi
                    </a>
                    <?php endif; ?>
                    <ul class="sub-menu dropdown-menu">
                        <li><a href="<?= base_url("coa"); ?>">Master COA</a></li>
                        <li><a href="<?= base_url("bkk"); ?>">Bukti Kas Keluar</a></li>
                        <li><a href="<?= base_url("bkm"); ?>">Bukti Kas Masuk</a></li>
                        <li><a href="<?= base_url("memo"); ?>">Memorial</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                <?php if ($roleID == 1 || $roleID == 4 || $roleID == 5): ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-bar-chart-o"></i>Laporan
                    </a>
                    <?php endif; ?>
                    <ul class="sub-menu dropdown-menu">
                        
                       <!-- <li><a href="<?= base_url("report/sales"); ?>">Laporan Penjualan</a></li>-->
                       <li><a href="<?= base_url("GLproses"); ?>">Saldo awal</a></li> 
                        <li><a href="<?= base_url("report/spk"); ?>">Laporan Penjualan</a></li>
                        <li><a href="<?= base_url("report/purchase"); ?>">Laporan Pembelian</a></li>
                        <li><a href="<?= base_url("report/kas"); ?>">Laporan Kas</a></li>
                        <li><a href="<?= base_url("report/hpp"); ?>">Laporan HPP</a></li>
                        <li><a href="<?= base_url("report/jurnal"); ?>">Laporan Buku Besar</a></li>
                        <li><a href="<?= base_url("report/neraca"); ?>">Laporan Neraca Saldo</a></li>
                        <li><a href="<?= base_url("viewdata"); ?>">GP & Jurnal</a></li> 
                    </ul>
                    
                </li>
                <li class="menu-item-has-children dropdown">
                <?php if ($roleID == 2 || $roleID == 3): ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-bar-chart-o"></i>Laporan
                    </a>
                    <?php endif; ?>
                    <ul class="sub-menu dropdown-menu">
                       <!-- <li><a href="<?= base_url("report/sales"); ?>">Laporan Penjualan</a></li>-->
                       <li><a href="<?= base_url("GLproses"); ?>">Saldo awal</a></li> 
                       <li><a href="<?= base_url("report/spk"); ?>">Laporan Penjualan</a></li>
                        <li><a href="<?= base_url("report/purchase"); ?>">Laporan Pembelian</a></li>
                        <li><a href="<?= base_url("report/kas"); ?>">Laporan Kas</a></li>
                        <li><a href="<?= base_url("report/hpp"); ?>">Laporan HPP</a></li>
                    </ul>
                    
                </li>
                
                <li class="menu-item-has-children dropdown">
                <?php if ($roleID == 1): ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="menu-icon fa fa-bar-chart-o"></i>Control Setting
                    </a>
                <?php endif; ?>
                    <ul class="sub-menu dropdown-menu">
                        <li><a href="<?= base_url("UserInput"); ?>">Tambah User</a></li>
                        <li><a href="<?= base_url("RoleInput"); ?>">Tambah Hak</a></li>
                    </ul>
                    </ul>
                </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7">
                    <div class="header-left">
                        <div style="height:41px"></div>
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="<?=base_url("assets/avatar-1.png");?>" alt="User Avatar">
                        </a>

                        <div class="user-menu dropdown-menu">
                        <div class="dropdown-header">
                            <span>Welcome, <?php echo $this->session->auth['username']; ?></span>
                            </div>

                            <a class="nav-link" href="<?=base_url("setting/change_password");?>"><i class="fa fa-key"></i> Ganti Password</a>

                            <a class="nav-link" href="<?=base_url("setting/shop_info");?>"><i class="fa fa-cog"></i> Pengaturan</a>

                            <a class="nav-link" href="<?=base_url("auth/logout");?>"><i class="fa fa-power-off"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>

        </header><!-- /header -->
        <!-- Header-->

<?php } ?>
