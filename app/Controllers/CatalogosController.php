<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\AreaClinica;
use App\Models\CategoriaVocabulario;
use App\Models\Admin;

/**
 * CatalogosController.php
 * Controlador para la gestión de catálogos: áreas clínicas y categorías gramaticales (HU18).
 */
class CatalogosController extends Controller {

    private AreaClinica $areaModel;
    private CategoriaVocabulario $categoriaModel;
    private Admin $adminModel;

    public function __construct() {
        parent::__construct();
        $this->areaModel = new AreaClinica();
        $this->categoriaModel = new CategoriaVocabulario();
        $this->adminModel = new Admin();
        iniciarSesion();

        if (!estaAutenticado() || obtenerRolSesion() !== 'admin') {
            $this->redirect('login');
        }
    }

    /**
     * Muestra la vista principal con las listas de áreas y categorías.
     */
    public function index(): void {
        $areas         = $this->areaModel->obtenerTodas();
        $categorias    = $this->categoriaModel->obtenerTodas();
        $totalUsuarios = $this->adminModel->obtenerTotalUsuarios();
        
        $exito = limpiar($_GET['exito'] ?? '');
        $error = limpiar($_GET['error'] ?? '');

        $this->render('admin/catalogos/index', compact('areas', 'categorias', 'totalUsuarios', 'exito', 'error'));
    }

    /**
     * Guarda (crea o actualiza) un elemento en el catálogo.
     */
    public function guardar(): void {
        if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
            $this->redirect('admin/catalogos');
        }

        $tipo   = limpiar($_POST['tipo'] ?? ''); // 'area' o 'categoria'
        $nombre = limpiar($_POST['nombre'] ?? '');
        $id     = limpiar($_POST['id'] ?? '');

        if (empty($tipo) || empty($nombre)) {
            $this->redirect('admin/catalogos?error=' . urlencode('El tipo y el nombre son obligatorios.'));
            return;
        }

        $model = $tipo === 'area' ? $this->areaModel : $this->categoriaModel;

        if (empty($id)) {
            // Crear nuevo
            if ($model->existeNombre($nombre)) {
                $this->redirect('admin/catalogos?error=' . urlencode('Ya existe un elemento con ese nombre.'));
                return;
            }
            $model->crear(generarUUID(), $nombre);
            $this->redirect('admin/catalogos?exito=creado');
        } else {
            // Actualizar existente
            if ($model->existeNombre($nombre, $id)) {
                $this->redirect('admin/catalogos?error=' . urlencode('Ya existe otro elemento con ese nombre.'));
                return;
            }
            $model->actualizar($id, $nombre);
            $this->redirect('admin/catalogos?exito=actualizado');
        }
    }

    /**
     * Alterna el estado activo/inactivo de un elemento del catálogo.
     */
    public function toggle(): void {
        if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
            $this->redirect('admin/catalogos');
        }

        $tipo = limpiar($_POST['tipo'] ?? '');
        $id   = limpiar($_POST['id'] ?? '');

        if (!empty($tipo) && !empty($id)) {
            $model = $tipo === 'area' ? $this->areaModel : $this->categoriaModel;
            $model->toggleActivo($id);
        }

        $this->redirect('admin/catalogos?exito=estado');
    }
}
