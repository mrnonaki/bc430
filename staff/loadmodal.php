<?php
require '../config.php';

if ($_GET['type'] == 'addEmployee') {
  echo '
<div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployeeLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="addCustomer" name="addEmployee" action="employee.php" method="post">
      <input type="hidden" name="type" value="addEmployee">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addEmployeeLabel">เพิ่มข้อมูลพนักงาน</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-right">อีเมล์:</td>
              <td><input type="email" name="email" id="email" placeholder="username" required> *</td>
            </tr>
            <tr>
              <td class="text-right">หมายเลขบัตรประชาชน:</td>
              <td><input type="number" name="idth" id="idth" placeholder="password" required> *</td>
            </tr>
            <tr>
              <td class="text-right">ชื่อ:</td>
              <td><input type="text" name="name" id="name" required> *</td>
            </tr>
            <tr>
              <td class="text-right">บ้านเลขที่:</td>
              <td><input name="address_no" type="text" required> *</td>
            </tr>
            <tr>
              <td class="text-right">ตำบล / แขวง:</td>
              <td><input name="address_district" type="text" required> *</td>
            </tr>
            <tr>
              <td class="text-right">อำเภอ / เขต:</td>
              <td><input name="address_amphoe" type="text" required> *</td>
            </tr>
            <tr>
              <td class="text-right">จังหวัด:</td>
              <td><input name="address_province" type="text" required> *</td>
            </tr>
            <tr>
              <td class="text-right">รหัสไปรษณีย์:</td>
              <td><input name="address_zipcode" type="number" required> *</td>
            </tr>
            <tr>
              <td class="text-right">เบอร์โทร:</td>
              <td><input type="number" name="tel" pattern="[0]{1}[2,6,8,9]{1}[0-9]{7,}" id="tel" required> *</td>
            </tr>
            <tr>
              <td class="text-right">ระดับ:</td>
              <td>
                <select id="role" name="role" required>
                <option disabled selected>-- กรุณาเลือกระดับ --</option>         
                <option value="1">พนักงาน</option>         
                <option value="2">เจ้าของร้าน</option> *        
              </td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-warning" onclick="loadModal(\'addEmployee\', ' . $_GET['id'] . ')">ล้างค่า</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="../js/JQL.min.js"></script>
<script src="../js/typeahead.bundle.js"></script>
<script src="../js/jquery.Thailand.min.js"></script>
<script>
    $.Thailand({
        database: \'../js/db.json\',
        $district: $(\'#addEmployee [name="address_district"]\'),
        $amphoe: $(\'#addEmployee [name="address_amphoe"]\'),
        $province: $(\'#addEmployee [name="address_province"]\'),
        $zipcode: $(\'#addEmployee [name="address_zipcode"]\'),
    });
</script>
  ';
}

if ($_GET['type'] == 'showEmployee') {
  $sql = 'SELECT * FROM employee WHERE id = ' . $_GET['id'];
  $result = $conn->query($sql);
  $row = $result ? $result->fetch_assoc() : NULL;
  $ban = $row['ban'] ? ' checked' : NULL;

  if ($row['role'] == 1) {
    $role = '<option selected value="1">พนักงาน</option>';
  } elseif ($row['role'] == 2) {
    $role = '<option selected value="2">เจ้าของร้าน</option>';
  }

  echo '
<div class="modal fade" id="showEmployee" tabindex="-1" role="dialog" aria-labelledby="showEmployeeLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="showCustomer" name="showEmplyee" action="employee.php" method="post">
      <input type="hidden" name="type" value="showEmployee">
      <input type="hidden" name="id" value="' . $row['id'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="showEmployeeLabel">รายละเอียดพนักงาน - E' . sprintf("%05d", $row['id']) . '</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-right">ชื่อผู้ใช้:</td>
              <td><input type="text" name="username" id="username" value="' . $row['username'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">ชื่อ:</td>
              <td><input type="text" name="name" id="name" value="' . $row['name'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">บ้านเลขที่:</td>
              <td><input name="address_no" type="text" value="' . $row['address_no'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">ตำบล / แขวง:</td>
              <td><input name="address_district" type="text" value="' . $row['address_district'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">อำเภอ / เขต:</td>
              <td><input name="address_amphoe" type="text" value="' . $row['address_amphoe'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">จังหวัด:</td>
              <td><input name="address_province" type="text" value="' . $row['address_province'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">รหัสไปรษณีย์:</td>
              <td><input name="address_zipcode" type="number" value="' . $row['address_zipcode'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">เบอร์โทร:</td>
              <td><input type="number" name="tel" id="tel" value="' . $row['tel'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">อีเมล:</td>
              <td><input type="email" name="email" id="email" value="' . $row['email'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">หมายเลขบัตรประชาชน:</td>
              <td><input type="number" name="idth" id="idth" value="' . $row['idth'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">ระดับ:</td>
              <td>
                <select id="role" name="role" disabled>
               ' . $role . ' *   
                </select>     
              </td>
            </tr>
            <tr>
              <td class="text-right">ระงับ:</td>
              <td><input type="checkbox" name="ban" id="ban"' . $ban . ' disabled></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="loadModal(\'editEmployee\', ' . $row['id'] . ')">แก้ไข</button>
          <button type="button" class="btn btn-danger" onclick="loadModal(\'delEmployee\', ' . $row['id'] . ')">ลบ</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'editEmployee') {
  $sql = 'SELECT * FROM employee WHERE id = ' . $_GET['id'];
  $result = $conn->query($sql);
  $row = $result ? $result->fetch_assoc() : NULL;
  $ban = $row['ban'] ? ' checked' : NULL;

  if ($row['role'] == 1) {
    $role_emp = "selected";
    $role_adm = "";
  } elseif ($row['role'] == 2) {
    $role_adm = "selected";
    $role_emp = "";
  }

  echo '
<div class="modal fade" id="editEmployee" tabindex="-1" role="dialog" aria-labelledby="editEmployeeLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="editEmployee" name="editEmployee" action="employee.php" method="post">
      <input type="hidden" name="type" value="editEmployee">
      <input type="hidden" name="id" value="' . $row['id'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editEmployeeLabel">แก้ไขข้อมูลพนักงาน - E' . sprintf("%05d", $row['id']) . '</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-right">ชื่อผู้ใช้:</td>
              <td><input type="text" name="username" id="username" value="' . $row['username'] . '" disabled> </td>
            </tr>
            <tr>
              <td class="text-right">รหัสผ่าน:</td>
              <td><input type="password" name="password" id="password"></td>
            </tr>
            <tr>
              <td class="text-right">ชื่อ:</td>
              <td><input type="text" name="name" id="name" value="' . $row['name'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">บ้านเลขที่:</td>
              <td><input name="address_no" type="text" value="' . $row['address_no'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">ตำบล / แขวง:</td>
              <td><input name="address_district" type="text" value="' . $row['address_district'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">อำเภอ / เขต:</td>
              <td><input name="address_amphoe" type="text" value="' . $row['address_amphoe'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">จังหวัด:</td>
              <td><input name="address_province" type="text" value="' . $row['address_province'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">รหัสไปรษณีย์:</td>
              <td><input name="address_zipcode" type="number" value="' . $row['address_zipcode'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">เบอร์โทร:</td>
              <td><input type="number" name="tel" pattern="[0]{1}[2,6,8,9]{1}[0-9]{7,}" id="tel" value="' . $row['tel'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">อีเมล:</td>
              <td><input type="email" name="email" id="email" value="' . $row['email'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">หมายเลขบัตรประชาชน:</td>
              <td><input type="number" name="idth" id="idth" value="' . $row['idth'] . '" > </td>
            </tr>
            <tr>
            <td class="text-right">ระดับ:</td>
            <td>
              <select id="role" name="role" required>
              <option disabled>-- กรุณาเลือกระดับ --</option>         
              <option ' . $role_emp . ' value="1">พนักงาน</option>         
              <option ' . $role_adm . ' value="2">เจ้าของร้าน</option> *        
            </td>
          </tr>
            <tr>
              <td class="text-right">ระงับ:</td>
              <td><input type="checkbox" name="ban" id="ban"' . $ban . '></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-warning" onclick="loadModal(\'editEmployee\', ' . $row['id'] . ')">คืนค่า</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="../js/JQL.min.js"></script>
<script src="../js/typeahead.bundle.js"></script>
<script src="../js/jquery.Thailand.min.js"></script>
<script>
    $.Thailand({
        database: \'../js/db.json\',
        $district: $(\'#editEmployee [name="address_district"]\'),
        $amphoe: $(\'#editEmployee [name="address_amphoe"]\'),
        $province: $(\'#editEmployee [name="address_province"]\'),
        $zipcode: $(\'#editEmployee [name="address_zipcode"]\'),
    });
</script>
  ';
}

if ($_GET['type'] == 'delEmployee') {
  echo '
<div class="modal fade" id="delEmployee" tabindex="-1" role="dialog" aria-labelledby="delEmployeeLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="delEmployee" name="delEmployee" action="employee.php" method="post">
      <input type="hidden" name="type" value="delEmployee">
      <input type="hidden" name="id" value="' . $_GET['id'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="delEmployeeLabel">ลบข้อมูลพนักงาน</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="col-md-8">
              E' . sprintf("%05d", $_GET['id']) . ': ต้องการลบพนักงานใช่หรือไม่?
            </div>
          </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'addCustomer') {
  echo '
<div class="modal fade" id="addCustomer" tabindex="-1" role="dialog" aria-labelledby="addCustomerLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="addCustomer" name="addCustomer" action="customer.php" method="post">
      <input type="hidden" name="type" value="addCustomer">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCustomerLabel">เพิ่มข้อมูลลูกค้า</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-right">อีเมล์:</td>
              <td><input type="email" name="email" id="email" placeholder="username" required> *</td>
            </tr>
            <tr>
              <td class="text-right">หมายเลขบัตรประชาชน:</td>
              <td><input type="number" name="idth" id="idth" placeholder="password" required> *</td>
            </tr>
            <tr>
              <td class="text-right">ชื่อ:</td>
              <td><input type="text" name="name" id="name" required> *</td>
            </tr>
            <tr>
              <td class="text-right">บ้านเลขที่:</td>
              <td><input name="address_no" type="text" required> *</td>
            </tr>
            <tr>
              <td class="text-right">ตำบล / แขวง:</td>
              <td><input name="address_district" type="text" required> *</td>
            </tr>
            <tr>
              <td class="text-right">อำเภอ / เขต:</td>
              <td><input name="address_amphoe" type="text" required> *</td>
            </tr>
            <tr>
              <td class="text-right">จังหวัด:</td>
              <td><input name="address_province" type="text" required> *</td>
            </tr>
            <tr>
              <td class="text-right">รหัสไปรษณีย์:</td>
              <td><input name="address_zipcode" type="number" required> *</td>
            </tr>
            <tr>
              <td class="text-right">เบอร์โทร:</td>
              <td><input type="number" name="tel" pattern="[0]{1}[2,6,8,9]{1}[0-9]{7,}" id="tel" required> *</td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-warning" onclick="loadModal(\'addCustomer\', ' . $row['id'] . ')">ล้างค่า</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="../js/JQL.min.js"></script>
<script src="../js/typeahead.bundle.js"></script>
<script src="../js/jquery.Thailand.min.js"></script>
<script>
    $.Thailand({
        database: \'../js/db.json\',
        $district: $(\'#addCustomer [name="address_district"]\'),
        $amphoe: $(\'#addCustomer [name="address_amphoe"]\'),
        $province: $(\'#addCustomer [name="address_province"]\'),
        $zipcode: $(\'#addCustomer [name="address_zipcode"]\'),
    });
</script>
  ';
}

if ($_GET['type'] == 'showCustomer') {
  $sql = 'SELECT * FROM customer WHERE id = ' . $_GET['id'];
  $result = $conn->query($sql);
  $row = $result ? $result->fetch_assoc() : NULL;
  $ban = $row['ban'] ? ' checked' : NULL;
  echo '
<div class="modal fade" id="showCustomer" tabindex="-1" role="dialog" aria-labelledby="showCustomerLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="showCustomer" name="showCustomer" action="customer.php" method="post">
      <input type="hidden" name="type" value="showCustomer">
      <input type="hidden" name="id" value="' . $row['id'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="showCustomerLabel">รายละเอียดลูกค้า - C' . sprintf("%05d", $row['id']) . '</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-right" width="50%">วันที่สมัคร</td>
              <td width="50%">' . date("d/m/Y", strtotime($row['register'])) . '</td>
            </tr>
            <tr>
              <td class="text-right">ชื่อผู้ใช้:</td>
              <td><input type="text" name="username" id="username" value="' . $row['username'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">ชื่อ:</td>
              <td><input type="text" name="name" id="name" value="' . $row['name'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">บ้านเลขที่:</td>
              <td><input name="address_no" type="text" value="' . $row['address_no'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">ตำบล / แขวง:</td>
              <td><input name="address_district" type="text" value="' . $row['address_district'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">อำเภอ / เขต:</td>
              <td><input name="address_amphoe" type="text" value="' . $row['address_amphoe'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">จังหวัด:</td>
              <td><input name="address_province" type="text" value="' . $row['address_province'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">รหัสไปรษณีย์:</td>
              <td><input name="address_zipcode" type="number" value="' . $row['address_zipcode'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">เบอร์โทร:</td>
              <td><input type="number" name="tel" id="tel" value="' . $row['tel'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">อีเมล:</td>
              <td><input type="email" name="email" id="email" value="' . $row['email'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">หมายเลขบัตรประชาชน:</td>
              <td><input type="number" name="idth" id="idth" value="' . $row['idth'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">ระงับ:</td>
              <td><input type="checkbox" name="ban" id="ban"' . $ban . ' disabled></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="loadModal(\'editCustomer\', ' . $row['id'] . ')">แก้ไข</button>
          <button type="button" class="btn btn-danger" onclick="loadModal(\'delCustomer\', ' . $row['id'] . ')">ลบ</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'editCustomer') {
  $sql = 'SELECT * FROM customer WHERE id = ' . $_GET['id'];
  $result = $conn->query($sql);
  $row = $result ? $result->fetch_assoc() : NULL;
  $ban = $row['ban'] ? ' checked' : NULL;
  echo '
<div class="modal fade" id="editCustomer" tabindex="-1" role="dialog" aria-labelledby="editCustomerLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="editCustomer" name="editCustomer" action="customer.php" method="post">
      <input type="hidden" name="type" value="editCustomer">
      <input type="hidden" name="id" value="' . $row['id'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editCustomerLabel">แก้ไขข้อมูลลูกค้า - C' . sprintf("%05d", $row['id']) . '</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-right" width="50%">วันที่สมัคร</td>
              <td width="50%">' . date("d/m/Y", strtotime($row['register'])) . '</td>
            </tr>
            <tr>
              <td class="text-right">ชื่อผู้ใช้:</td>
              <td><input type="text" name="username" id="username" value="' . $row['username'] . '" disabled> </td>
            </tr>';
  if (!isset($_SESSION)) {
    session_start();
  }
  if ($_SESSION['emp_role'] == 2) {
    echo
      '<tr>
              <td class="text-right">รหัสผ่าน:</td>
              <td><input type="password" name="password" id="password"></td>
            </tr>';
  }
  echo '
            <tr>
              <td class="text-right">ชื่อ:</td>
              <td><input type="text" name="name" id="name" value="' . $row['name'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">บ้านเลขที่:</td>
              <td><input name="address_no" type="text" value="' . $row['address_no'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">ตำบล / แขวง:</td>
              <td><input name="address_district" type="text" value="' . $row['address_district'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">อำเภอ / เขต:</td>
              <td><input name="address_amphoe" type="text" value="' . $row['address_amphoe'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">จังหวัด:</td>
              <td><input name="address_province" type="text" value="' . $row['address_province'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">รหัสไปรษณีย์:</td>
              <td><input name="address_zipcode" type="number" value="' . $row['address_zipcode'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">เบอร์โทร:</td>
              <td><input type="number" name="tel" pattern="[0]{1}[2,6,8,9]{1}[0-9]{7,}" id="tel" value="' . $row['tel'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">อีเมล:</td>
              <td><input type="email" name="email" id="email" value="' . $row['email'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">หมายเลขบัตรประชาชน:</td>
              <td><input type="number" name="idth" id="idth" value="' . $row['idth'] . '" > </td>
            </tr>
            <tr>
              <td class="text-right">ระงับ:</td>
              <td><input type="checkbox" name="ban" id="ban"' . $ban . '></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-warning" onclick="loadModal(\'editCustomer\', ' . $row['id'] . ')">คืนค่า</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="../js/JQL.min.js"></script>
<script src="../js/typeahead.bundle.js"></script>
<script src="../js/jquery.Thailand.min.js"></script>
<script>
    $.Thailand({
        database: \'../js/db.json\',
        $district: $(\'#editCustomer [name="address_district"]\'),
        $amphoe: $(\'#editCustomer [name="address_amphoe"]\'),
        $province: $(\'#editCustomer [name="address_province"]\'),
        $zipcode: $(\'#editCustomer [name="address_zipcode"]\'),
    });
</script>
  ';
}

if ($_GET['type'] == 'delCustomer') {
  echo '
<div class="modal fade" id="delCustomer" tabindex="-1" role="dialog" aria-labelledby="delCustomerLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="delCustomer" name="delCustomer" action="customer.php" method="post">
      <input type="hidden" name="type" value="delCustomer">
      <input type="hidden" name="id" value="' . $_GET['id'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="delCustomerLabel">ลบข้อมูลลูกค้า - C' . sprintf("%05d", $_GET['id']) . '</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'addCategory') {
  echo '
<div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="addCategoryLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="addCategory" name="addCategory" action="category.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="type" value="addCategory">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCategoryLabel">เพิ่มข้อมูลประเภท</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-right">ชื่อ:</td>
              <td><input type="text" name="name" required id="name" required> *</td>
            </tr>
            <tr>
              <td class="text-right">หน่วยนับ:</td>
              <td><input type="text" name="unit" required id="unit" required> *</td>
            </tr>
            <tr>
              <td class="text-right">ราคา:</td>
              <td><input type="number" step="0.01" required name="price" id="price" required> * บาท</td>
            </tr>
            <tr>
              <td class="text-right">ค่าส่ง:</td>
              <td><input type="number" step="0.01" required name="ship" id="ship" required> * บาท</td>
            </tr>
            <tr>
              <td class="text-center" colspan=2>รูป: (ชนิดjpg ขนาดไม่เกิน500KB) *</td>
            </tr>
            <tr>
              <td colspan=2><input type="file" required name="pic" id="pic" required></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-warning" onclick="loadModal(\'addCategory\', ' . $row['id'] . ')">ล้างค่า</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'showCategory') {
  $sql = 'SELECT * FROM category WHERE id = ' . $_GET['id'];
  $result = $conn->query($sql);
  $row = $result ? $result->fetch_assoc() : NULL;
  $issell = $row['issell'] ? ' checked' : NULL;
  echo '
<div class="modal fade" id="showCategory" tabindex="-1" role="dialog" aria-labelledby="showCategoryLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="showCategory" name="showCategory" action="category.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="type" value="showCategory">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="showCategoryLabel">รายละเอียดประเภท - T' . sprintf("%05d", $_GET['id']) . '</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-center" colspan=2><img src="/images/category/' . $row['id'] . '.jpg" class="img-fluid"></td>
            </tr>
            <tr>
              <td class="text-right">ชื่อ:</td>
              <td><input type="text" name="name" id="name" value="' . $row['name'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">หน่วยนับ:</td>
              <td><input type="text" name="unit" id="unit" value="' . $row['unit'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">ราคา:</td>
              <td><input type="number" step="0.01" name="price" id="price" value="' . $row['price'] . '" disabled> บาท</td>
            </tr>
            <tr>
              <td class="text-right">ค่าส่ง:</td>
              <td><input type="number" step="0.01" name="ship" id="ship" value="' . $row['ship'] . '" disabled> บาท</td>
            </tr>
            <tr>
              <td class="text-right">จำนวนทั้งหมด:</td>
              <td><input type="number" name="quantity" id="quantity" value="' . $row['quantity'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">พร้อมขาย:</td>
              <td><input type="number" name="ready" id="ready" value="' . $row['ready'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">ขาย:</td>
              <td><input type="checkbox" name="issell" id="issell"' . $issell . ' disabled></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="loadModal(\'editCategory\', ' . $row['id'] . ')">แก้ไข</button>
          <button type="button" class="btn btn-danger" onclick="loadModal(\'delCategory\', ' . $row['id'] . ')">ลบ</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'editCategory') {
  $sql = 'SELECT * FROM category WHERE id = ' . $_GET['id'];
  $result = $conn->query($sql);
  $row = $result ? $result->fetch_assoc() : NULL;
  $issell = $row['issell'] ? ' checked' : NULL;
  echo '
<div class="modal fade" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="editCategoryLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="editCategory" name="editCategory" action="category.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="type" value="editCategory">
      <input type="hidden" name="id" value="' . $row['id'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editCategoryLabel">แก้ไขข้อมูลประเภท - T' . sprintf("%05d", $_GET['id']) . '</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-center" colspan=2><img src="/images/category/' . $row['id'] . '.jpg" class="img-fluid"></td>
            </tr>
            <tr>
              <td class="text-right">ชื่อ:</td>
              <td><input type="text" name="name" id="name" value="' . $row['name'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">หน่วยนับ:</td>
              <td><input type="text" name="unit" id="unit" value="' . $row['unit'] . '" required> *</td>
            </tr>
            <tr>
              <td class="text-right">ราคา:</td>
              <td><input type="number" step="0.01" name="price" id="price" value="' . $row['price'] . '" required> * บาท</td>
            </tr>
            <tr>
              <td class="text-right">ค่าส่ง:</td>
              <td><input type="number" step="0.01" name="ship" id="ship" value="' . $row['ship'] . '" required> * บาท</td>
            </tr>
            <tr>
              <td class="text-right">จำนวนทั้งหมด:</td>
              <td><input type="number" name="quantity" id="quantity" value="' . $row['quantity'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">พร้อมขาย:</td>
              <td><input type="number" name="ready" id="ready" value="' . $row['ready'] . '" disabled></td>
            </tr>
            <tr>
              <td class="text-right">ขาย:</td>
              <td><input type="checkbox" name="issell" id="issell"' . $issell . '></td>
            </tr>
            <tr>
              <td class="text-center" colspan=2>รูปใหม่: (ชนิดjpg ขนาดไม่เกิน500KB)</td>
            </tr>
            <tr>
              <td colspan=2><input type="file" name="pic" id="pic"></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-warning" onclick="loadModal(\'editCategory\', ' . $row['id'] . ')">ล้างค่า</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'delCategory') {
  echo '
<div class="modal fade" id="delCategory" tabindex="-1" role="dialog" aria-labelledby="delCategoryLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="delCategory" name="delCategory" action="category.php" method="post">
      <input type="hidden" name="type" value="delCategory">
      <input type="hidden" name="id" value="' . $_GET['id'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="delCustomerLabel">ลบข้อมูลประเภท - T' . sprintf("%05d", $_GET['id']) . '</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'addProduct') {
  echo '
<div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="addProductLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="addProduct" name="addProduct" action="product.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="type" value="addProduct">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addProductLabel">เพิ่มข้อมูลสินค้า</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-right">ประเภทสินค้า:</td>
              <td>
                <select name="category" required id="category">
                  <option value="">เลือกประเภทสินค้า</option>
  ';
  $sql = 'SELECT * FROM category';
  $result = $conn->query($sql);
  while ($row = $result->fetch_assoc()) {
    $selected = $_GET['id'] == $row['id'] ? ' selected' : NULL;
    echo '<option value="' . $row['id'] . '"' . $selected . '>T' . sprintf("%05d", $row['id']) . ' - ' . $row['name'] . '</option>';
  }
  echo '
                </select>
              </td>
            </tr>
            <tr>
              <td class="text-right">S/N:</td>
              <td><textarea required rows="5" name="id" id="id"></textarea></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-warning" onclick="loadModal(\'addProduct\', ' . $row['id'] . ')">ล้างค่า</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'showProduct') {
  $sql = 'SELECT * FROM product WHERE id = \'' . $_GET['id'] . '\'';
  $result = $conn->query($sql);
  $row = $result ? $result->fetch_assoc() : NULL;
  $datein = date("d/m/Y H:i", strtotime($row['datein']."+543 years"));
  switch ($row['status']) {
    case 1:
      $status = 'พร้อมขาย';
      break;
    case 2:
      $status = 'รอชำระ';
      break;
    case 3:
      $status = 'ขายแล้ว';
      break;
    case 4:
      $status = 'เคลมเข้า';
      break;
    case 5:
      $status = 'เคลมออก';
      break;
    default:
      $status = '';
      break;
  }
  $sql = 'SELECT * FROM category WHERE id = ' . $row['category'];
  $result = $conn->query($sql);
  $row = $result ? $result->fetch_assoc() : NULL;
  echo '
<div class="modal fade" id="showProduct" tabindex="-1" role="dialog" aria-labelledby="showProductLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="showProduct" name="showProduct" action="product.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="type" value="showProduct">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="showProductLabel">รายละเอียดสินค้า - ' . $_GET['id'] . '</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-right">รหัสประเภท:</td>
              <td>T' . sprintf("%05d", $row['id']) . '</td>
            </tr>
            <tr>
              <td class="text-right">ชื่อประเภท:</td>
              <td>' . $row['name'] . '</td>
            </tr>
            <tr>
              <td class="text-right">เข้าระบบ:</td>
              <td>' . $datein . '</td>
            </tr>
            <tr>
              <td class="text-right">สถานะ:</td>
              <td>' . $status . '</td>
            </tr>
            <tr>
              <td class="text-center" colspan=2><img src="/images/category/' . $row['id'] . '.jpg" class="img-fluid"></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="loadModal(\'editProduct\', ' . $row['id'] . ')">แก้ไข</button>
          <button type="button" class="btn btn-danger" onclick="loadModal(\'drlProduct\', ' . $row['id'] . ')">ลบ</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'editProduct') {
  echo '
<div class="modal fade" id="editProduct" tabindex="-1" role="dialog" aria-labelledby="editProductLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="editProduct" name="editProduct" action="product.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="type" value="editProduct">
      <input type="hidden" name="id" value="' . $_GET['id'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProductLabel">แก้ไขข้อมูลสินค้า - ' . $_GET['id'] . '</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-right">S/N:</td>
              <td><input type="text" required name="idnew" id="idnew"</td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-warning" onclick="loadModal(\'editProduct\', ' . $_GET['id'] . ')">ล้างค่า</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'delProduct') {
  echo '
<div class="modal fade" id="delProduct" tabindex="-1" role="dialog" aria-labelledby="delProductLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="delProduct" name="delProduct" action="product.php" method="post">
      <input type="hidden" name="type" value="delProduct">
      <input type="hidden" name="id" value="' . $_GET['id'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="delCustomerLabel">ลบข้อมูลสินค้า - ' . $_GET['id'] . '</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'showInvoice') {
  echo '
  <div class="modal fade" id="showInvoice" tabindex="-1" role="dialog" aria-labelledby="showInvoiceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form id="showInvoice" name="showInvoice" action="orderlist.php" method="post">
        <input type="hidden" name="type" value="showInvoice">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="showInvoiceLabel">รายละเอียดใบแจ้งหนี้</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="embed-responsive embed-responsive-4by3">
              <iframe class="embed-responsive-item" src="../peak.php?type=invoice&id=' . $_GET['id'] . '"></iframe>
            </div>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </form>
    </div>
  </div>
    ';
}

if ($_GET['type'] == 'getPayment') {
  $sql = 'SELECT * FROM invoice WHERE id = ' . $_GET['id'];
  $result = $conn->query($sql);
  $row = $result ? $result->fetch_assoc() : NULL;
  echo '
<div class="modal fade" id="getPayment" tabindex="-1" role="dialog" aria-labelledby="getPaymentLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="getPayment" name="getPayment" action="invoice.php" method="post">
      <input type="hidden" name="type" value="getPayment">
      <input type="hidden" name="id" value="' . $_GET['id'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="getPaymentLabel">รับชำระ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-right" width="50%">ยอดที่ต้องชำระ:</td>
              <td width="50%">' . number_format($row['amount'], 2, '.', ',') . 'บาท</td>
            </tr>
            <tr>
              <td class="text-right" width="50%">รายละเอียดการชำระ:</td>
              <td><textarea name="payment" rows="3"></textarea>*</td>
            </tr>
            <tr>
              <td class="text-right" width="50%">หมายเหตุ:</td>
              <td><textarea name="ps"rows="3"></textarea></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-warning" onclick="loadModal(\'getPayment\', ' . $_GET['id'] . ')">ล้างค่า</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}
// ----------------------- Orderlist ----------------------------------
if ($_GET['type'] == 'showOrders') {
  echo '
  <div class="modal fade" id="showOrders" tabindex="-1" role="dialog" aria-labelledby="showOrdersLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form id="showOrders" name="showOrders" action="orderlist.php" method="post">
        <input type="hidden" name="type" value="showOrders">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="showOrdersLabel">รายละเอียดการสั่งซื้อสินค้า</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="embed-responsive embed-responsive-4by3">
              <iframe class="embed-responsive-item" src="../peak.php?type=orders&id=' . $_GET['id'] . '"></iframe>
            </div>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </form>
    </div>
  </div>
    ';
}
if ($_GET['type'] == 'downloadInvoice') {
  session_start();
  $id = $_GET['id'];
  // $sum_price = 0;
  // $sum_ship = 0;
  // foreach ($_SESSION['cart'] as $cart) {
  //   $sql = 'SELECT * FROM category WHERE id = '.$cart['id'];
  //   $result = $conn->query($sql);
  //   $row = $result->fetch_assoc();
  //   if ($cart['quantity']) {
  //       $sum_price += $row['price'] * $cart['quantity'];
  //       $sum_ship += $row['ship'] * $cart['quantity'];
  //   }
  // }
  // $sum = $sum_price+$sum_ship;
  echo '
  <div class="modal fade" id="downloadInvoice" tabindex="-1" role="dialog" aria-labelledby="downloadInvoiceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="downloadInvoice" name="downloadInvoice" target="_blank" action="../peak.php" method="post">
        <input type="hidden" name="type" value="invoice">
        <input type="hidden" name="id" value="' . $id . '">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="downloadInvoiceLabel">ออกใบแจ้งหนี้</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">ยืนยัน</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
          </div>
        </div>
      </form>
    </div>
  </div>
    ';
}

if ($_GET['type'] == 'addCart') {
  $cus = (isset($_GET['cus']) && $_GET['cus'] != "" && $_GET['cus'] != "undefined") ? $_GET['cus'] : NULL;
  session_start();
  $sql = 'SELECT * FROM category WHERE id = ' . $_GET['id'];
  $result = $conn->query($sql);
  $row = $result ? $result->fetch_assoc() : NULL;
  foreach ($_SESSION['cart'] as $cart) {
    if ($cart['id'] == $_GET['id']) {
      $quantity = $cart['quantity'];
    }
  }
  echo '
  <div class="modal fade" id="addCart" tabindex="-1" role="dialog" aria-labelledby="addCartLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="addCart" name="addCart" action="cart_staff.php?cus=' . $cus . '" method="post" enctype="multipart/form-data">
        <input type="hidden" name="type" value="addCart">
        <input type="hidden" name="id" value="' . $row['id'] . '">
        <input type="hidden" name="name" value="' . $row['name'] . '">
        <input type="hidden" name="price" value="' . $row['price'] . '">
        <input type="hidden" name="ship" value="' . $row['ship'] . '">';
  if ($cus) {
    echo '<input type="text" hidden name="cus" value="' . $cus . '">';
  }
  echo '
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addCartLabel">รายละเอียดสินค้า</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>' . $cus . '
            </button>
          </div>
          <div class="modal-body">
            <table align="center" class="table table-borderless" style="width:55%">
              <tr>
                <td class="text-center" colspan=2><img src="../images/category/' . $row['id'] . '.jpg" class="img-fluid"></td>
              </tr>
              <tr>
                <td class="text-right" style="width:30%">ชื่อ:</td>
                <td>' . $row['name'] . '</td>
              </tr>
              <tr>
                <td class="text-right">ราคา:</td>
                <td>' . number_format($row['price'], 2) . ' </td>
                <td>บาท</td>
              </tr>
              <tr>
                <td class="text-right">ค่าส่ง:</td>
                <td>' . number_format($row['ship'], 2) . '</td>
                <td>บาท</td>
              </tr>
              <tr>
                <td class="text-right">พร้อมขาย:</td>
                <td>' . $row['ready'] . ' ' . $row['unit'] . '</td>
              </tr>
              <tr>
                <td class="text-center" colspan=2>สั่งซื้อ</td>
              </tr>
              <tr>
                <td class="text-center" colspan=2><input type="number" name="quantity" id="quantity" value="' . $quantity . '" required> * ' . $row['unit'] . '</td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-success">ยืนยัน</button>
              <button type="button" class="btn btn-warning" onclick="loadModal(\'addCart\', ' . $row['id'] . ')">ล้างค่า</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
          </div>
        </div>
      </form>
    </div>
  </div>
    ';
}
if ($_GET['type'] == 'addOrder') {
  echo '
  <div class="modal fade" id="addOrder" tabindex="-1" role="dialog" aria-labelledby="addOrderLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="addOrder" name="addOrder" action="orderlist.php" method="post">
        <input type="hidden" name="type" value="addOrder">
        <input type="hidden" name="cus" value="' . $_GET['id'] . '">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addOrderLabel">ยืนยันการสั่งซื้อสินค้า</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="col-md-7">
              ต้องการสั่งซื้อสินค้าใช่หรือไม่?
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">ยืนยัน</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
          </div>
        </div>
      </form>
    </div>
  </div>
    ';
}

if ($_GET['type'] == 'addInvoice') {
  echo '
  <div class="modal fade" id="addInvoice" tabindex="-1" role="dialog" aria-labelledby="addInvoiceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="addInvoice" name="addInvoice" action="orderlist.php" method="post">
        <input type="hidden" name="type" value="addInvoice">
        <input type="hidden" name="orderid" value="' . $_GET['id'] . '">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addInvoiceLabel">ออกใบแจ้งหนี้</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="col-md-7">
              ต้องการออกใบแจ้งหนี้ใช่หรือไม่?
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">ยืนยัน</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
          </div>
        </div>
      </form>
    </div>
  </div>
    ';
}

if ($_GET['type'] == 'payReceipt') {
  session_start();
  $id = $_GET['id'];

  $sql = "SELECT od.id AS id, od.invoice AS invoice, od.status AS status, invc.ps AS ps, invc.date AS date, invc.payment AS payment , invc.amount AS total FROM invoice AS invc
          LEFT JOIN orders AS od ON od.invoice = invc.id WHERE invc.id = '$id';
  ";


  // $sql = "SELECT od.id AS id, od.invoice AS invoice, od.status AS status, invc.ps AS ps, invc.date AS date, invc.payment AS payment , invc.amount AS total FROM orders AS od
  //         LEFT JOIN invoice AS invc ON invc.id = od.invoice
  //         WHERE od.id = '$id'";
  $result = $conn->query($sql);
  $row_order = $result->fetch_assoc();
  $date_pay = date("Y-m-d H:i:s");

  $payment = $row_order['payment'] ? $row_order['payment'] : "";
  $ps = $row['ps'] ? $row['ps'] : "";

  echo '
<div class="modal fade" id="payReceipt" tabindex="-1" role="dialog" aria-labelledby="payReceiptLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="payInvoice" name="payReceipt" action="payReceipt.php" enctype="multipart/form-data" method="post" >
      <input type="hidden" name="type" value="payReceipt">
      <input type="hidden" name="id" value="' . $row_order['invoice'] . '">
      <input type="hidden" name="orderid" value="' . $row_order['id'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="payReceiptLabel">รับชำระเงิน</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <div class="form-group">
        <div class="row">
        <label class="offset-md-1 col-md-5" style="margin-top:11px;">ยอดที่ต้องชำระ (บาท) :</font> </label>
        <div class="col-md-4 text-right" style="margin-top:9px;">
        <input type="text"  name="start_date" hidden id="start_date" value="' . date("Y-m-d", strtotime($row_order['date'])) . '">
          <input type="text" class="form-control" name="amount" value="' . number_format($row_order['total'], 2) . '" disabled>
        </div>';
  if ($row_order['status'] == 1) {
    $disabled = "";
    echo '
        <label class="offset-md-1 col-md-5" style="margin-top:12px;">แนบหลักฐาน :<font color="red"></font> </label>
        <div class="col-md-4 text-right" style="margin-top:12px;">
        <input type="file" accept="image/x-png,image/gif,image/jpeg" class="" name="detail" id="detail" >
        </div>';
  } elseif ($row_order['status'] == 2) {
    $disabled = "disabled";
    echo '<div class="offset-md-4 col-md-5 pb-1 pt-2">
      <a target="_blank" href="../images/payment/' . $row_order['invoice'] . '.jpg"><img class="img-fluid" src="../images/payment/' . $row_order['invoice'] . '.jpg"></a>
      </div>
    ';
  }
  echo '
        <label class="offset-md-1 col-md-5" style="margin-top:11px;">วันที่โอน :<font color="red">*</font> </label>
        <div class="col-md-6 text-right" style="margin-top:9px;">
          <input type="text" class="form-control datepicker-payment" ' . $disabled . ' required onfocus="$(this).blur();" name="payment" id="payment"  value="' . $payment . '">
        </div>
        <label class="offset-md-1 col-md-5" style="margin-top:12px;">รายละเอียด : </label>
        <div class="col-md-6 text-right" style="margin-top:12px;">
        <textarea class="form-control" rows="3" name="ps" ' . $disabled . '>' . $ps . '</textarea>
        <input type="text" hidden name="status" value="' . $row_order['status'] . '">
       <!-- <input type="text" class="form-control" name="payment"> -->
        </div>
        </div>
        </div>
        <div class="modal-footer">
          <button type="submit" onclick="if(confirm(\'ต้องการยืนยันการรับชำระใช่หรือไม่?\')) return true; else return false;" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
    </form>
  </div>
</div>
<script>
    $(function() {
      var start_report = new Date($("#start_date").val());
      console.log(start_report);
      var end_report = new Date();
            // Report Selector
            $(\'.datepicker-payment\').datepicker({
                language: \'th-th\', //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                format: \'dd/mm/yyyy\',
                disableTouchKeyboard: true,
                todayBtn: false,
                clearBtn: false,
                closeBtn: false,
                //daysOfWeekDisabled: [0],
                endDate: end_report,
                startDate: start_report,
                autoclose: true, //Set เป็นปี พ.ศ.
                inline: true
            }) //กำหนดเป็นวันปัจุบัน       
        });
</script>
  ';
}

if ($_GET['type'] == 'cancelInvoice') {
  session_start();
  $id = $_GET['id'];
  // $sum_price = 0;
  // $sum_ship = 0;
  // foreach ($_SESSION['cart'] as $cart) {
  //   $sql = 'SELECT * FROM category WHERE id = '.$cart['id'];
  //   $result = $conn->query($sql);
  //   $row = $result->fetch_assoc();
  //   if ($cart['quantity']) {
  //       $sum_price += $row['price'] * $cart['quantity'];
  //       $sum_ship += $row['ship'] * $cart['quantity'];
  //   }
  // }
  // $sum = $sum_price+$sum_ship;
  echo '
<div class="modal fade" id="cancelInvoice" tabindex="-1" role="dialog" aria-labelledby="cancelInvoiceLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="getReceipt" name="cancelInvoice" action="orderlist.php" method="post">
      <input type="hidden" name="type" value="cancelInvoice">
      <input type="hidden" name="id" value="' . $id . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelInvoiceLabel">ยกเลิกการสั่งซื้อ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-md-7">
            ต้องการยกเลิกการสั่งซื้อใช่หรือไม่?
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'getReceipt') {
  echo '
  <div class="modal fade" id="getReceipt" tabindex="-1" role="dialog" aria-labelledby="getReceiptLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form id="getReceipt" name="getReceipt" action="orderlist.php" method="post">
        <input type="hidden" name="type" value="getReceipt">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="showOrdersLabel">ใบเสร็จรับเงิน - R' . sprintf("%05d", $_GET['id']) . '</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="embed-responsive embed-responsive-4by3">
              <iframe class="embed-responsive-item" src="../peak.php?type=receipt&id=' . $_GET['id'] . '"></iframe>
            </div>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </form>
    </div>
  </div>
    ';
}

if ($_GET['type'] == 'cancelInvoice') {
  session_start();
  $id = $_GET['id'];
  // $sum_price = 0;
  // $sum_ship = 0;
  // foreach ($_SESSION['cart'] as $cart) {
  //   $sql = 'SELECT * FROM category WHERE id = '.$cart['id'];
  //   $result = $conn->query($sql);
  //   $row = $result->fetch_assoc();
  //   if ($cart['quantity']) {
  //       $sum_price += $row['price'] * $cart['quantity'];
  //       $sum_ship += $row['ship'] * $cart['quantity'];
  //   }
  // }
  // $sum = $sum_price+$sum_ship;
  echo '
<div class="modal fade" id="cancelInvoice" tabindex="-1" role="dialog" aria-labelledby="cancelInvoiceLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="getReceipt" name="cancelInvoice" action="orderlist.php" method="post">
      <input type="hidden" name="type" value="cancelInvoice">
      <input type="hidden" name="id" value="' . $id . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelInvoiceLabel">ยกเลิกการสั่งซื้อ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-md-7">
            ต้องการยกเลิกการสั่งซื้อใช่หรือไม่?
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'addDelivery') {
  echo '
  <div class="modal fade" id="addDelivery" tabindex="-1" role="dialog" aria-labelledby="addDeliveryLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="addDelivery" name="addDelivery" action="orderlist.php" method="post">
        <input type="hidden" name="type" value="addDelivery">
        <input type="text" hidden name="id" value="' . $_GET['id'] . '">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="showOrdersLabel">บันทึกการส่ง - O' . sprintf("%05d", $_GET['id']) . '</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <div class="row">
          <label class="offset-md-1 col-md-4" style="margin-top:11px;">หมายเลขพัสดุ :<font color="red">*</font> </label>
          <div class="col-md-5" style="margin-top:9px;">
            <input type="text" class="form-control" name="track" id="track" required>
          </div>
          </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">ยืนยัน</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
          </div>
        </div>
      </form>
    </div>
  </div>
    ';
}
