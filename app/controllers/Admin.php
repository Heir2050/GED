<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Core\Session; //Importing namespace
use \Core\Request;
use Model\Brand;
use Model\Order;
use Model\Orderitems;
use \Model\product;
use \Model\Categories;
use Model\Category;
use \Model\Image;
use Model\User;
use Stripe\Climate\Product as ClimateProduct;

/**
 * Admin class
 */
class Admin
{
    use MainController; // this importing another class

    public function index()
    {

        $req = new Request();
        $ses = new Session();
        // $categories = new Category();

        # After doing anything, we unsure that the user is loged in.

        // if (!$ses->is_logged_in() || $ses->user('role') != 'admin') {
        //     message('Please login as admin');
        //     redirect('login');
        // }
        $this->view('home');
    }

    // public function users($action = null, $id = null)
    // {
    //     $users = new User();
    //     $req = new Request();
    //     $ses = new Session();
    //     $data = [];

    //     $action = $data['action'] = URL(2) ?? 'View';

    //     # After doing anything, we unsure that the user is loged in.
    //     if (!$ses->is_logged_in() || $ses->user('role') != 'admin') {
    //         message('Please login as admin');
    //         redirect('login');
    //     }

    //     // $users->setOrder_column('user_id');

    //     if ($action == 'add') {
    //         if ($req->posted()) { # if ($_SERVER['REQUEST_METHOD'] = 'POST') the same
    //             if ($users->validate($_FILES, $_POST)) {  #$users->validate($_POST) Same code

    //                 $file = $req->files();

    //                 $arr = $req->post();

    //                 if (!empty($file['image']['name'])) {
    //                     $folder = "uploads/";
    //                     if (!file_exists($folder)) {
    //                         mkdir($folder, 0777, true);
    //                     }

    //                     $arr['image'] = $folder . $file['image']['name'];
    //                     move_uploaded_file($file['image']['tmp_name'], $arr['image']);

    //                     # For resizing image 
    //                     $image_class = new \Model\Image();
    //                     $image_class->resize($arr['image'], 1000); # 1000 is maximum of pixel
    //                 }

    //                 $users->insert($arr);

    //                 message("Users added successfully");

    //                 redirect('users');
    //             }

    //             $data['errors'] = $users->errors;
    //         }
    //     } elseif ($action == 'edit') {
    //         $data['row'] = $users->first(['user_id' => $id]);

    //         if (!$data['row']) {
    //             message("User not found!");
    //             redirect('users');
    //         }

    //         if ($_SERVER['REQUEST_METHOD'] == "POST") {

    //             $folder = "uploads/";
    //             if (!file_exists($folder)) {
    //                 mkdir($folder, 0777, true);
    //             }

    //             if ($users->validate($_FILES, $_POST, $id)) {

    //                 if (empty($_POST['password'])) {
    //                     unset($_POST['password']);
    //                 } else {
    //                     $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    //                 }

    //                 if (!empty($_FILES['image']['name'])) {
    //                     $destination = $folder . time() . $_FILES['image']['name'];
    //                     move_uploaded_file($_FILES['image']['tmp_name'], $destination);

    //                     $image_class = new Image;
    //                     $image_class->resize($destination);

    //                     $_POST['image'] = $destination;

    //                     if (file_exists($data['row']->image)) {
    //                         unlink($data['row']->image);
    //                     }
    //                 }

    //                 $users->update($id, $_POST, 'user_id');

    //                 message("User edited successfully");

    //                 redirect('users');
    //             } else {
    //                 $data['errors'] = $users->errors;
    //             }
    //         }
    //     }

    //     $data['total_users'] = $users->get_row("select count(*) as total from users");

    //     $data['rows'] = $users->findAll();

    //     $this->view('users', $data);
    // }

    public function categories($action = null, $id = null)
    {
        $categories = new Category();
        $req = new Request();
        $ses = new Session();

        $data = [];

        $action = $data['action'] = URL(2) ?? 'View';

        // $categories->setOrder_column('category_id');

        // if (!$ses->is_logged_in() || $ses->user('role') != 'admin') {
        //     message('Please login as admin');
        //     redirect('login');
        // }

        if ($action == 'add') {
            if ($req->posted()) { # if ($_SERVER['REQUEST_METHOD'] = 'POST') the same
                if ($categories->validate($_FILES, $_POST)) {  #$categories->validate($_POST) Same code

                    $file = $req->files();

                    $arr = $req->post();

                    if (!empty($file['image']['name'])) {
                        $folder = "uploads/";
                        if (!file_exists($folder)) {
                            mkdir($folder, 0777, true);
                        }

                        $arr['image'] = $folder . $file['image']['name'];
                        move_uploaded_file($file['image']['tmp_name'], $arr['image']);

                        # For resizing image 
                        $image_class = new \Model\Image();
                        $image_class->resize($arr['image'], 1000); # 1000 is maximum of pixel
                    }

                    $categories->insert($arr);

                    message("Product added successfully");

                    redirect('add-categories');
                }

                $data['errors'] = $categories->errors;
            }
        } elseif ($action == 'edit') {

            $data['row'] = $categories->first(['category_id' => $id]);

            if ($_SERVER['REQUEST_METHOD'] == "POST") {

                $folder = "uploads/";
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                if ($categories->validate($_FILES, $_POST, $id)) {
                    if (!empty($_FILES['image']['name'])) {
                        $destination = $folder . time() . $_FILES['image']['name'];
                        move_uploaded_file($_FILES['image']['tmp_name'], $destination);

                        $image_class = new Image;
                        $image_class->resize($destination);

                        $_POST['image'] = $destination;

                        if (file_exists($data['row']->image))
                            unlink($data['row']->image);
                    }

                    $categories->update($id, $_POST, 'category_id');

                    message("Product Edited successfully");

                    redirect('admin/categories');
                }
            }
        } elseif ($action == 'delete') {

            $data['row'] = $categories->first(['category_id' => $id]);

            if ($_SERVER['REQUEST_METHOD'] == "POST") {

                $categories->delete($id, 'category_id');

                if (file_exists($data['row']->image))
                    unlink($data['row']->image);

                message("A Product deleted successfully");

                redirect('admin/categories');
            }

            $data['errors'] = $categories->errors;
            $data['categories'] = $categories->findAll();
        } else {
            $data['rows'] = $categories->findAll();
        }

        $this->view('admin/categories', $data);
    }

}
