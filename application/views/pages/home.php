<main>
    <div class="container" style="margin-top: 80px; margin-bottom: 80px">

        <?php
            if(isset($token_err)){
                echo '<div class="alert alert-danger" id="regAlert" role="alert">
                            '.$token_err.'
                      </div>';
            }
            if(!isset($_SESSION['username'])){//cek udah login apa blm
                echo '<h4>Login dulu buat nyoba edit delete</h4>';
            }
        ?>
        <table class="table" id="usersTable">
            <thead class="thead-dark">
            <tr>
               <th style="display: none">ID users</th> <!-- ini harus disembunyikan biar aman aja :p-->
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">Password</th>
                <th scope="col">Email</th>
                <th scope="col">Nama</th>
                <?php
                    if(isset($_SESSION['username'])){//cek udah login apa belum
                        echo '<th scope="col" colspan="2">Action</th>';
                    }
                ?>

            </tr>
            </thead>
            <tbody>
                <?php
                    if($userTable == false){
                        echo '<tr>
                                <td colspan="5" align="center"> Tidak ada data pada table users silahkan registrasi</td>
                            </tr>';
                    }else{
                        $i = 1;
                        foreach ($userTable->result() as $row){
                            //row->id harus disembunyiin data penting
                            echo '
                                <tr>
                                    <td style="display: none">'.$row->id.'</td>
                                    <td>'.$i.'</td>
                                    <td>'.$row->username.'</td>
                                    <td>'.$row->password.'</td>
                                    <td>'.$row->email.'</td>
                                    <td>'.$row->nama.'</td>
                                  
                            ';
                            if(isset($_SESSION['username'])){//cek udah login blm
                                echo '
                                    <td><button id="btnEdit" data-toggle="modal" data-target="#editModal" class="btn btn-sm btn-outline-primary">Edit</button></td>
                                    <td><button id="btnDel" data-toggle="modal" data-target="#deleteModal" class="btn btn-sm btn-outline-danger">Delete</button></td>
                                ';
                            }

                            echo '</tr>';
                            $i++;
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Modal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="<?php echo base_url("Home/edit")?>">
                        <div>
                            <div class="alert" id="editAlert" role="alert" style="display: none">

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editUsername">Username</label>
                            <input type="text" class="form-control" name="username" id="editUsername" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email address</label>
                            <input type="email" class="form-control" name="email" id="editEmail" aria-describedby="emailHelp" placeholder="Enter email">
                            <small id="regEmailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="regName">Full Name</label>
                            <input type="text" class="form-control" name="name" id="editNama" placeholder="Nama Lengkap">
                        </div>
                        <div class="form-group">
                            <label for="regPass">Password</label>
                            <input type="password" class="form-control" name="password" id="editPass" placeholder="Password">
                            <small id="passHelp" class="form-text text-muted">Anda dapat membiarkan ini kosong jika tidak ingin mengganti password</small>
                        </div>
                        <div class="form-group">
                            <label for="regRePass">re-Password</label>
                            <input type="password" class="form-control" name="repassword" id="editRePass" placeholder="Retype your password">
                        </div>

                        <input type="hidden" name="id" id="editId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="deleteForm" action="<?php echo base_url('Home/delete')?>">
                        <div>
                            <div class="alert" id="deleteAlert" role="alert" style="display: none">

                            </div>
                        </div>
                    Apa anda yakin ingin menghapus user ini?
                    <input type="hidden" name="id" id="deleteId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-danger">Hapus</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    $(document).ready(function () {
        $('table#usersTable').on('click', 'tr:not(:first)' ,function() {
            var $this = $(this);
            var username = $this.find("td").eq(2).html();
            var email = $this.find("td").eq(4).html();
            var nama = $this.find("td").eq(5).html();
            var id = $this.find("td").eq(0).html();

            //ini buat munculin datanya ke modal
            $('#editUsername').val(username);
            $('#editNama').val(nama);
            $('#editEmail').val(email);
            $('#editId').val(id);
            $('#deleteId').val(id);
        });

        $('#editModal').on('shown.bs.modal', function () {
           $('#editUsername').focus(); //biar pointer langsung ke username pas edit modal muncul
        });
    });

    $("#editForm").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'post', //gaperlu dijelasin lagi bu ratna udah pernah jelasin ini buat apa
            url: $(this).attr("action"), //ini buat ngambil nilai attribute action dari editform
            data: $(this).find('input, select, textarea').serialize(), //buat set input yang mau di edit
            dataType: 'json', //type data response dari server
            success: function(data) {
                if(data.status ==  'fail'){
                    $('#editAlert').removeClass('alert-success').addClass('alert-danger')
                        .attr('style', 'display: block').html(data.msg);
                    //menampilkan pesen error ke div yang ada diatas cari aja ya idnya editAlert
                }else{
                    //menampilkan pesen succ ke div yang ada diatas cari aja ya idnya editAlert
                    $('#editAlert').removeClass('alert-danger').addClass('alert-success')
                        .attr('style', 'display: block').html(data.msg);
                    setTimeout(function () {
                        $('#editModal').modal('hide'); //nyembunyiin modal kalo sukses
                        $('#editForm')[0].reset(); //reset Formnya kalo udah bener
                        window.location.href = "<?php echo  base_url()?>";
                    },1000);
                }
            }
        });
    });

    $("#deleteForm").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'post', //gaperlu dijelasin lagi bu ratna udah pernah jelasin ini buat apa
            url: $(this).attr("action"), //ini buat ngambil nilai attribute action dari editform
            data: $(this).find('input, select, textarea').serialize(), //buat set input yang mau di edit
            dataType: 'json', //type data response dari server
            success: function(data) {
                if(data.status ==  'fail'){
                    $('#deleteAlert').removeClass('alert-success').addClass('alert-danger')
                        .attr('style', 'display: block').html(data.msg);
                    //menampilkan pesen error ke div yang ada diatas cari aja ya idnya deleteAlert
                }else{
                    //menampilkan pesen succ ke div yang ada diatas cari aja ya idnya deleteAlert
                    $('#deleteAlert').removeClass('alert-danger').addClass('alert-success')
                        .attr('style', 'display: block').html(data.msg);
                    setTimeout(function () {
                        $('#deleteModal').modal('hide'); //nyembunyiin modal kalo sukses
                        $('#deleteForm')[0].reset(); //reset Formnya kalo udah bener
                        window.location.href = "<?php echo  base_url()?>";
                    },1000);
                }
            }
        });
    });

</script>


