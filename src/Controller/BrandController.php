<?php
/**
 * Created by PhpStorm.
 * User: wilder19
 * Date: 10/10/18
 * Time: 15:03
 */


namespace Controller;
use Model\Brands;
use Model\BrandManager;

/**
     * Display item creation page
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */

class BrandController extends AbstractController
{
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = [];

            if (empty($_POST['name'])) {
                $errors['name'] = "La marque doit être renseignée !";
                return $this->twig->render('Admin/brand/add.html.twig', ['error' => $errors]);
            }
             else {

                $BrandsManager = new BrandManager($this->getPdo());
                $brands = new Brands();
                $brands->setName($_POST['name']);
                $id = $BrandsManager->insert($brands);
            }

            header('Location:/admin');

        }

        return $this->twig->render('Admin/brand/add.html.twig');
    }

}
