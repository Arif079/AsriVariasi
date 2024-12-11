        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Buku Besar</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Buku Besar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
    <div class="card">
        <div class="card-header">
            <div class="date form-inline">
                <input type="text" name="start" class="form-control form-control-sm" placeholder="YYYY-MM-DD">
                <span class="mx-2">-</span>
                <input type="text" name="end" class="form-control form-control-sm" placeholder="YYYY-MM-DD">
                <button type="button" class="btn btn-danger btn-sm ml-3 btn-print"><i class="fa fa-print"></i> EXCEL</button>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="coa-checkboxes">Select CoA:</label>
                <div id="coa-checkboxes">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all">
                        <label class="form-check-label" for="select-all">Select All</label>
                    </div>
                    <?php
                    foreach($coa as $akun) {
                        if ($akun->isdetail == '1') {
                            echo '<div class="form-check">';
                            echo '<input class="form-check-input" type="checkbox" value="'.$akun->coa.'" id="coa-'.$akun->coa.'">';
                            echo '<label class="form-check-label" for="coa-'.$akun->coa.'">'.$akun->coa.' - '.$akun->nama.'</label>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const elem = document.querySelector('.date');
    const datepicker = new DateRangePicker(elem, {
        format: "yyyy-mm-dd"
    });

    $(".btn-print").on("click", function () {
        var start = $("input[name=start]").val();
        var end = $("input[name=end]").val();

        var selectedCOA = [];
        $("#coa-checkboxes .form-check-input:checked").each(function() {
            selectedCOA.push($(this).val());
        });

        if (selectedCOA.length === 0) {
            alert("Tidak ada COA yang dipilih");
            return;
        }

        var selectedCOA_JSON = JSON.stringify(selectedCOA);

        var baseUrl = "<?=base_url('Excel_exports/action');?>";

        var data = {
            start: start,
            end: end,
            selectedCOA: selectedCOA_JSON
        };

        $.ajax({
            type: "POST",
            url: baseUrl,
            data: data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                var blob = new Blob([response], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'ujicobabukubesar.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            },
            error: function(xhr, status, error) {
                console.log("Error: ", xhr.responseText);
            }
        });
    });

    $("#select-all").on("click", function () {
        var isChecked = $(this).is(":checked");
        $("#coa-checkboxes .form-check-input").prop("checked", isChecked);
    });
});

</script>
