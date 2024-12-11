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
                    <button type="button" class="btn btn-primary btn-sm" id="toggle-select-all">Select All</button>
                    <?php foreach($coa as $akun) {
                        if ($akun->isdetail == '1') {
                            echo '<div class="form-check">';
                            echo '<input class="form-check-input" type="checkbox" value="'.$akun->coa.'" id="coa-'.$akun->coa.'">';
                            echo '<label class="form-check-label" for="coa-'.$akun->coa.'">'.$akun->coa.' - '.$akun->nama.'</label>';
                            echo '</div>';
                        }
                    } ?>
                </div>
            </div>
            <div class="progress mt-3" style="display: none;">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Report Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="previewTable">
                    <thead>
                        <tr>
                            <th>CoA</th>
                            <th>Name</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                         
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-print"><i class="fa fa-print"></i> Download as Excel</button>
            </div>
        </div>
    </div>
</div>
<script>
  $(document).ready(function() {
    $("input[name='start']").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });
    $("input[name='end']").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });
});

        function updateProgressBar(percentComplete) {
            $('.progress').show();
            $('.progress-bar').css('width', percentComplete + '%').attr('aria-valuenow', percentComplete).text(percentComplete + '%');
        }

        $(".btn-print").on("click", function () {
            var start = $("input[name=start]").val();
            var end = $("input[name=end]").val();

            var selectedCOA = [];
            $("#coa-checkboxes .form-check-input").each(function() {
                if ($(this).is(":checked")) {
                    selectedCOA.push($(this).val());
                }
            });

            if (selectedCOA.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak ada COA yang dipilih',
                    text: 'Silakan pilih COA sebelum melanjutkan.',
                });
                return;
            }

            var selectedCOA_JSON = JSON.stringify(selectedCOA);
            var baseUrl = "<?=base_url('Excel_exports/neraca');?>";
            var data = {
                start: start,
                end: end,
                selectedCOA: selectedCOA_JSON
            };

            console.log("Data to be sent to controller:", data); // Log the data being sent

            $.ajax({
                type: "POST",
                url: baseUrl,
                data: data,
                xhrFields: {
                    responseType: 'blob'  // Ensure the response is a blob
                },
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                            updateProgressBar(percentComplete);
                        }
                    }, false);
                    xhr.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                            updateProgressBar(percentComplete);
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    console.log("Response from controller:", response); // Log the response from controller

                    // Ensure response is a Blob before proceeding
                    if (!(response instanceof Blob)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid response',
                            text: 'Expected a Blob response',
                        });
                        return;
                    }

                    var url = window.URL.createObjectURL(response);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'ujicobaneraca.xlsx';
                    document.body.appendChild(a); // Append anchor to the body
                    a.click();
                    document.body.removeChild(a); // Remove anchor from the body

                    Swal.fire({
                        icon: 'success',
                        title: 'Download sukses',
                        text: 'Mohon tunggu proses download.',
                    });
                    $('.progress').hide();
                    updateProgressBar(0);
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error); // Log the error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan: ' + xhr.responseText,
                    });
                    $('.progress').hide();
                    updateProgressBar(0);
                }
            });
        });

        $("#toggle-select-all").on("click", function () {
            var isChecked = $(this).data('checked') || false;
            $("#coa-checkboxes .form-check-input").prop("checked", !isChecked);
            $(this).data('checked', !isChecked);
            $(this).text(isChecked ? "Select All" : "Deselect All");
        });
</script>
