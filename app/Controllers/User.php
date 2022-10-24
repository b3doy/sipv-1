<?php

namespace App\Controllers;

use Config\Database;
use Myth\Auth\Authorization\GroupModel;

class User extends BaseController
{
    protected $db, $builder, $users, $groupModel;

    public function __construct()
    {
        $this->groupModel = new GroupModel;
        $this->db      = Database::connect();
        $this->builder = $this->db->table('users');
        $this->users = $this->builder->select('users.id as userid, username, fullname, email, telpon, alamat, auth_groups.id as groupid, name, description')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
    }
    public function index()
    {
        $data = [
            'title' => 'SIP || User List',
            'users'  => $this->users->get()->getResult()
        ];
        return view('user/index', $data);
    }

    public function edit($id)
    {
        $role = $this->db->table('auth_groups')->select('*')->get()->getResult();
        $data = [
            'title' => 'SIP || Role Edit',
            'role' => $role,
            'users' => $this->users->getWhere(['users.id' => $id])->getRow()
        ];
        return view('user/edit', $data);
    }

    public function update($id)
    {
        $userid = intval($id);
        $groupid = intval($this->request->getVar('groupid'));
        $groupid_lama0 = $this->users->getWhere(['users.id' => $id])->getRow();
        $groupid_lama = intval($groupid_lama0->groupid);
        $this->groupModel->removeUserFromGroup($userid, $groupid_lama);

        $sql = $this->groupModel->addUserToGroup($userid, $groupid);
        if ($sql) {
?>
            <script>
                alert("User's Role Berhasil Diubah!")
                window.location.href = "<?= base_url('user/index'); ?>"
            </script>
        <?php
        } else {
        ?>
            <script>
                alert("Gagal Mengubah User's Role!")
                window.location.href = "<?= base_url('user/index'); ?>"
            </script>
        <?php
        }
    }

    public function delete($id)
    {
        $sql = $this->builder->delete(['users.id' => $id]);
        if ($sql) {
        ?>
            <script>
                alert('Data User Berhasil Dihapus!')
                window.location.href = "<?= base_url('/user'); ?>"
            </script>
        <?php
        } else {
        ?>
            <script>
                alert('Gagal Menghapus Data User!')
                window.location.href = "<?= base_url('/user'); ?>"
            </script>
<?php
        }
    }
}
