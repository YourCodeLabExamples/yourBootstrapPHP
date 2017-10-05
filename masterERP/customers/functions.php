<?php
require_once('../config.php');
require_once(DBAPI);
$customers = null;
$customer = null;

/**
 *  Listagem de Clientes
 */
function index() {
	global $customers;
	$customers = find_all('customers');
}

/**
 *  Cadastro de Clientes
 */
function add() {
  if (!empty($_POST['customer'])) {
    
    $today = 
      date_create('now', new DateTimeZone('America/Sao_Paulo'));
    $customer = $_POST['customer'];
    $customer['modified'] = $customer['created'] = $today->format("Y-m-d H:i:s");
    
    save('customers', $customer);
    header('location: index.php');
  }
}

/**
 *	Atualizacao/Edicao de Cliente
 */
function edit() {
  $now = date_create('now', new DateTimeZone('America/Sao_Paulo'));
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_POST['customer'])) {
      $customer = $_POST['customer'];
      $customer['modified'] = $now->format("Y-m-d H:i:s");
      update('customers', $id, $customer);
      header('location: index.php');
    } else {
      global $customer;
      $customer = find('customers', $id);
    } 
  } else {
    header('location: index.php');
  }
}

/**
 *  Atualiza um registro em uma tabela, por ID
 */
function update($table = null, $id = 0, $data = null) {
  $database = open_database();
  $items = null;
  foreach ($data as $key => $value) {
    $items .= trim($key, "'") . "='$value',";
  }
  // remove a ultima virgula
  $items = rtrim($items, ',');
  $sql  = "UPDATE " . $table;
  $sql .= " SET $items";
  $sql .= " WHERE id=" . $id . ";";
  try {
    $database->query($sql);
    $_SESSION['message'] = 'Registro atualizado com sucesso.';
    $_SESSION['type'] = 'success';
  } catch (Exception $e) { 
    $_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
    $_SESSION['type'] = 'danger';
  } 
  close_database($database);
}

/**
 *  Visualização de um Cliente
 */
function view($id = null) {
  global $customer;
  $customer = find('customers', $id);
}

/**
 *  Exclusão de um Cliente
 */
function delete($id = null) {
  global $customer;
  $customer = remove('customers', $id);
  header('location: index.php');
}

?>