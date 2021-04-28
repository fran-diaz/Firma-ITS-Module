<?php
/**
 * Componente text
 */

class firma extends base_component implements components_interface {

	public function make_firma() : string {

		if( isset( $_REQUEST['d'] ) ) { // Es el detalle de una línea de tabla
			$data = decode( $_REQUEST['d'] );
			$d_ckfinder = [
				'table' => $data['table'],
				'id' => $data['id'],
				'dsn' => $data['dsn'],
			];

			$info = $this -> _ITExt -> get($data['table'], '*', [$data['table'].'_id' => $data['id']]);
			
			if( $info ){
				$folder_name_field = $this->cfg( 'contenido', 'folder_name_field' );
				if( ! empty( $folder_name_field ) ){
					$d_ckfinder['name'] = $info[ $folder_name_field ];
				} else {
					$d_ckfinder['name'] = $info[ array_keys( $info )[1] ];
				}
			}
			
			$d_ckfinder = encode( $d_ckfinder );
			$ckfinder_id = $_REQUEST['d'];
		} else {
			
			$d_ckfinder = encode( [
				'report' => $_SESSION['current']['report'],
				'id' => $_SESSION['current']['reports_id'],
			] );
			$ckfinder_id = $_SESSION['current']['report'].'-'.$_SESSION['current']['reports_id'];
		}

		$this -> component_d = $d_ckfinder;


		$html = '';
		ob_start();

		
		?>
		<div class="d-flex justify-content-center align-items-center h-100">
			<!-- Button trigger modal -->
			<button type="button" class="btn btn-primary firma__btn-open" data-toggle="modal" data-type="cliente" data-target="#firma__modal" data-d="<?=$d_ckfinder?>">
			  	Añadir firma cliente
			</button>
			<!-- Button trigger modal -->
			<button type="button" class="btn btn-primary firma__btn-open" data-toggle="modal" data-type="tecnico" data-target="#firma__modal" data-d="<?=$d_ckfinder?>">
			  	Añadir firma técnico
			</button>
		</div>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public function gen_content( ) : string {		
		return $this -> make_firma();
	}
}