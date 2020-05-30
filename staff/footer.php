<div class="footer-copyright text-center pt-1">

    <div class="offset-md col-md-12" style="background:#E6E6E6">
        <center><a onclick="$('#policy').modal().show();"><u>ข้อตกลงและเงื่อนไขบริษัท</u></a></center>
    </div>
    © 2019 Copyright NextHop Co., Ltd. - tested on Chrome<br>
    320 Tungree Road, Khohong, Hatyai, Songkhla, 90110
</div>

<div class="modal fade" id="policy" tabindex="-1" role="dialog" aria-labelledby="addWarrantyLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="policyLabel">ข้อตกลงและเงื่อนไขบริษัท</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe width="100%" height="450px" frameBorder="0" src="../policy.php"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<script>
    function loadModal(type, id, cus) {
        $('.modal-backdrop').remove();
        $('#loadModal').load('loadmodal.php?type=' + type + '&id=' + id + '&cus=' + cus, function(result) {
            $('#' + type).modal('show');
        });
    }
</script>
</body>

</html>
<?php
$conn->close();
?>