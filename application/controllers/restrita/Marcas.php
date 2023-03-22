<?php

defined('BASEPATH') OR exit ('Ação não permitida');
class Marcas extends CI_Controller{

public function __construct() {
parent::__construct();

    if (!$this->ion_auth->logged_in())
    {
      redirect('restrita/login');
    }

}

public function index(){

$data = array(
'titulo' => 'Marcas Cadastradas' , 

'marcas' => $this->core_model->get_all('marcas'),  
);



 $this->load->view ('restrita/layout/header', $data);
    $this->load->view ('restrita/marcas/index');
    $this->load->view ('restrita/layout/footer');


}

public function core($marca_id = NULL) {

if(!$marca_id){

//cadastrando
$this->form_validation->set_rules('marca_nome','Nome da Marca','trim|required|min_length[2]|max_length[40]|callback_valida_nome_marca');

if($this->form_validation->run()){

$data = elements(
 array(

'marca_nome',
'marca_ativa',



), $this->input->post()

);


$data['marca_meta_link']= url_amigavel($data['marca_nome']);

$data = html_escape($data);

$this->core_model->insert('marcas', $data);
redirect ('restrita/marcas');
}else{



$data = array(
'titulo' => 'Cadastrar Marca' , 

);



 $this->load->view ('restrita/layout/header', $data);
    $this->load->view ('restrita/marcas/core');
    $this->load->view ('restrita/layout/footer');

}
















}else{
 
if(!$marca = $this->core_model->get_by_id('marcas' , array('marca_id' => $marca_id))){
$this->session->set_flashdata('erro', 'A marca não foi localizada');
redirect ('restrita/marcas');
}else{


$this->form_validation->set_rules('marca_nome','Nome da Marca','trim|required|min_length[2]|max_length[40]|callback_valida_nome_marca');

if($this->form_validation->run()){

$data = elements(
 array(

'marca_nome',
'marca_ativa',



), $this->input->post()

);


$data['marca_meta_link']= url_amigavel($data['marca_nome']);

$data = html_escape($data);

$this->core_model->update('marcas', $data, array('marca_id' => $marca_id));
redirect ('restrita/marcas');
}else{



$data = array(
'titulo' => 'Editar Marca' , 
'marca' => $marca,
);



 $this->load->view ('restrita/layout/header', $data);
    $this->load->view ('restrita/marcas/core');
    $this->load->view ('restrita/layout/footer');

}






}

}

}




public function valida_nome_marca($marca_nome){

$marca_id = $this->input->post('marca_id');
if(!$marca_id){
 //cadastrando

if($this->core_model->get_by_id('marcas', array('marca_nome'=> $marca_nome) )){
$this-> form_validation->set_message('valida_marca_nome','Essa marca já existe');
return false;


}else{
 
return true;
}
}else{

if($this->core_model->get_by_id('marcas', array('marca_nome'=> $marca_nome, 'marca_id !=' => $marca_id) )){
$this-> form_validation->set_message('valida_nome_marca','Essa marca já existe');
return false;


}else{
 
return true;
}

}

}



public function delete($marca_id = NULL){

$marca_id = (int) $marca_id;

if(!$marca_id || !$this->core_model->get_by_id('marcas' , array ('marca_id'=>$marca_id))){
$this->session->set_flashdata('erro', 'A marca não foi localizada');
redirect ('restrita/marcas');
}

if( $this->core_model->get_by_id('marcas' , array ('marca_id'=> $marca_id, 'marca_ativa' => 1))){
$this->session->set_flashdata('erro', 'Marcas ativas não podem ser removidas');
redirect ('restrita/marcas');
}

$this->core_model->delete('marcas' , array('marca_id' => $marca_id));
redirect ('restrita/marcas');
}


}



