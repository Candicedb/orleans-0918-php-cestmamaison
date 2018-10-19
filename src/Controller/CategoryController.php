<?php
/**
 * Created by PhpStorm.
 * User: billyvivant
 * Date: 10/10/18
 * Time: 10:09
 */

namespace Controller;
use Model\Category;
use Model\CategoryManager;

class CategoryController extends AbstractController
{
    const EXTENSION = ['png','jpeg','jpg'];
    const MAX_SIZE = 1048576;
    public function add()
    {
        $errors = [];
        $extension = ['png','jpg','jpeg'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty(trim($_POST['name']))) {
                $errors['name'] = "La catégorie doit être renseignée";
            } elseif (strlen(trim($_POST['name'])) > 255) {
                $errors['name'] = "La catégorie doit contenir moins de 255 caractères";
            } elseif (empty($_FILES['fichier']['name'])) {
                $errors['name'] = 'Vous devez mettre une image';
            } else {
                if ($_FILES) {
                    $length = filesize($_FILES['fichier']['tmp_name']);
                    $ext = explode('.', $_FILES['fichier']['name']);
                    if ($length > self::MAX_SIZE) {
                        $errors['name'] = 'Votre fichier ne peut exceder 1Mo';
                    } elseif (!in_array($ext[1], self::EXTENSION)) {
                        $errors['name'] = 'Votre fichier peut uniquement posseder l\'extension ' . implode(' , ', self::EXTENSION);
                    } else {
                        $fileName = 'image' . uniqid() . '.' . $ext[1];
                        $uploadDir = 'assets/images/CategoryImages/';
                        $uploadFile = $uploadDir . basename($fileName);
                        move_uploaded_file($_FILES['fichier']['tmp_name'], $uploadFile);
                        $categoryManager = new CategoryManager($this->getPdo());
                        $category = new Category;
                        $category->setName(trim(($_POST['name'])));
                        $category->setImage($fileName);
                        $id = $categoryManager->insert($category);
                        header('Location:/admin');
                        exit();

                    }
                }
            }
        }
        return $this->twig->render('Admin/Category/add.html.twig', ['error' => $errors]);
    }
}
